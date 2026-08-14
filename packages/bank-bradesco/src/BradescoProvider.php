<?php

namespace Sabba\OpenFinance\Bradesco;

use DateTimeImmutable;
use DateTimeZone;
use Psr\Http\Message\ServerRequestInterface;
use Sabba\OpenFinance\Core\Contracts\BoletoReceivablesProvider;
use Sabba\OpenFinance\Core\Contracts\PixReceiptsProvider;
use Sabba\OpenFinance\Core\Contracts\PixReceivablesProvider;
use Sabba\OpenFinance\Core\Contracts\WebhookVerifier;
use Sabba\OpenFinance\Core\DTO\CanonicalTransaction;
use Sabba\OpenFinance\Core\DTO\ConnectionContext;
use Sabba\OpenFinance\Core\DTO\Money;
use Sabba\OpenFinance\Core\DTO\PixReceiptQuery;
use Sabba\OpenFinance\Core\DTO\ReceivableCommand;
use Sabba\OpenFinance\Core\DTO\ReceivableResult;
use Sabba\OpenFinance\Core\DTO\TransactionPage;
use Sabba\OpenFinance\Core\Enums\TransactionDirection;
use Sabba\OpenFinance\Core\Enums\TransactionStatus;

final readonly class BradescoProvider implements BoletoReceivablesProvider, PixReceiptsProvider, PixReceivablesProvider, WebhookVerifier
{
    public function __construct(private BradescoHttpClient $http) {}

    public function createPix(ConnectionContext $context, ReceivableCommand $command): ReceivableResult
    {
        $pixKey = $command->options['pix_key'] ?? $context->credentials['default_pix_key'] ?? null;
        if (! is_string($pixKey) || $pixKey === '') {
            throw new BradescoProviderException('Informe uma chave Pix para criar a cobrança no Bradesco.');
        }

        $txid = $this->identifier($command->reference, 26, 35);
        $due = $command->dueAt !== null;
        $path = str_replace(
            '{txid}',
            rawurlencode($txid),
            $this->path(
                $context,
                'pix',
                $due ? 'due_charge' : 'charge',
                $due ? '/v2/cobv/{txid}' : '/v2/cob/{txid}',
            ),
        );
        $payload = [
            'calendario' => $due
                ? [
                    'dataDeVencimento' => $command->dueAt->format('Y-m-d'),
                    'validadeAposVencimento' => (int) ($command->options['valid_after_due_days'] ?? 30),
                ]
                : ['expiracao' => (int) ($command->options['expires_in'] ?? 3600)],
            'valor' => [
                'original' => $command->amount->toDecimal(),
                ...($due ? [] : ['modalidadeAlteracao' => 0]),
            ],
            'chave' => $pixKey,
            'solicitacaoPagador' => $command->options['message'] ?? null,
            ...($command->payer ? ['devedor' => $this->payer($command->payer)] : []),
        ];
        $response = $this->http->request($context, 'pix', 'PUT', $path, [
            'json' => array_filter($payload, fn (mixed $value): bool => $value !== null),
        ]);

        return $this->receivableResult($response, $command->amount, $txid);
    }

    public function getPix(ConnectionContext $context, string $externalId, ?string $subtype = null): ReceivableResult
    {
        $due = $subtype === 'due';
        $path = str_replace(
            '{txid}',
            rawurlencode($externalId),
            $this->path(
                $context,
                'pix',
                $due ? 'due_charge' : 'charge',
                $due ? '/v2/cobv/{txid}' : '/v2/cob/{txid}',
            ),
        );
        $response = $this->http->request($context, 'pix', 'GET', $path);

        return $this->receivableResult($response, $this->moneyFromResponse($response), $externalId);
    }

    public function receivedPix(PixReceiptQuery $query): TransactionPage
    {
        $page = max(0, (int) ($query->cursor ?? 0));
        $taxId = preg_replace('/\D+/', '', (string) $query->payerTaxId) ?? '';
        $pagination = $query->cursor !== null || $query->limit !== 100
            ? [
                'paginacao.paginaAtual' => $page,
                'paginacao.itensPorPagina' => max(1, min(100, $query->limit)),
            ]
            : [];
        $response = $this->http->request(
            $query->context,
            'pix',
            'GET',
            $this->path($query->context, 'pix', 'receipts', '/v2/pix'),
            [
                'timeout' => max(5, (int) ($query->context->credentials['products']['pix']['receipts_timeout_seconds'] ?? 45)),
                'query' => array_filter([
                    'inicio' => $this->rfc3339Utc($query->from),
                    'fim' => $this->rfc3339Utc($query->to),
                    'txid' => $query->txid,
                    'txIdPresente' => $query->hasTxid,
                    'devolucaoPresente' => $query->hasRefund,
                    'cpf' => strlen($taxId) === 11 ? $taxId : null,
                    'cnpj' => strlen($taxId) === 14 ? $taxId : null,
                    ...$pagination,
                ], fn (mixed $value): bool => $value !== null && $value !== ''),
            ],
        );
        $transactions = array_map(
            fn (array $item): CanonicalTransaction => $this->receipt($item),
            $this->items($response, ['pix', 'data.pix', 'recebimentos']),
        );
        $currentPage = (int) $this->value($response, ['parametros.paginacao.paginaAtual'], $page);
        $totalPages = (int) $this->value($response, ['parametros.paginacao.quantidadeDePaginas'], 1);

        return new TransactionPage(
            $transactions,
            $currentPage + 1 < $totalPages ? (string) ($currentPage + 1) : null,
        );
    }

    private function rfc3339Utc(DateTimeImmutable $date): string
    {
        return $date
            ->setTimezone(new DateTimeZone('UTC'))
            ->format('Y-m-d\TH:i:s.v\Z');
    }

    public function receivedPixById(ConnectionContext $context, string $endToEndId): CanonicalTransaction
    {
        $path = str_replace(
            '{endToEndId}',
            rawurlencode($endToEndId),
            $this->path($context, 'pix', 'receipt', '/v2/pix/{endToEndId}'),
        );

        return $this->receipt($this->http->request($context, 'pix', 'GET', $path));
    }

    public function refundPix(ConnectionContext $context, string $externalId, string $refundId, Money $amount): ReceivableResult
    {
        $path = str_replace(
            ['{endToEndId}', '{refundId}'],
            [rawurlencode($externalId), rawurlencode($this->identifier($refundId, 1, 35))],
            $this->path($context, 'pix', 'refund', '/v2/pix/{endToEndId}/devolucao/{refundId}'),
        );
        $response = $this->http->request($context, 'pix', 'PUT', $path, [
            'json' => ['valor' => $amount->toDecimal()],
        ]);

        return $this->receivableResult($response, $amount, $refundId);
    }

    public function createBoleto(ConnectionContext $context, ReceivableCommand $command): ReceivableResult
    {
        if ($command->dueAt === null) {
            throw new BradescoProviderException('Informe o vencimento para registrar o boleto no Bradesco.');
        }

        $subtype = $this->boletoSubtype($command->options['subtype'] ?? null);
        $externalId = $this->boletoIdentifier($command->options['our_number'] ?? $command->reference);
        $payer = $this->bradescoBoletoPayer($command->payer);
        $payload = $subtype === 'hybrid'
            ? $this->hybridBoletoPayload($context, $command, $externalId, $payer)
            : $this->normalBoletoPayload($context, $command, $externalId, $payer);
        $response = $this->http->request(
            $context,
            'boleto',
            'POST',
            $this->path(
                $context,
                'boleto',
                $subtype === 'hybrid' ? 'hybrid_create' : 'normal_create',
                $subtype === 'hybrid'
                    ? '/boleto-hibrido/cobranca-registro/v1/gerarBoleto'
                    : '/boleto/cobranca-registro/v1/cobranca',
            ),
            ['json' => $payload],
        );

        return $this->boletoResult($response, $command->amount, $externalId, 'active');
    }

    public function getBoleto(ConnectionContext $context, string $externalId, ?string $subtype = null): ReceivableResult
    {
        $subtype = $this->boletoSubtype($subtype);
        $payload = $subtype === 'hybrid'
            ? [
                'contaProduto' => $this->operationNegotiation($context),
                'controleCpfCnpjUsuario' => $this->beneficiaryDocument($context)['controle'],
                'cpfCnpjUsuario' => $this->beneficiaryDocument($context)['cpfCnpj'],
                'filialCnpjUsuario' => $this->beneficiaryDocument($context)['filial'],
                'idProduto' => $this->boletoProductCode($context),
                'nomePersonalizado' => '',
                'nossoNumero' => (int) $externalId,
                'seqTitulo' => 0,
                'status' => 0,
            ]
            : [...$this->boletoIdentity($context, $externalId), 'sequencia' => 0, 'status' => 0];
        $response = $this->http->request(
            $context,
            'boleto',
            'POST',
            $this->path(
                $context,
                'boleto',
                $subtype === 'hybrid' ? 'hybrid_get' : 'normal_get',
                $subtype === 'hybrid'
                    ? '/boleto-hibrido/cobranca-consulta-titulo/v1/consultar'
                    : '/boleto/cobranca-consulta/v1/consultar',
            ),
            ['json' => $payload],
        );

        return $this->boletoResult($response, $this->boletoMoneyFromResponse($response), $externalId);
    }

    public function updateBoleto(ConnectionContext $context, string $externalId, array $changes, ?string $subtype = null): ReceivableResult
    {
        if (array_key_exists('amount_minor', $changes)) {
            throw new BradescoProviderException('O Bradesco não permite alterar o valor nominal de um boleto registrado.');
        }

        $subtype = $this->boletoSubtype($subtype);
        $dueAt = $this->changedDueDate($changes);
        if ($dueAt === null && empty($changes['options']['bradesco_payload'])) {
            throw new BradescoProviderException('Informe uma nova data de vencimento ou opções de manutenção do boleto Bradesco.');
        }

        $payload = $subtype === 'hybrid'
            ? $this->hybridBoletoUpdatePayload($context, $externalId, $dueAt, $changes)
            : $this->normalBoletoUpdatePayload($context, $externalId, $dueAt, $changes);
        $headers = [];
        if ($subtype === 'hybrid') {
            $txid = $this->providerTxid($changes['provider_metadata'] ?? []);
            if ($txid === null) {
                throw new BradescoProviderException('O txId retornado no registro é obrigatório para alterar o boleto híbrido Bradesco.');
            }
            $headers['txId'] = $txid;
        }
        $response = $this->http->request(
            $context,
            'boleto',
            $subtype === 'hybrid' ? 'POST' : 'PUT',
            $this->path(
                $context,
                'boleto',
                $subtype === 'hybrid' ? 'hybrid_update' : 'normal_update',
                $subtype === 'hybrid'
                    ? '/boleto-hibrido/cobranca-alteracao/v1/alteraBoletoConsulta'
                    : '/boleto/cobranca-altera/v1/alterar',
            ),
            ['headers' => $headers, 'json' => $payload],
        );

        return $this->boletoResult($response, new Money(0), $externalId, 'active');
    }

    public function cancelBoleto(ConnectionContext $context, string $externalId, ?string $subtype = null): ReceivableResult
    {
        $response = $this->http->request(
            $context,
            'boleto',
            'POST',
            $this->path($context, 'boleto', 'cancel', '/boleto/cobranca-baixa/v1/baixar'),
            ['json' => [...$this->boletoIdentity($context, $externalId), 'sequencia' => 0, 'codigoBaixa' => 57]],
        );

        return $this->boletoResult($response, new Money(0), $externalId, 'cancelled');
    }

    /** @return array<string,mixed> */
    private function normalBoletoPayload(
        ConnectionContext $context,
        ReceivableCommand $command,
        string $externalId,
        array $payer,
    ): array {
        $beneficiary = $this->beneficiaryDocument($context);
        $native = is_array($command->options['bradesco_payload'] ?? null)
            ? $command->options['bradesco_payload']
            : [];

        return [
            'debitoAutomatico' => 'N',
            'tpProtestoAutomaticoNegativacao' => 0,
            'prazoProtestoAutomaticoNegativacao' => 0,
            'controleParticipante' => '',
            'cdPagamentoParcial' => 'N',
            'qtdePagamentoParcial' => 0,
            'tipoPrazoDecursoTres' => 0,
            'percentualJuros' => 0,
            'vlJuros' => 0,
            'qtdeDiasJuros' => 0,
            'percentualMulta' => 0,
            'vlMulta' => 0,
            'qtdeDiasMulta' => 0,
            'percentualDesconto1' => 0,
            'vlDesconto1' => 0,
            'dataLimiteDesconto1' => '',
            'percentualDesconto2' => 0,
            'vlDesconto2' => 0,
            'dataLimiteDesconto2' => '',
            'percentualDesconto3' => 0,
            'vlDesconto3' => 0,
            'dataLimiteDesconto3' => '',
            'percentualBonificacao' => 0,
            'vlBonificacao' => 0,
            'dtLimiteBonificacao' => '',
            'vlAbatimento' => 0,
            'vlIOF' => 0,
            ...$native,
            'nuCPFCNPJ' => $beneficiary['cpfCnpj'],
            'filialCPFCNPJ' => $beneficiary['filial'],
            'ctrlCPFCNPJ' => $beneficiary['controle'],
            'idProduto' => $this->boletoProductCode($context),
            'nuNegociacao' => (int) $this->registrationNegotiation($context),
            'nuTitulo' => (int) $externalId,
            'nuCliente' => mb_substr($command->reference, 0, 25),
            'dtEmissaoTitulo' => (new DateTimeImmutable)->format('d.m.Y'),
            'dtVencimentoTitulo' => $command->dueAt?->format('d.m.Y'),
            'indicadorMoeda' => 1,
            'vlNominalTitulo' => $command->amount->toDecimal(),
            'cdEspecieTitulo' => (int) ($command->options['document_species_code'] ?? 1),
            'tpVencimento' => 0,
            'nomePagador' => $payer['name'],
            'logradouroPagador' => $payer['address'],
            'nuLogradouroPagador' => $payer['number'],
            'complementoLogradouroPagador' => $payer['complement'],
            'cepPagador' => (int) $payer['postal_root'],
            'complementoCepPagador' => (int) $payer['postal_suffix'],
            'bairroPagador' => $payer['neighborhood'],
            'municipioPagador' => $payer['city'],
            'ufPagador' => $payer['state'],
            'cdIndCpfcnpjPagador' => strlen($payer['tax_id']) === 14 ? 2 : 1,
            'nuCpfcnpjPagador' => (int) $payer['tax_id'],
            'endEletronicoPagador' => $payer['email'],
        ];
    }

    /** @return array<string,mixed> */
    private function hybridBoletoPayload(
        ConnectionContext $context,
        ReceivableCommand $command,
        string $externalId,
        array $payer,
    ): array {
        $beneficiary = $this->beneficiaryDocument($context);
        $native = is_array($command->options['bradesco_payload'] ?? null)
            ? $command->options['bradesco_payload']
            : [];

        return [
            'registrarTitulo' => '1',
            'qtdDecurPrz' => (string) max(0, min(365, (int) ($command->options['valid_after_due_days'] ?? 30))),
            'codUsuario' => 'APISERVIC',
            'tipoAcesso' => '2',
            'cpssoaJuridContr' => '0',
            'ctpoContrNegoc' => '0',
            'nseqContrNegoc' => '0',
            'filler' => '',
            'codigoBanco' => '237',
            'eNseqContrNegoc' => '0',
            'tipoRegistro' => '001',
            'cprodtServcOper' => '0',
            'cidtfdTpoVcto' => '0',
            'cindcdEconmMoeda' => '00006',
            'qmoedaNegocTitlo' => '0',
            'cindcdAceitSacdo' => 'N',
            'ctpoProteTitlo' => '0',
            'ctpoPrzProte' => '0',
            'ctpoProteDecurs' => '0',
            'ctpoPrzDecurs' => '0',
            'cformaEmisPplta' => '02',
            'cindcdPgtoParcial' => 'N',
            'qtdePgtoParcial' => '000',
            'filler1' => '',
            'ptxJuroVcto' => '0',
            'vdiaJuroMora' => '0',
            'qdiaInicJuro' => '0',
            'pmultaAplicVcto' => '0',
            'vmultaAtrsoPgto' => '0',
            'qdiaInicMulta' => '0',
            'pdescBonifPgto01' => '0',
            'vdescBonifPgto01' => '0',
            'dlimDescBonif1' => '',
            'pdescBonifPgto02' => '0',
            'vdescBonifPgto02' => '0',
            'dlimDescBonif2' => '',
            'pdescBonifPgto03' => '0',
            'vdescBonifPgto03' => '0',
            'dlimDescBonif3' => '',
            'ctpoPrzCobr' => '00',
            'pdescBonifPgto' => '0',
            'vdescBonifPgto' => '0',
            'dlimBonifPgto' => '',
            'vabtmtTitloCobr' => '0',
            'viofPgtoTitlo' => '0',
            'filler2' => '',
            'bancoDeb' => '000',
            'agenciaDeb' => '00000',
            'agenciaDebDv' => '0',
            'contaDeb' => '0000000000000',
            'bancoCentProt' => '000',
            'agenciaDvCentPr' => '00000',
            'isacdrAvalsTitlo' => '',
            'elogdrSacdrAvals' => '',
            'enroLogdrSacdr' => '',
            'ecomplLogdrSacdr' => '',
            'ccepSacdrTitlo' => '00000',
            'ccomplCepSacdr' => '000',
            'ebairoLogdrSacdr' => '',
            'imunSacdrAvals' => '',
            'csglUfSacdr' => '',
            'indCpfCnpjSacdr' => '',
            'nroCpfCnpjSacdr' => '00000000000000',
            'renderEletrSacdr' => '',
            'cdddFoneSacdr' => '',
            'cfoneSacdrTitlo' => '',
            'filler3' => '',
            'fase' => '1',
            'cindcdCobrMisto' => 'S',
            'ialiasAdsaoCta' => '',
            'iconcPgtoSpi' => '',
            'caliasAdsaoCta' => '',
            'ilinkGeracQrcd' => '',
            ...$native,
            'ctitloCobrCdent' => $externalId,
            'nroCpfCnpjBenef' => (string) $beneficiary['cpfCnpj'],
            'filCpfCnpjBenef' => str_pad((string) $beneficiary['filial'], 4, '0', STR_PAD_LEFT),
            'digCpfCnpjBenef' => str_pad((string) $beneficiary['controle'], 2, '0', STR_PAD_LEFT),
            'cidtfdProdCobr' => str_pad((string) $this->boletoProductCode($context), 2, '0', STR_PAD_LEFT),
            'cnegocCobr' => $this->registrationNegotiation($context),
            'ctitloCliCdent' => mb_substr($command->reference, 0, 25),
            'demisTitloCobr' => (new DateTimeImmutable)->format('d.m.Y'),
            'dvctoTitloCobr' => $command->dueAt?->format('d.m.Y'),
            'vnmnalTitloCobr' => (string) $command->amount->minor,
            'cespceTitloCobr' => str_pad((string) ($command->options['document_species_code'] ?? 2), 2, '0', STR_PAD_LEFT),
            'cctrlPartcTitlo' => mb_substr($command->idempotencyKey, 0, 25),
            'isacdoTitloCobr' => $payer['name'],
            'elogdrSacdoTitlo' => $payer['address'],
            'enroLogdrSacdo' => $payer['number'],
            'ecomplLogdrSacdo' => $payer['complement'],
            'ccepSacdoTitlo' => $payer['postal_root'],
            'ccomplCepSacdo' => $payer['postal_suffix'],
            'ebairoLogdrSacdo' => $payer['neighborhood'],
            'imunSacdoTitlo' => $payer['city'],
            'csglUfSacdo' => $payer['state'],
            'indCpfCnpjSacdo' => strlen($payer['tax_id']) === 14 ? '2' : '1',
            'nroCpfCnpjSacdo' => str_pad($payer['tax_id'], 14, '0', STR_PAD_LEFT),
            'renderEletrSacdo' => $payer['email'],
            'cdddFoneSacdo' => '',
            'cfoneSacdoTitlo' => '',
        ];
    }

    /** @return array<string,mixed> */
    private function normalBoletoUpdatePayload(
        ConnectionContext $context,
        string $externalId,
        ?DateTimeImmutable $dueAt,
        array $changes,
    ): array {
        $native = is_array($changes['options']['bradesco_payload'] ?? null)
            ? $changes['options']['bradesco_payload']
            : [];
        $title = [
            'seuNumero' => '',
            'dataEmissao' => 0,
            'especie' => '',
            'vencimento' => ['dataVencimento' => $dueAt ? (int) $dueAt->format('dmY') : 0, 'tipoVencimento' => 0],
            'protesto' => ['codInstrucaoProtesto' => 0, 'qtdeDiasProtesto' => 0],
            'decurso' => ['codDecursoPrazo' => 0, 'diasDecursoPrazo' => 0],
            'abatimento' => ['tipoAbatimento' => 0, 'valorAbatimento' => 0],
            'dataDesc1' => 0,
            'valDesc1' => 0,
            'codValDe1' => 0,
            'tipoDesc1' => 0,
            'dataDesc2' => 0,
            'valDesc2' => 0,
            'codValDe2' => 0,
            'tipoDesc2' => 0,
            'dataDesc3' => 0,
            'valDesc3' => 0,
            'codValDe3' => 0,
            'tipoDesc3' => 0,
            'codigoControleParticipante' => '',
            'indicadorAvisoSacado' => '',
            'comissaoPermanencia' => [
                'diasComissaoPermanencia' => 0,
                'valorComissaoPermanencia' => 0,
                'codigoComissaoPermanencia' => 0,
            ],
            'codigoMulta' => 0,
            'diasMulta' => 0,
            'valorMulta' => 0,
            'codigoNegativacao' => 0,
            'diasNegativacao' => 0,
            'pagamentoParcial' => '',
            'qtdePagamentoParcial' => 0,
            ...(is_array($native['dadosTitulo'] ?? null) ? $native['dadosTitulo'] : []),
        ];

        return [
            ...$native,
            ...$this->boletoIdentity($context, $externalId),
            'dadosPagador' => is_array($native['dadosPagador'] ?? null) ? $native['dadosPagador'] : [],
            'dadosTitulo' => $title,
        ];
    }

    /** @return array<string,mixed> */
    private function hybridBoletoUpdatePayload(
        ConnectionContext $context,
        string $externalId,
        ?DateTimeImmutable $dueAt,
        array $changes,
    ): array {
        $native = is_array($changes['options']['bradesco_payload'] ?? null)
            ? $changes['options']['bradesco_payload']
            : [];
        $beneficiary = $this->beneficiaryDocument($context);
        $title = [
            'seuNumero' => '',
            'dataEmissao' => 0,
            'especie' => '',
            'dataVencimento' => $dueAt ? (int) $dueAt->format('dmY') : 0,
            'codVencimento' => 0,
            'codInstrucaoProtesto' => 0,
            'diasProtesto' => 0,
            'codDecurso' => 0,
            'diasDecurso' => 0,
            'codAbatimento' => 0,
            'valorAbatimentoTitulo' => 0,
            'dataPrimeiroDesc' => 0,
            'valorPrimeiroDesc' => 0,
            'codPrimeiroDesc' => 0,
            'acaoPrimeiroDesc' => 0,
            'dataSegundoDesc' => 0,
            'valorSegundoDesc' => 0,
            'codSegundoDesc' => 0,
            'acaoSegundoDesc' => 0,
            'dataTerceiroDesc' => 0,
            'valorTerceiroDesc' => 0,
            'codTerceiroDesc' => 0,
            'acaoTerceiroDesc' => 0,
            'controleParticipante' => '',
            'idAvisoSacado' => '',
            'diasAposVencidoJuros' => 0,
            'valorJuros' => 0,
            'codJuros' => 0,
            'diasAposVencimentoMulta' => 0,
            'valorMulta' => 0,
            'codMulta' => 0,
            'codNegativacao' => 0,
            'diasNegativacao' => 0,
            'codPagamentoParcial' => 'N',
            'qtdePagamentosParciais' => 0,
            ...(is_array($native['dadosTitulo'] ?? null) ? $native['dadosTitulo'] : []),
        ];

        return [
            ...$native,
            'codUsuario' => 'OPENAPI',
            'chave' => [
                'cnpjCpf' => $beneficiary['cpfCnpj'],
                'filial' => $beneficiary['filial'],
                'controle' => str_pad((string) $beneficiary['controle'], 2, '0', STR_PAD_LEFT),
                'idprod' => $this->boletoProductCode($context),
                'ctaprod' => $this->operationNegotiation($context),
                'nossoNumero' => $externalId,
            ],
            'dadosTitulo' => $title,
        ];
    }

    /** @return array{cpfCnpj:int,filial:int,controle:int,produto:int,negociacao:int,nossoNumero:int} */
    private function boletoIdentity(ConnectionContext $context, string $externalId): array
    {
        $beneficiary = $this->beneficiaryDocument($context);

        return [
            'cpfCnpj' => [
                'cpfCnpj' => $beneficiary['cpfCnpj'],
                'filial' => $beneficiary['filial'],
                'controle' => $beneficiary['controle'],
            ],
            'produto' => $this->boletoProductCode($context),
            'negociacao' => $this->operationNegotiation($context),
            'nossoNumero' => (int) $externalId,
        ];
    }

    /** @return array{cpfCnpj:int,filial:int,controle:int} */
    private function beneficiaryDocument(ConnectionContext $context): array
    {
        $taxId = preg_replace('/\D+/', '', (string) data_get($context->credentials, 'products.boleto.beneficiary_tax_id')) ?? '';
        if (! in_array(strlen($taxId), [11, 14], true)) {
            throw new BradescoProviderException('CPF/CNPJ do beneficiário Bradesco não configurado.');
        }

        if (strlen($taxId) === 14) {
            return [
                'cpfCnpj' => (int) substr($taxId, 0, 8),
                'filial' => (int) substr($taxId, 8, 4),
                'controle' => (int) substr($taxId, 12, 2),
            ];
        }

        return [
            'cpfCnpj' => (int) substr($taxId, 0, 9),
            'filial' => 0,
            'controle' => (int) substr($taxId, 9, 2),
        ];
    }

    private function boletoProductCode(ConnectionContext $context): int
    {
        $code = (int) data_get($context->credentials, 'products.boleto.product_code', 0);
        if ($code < 1 || $code > 99) {
            throw new BradescoProviderException('Carteira do produto Cobrança Bradesco não configurada.');
        }

        return $code;
    }

    private function registrationNegotiation(ConnectionContext $context): string
    {
        $number = preg_replace('/\D+/', '', (string) data_get($context->credentials, 'products.boleto.negotiation_number')) ?? '';
        if (strlen($number) !== 18) {
            throw new BradescoProviderException('A negociação da Cobrança Bradesco deve conter 18 dígitos.');
        }

        return $number;
    }

    private function operationNegotiation(ConnectionContext $context): int
    {
        $number = $this->registrationNegotiation($context);

        return (int) (substr($number, 0, 4).substr($number, -7));
    }

    /** @return array{name:string,tax_id:string,address:string,number:string,complement:string,neighborhood:string,city:string,state:string,postal_root:string,postal_suffix:string,email:string} */
    private function bradescoBoletoPayer(array $payer): array
    {
        $taxId = preg_replace('/\D+/', '', (string) ($payer['cnpj'] ?? $payer['cpf'] ?? $payer['tax_id'] ?? '')) ?? '';
        $postalCode = preg_replace('/\D+/', '', (string) ($payer['cep'] ?? $payer['postal_code'] ?? '')) ?? '';
        $data = [
            'name' => $this->plainText((string) ($payer['nome'] ?? $payer['name'] ?? '')),
            'tax_id' => $taxId,
            'address' => $this->plainText((string) ($payer['endereco'] ?? $payer['address'] ?? '')),
            'number' => $this->plainText((string) ($payer['numero'] ?? $payer['number'] ?? '')),
            'complement' => $this->plainText((string) ($payer['complemento'] ?? $payer['complement'] ?? '')),
            'neighborhood' => $this->plainText((string) ($payer['bairro'] ?? $payer['neighborhood'] ?? '')),
            'city' => $this->plainText((string) ($payer['cidade'] ?? $payer['city'] ?? '')),
            'state' => strtoupper((string) ($payer['uf'] ?? $payer['state'] ?? '')),
            'postal_root' => substr(str_pad($postalCode, 8, '0', STR_PAD_LEFT), 0, 5),
            'postal_suffix' => substr(str_pad($postalCode, 8, '0', STR_PAD_LEFT), 5, 3),
            'email' => (string) ($payer['email'] ?? ''),
        ];
        $missing = array_filter(
            ['name', 'address', 'number', 'neighborhood', 'city', 'state'],
            fn (string $key): bool => $data[$key] === '',
        );
        if (! in_array(strlen($taxId), [11, 14], true) || strlen($postalCode) !== 8 || $missing !== []) {
            throw new BradescoProviderException('Informe nome, CPF/CNPJ e endereço completo do pagador para registrar o boleto Bradesco.');
        }

        return $data;
    }

    private function plainText(string $value): string
    {
        $converted = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', trim($value));
        $converted = is_string($converted) ? $converted : trim($value);

        return trim(preg_replace('/[^a-zA-Z0-9 ]+/', '', $converted) ?? '');
    }

    private function boletoSubtype(mixed $subtype): string
    {
        $subtype = $subtype ?: 'normal';
        if (! in_array($subtype, ['normal', 'hybrid'], true)) {
            throw new BradescoProviderException('Modalidade de boleto Bradesco inválida.');
        }

        return $subtype;
    }

    private function boletoIdentifier(mixed $value): string
    {
        $digits = preg_replace('/\D+/', '', (string) $value) ?? '';
        if ($digits !== '' && strlen($digits) <= 11 && (int) $digits > 0) {
            return str_pad($digits, 11, '0', STR_PAD_LEFT);
        }

        $number = ((int) hexdec(substr(hash('sha256', (string) $value), 0, 12)) % 99999999999) + 1;

        return str_pad((string) $number, 11, '0', STR_PAD_LEFT);
    }

    private function changedDueDate(array $changes): ?DateTimeImmutable
    {
        $value = $changes['due_at'] ?? null;

        return is_string($value) && $value !== '' ? new DateTimeImmutable($value) : null;
    }

    private function providerTxid(mixed $metadata): ?string
    {
        if (! is_array($metadata)) {
            return null;
        }

        return $this->nullable($this->value($metadata, [
            'iconcPgtoSpi',
            'txId',
            'txid',
            'data.iconcPgtoSpi',
            'body.iconcPgtoSpi',
        ]));
    }

    private function boletoResult(
        array $response,
        Money $fallbackAmount,
        string $fallbackId,
        string $fallbackStatus = 'pending',
    ): ReceivableResult {
        $status = $this->value($response, [
            'status10',
            'statusTitulo',
            'titulo.status',
            'Titulo.status',
            'dados.status',
        ]);
        $paidAt = $this->nullable($this->value($response, [
            'dataPagamento',
            'titulo.dataPagamento',
            'Titulo.dataPagamento',
        ]));

        return new ReceivableResult(
            externalId: (string) $this->value($response, [
                'nuTituloGerado',
                'ctitloCobrCdent',
                'nossoNumero',
                'titulo.nossoNumero',
                'Titulo.nossoNumero',
            ], $fallbackId),
            status: $status !== null ? $this->boletoStatus((string) $status) : $fallbackStatus,
            amount: $this->boletoMoneyFromResponse($response, $fallbackAmount),
            copyAndPaste: $this->nullable($this->value($response, ['wqrcdPdraoMercd', 'emv', 'pixCopiaECola'])),
            barcode: $this->nullable($this->value($response, ['cdBarras', 'codBarras', 'codBarras10', 'titulo.codigoBarras'])),
            digitableLine: $this->nullable($this->value($response, ['linhaDigitavel', 'linhaDig', 'linhaDig10', 'titulo.linhaDigitavel'])),
            paidAt: $paidAt ? $this->bradescoDate($paidAt) : null,
            metadata: $response,
        );
    }

    private function boletoMoneyFromResponse(array $response, ?Money $fallback = null): Money
    {
        $minor = $this->value($response, [
            'vlTitulo',
            'valMoeda10',
            'valorMoedaBol10',
            'vnmnalTitloCobr',
            'titulo.valorTitulo',
            'Titulo.valorTitulo',
        ]);
        if (is_numeric($minor)) {
            return new Money((int) $minor);
        }
        $decimal = $this->value($response, ['valorBoleto', 'valorPagamento', 'amount']);

        return $decimal !== null ? Money::fromDecimal((string) $decimal) : ($fallback ?? new Money(0));
    }

    private function boletoStatus(string $status): string
    {
        $status = mb_strtolower(trim($status));

        return match (true) {
            in_array($status, ['0', '00', '1', '01', 'a vencer/vencido', 'a vencer', 'vencido', 'active', 'ativo'], true) => 'active',
            str_contains($status, 'registr') || str_contains($status, 'abert') => 'active',
            str_contains($status, 'liquid') || str_contains($status, 'pag') => 'paid',
            str_contains($status, 'baix') || str_contains($status, 'cancel') => 'cancelled',
            str_contains($status, 'rejeit') || str_contains($status, 'erro') => 'failed',
            default => $status,
        };
    }

    private function bradescoDate(string $value): DateTimeImmutable
    {
        foreach (['Y-m-d', 'd/m/Y', 'dmY', DATE_ATOM] as $format) {
            $date = DateTimeImmutable::createFromFormat($format, $value);
            if ($date instanceof DateTimeImmutable) {
                return $date;
            }
        }

        return new DateTimeImmutable($value);
    }

    /**
     * O edge deve validar o certificado cliente do callback Bradesco e injetar
     * um segredo interno antes que a requisição alcance a aplicação.
     */
    public function verify(ConnectionContext $context, ServerRequestInterface $request): bool
    {
        $secret = (string) ($context->credentials['webhook_secret'] ?? '');
        $header = (string) ($context->credentials['webhook_header'] ?? 'Authorization');
        $received = $request->getHeaderLine($header);
        $received = str_starts_with($received, 'Bearer ') ? substr($received, 7) : $received;

        return $secret !== '' && hash_equals($secret, $received);
    }

    private function receivableResult(array $response, Money $fallbackAmount, string $fallbackId): ReceivableResult
    {
        $data = $this->firstObject($response, ['data', 'body', 'cobv']);
        $paidAt = $this->nullable($this->value($data, ['paidAt', 'dataLiquidacao', 'pix.0.horario', 'horario.liquidacao']));
        $copyAndPaste = $this->value($response, ['emv', 'pixCopiaECola', 'copyAndPaste', 'qrCode', 'qr_code', 'brcode', 'payload'])
            ?? $this->value($data, ['emv', 'pixCopiaECola', 'copyAndPaste', 'qrCode', 'qr_code', 'brcode', 'payload']);

        return new ReceivableResult(
            externalId: (string) $this->value($data, ['txid', 'id', 'externalId'], $fallbackId),
            status: $this->status((string) $this->value($data, ['status', 'situacao'], 'pending')),
            amount: $this->moneyFromResponse($data, $fallbackAmount),
            copyAndPaste: $this->nullable($copyAndPaste),
            paidAt: $paidAt ? new DateTimeImmutable($paidAt) : null,
            metadata: $response,
        );
    }

    private function moneyFromResponse(array $response, ?Money $fallback = null): Money
    {
        $value = $this->value($response, ['valor.0.original', 'valor.original', 'valor', 'amount']);

        return $value !== null ? Money::fromDecimal((string) $value) : ($fallback ?? new Money(0));
    }

    private function status(string $status): string
    {
        return match (mb_strtolower($status)) {
            'ativa', 'active', 'created' => 'active',
            'concluida', 'concluída', 'concluido', 'concluído', 'paid', 'liquidado', 'liquidada' => 'paid',
            'devolvido', 'devolvida', 'refunded' => 'refunded',
            'removida_pelo_usuario_recebedor', 'removida_pelo_psp', 'cancelado', 'cancelada', 'removed' => 'cancelled',
            'em_processamento', 'pending', 'pendente' => 'pending',
            'nao_realizado', 'não_realizado', 'failed', 'rejeitado', 'rejeitada' => 'failed',
            default => mb_strtolower($status),
        };
    }

    private function path(ConnectionContext $context, string $product, string $key, string $default): string
    {
        return (string) ($context->credentials['products'][$product]['paths'][$key] ?? $default);
    }

    /** @return list<array<string,mixed>> */
    private function items(array $response, array $keys): array
    {
        foreach ($keys as $key) {
            $value = $this->dot($response, $key);
            if (is_array($value) && array_is_list($value)) {
                return array_values(array_filter($value, 'is_array'));
            }
        }

        return array_is_list($response) ? array_values(array_filter($response, 'is_array')) : [];
    }

    /** @param array<string,mixed> $item */
    private function receipt(array $item): CanonicalTransaction
    {
        $endToEndId = $this->nullable($this->value($item, ['endToEndId', 'e2eid']));
        $txid = $this->nullable($this->value($item, ['txid', 'txId']));
        $pixKey = $this->nullable($this->value($item, ['chave', 'pixKey']));

        return new CanonicalTransaction(
            externalId: $endToEndId,
            type: 'pix',
            direction: TransactionDirection::Credit,
            status: TransactionStatus::Posted,
            amount: Money::fromDecimal((string) $this->value($item, ['valor', 'amount'], '0')),
            occurredAt: new DateTimeImmutable((string) $this->value($item, ['horario', 'paidAt'], 'now')),
            observedAt: new DateTimeImmutable,
            description: $this->nullable($this->value($item, ['infoPagador', 'descricao'], 'Pix recebido')),
            counterpartyName: $this->nullable($this->value($item, ['pagador.nome', 'payer.name'])),
            counterpartyTaxId: $this->nullable($this->value($item, ['pagador.cpf', 'pagador.cnpj', 'payer.taxId'])),
            identifiers: array_filter([
                'end_to_end_id' => $endToEndId,
                'txid' => $txid,
                'pix_key' => $pixKey,
            ], fn (?string $value): bool => $value !== null && $value !== ''),
            metadata: $item,
        );
    }

    /** @return array<string,mixed> */
    private function firstObject(array $response, array $keys): array
    {
        foreach ($keys as $key) {
            $value = $this->dot($response, $key);
            if (is_array($value) && ! array_is_list($value)) {
                return $value;
            }
        }

        return $response;
    }

    private function value(array $data, array $keys, mixed $default = null): mixed
    {
        foreach ($keys as $key) {
            $value = $this->dot($data, $key);
            if ($value !== null && $value !== '') {
                return $value;
            }
        }

        return $default;
    }

    private function dot(array $data, string $key): mixed
    {
        $value = $data;
        foreach (explode('.', $key) as $segment) {
            if (! is_array($value) || ! array_key_exists($segment, $value)) {
                return null;
            }
            $value = $value[$segment];
        }

        return $value;
    }

    private function nullable(mixed $value): ?string
    {
        return $value === null || $value === '' ? null : (string) $value;
    }

    /** @param array<string,mixed> $payer */
    private function payer(array $payer): array
    {
        return array_filter([
            'cpf' => $payer['cpf'] ?? null,
            'cnpj' => $payer['cnpj'] ?? null,
            'nome' => $payer['nome'] ?? $payer['name'] ?? null,
            'email' => $payer['email'] ?? null,
            'logradouro' => $payer['logradouro'] ?? $payer['address'] ?? null,
            'cidade' => $payer['cidade'] ?? $payer['city'] ?? null,
            'uf' => $payer['uf'] ?? $payer['state'] ?? null,
            'cep' => $payer['cep'] ?? $payer['postal_code'] ?? null,
        ], fn (mixed $value): bool => $value !== null && $value !== '');
    }

    private function identifier(string $value, int $minimum, int $maximum): string
    {
        $normalized = preg_replace('/[^a-zA-Z0-9]/', '', $value) ?? '';
        if (strlen($normalized) >= $minimum && strlen($normalized) <= $maximum) {
            return $normalized;
        }

        return substr(hash('sha256', $value), 0, max($minimum, min(32, $maximum)));
    }
}
