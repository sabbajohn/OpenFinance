<?php
/**
 * PagamentosApi
 * PHP version 8.1
 *
 * @category Class
 * @package  OpenAPI\Client
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 */

/**
 * API Payment Initiation - Open Finance Brasil
 *
 * API de Iniciação de Pagamentos, responsável por viabilizar as operações de iniciação de pagamentos para o Open Finance Brasil. Para cada uma das formas de pagamento previstas é necessário obter prévio consentimento do cliente através dos `endpoints` dedicados ao consentimento nesta API.  # Orientações No diretório de participantes duas `Roles` estão relacionadas à presente API: - `CONTA`, referente às instituições detentoras de conta participantes do Open Finance Brasil; - `PAGTO`, referente às instituições iniciadoras de pagamento participantes do Open Finance Brasil.  Os tokens utilizados para consumo nos endpoints de consentimentos devem possuir o scope `payments` e os `endpoints` de pagamentos devem possuir os `scopes`, `openid` e `payments`.  Esta API não requer a implementação de `permissions` para sua utilização. Todas as requisições e respostas devem ser assinadas seguindo o protocolo estabelecido na sessão <a href=\"https://openbanking-brasil.github.io/areadesenvolvedor/#assinaturas\" target=\"_blank\">Assinaturas</a> do guia de segurança.  ## Regras do arranjo Pix A implementação e o uso da API de Pagamentos Pix devem seguir as regras do arranjo Pix do Banco Central, que podem ser encontradas no link abaixo:    [https://www.bcb.gov.br/estabilidadefinanceira/pix?modalAberto=regulamentacao_pix](https://www.bcb.gov.br/estabilidadefinanceira/pix?modalAberto=regulamentacao_pix)  ## Assinatura de payloads  No contexto da API Payment Initiation, os `payloads` de mensagem que trafegam tanto por parte da instituição iniciadora de transação de pagamento quanto por parte da instituição detentora de conta devem estar assinados. Para o processo de assinatura destes `payloads` as instituições devem seguir as especificações de segurança publicadas no Portal do desenvolvedor:  - Certificados exigidos para assinatura de mensagens: [[EN] Padrão de Certificados Open Finance Brasil 2.1](https://openfinancebrasil.atlassian.net/wiki/spaces/OF/pages/82084176/EN+Padr+o+de+Certificados+Open+Finance+Brasil+2.1%20%E2%80%8B)  - Como assinar o payload JWS: [Como Assinar o Payload](https://openfinancebrasil.atlassian.net/wiki/spaces/OF/pages/180061175/Como+Assinar+o+Payload+-+v3.0.0+-+Pagamentos)  ## Controle de acesso  Os endpoints de consulta e cancelamento devem suportar somente acesso a partir de access_token emitido por meio de um grant_type do tipo client_credentials.  Para a criação do consentimento deve-se utilizar client_credentials e para criação de pagamentos deve-se utilizar authorization_code.  ## Aprovações de múltipla alçada  - Para o caso de pagamento imediato, todas as aprovações necessárias devem ser realizadas nos canais da detentora até às 23:59 (horário de Brasília) da data de solicitação do pagamento.  Já para o caso de pagamento agendado, todas as aprovações devem ser realizadas até o exato dia anterior à data/hora prevista para primeira liquidação, respeitando a data/hora limite suportada pela detentora.  Caso não seja possível aprovação, o consentimento deve ser rejeitado pelo detentor.  ## Validações para pagamentos recorrentes  - No cenário onde o usuário pagador tenha agendado recorrências para os dias 29, 30 ou 31 de cada mês e o dia previsto na recorrência não exista no respectivo mês,  o iniciador deve enviar a ordem de pagamento para liquidação com o endToEndId representando o dia seguinte à data prevista para a liquidação.  Se identificado pelo detentor que a data enviada no endToEndId corresponde a um dia inexistente, ele deve rejeitar o pagamento com erro 422,  com código PARAMETRO_INVALIDO e detalhe “Data de liquidação inválida”  - Quando o detentor receber mais de um item na lista de pagamentos enviados pelo iniciador e optar por responder  assincronamente, é de responsabilidade do detentor realizar a transição para o status SCHD de todos os itens enviados na  lista de pagamentos em até 60 minutos (contados a partir da resposta de sucesso da solicitação).  Caso não seja possível realizar a transição de todos os pagamentos para SCHD, o detentor deverá mover todos os pagamentos  enviados pelo iniciador naquela mesma requisição para RJCT e preencher o motivo de rejeição correspondente,  FALHA_AGENDAMENTO_PAGAMENTOS. O consentimento irá para CONSUMED.  ## Validações **Validações** (*após o processo de DCR e obtenção de token client credential*– não escopo dessa documentação)   Durante a jornada de iniciação de pagamento, diferentes validações são necessárias pela instituição detentora  de conta e devem ocorrer conforme a seguir:   1. Na criação do consentimento (*POST /consents*);  2. Na criação do pagamento - Síncrono (*POST /payments*);  3. Validações na consulta do pagamento (*GET /pix/payments/{paymentId}*); 4. Demais validações executadas durante o processamento assíncrono do pagamento pela detentora, poderão ser consultados pela iniciadora através do endpoint (*GET /pix/payments/{paymentId}*) previstos com retorno HTTP Code 200 - OK com status RJCT (Rejected) e rejectionReason; 5. Demais validações executadas durante o processamento assíncrono do consentimento pela detentora poderão ser consultados pela iniciadora através do endpoint (*GET /consents/{consentId}*) previstos com retorno HTTP Code 200 – OK com status REJECTED e rejectionReason  **Os tipos de validações dispostas abaixo não determinam a ordem em que as instituições devem implementá-las**  1. **Validações na criação do consentimento (_POST /consents_)**     1.1 **Orientações Iniciais**       &ensp;1.1.1 Não devem ser retornadas na resposta deste endpointinformações associadas ao usuário/cliente (ex.  insuficiência de saldo, conta inexistente/bloqueada).       &ensp;1.1.2 Não devem ser executadas validações no DICT (Diretório de Identificadores de Contas Transacionais do Pix), a partir dos dados compartilhados nesse *endpoint*. Tais  validações podem ocorrer somente na criação do pagamento;        &ensp;1.1.3 Não devem ser realizadas validações de informações sobre o usuário/cliente durante a criação do consentimento.   1.2 **Casos de erro relacionados às permissões de segurança para acesso à API (ex. certificado, access_token, jwt, assinatura)**       &ensp;1.2.1 Validação de Certificado: Valida utilização de certificado correto durante processo de DCR - HTTP Code 401 (INVALID_CLIENT);       &ensp;1.2.2 Validação de Access_Token: Verifica se Access_Token utilizado está correto - HTTP Code 401 (UNAUTHORIZED);       &ensp;1.2.3 Validação de assinatura da mensagem: Valida se assinatura das mensagens enviadas está correta – HTTP Code 400 (BAD_SIGNATURE);       &ensp;1.2.4 Validação de Claims (exceto data);         &emsp;1.2.4.1 Valida se dados (aud, iss, iat e jti) são válidos - HTTP status code 403 – (INVALID_CLIENT);         &emsp;1.2.4.2 Valida reuso de jti - HTTP Code 403 (INVALID_CLIENT).     1.3 **Casos de erro sintáticos e semânticos, previstos com retorno HTTP Code 422 - Unprocessable Entity (detalhamento adicional na documentação técnica da API):**        &ensp;1.3.1 **Sintáticos**         &emsp;1.3.1.1 Envio de campos obrigatórios: Valida se todos os campos obrigatórios são informados (PARAMETRO_NAO_INFORMADO);         &emsp;1.3.1.2 Formatação de parâmetros: Valida se parâmetros informados obedecem a formatação especificada (PARAMETRO_INVALIDO).       &ensp;1.3.2 **Semânticos**         &emsp;1.3.2.1 Forma de pagamento: Valida se a forma de pagamento é suportada pela detentora (FORMA_PAGAMENTO_INVALIDA) **Obs. No detalhe do erro, a variável “modalidade” deve ser comunicada pela detentora da forma mais clara possível - ex. modalidade de pagamento não suportada (_localInstrument_ - QRES) ou tipo de arranjo pagamento não suportado (_type_ – ex. Pix / TED – previsto para inclusão futura);**         &emsp;1.3.2.2 Data de pagamento: Valida se a data de pagamento enviada é válida para a forma de pagamento selecionada (DATA_PAGAMENTO_INVALIDA);         &emsp;1.3.2.3 Detalhes do pagamento: Valida se determinado parâmetro informado obedece às regras de negócio (DETALHE_PAGAMENTO_INVALIDO);         &emsp;1.3.2.4 Demais validações não explicitamente informadas (ex. suspeita de fraude): (NAO_INFORMADO);         &emsp;1.3.2.5 Idempotência: Valida se há divergência entre chave de idempotência e informações enviadas (ERRO_IDEMPOTENCIA).    2. **Validações na criação do pagamento - Síncrono (_POST /payments_)**     2.1 **Casos de erro relacionados às permissões de segurança para acesso à API (ex. certificado, access_token, jwt, assinatura)**       &ensp;2.1.1 Validação de Certificado: Valida utilização de certificado correto durante processo de DCR - HTTP Code 401 (INVALID_CLIENT);       &ensp;2.1.2 Validação de Access_Token: Verifica se Access_Token utilizado está correto - HTTP Code 401 (UNAUTHORIZED);       &ensp;2.1.3 Validação de assinatura da mensagem: Valida se assinatura das mensagens enviadas está correta – HTTP Code 400 (BAD_SIGNATURE);       &ensp;2.1.4 Validação de Claims (exceto data);         &emsp;2.1.4.1 Valida se dados (aud, iss, iat e jti) são válidos - HTTP status code 403 – (INVALID_CLIENT);         &emsp;2.1.4.2 Valida reuso de jti - HTTP Code 403 (INVALID_CLIENT).     2.2 **Casos de erro sintáticos e semânticos, previstos com retorno HTTP Code 422 - Unprocessable Entity (detalhamento adicional na documentação técnica da API):**       &ensp;2.2.1 **Sintáticos**         &emsp;2.3.1.1 Envio de campos obrigatórios: Valida se todos os campos obrigatórios são informados (PARAMETRO_NAO_INFORMADO);         &emsp;2.3.1.2 Formatação de parâmetros: Valida se parâmetros informados obedecem a formatação especificada (PARAMETRO_INVALIDO).       &ensp;2.2.2 **Semânticos**         &emsp;2.2.2.1 Saldo do usuário: Valida se a conta selecionada possui saldo suficiente para realizar o pagamento (SALDO_INSUFICIENTE);         &emsp;2.2.2.2 Limites da transação: Valida se valor (ou quantidade de transações) ultrapassa faixa de limite parametrizada na detentora (VALOR_ACIMA_LIMITE);         &emsp;2.2.2.3 Valor informado (QR Code): Valida se valor enviado é válido para o QR Code informado (VALOR_INVALIDO);         &emsp;2.2.2.4 Cobrança inválida: Valida expiração, vencimento e status (COBRANCA_INVALIDA);         &emsp;2.2.2.5 Status Consentimento: Valida se o consentimento encontra-se em um dos estados finais “CONSUMED” ou “REJECTED\" (CONSENTIMENTO_INVALIDO);         &emsp;2.2.2.6 Divergência entre pagamento e consentimento: Valida se dados do pagamento são diferentes dos dados do consentimento (PAGAMENTO_DIVERGENTE_CONSENTIMENTO)         &emsp;2.2.2.7 Recusado pela detentora: Valida se pagamento foi recusado pela detentora (PAGAMENTO_RECUSADO_DETENTORA), com a descrição do motivo de recusa (ex. chave Pix inválida, QRCode inválido, conta bloqueada);         &emsp;2.2.2.8 Detalhes do pagamento: Valida se determinado parâmetro informado obedece às regras de negócio (DETALHE_PAGAMENTO_INVALIDO);         &emsp;2.2.2.9 Demais validações não explicitamente informadas (ex. suspeita de fraude): (NAO_INFORMADO);         &emsp;2.2.2.10 Idempotência: Valida se há divergência entre chave de idempotência e informações enviadas (ERRO_IDEMPOTENCIA);         &emsp;2.2.2.11 Consentimento pendente de autorização: Em `PARTIALLY_ACCEPTED` aguardando aprovação de múltiplas alçadas. Não consome nem invalida o consentimento (CONSENTIMENTO_PENDENTE_AUTORIZACAO).     2.3 **Casos de erro para validações síncronas no DICT**       &ensp;Nesse cenário, o pagamento não é criado, porém o consentimento deve ser alterado para o status CONSUMED Retorno esperado do endpoint POST/Payments: HTTP Code 422 - Unprocessable Entity:       &ensp;• Erro por dados inválidos: Conforme item **2.2.2.8**       &ensp;• Erro por suspeita de fraude: Conforme item **2.2.2.9**    3. **Validações na consulta do pagamento (_GET /pix/payments/{paymentId}_)**     3.1 **Casos de erro relacionados às permissões de segurança para acesso à API (ex. certificado, access_token)**       &ensp;3.1.1 Validação de Certificado: Valida utilização de certificado correto durante processo de DCR - HTTP Code 401 (INVALID_CLIENT);       &ensp;3.1.2 Validação de Access_Token: Verifica se Access_Token utilizado está correto - HTTP Code 401 (UNAUTHORIZED).    4. **Demais validações executadas durante o processamento assíncrono do pagamento pela detentora, poderão ser consultados pela iniciadora através do endpoint _GET /pix/payments/{paymentId}_ previstos com retorno HTTP Code 200 - OK com status RJCT (Rejected) e rejectionReason conforme abaixo (detalhamento adicional na documentação técnica da API):**     4.1 **Demais validações durante processamento assíncrono**       &ensp;4.1.1 Saldo do usuário: Valida se a conta selecionada possui saldo suficiente para realizar o pagamento. No caso de um pagamento agendado, a validação só ocorre na tentativa de liquidação do pagamento (SALDO_INSUFICIENTE);       &ensp;4.1.2 Limites da transação: Valida se valor (ou quantidade de transações) ultrapassa faixa de limite parametrizada na detentora (VALOR_ACIMA_LIMITE);       &ensp;4.1.3 Valor informado (QR Code): Valida se valor enviado é válido para o QR Code informado (VALOR_INVALIDO);       &ensp;4.1.4 Cobrança inválida: Valida expiração, vencimento e status (COBRANCA_INVALIDA);       &ensp;4.1.5 Divergência entre pagamento e consentimento: Valida se dados do pagamento são diferentes dos dados do consentimento (PAGAMENTO_DIVERGENTE_CONSENTIMENTO);       &ensp;4.1.6 Recusado pela detentora: Valida se pagamento foi recusado pela detentora (PAGAMENTO_RECUSADO_DETENTORA), com a descrição do motivo de recusa (ex. chave Pix inválida, QRCode inválido, conta bloqueada);       &ensp;4.1.7 Detalhes do pagamento: Valida se determinado parâmetro informado obedece às regras de negócio (DETALHE_PAGAMENTO_INVALIDO);       &ensp;4.1.8 Demais validações não explicitamente informadas (ex. suspeita de fraude): (NAO_INFORMADO);       &ensp;4.1.9 Validação SPI: Externaliza validações no SPI (PAGAMENTO_RECUSADO_SPI);       &ensp;4.1.10 Falha em agendamentos: Uma ou mais incidências de pagamento não foram possíveis de ser agendadas (FALHA_AGENDAMENTO_PAGAMENTOS);     4.2 **Casos de erro para validações assíncronas no DICT**       &ensp;Neste cenário o pagamento é criado com sucesso (status RCVD) e o consentimento é consumido (status CONSUMED), porém, as validações contra o DICT só ocorrerão de forma assíncrona e em caso de negativa será percebido pela iniciadora na consulta do pagamento (GET /Payments).       &ensp;Retorno esperado do endpoint GET /Payments: HTTP Code 200 - OK.       &ensp;Status do Pagamento: RJCT (Rejected), com as seguintes opções rejectionReason:       &ensp;• Erro por dados inválidos: Conforme item **4.1.7**;       &ensp;• Erro por suspeita de fraude: Conforme item **4.1.8**.  5. **Demais validações executadas durante o processamento assíncrono do consentimento pela detentora poderão ser consultados pela iniciadora através do endpoint _GET /consents/{consentId}_ previstos com retorno HTTP Code 200 – OK com status REJECTED e rejectionReason conforme abaixo:**     5.1 **Validações durante o processamento assíncrono**       &ensp;5.1.1 - Falha de infraestrutura: Ocorreu algum erro interno na detentora durante processamento da criação do consentimento (FALHA_INFRAESTRUTURA)       &ensp;5.1.2 - Tempo de autorização expirado: O usuário não confirmou o consentimento e o mesmo expirou (TEMPO_EXPIRADO_AUTORIZACAO);       &ensp;5.1.3 - Rejeitado pelo usuário: O usuário explicitamente rejeitou a autorização do consentimento (REJEITADO_USUARIO);       &ensp;5.1.4 - Mesma conta origem/destino: A conta indicada pelo usuário para recebimento é a mesma selecionada para o pagamento (CONTAS_ORIGEM_DESTINO_IGUAIS);       &ensp;5.1.5 - Tipo de conta inválida: A conta indicada não permite operações de pagamento (CONTA_NAO_PERMITE_PAGAMENTO);       &ensp;5.1.6 - Saldo do usuário: Valida se a conta selecionada possui saldo suficiente para realizar o pagamento. Essa validação não deverá ocorrer no caso de um pagamento agendado (SALDO_INSUFICIENTE);       &ensp;5.1.7 - Limites da transação: Valida se o valor ultrapassa o limite estabelecido [na instituição/no arranjo/outro] para permitir a realização de transações pelo cliente (VALOR_ACIMA_LIMITE);       &ensp;5.1.8 - QRCode inválido: O QRCode utilizado para a iniciação de pagamento não é válido (QRCODE_INVALIDO);       &ensp;5.1.9 - Valor inválido: O valor enviado não é válido para o QR Code informado (VALOR_INVALIDO);       &ensp;5.1.10 - Não informado: Demais validações não explicitamente informadas (ex. suspeita de fraude) e consentimentos rejeitados em versões que não existiam o campo rejectionReason na API de Pagamentos (NAO_INFORMADO)       &ensp;5.1.11 - Tempo expirado consumo: O usuário não finalizou o fluxo de pagamento e o consentimento expirou (TEMPO_EXPIRADO_CONSUMO).     5.2 **[Momentos obrigatórios de validação dos rejectionReasons de acordo com o funil de consentimentos.](https://openfinancebrasil.atlassian.net/wiki/spaces/OF/pages/150863940) Para casos em que um consentimento for rejeitado por mais de um motivo, seguir a ordem de prioridade da tabela.**      ```   |----------------------------------|------------------------------|---------------------|   | Etapas do funil de consentimento | rejectionReason/code         | Ordem de prioridade |   |----------------------------------|------------------------------|---------------------|   |                                  | TEMPO_EXPIRADO_AUTORIZACAO   |          1          |   | Início da autenticação           | FALHA_INFRAESTRUTURA         |          2          |   |                                  | NAO_INFORMADO                |          3          |   |----------------------------------|------------------------------|---------------------|   |                                  | TEMPO_EXPIRADO_AUTORIZACAO   |          1          |   |                                  | REJEITADO_USUARIO            |          2          |   | Conclusão da autenticação        | FALHA_INFRAESTRUTURA         |          3          |   |                                  | NAO_INFORMADO                |          4          |   |----------------------------------|------------------------------|---------------------|   |                                  | CONTA_NAO_PERMITE_PAGAMENTO  |          1          |   |                                  | CONTAS_ORIGEM_DESTINO_IGUAIS |          2          |   |                                  | VALOR_INVALIDO               |          3          |   | Autorização do cliente           | QRCODE_INVALIDO              |          4          |   |                                  | VALOR_ACIMA_LIMITE           |          5          |   |                                  | SALDO_INSUFICIENTE           |          6          |   |                                  | FALHA_INFRAESTRUTURA         |          7          |   |                                  | NAO_INFORMADO                |          8          |   |----------------------------------|------------------------------|---------------------|   |                                  | FALHA_INFRAESTRUTURA         |          1          |   | Authorisation code emitido       | NAO_INFORMADO                |          2          |   |                                  | TEMPO_EXPIRADO_CONSUMO       |          3          |   |----------------------------------|------------------------------|---------------------|   ```   Existem dois `endpoints` para cancelamento de pagamentos, um deles é o _PATCH /pix/payments/{paymentId}_ e o outro é o _PATCH /pix/payments/consents/{consentId}_.   - O _PATCH /pix/payments/{paymentId}_ deve ser utilizado para o cancelamento de um pagamento de forma unitária. Não deve ser utilizado para o cancelamento de todos os agendamentos recorrentes associados a um consentimento.   - O _PATCH /pix/payments/consents/{consentId}_ deve ser utilizado no cancelamento de todas as ocorrências de pagamentos agendados presentes em uma recorrência de pagamentos. Todos os pagamentos associados ao consentimento informado e passíveis de cancelamento (ainda não liquidados, com os status PDNG e SCHD) deverão ser cancelados.      ## Quantidade máxima permitida para agendamentos recorrentes   A quantidade máxima de pagamentos que podem transitar do iniciador para o detentor são de 60 pagamentos, independente do modelo de recorrência definido no consentimento, respeitando o prazo máximo de dois anos para agendamentos.    Caso a opção de recorrência enviada pelo iniciador não respeite a regra acima, o detentor deve retornar o erro 422 \"PARAMETRO_INVALIDO\" com o detalhe \"Quantidade permitida de pagamentos excedida\".
 *
 * The version of the OpenAPI document: 4.0.0
 * Contact: gt-interfaces@openbankingbr.org
 * Generated by: https://openapi-generator.tech
 * Generator version: 7.17.0
 */

/**
 * NOTE: This class is auto generated by OpenAPI Generator (https://openapi-generator.tech).
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */

namespace OpenAPI\Client\Api;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\MultipartStream;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use OpenAPI\Client\ApiException;
use OpenAPI\Client\Configuration;
use OpenAPI\Client\FormDataProcessor;
use OpenAPI\Client\HeaderSelector;
use OpenAPI\Client\ObjectSerializer;

/**
 * PagamentosApi Class Doc Comment
 *
 * @category Class
 * @package  OpenAPI\Client
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 */
class UpaymentsPagamentosApi
{
    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @var Configuration
     */
    protected $config;

    /**
     * @var HeaderSelector
     */
    protected $headerSelector;

    /**
     * @var int Host index
     */
    protected $hostIndex;

    /** @var string[] $contentTypes **/
    public const contentTypes = [
        'paymentsGetConsentsConsentId' => [
            'application/json',
        ],
        'paymentsGetPixPaymentsPaymentId' => [
            'application/json',
        ],
        'paymentsPatchPixPaymentsConsentId' => [
            'application/jwt',
        ],
        'paymentsPatchPixPaymentsPaymentId' => [
            'application/jwt',
        ],
        'paymentsPostConsents' => [
            'application/jwt',
        ],
        'paymentsPostPixPayments' => [
            'application/jwt',
        ],
    ];

    /**
     * @param ClientInterface $client
     * @param Configuration   $config
     * @param HeaderSelector  $selector
     * @param int             $hostIndex (Optional) host index to select the list of hosts if defined in the OpenAPI spec
     */
    public function __construct(
        ?ClientInterface $client = null,
        ?Configuration $config = null,
        ?HeaderSelector $selector = null,
        int $hostIndex = 0
    ) {
        $this->client = $client ?: new Client();
        $this->config = $config ?: Configuration::getDefaultConfiguration();
        $this->headerSelector = $selector ?: new HeaderSelector();
        $this->hostIndex = $hostIndex;
    }

    /**
     * Set the host index
     *
     * @param int $hostIndex Host index (required)
     */
    public function setHostIndex($hostIndex): void
    {
        $this->hostIndex = $hostIndex;
    }

    /**
     * Get the host index
     *
     * @return int Host index
     */
    public function getHostIndex()
    {
        return $this->hostIndex;
    }

    /**
     * @return Configuration
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Operation paymentsGetConsentsConsentId
     *
     * Consultar consentimento para iniciação de pagamento.
     *
     * @param  string $consent_id O consentId é o identificador único do consentimento e deverá ser um URN - Uniform Resource Name.   Um URN, conforme definido na [RFC8141](https://tools.ietf.org/html/rfc8141) é um Uniform Resource  Identifier - URI - que é atribuído sob o URI scheme \&quot;urn\&quot; e um namespace URN específico, com a intenção de que o URN  seja um identificador de recurso persistente e independente da localização.   Considerando a string urn:bancoex:C1DD33123 como exemplo para consentId temos: - o namespace(urn) - o identificador associado ao namespace da instituição transnmissora (bancoex)  - o identificador específico dentro do namespace (C1DD33123).   Informações mais detalhadas sobre a construção de namespaces devem ser consultadas na [RFC8141](https://tools.ietf.org/html/rfc8141). (required)
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser “espelhado” pela detentora (server) no cabeçalho de resposta. Caso não seja enviado pela iniciadora, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. Caso recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400 ou 422 (com o código PARAMETRO_INVALIDO). A iniciadora deve acatar o valor gerado pelo detentor e recebido na resposta. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['paymentsGetConsentsConsentId'] to see the possible values for this operation
     *
     * @throws \OpenAPI\Client\ApiException on non-2xx response or if the response body is not in the expected format
     * @throws \InvalidArgumentException
     * @return \OpenAPI\Client\Model\ResponsePaymentConsent|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError
     */
    public function paymentsGetConsentsConsentId($consent_id, $authorization, $x_fapi_interaction_id, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, string $contentType = self::contentTypes['paymentsGetConsentsConsentId'][0])
    {
        list($response) = $this->paymentsGetConsentsConsentIdWithHttpInfo($consent_id, $authorization, $x_fapi_interaction_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $contentType);
        return $response;
    }

    /**
     * Operation paymentsGetConsentsConsentIdWithHttpInfo
     *
     * Consultar consentimento para iniciação de pagamento.
     *
     * @param  string $consent_id O consentId é o identificador único do consentimento e deverá ser um URN - Uniform Resource Name.   Um URN, conforme definido na [RFC8141](https://tools.ietf.org/html/rfc8141) é um Uniform Resource  Identifier - URI - que é atribuído sob o URI scheme \&quot;urn\&quot; e um namespace URN específico, com a intenção de que o URN  seja um identificador de recurso persistente e independente da localização.   Considerando a string urn:bancoex:C1DD33123 como exemplo para consentId temos: - o namespace(urn) - o identificador associado ao namespace da instituição transnmissora (bancoex)  - o identificador específico dentro do namespace (C1DD33123).   Informações mais detalhadas sobre a construção de namespaces devem ser consultadas na [RFC8141](https://tools.ietf.org/html/rfc8141). (required)
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser “espelhado” pela detentora (server) no cabeçalho de resposta. Caso não seja enviado pela iniciadora, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. Caso recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400 ou 422 (com o código PARAMETRO_INVALIDO). A iniciadora deve acatar o valor gerado pelo detentor e recebido na resposta. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['paymentsGetConsentsConsentId'] to see the possible values for this operation
     *
     * @throws \OpenAPI\Client\ApiException on non-2xx response or if the response body is not in the expected format
     * @throws \InvalidArgumentException
     * @return array of \OpenAPI\Client\Model\ResponsePaymentConsent|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError, HTTP status code, HTTP response headers (array of strings)
     */
    public function paymentsGetConsentsConsentIdWithHttpInfo($consent_id, $authorization, $x_fapi_interaction_id, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, string $contentType = self::contentTypes['paymentsGetConsentsConsentId'][0])
    {
        $request = $this->paymentsGetConsentsConsentIdRequest($consent_id, $authorization, $x_fapi_interaction_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $contentType);

        try {
            $options = $this->createHttpClientOption();
            try {
                $response = $this->client->send($request, $options);
            } catch (RequestException $e) {
                throw new ApiException(
                    "[{$e->getCode()}] {$e->getMessage()}",
                    (int) $e->getCode(),
                    $e->getResponse() ? $e->getResponse()->getHeaders() : null,
                    $e->getResponse() ? (string) $e->getResponse()->getBody() : null
                );
            } catch (ConnectException $e) {
                throw new ApiException(
                    "[{$e->getCode()}] {$e->getMessage()}",
                    (int) $e->getCode(),
                    null,
                    null
                );
            }

            $statusCode = $response->getStatusCode();


            switch($statusCode) {
                case 200:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponsePaymentConsent',
                        $request,
                        $response,
                    );
                case 400:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 401:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 403:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 404:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 405:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 406:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 500:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 529:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                default:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
            }

            

            if ($statusCode < 200 || $statusCode > 299) {
                throw new ApiException(
                    sprintf(
                        '[%d] Error connecting to the API (%s)',
                        $statusCode,
                        (string) $request->getUri()
                    ),
                    $statusCode,
                    $response->getHeaders(),
                    (string) $response->getBody()
                );
            }

            return $this->handleResponseWithDataType(
                '\OpenAPI\Client\Model\ResponsePaymentConsent',
                $request,
                $response,
            );
        } catch (ApiException $e) {
            switch ($e->getCode()) {
                case 200:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponsePaymentConsent',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 400:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 401:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 403:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 404:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 405:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 406:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 500:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 529:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                default:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
            }
        

            throw $e;
        }
    }

    /**
     * Operation paymentsGetConsentsConsentIdAsync
     *
     * Consultar consentimento para iniciação de pagamento.
     *
     * @param  string $consent_id O consentId é o identificador único do consentimento e deverá ser um URN - Uniform Resource Name.   Um URN, conforme definido na [RFC8141](https://tools.ietf.org/html/rfc8141) é um Uniform Resource  Identifier - URI - que é atribuído sob o URI scheme \&quot;urn\&quot; e um namespace URN específico, com a intenção de que o URN  seja um identificador de recurso persistente e independente da localização.   Considerando a string urn:bancoex:C1DD33123 como exemplo para consentId temos: - o namespace(urn) - o identificador associado ao namespace da instituição transnmissora (bancoex)  - o identificador específico dentro do namespace (C1DD33123).   Informações mais detalhadas sobre a construção de namespaces devem ser consultadas na [RFC8141](https://tools.ietf.org/html/rfc8141). (required)
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser “espelhado” pela detentora (server) no cabeçalho de resposta. Caso não seja enviado pela iniciadora, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. Caso recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400 ou 422 (com o código PARAMETRO_INVALIDO). A iniciadora deve acatar o valor gerado pelo detentor e recebido na resposta. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['paymentsGetConsentsConsentId'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function paymentsGetConsentsConsentIdAsync($consent_id, $authorization, $x_fapi_interaction_id, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, string $contentType = self::contentTypes['paymentsGetConsentsConsentId'][0])
    {
        return $this->paymentsGetConsentsConsentIdAsyncWithHttpInfo($consent_id, $authorization, $x_fapi_interaction_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $contentType)
            ->then(
                function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation paymentsGetConsentsConsentIdAsyncWithHttpInfo
     *
     * Consultar consentimento para iniciação de pagamento.
     *
     * @param  string $consent_id O consentId é o identificador único do consentimento e deverá ser um URN - Uniform Resource Name.   Um URN, conforme definido na [RFC8141](https://tools.ietf.org/html/rfc8141) é um Uniform Resource  Identifier - URI - que é atribuído sob o URI scheme \&quot;urn\&quot; e um namespace URN específico, com a intenção de que o URN  seja um identificador de recurso persistente e independente da localização.   Considerando a string urn:bancoex:C1DD33123 como exemplo para consentId temos: - o namespace(urn) - o identificador associado ao namespace da instituição transnmissora (bancoex)  - o identificador específico dentro do namespace (C1DD33123).   Informações mais detalhadas sobre a construção de namespaces devem ser consultadas na [RFC8141](https://tools.ietf.org/html/rfc8141). (required)
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser “espelhado” pela detentora (server) no cabeçalho de resposta. Caso não seja enviado pela iniciadora, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. Caso recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400 ou 422 (com o código PARAMETRO_INVALIDO). A iniciadora deve acatar o valor gerado pelo detentor e recebido na resposta. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['paymentsGetConsentsConsentId'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function paymentsGetConsentsConsentIdAsyncWithHttpInfo($consent_id, $authorization, $x_fapi_interaction_id, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, string $contentType = self::contentTypes['paymentsGetConsentsConsentId'][0])
    {
        $returnType = '\OpenAPI\Client\Model\ResponsePaymentConsent';
        $request = $this->paymentsGetConsentsConsentIdRequest($consent_id, $authorization, $x_fapi_interaction_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $contentType);

        return $this->client
            ->sendAsync($request, $this->createHttpClientOption())
            ->then(
                function ($response) use ($returnType) {
                    if ($returnType === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ($returnType !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, $returnType, []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
                },
                function ($exception) {
                    $response = $exception->getResponse();
                    $statusCode = $response->getStatusCode();
                    throw new ApiException(
                        sprintf(
                            '[%d] Error connecting to the API (%s)',
                            $statusCode,
                            $exception->getRequest()->getUri()
                        ),
                        $statusCode,
                        $response->getHeaders(),
                        (string) $response->getBody()
                    );
                }
            );
    }

    /**
     * Create request for operation 'paymentsGetConsentsConsentId'
     *
     * @param  string $consent_id O consentId é o identificador único do consentimento e deverá ser um URN - Uniform Resource Name.   Um URN, conforme definido na [RFC8141](https://tools.ietf.org/html/rfc8141) é um Uniform Resource  Identifier - URI - que é atribuído sob o URI scheme \&quot;urn\&quot; e um namespace URN específico, com a intenção de que o URN  seja um identificador de recurso persistente e independente da localização.   Considerando a string urn:bancoex:C1DD33123 como exemplo para consentId temos: - o namespace(urn) - o identificador associado ao namespace da instituição transnmissora (bancoex)  - o identificador específico dentro do namespace (C1DD33123).   Informações mais detalhadas sobre a construção de namespaces devem ser consultadas na [RFC8141](https://tools.ietf.org/html/rfc8141). (required)
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser “espelhado” pela detentora (server) no cabeçalho de resposta. Caso não seja enviado pela iniciadora, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. Caso recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400 ou 422 (com o código PARAMETRO_INVALIDO). A iniciadora deve acatar o valor gerado pelo detentor e recebido na resposta. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['paymentsGetConsentsConsentId'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Psr7\Request
     */
    public function paymentsGetConsentsConsentIdRequest($consent_id, $authorization, $x_fapi_interaction_id, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, string $contentType = self::contentTypes['paymentsGetConsentsConsentId'][0])
    {

        // verify the required parameter 'consent_id' is set
        if ($consent_id === null || (is_array($consent_id) && count($consent_id) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $consent_id when calling paymentsGetConsentsConsentId'
            );
        }
        if (strlen($consent_id) > 256) {
            throw new \InvalidArgumentException('invalid length for "$consent_id" when calling PagamentosApi.paymentsGetConsentsConsentId, must be smaller than or equal to 256.');
        }
        if (strlen($consent_id) < 1) {
            throw new \InvalidArgumentException('invalid length for "$consent_id" when calling PagamentosApi.paymentsGetConsentsConsentId, must be bigger than or equal to 1.');
        }
        if (!preg_match("/^urn:[a-zA-Z0-9][a-zA-Z0-9\\-]{0,31}:[a-zA-Z0-9()+,\\-.:=@;$_!*'%\/?#]+$/", $consent_id)) {
            throw new \InvalidArgumentException("invalid value for \"consent_id\" when calling PagamentosApi.paymentsGetConsentsConsentId, must conform to the pattern /^urn:[a-zA-Z0-9][a-zA-Z0-9\\-]{0,31}:[a-zA-Z0-9()+,\\-.:=@;$_!*'%\/?#]+$/.");
        }
        
        // verify the required parameter 'authorization' is set
        if ($authorization === null || (is_array($authorization) && count($authorization) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $authorization when calling paymentsGetConsentsConsentId'
            );
        }
        if (strlen($authorization) > 2048) {
            throw new \InvalidArgumentException('invalid length for "$authorization" when calling PagamentosApi.paymentsGetConsentsConsentId, must be smaller than or equal to 2048.');
        }
        if (strlen($authorization) < 1) {
            throw new \InvalidArgumentException('invalid length for "$authorization" when calling PagamentosApi.paymentsGetConsentsConsentId, must be bigger than or equal to 1.');
        }
        if (!preg_match("/[\\w\\W\\s]*/", $authorization)) {
            throw new \InvalidArgumentException("invalid value for \"authorization\" when calling PagamentosApi.paymentsGetConsentsConsentId, must conform to the pattern /[\\w\\W\\s]*/.");
        }
        
        // verify the required parameter 'x_fapi_interaction_id' is set
        if ($x_fapi_interaction_id === null || (is_array($x_fapi_interaction_id) && count($x_fapi_interaction_id) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $x_fapi_interaction_id when calling paymentsGetConsentsConsentId'
            );
        }
        if (strlen($x_fapi_interaction_id) > 36) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_interaction_id" when calling PagamentosApi.paymentsGetConsentsConsentId, must be smaller than or equal to 36.');
        }
        if (strlen($x_fapi_interaction_id) < 1) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_interaction_id" when calling PagamentosApi.paymentsGetConsentsConsentId, must be bigger than or equal to 1.');
        }
        if (!preg_match("/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/", $x_fapi_interaction_id)) {
            throw new \InvalidArgumentException("invalid value for \"x_fapi_interaction_id\" when calling PagamentosApi.paymentsGetConsentsConsentId, must conform to the pattern /^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/.");
        }
        
        if ($x_fapi_auth_date !== null && strlen($x_fapi_auth_date) > 29) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_auth_date" when calling PagamentosApi.paymentsGetConsentsConsentId, must be smaller than or equal to 29.');
        }
        if ($x_fapi_auth_date !== null && strlen($x_fapi_auth_date) < 29) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_auth_date" when calling PagamentosApi.paymentsGetConsentsConsentId, must be bigger than or equal to 29.');
        }
        if ($x_fapi_auth_date !== null && !preg_match("/^(Mon|Tue|Wed|Thu|Fri|Sat|Sun), \\d{2} (Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec) \\d{4} \\d{2}:\\d{2}:\\d{2} (GMT|UTC)$/", $x_fapi_auth_date)) {
            throw new \InvalidArgumentException("invalid value for \"x_fapi_auth_date\" when calling PagamentosApi.paymentsGetConsentsConsentId, must conform to the pattern /^(Mon|Tue|Wed|Thu|Fri|Sat|Sun), \\d{2} (Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec) \\d{4} \\d{2}:\\d{2}:\\d{2} (GMT|UTC)$/.");
        }
        
        if ($x_fapi_customer_ip_address !== null && strlen($x_fapi_customer_ip_address) > 100) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_customer_ip_address" when calling PagamentosApi.paymentsGetConsentsConsentId, must be smaller than or equal to 100.');
        }
        if ($x_fapi_customer_ip_address !== null && strlen($x_fapi_customer_ip_address) < 1) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_customer_ip_address" when calling PagamentosApi.paymentsGetConsentsConsentId, must be bigger than or equal to 1.');
        }
        if ($x_fapi_customer_ip_address !== null && !preg_match("/[\\w\\W\\s]*/", $x_fapi_customer_ip_address)) {
            throw new \InvalidArgumentException("invalid value for \"x_fapi_customer_ip_address\" when calling PagamentosApi.paymentsGetConsentsConsentId, must conform to the pattern /[\\w\\W\\s]*/.");
        }
        
        if ($x_customer_user_agent !== null && strlen($x_customer_user_agent) > 100) {
            throw new \InvalidArgumentException('invalid length for "$x_customer_user_agent" when calling PagamentosApi.paymentsGetConsentsConsentId, must be smaller than or equal to 100.');
        }
        if ($x_customer_user_agent !== null && strlen($x_customer_user_agent) < 1) {
            throw new \InvalidArgumentException('invalid length for "$x_customer_user_agent" when calling PagamentosApi.paymentsGetConsentsConsentId, must be bigger than or equal to 1.');
        }
        if ($x_customer_user_agent !== null && !preg_match("/[\\w\\W\\s]*/", $x_customer_user_agent)) {
            throw new \InvalidArgumentException("invalid value for \"x_customer_user_agent\" when calling PagamentosApi.paymentsGetConsentsConsentId, must conform to the pattern /[\\w\\W\\s]*/.");
        }
        

        $resourcePath = '/consents/{consentId}';
        $formParams = [];
        $queryParams = [];
        $headerParams = [];
        $httpBody = '';
        $multipart = false;


        // header params
        if ($authorization !== null) {
            $headerParams['Authorization'] = ObjectSerializer::toHeaderValue($authorization);
        }
        // header params
        if ($x_fapi_auth_date !== null) {
            $headerParams['x-fapi-auth-date'] = ObjectSerializer::toHeaderValue($x_fapi_auth_date);
        }
        // header params
        if ($x_fapi_customer_ip_address !== null) {
            $headerParams['x-fapi-customer-ip-address'] = ObjectSerializer::toHeaderValue($x_fapi_customer_ip_address);
        }
        // header params
        if ($x_fapi_interaction_id !== null) {
            $headerParams['x-fapi-interaction-id'] = ObjectSerializer::toHeaderValue($x_fapi_interaction_id);
        }
        // header params
        if ($x_customer_user_agent !== null) {
            $headerParams['x-customer-user-agent'] = ObjectSerializer::toHeaderValue($x_customer_user_agent);
        }

        // path params
        if ($consent_id !== null) {
            $resourcePath = str_replace(
                '{' . 'consentId' . '}',
                ObjectSerializer::toPathValue($consent_id),
                $resourcePath
            );
        }


        $headers = $this->headerSelector->selectHeaders(
            ['application/jwt', 'application/json; charset=utf-8', 'application/json', ],
            $contentType,
            $multipart
        );

        // for model (json/xml)
        if (count($formParams) > 0) {
            if ($multipart) {
                $multipartContents = [];
                foreach ($formParams as $formParamName => $formParamValue) {
                    $formParamValueItems = is_array($formParamValue) ? $formParamValue : [$formParamValue];
                    foreach ($formParamValueItems as $formParamValueItem) {
                        $multipartContents[] = [
                            'name' => $formParamName,
                            'contents' => $formParamValueItem
                        ];
                    }
                }
                // for HTTP post (form)
                $httpBody = new MultipartStream($multipartContents);

            } elseif (stripos($headers['Content-Type'], 'application/json') !== false) {
                # if Content-Type contains "application/json", json_encode the form parameters
                $httpBody = \GuzzleHttp\Utils::jsonEncode($formParams);
            } else {
                // for HTTP post (form)
                $httpBody = ObjectSerializer::buildQuery($formParams);
            }
        }

        // this endpoint requires OAuth (access token)
        if (!empty($this->config->getAccessToken())) {
            $headers['Authorization'] = 'Bearer ' . $this->config->getAccessToken();
        }

        $defaultHeaders = [];
        if ($this->config->getUserAgent()) {
            $defaultHeaders['User-Agent'] = $this->config->getUserAgent();
        }

        $headers = array_merge(
            $defaultHeaders,
            $headerParams,
            $headers
        );

        $operationHost = $this->config->getHost();
        $query = ObjectSerializer::buildQuery($queryParams);
        return new Request(
            'GET',
            $operationHost . $resourcePath . ($query ? "?{$query}" : ''),
            $headers,
            $httpBody
        );
    }

    /**
     * Operation paymentsGetPixPaymentsPaymentId
     *
     * Consultar iniciação de pagamento.
     *
     * @param  string $payment_id Identificador da operação de pagamento. (required)
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser “espelhado” pela detentora (server) no cabeçalho de resposta. Caso não seja enviado pela iniciadora, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. Caso recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400 ou 422 (com o código PARAMETRO_INVALIDO). A iniciadora deve acatar o valor gerado pelo detentor e recebido na resposta. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['paymentsGetPixPaymentsPaymentId'] to see the possible values for this operation
     *
     * @throws \OpenAPI\Client\ApiException on non-2xx response or if the response body is not in the expected format
     * @throws \InvalidArgumentException
     * @return \OpenAPI\Client\Model\ResponsePixPayment|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError
     */
    public function paymentsGetPixPaymentsPaymentId($payment_id, $authorization, $x_fapi_interaction_id, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, string $contentType = self::contentTypes['paymentsGetPixPaymentsPaymentId'][0])
    {
        list($response) = $this->paymentsGetPixPaymentsPaymentIdWithHttpInfo($payment_id, $authorization, $x_fapi_interaction_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $contentType);
        return $response;
    }

    /**
     * Operation paymentsGetPixPaymentsPaymentIdWithHttpInfo
     *
     * Consultar iniciação de pagamento.
     *
     * @param  string $payment_id Identificador da operação de pagamento. (required)
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser “espelhado” pela detentora (server) no cabeçalho de resposta. Caso não seja enviado pela iniciadora, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. Caso recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400 ou 422 (com o código PARAMETRO_INVALIDO). A iniciadora deve acatar o valor gerado pelo detentor e recebido na resposta. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['paymentsGetPixPaymentsPaymentId'] to see the possible values for this operation
     *
     * @throws \OpenAPI\Client\ApiException on non-2xx response or if the response body is not in the expected format
     * @throws \InvalidArgumentException
     * @return array of \OpenAPI\Client\Model\ResponsePixPayment|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError, HTTP status code, HTTP response headers (array of strings)
     */
    public function paymentsGetPixPaymentsPaymentIdWithHttpInfo($payment_id, $authorization, $x_fapi_interaction_id, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, string $contentType = self::contentTypes['paymentsGetPixPaymentsPaymentId'][0])
    {
        $request = $this->paymentsGetPixPaymentsPaymentIdRequest($payment_id, $authorization, $x_fapi_interaction_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $contentType);

        try {
            $options = $this->createHttpClientOption();
            try {
                $response = $this->client->send($request, $options);
            } catch (RequestException $e) {
                throw new ApiException(
                    "[{$e->getCode()}] {$e->getMessage()}",
                    (int) $e->getCode(),
                    $e->getResponse() ? $e->getResponse()->getHeaders() : null,
                    $e->getResponse() ? (string) $e->getResponse()->getBody() : null
                );
            } catch (ConnectException $e) {
                throw new ApiException(
                    "[{$e->getCode()}] {$e->getMessage()}",
                    (int) $e->getCode(),
                    null,
                    null
                );
            }

            $statusCode = $response->getStatusCode();


            switch($statusCode) {
                case 200:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponsePixPayment',
                        $request,
                        $response,
                    );
                case 400:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 401:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 403:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 404:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 405:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 406:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 500:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 529:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                default:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
            }

            

            if ($statusCode < 200 || $statusCode > 299) {
                throw new ApiException(
                    sprintf(
                        '[%d] Error connecting to the API (%s)',
                        $statusCode,
                        (string) $request->getUri()
                    ),
                    $statusCode,
                    $response->getHeaders(),
                    (string) $response->getBody()
                );
            }

            return $this->handleResponseWithDataType(
                '\OpenAPI\Client\Model\ResponsePixPayment',
                $request,
                $response,
            );
        } catch (ApiException $e) {
            switch ($e->getCode()) {
                case 200:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponsePixPayment',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 400:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 401:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 403:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 404:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 405:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 406:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 500:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 529:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                default:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
            }
        

            throw $e;
        }
    }

    /**
     * Operation paymentsGetPixPaymentsPaymentIdAsync
     *
     * Consultar iniciação de pagamento.
     *
     * @param  string $payment_id Identificador da operação de pagamento. (required)
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser “espelhado” pela detentora (server) no cabeçalho de resposta. Caso não seja enviado pela iniciadora, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. Caso recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400 ou 422 (com o código PARAMETRO_INVALIDO). A iniciadora deve acatar o valor gerado pelo detentor e recebido na resposta. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['paymentsGetPixPaymentsPaymentId'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function paymentsGetPixPaymentsPaymentIdAsync($payment_id, $authorization, $x_fapi_interaction_id, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, string $contentType = self::contentTypes['paymentsGetPixPaymentsPaymentId'][0])
    {
        return $this->paymentsGetPixPaymentsPaymentIdAsyncWithHttpInfo($payment_id, $authorization, $x_fapi_interaction_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $contentType)
            ->then(
                function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation paymentsGetPixPaymentsPaymentIdAsyncWithHttpInfo
     *
     * Consultar iniciação de pagamento.
     *
     * @param  string $payment_id Identificador da operação de pagamento. (required)
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser “espelhado” pela detentora (server) no cabeçalho de resposta. Caso não seja enviado pela iniciadora, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. Caso recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400 ou 422 (com o código PARAMETRO_INVALIDO). A iniciadora deve acatar o valor gerado pelo detentor e recebido na resposta. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['paymentsGetPixPaymentsPaymentId'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function paymentsGetPixPaymentsPaymentIdAsyncWithHttpInfo($payment_id, $authorization, $x_fapi_interaction_id, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, string $contentType = self::contentTypes['paymentsGetPixPaymentsPaymentId'][0])
    {
        $returnType = '\OpenAPI\Client\Model\ResponsePixPayment';
        $request = $this->paymentsGetPixPaymentsPaymentIdRequest($payment_id, $authorization, $x_fapi_interaction_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $contentType);

        return $this->client
            ->sendAsync($request, $this->createHttpClientOption())
            ->then(
                function ($response) use ($returnType) {
                    if ($returnType === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ($returnType !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, $returnType, []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
                },
                function ($exception) {
                    $response = $exception->getResponse();
                    $statusCode = $response->getStatusCode();
                    throw new ApiException(
                        sprintf(
                            '[%d] Error connecting to the API (%s)',
                            $statusCode,
                            $exception->getRequest()->getUri()
                        ),
                        $statusCode,
                        $response->getHeaders(),
                        (string) $response->getBody()
                    );
                }
            );
    }

    /**
     * Create request for operation 'paymentsGetPixPaymentsPaymentId'
     *
     * @param  string $payment_id Identificador da operação de pagamento. (required)
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser “espelhado” pela detentora (server) no cabeçalho de resposta. Caso não seja enviado pela iniciadora, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. Caso recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400 ou 422 (com o código PARAMETRO_INVALIDO). A iniciadora deve acatar o valor gerado pelo detentor e recebido na resposta. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['paymentsGetPixPaymentsPaymentId'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Psr7\Request
     */
    public function paymentsGetPixPaymentsPaymentIdRequest($payment_id, $authorization, $x_fapi_interaction_id, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, string $contentType = self::contentTypes['paymentsGetPixPaymentsPaymentId'][0])
    {

        // verify the required parameter 'payment_id' is set
        if ($payment_id === null || (is_array($payment_id) && count($payment_id) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $payment_id when calling paymentsGetPixPaymentsPaymentId'
            );
        }
        if (strlen($payment_id) > 100) {
            throw new \InvalidArgumentException('invalid length for "$payment_id" when calling PagamentosApi.paymentsGetPixPaymentsPaymentId, must be smaller than or equal to 100.');
        }
        if (!preg_match("/^[a-zA-Z0-9][a-zA-Z0-9\\-]{0,99}$/", $payment_id)) {
            throw new \InvalidArgumentException("invalid value for \"payment_id\" when calling PagamentosApi.paymentsGetPixPaymentsPaymentId, must conform to the pattern /^[a-zA-Z0-9][a-zA-Z0-9\\-]{0,99}$/.");
        }
        
        // verify the required parameter 'authorization' is set
        if ($authorization === null || (is_array($authorization) && count($authorization) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $authorization when calling paymentsGetPixPaymentsPaymentId'
            );
        }
        if (strlen($authorization) > 2048) {
            throw new \InvalidArgumentException('invalid length for "$authorization" when calling PagamentosApi.paymentsGetPixPaymentsPaymentId, must be smaller than or equal to 2048.');
        }
        if (strlen($authorization) < 1) {
            throw new \InvalidArgumentException('invalid length for "$authorization" when calling PagamentosApi.paymentsGetPixPaymentsPaymentId, must be bigger than or equal to 1.');
        }
        if (!preg_match("/[\\w\\W\\s]*/", $authorization)) {
            throw new \InvalidArgumentException("invalid value for \"authorization\" when calling PagamentosApi.paymentsGetPixPaymentsPaymentId, must conform to the pattern /[\\w\\W\\s]*/.");
        }
        
        // verify the required parameter 'x_fapi_interaction_id' is set
        if ($x_fapi_interaction_id === null || (is_array($x_fapi_interaction_id) && count($x_fapi_interaction_id) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $x_fapi_interaction_id when calling paymentsGetPixPaymentsPaymentId'
            );
        }
        if (strlen($x_fapi_interaction_id) > 36) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_interaction_id" when calling PagamentosApi.paymentsGetPixPaymentsPaymentId, must be smaller than or equal to 36.');
        }
        if (strlen($x_fapi_interaction_id) < 1) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_interaction_id" when calling PagamentosApi.paymentsGetPixPaymentsPaymentId, must be bigger than or equal to 1.');
        }
        if (!preg_match("/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/", $x_fapi_interaction_id)) {
            throw new \InvalidArgumentException("invalid value for \"x_fapi_interaction_id\" when calling PagamentosApi.paymentsGetPixPaymentsPaymentId, must conform to the pattern /^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/.");
        }
        
        if ($x_fapi_auth_date !== null && strlen($x_fapi_auth_date) > 29) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_auth_date" when calling PagamentosApi.paymentsGetPixPaymentsPaymentId, must be smaller than or equal to 29.');
        }
        if ($x_fapi_auth_date !== null && strlen($x_fapi_auth_date) < 29) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_auth_date" when calling PagamentosApi.paymentsGetPixPaymentsPaymentId, must be bigger than or equal to 29.');
        }
        if ($x_fapi_auth_date !== null && !preg_match("/^(Mon|Tue|Wed|Thu|Fri|Sat|Sun), \\d{2} (Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec) \\d{4} \\d{2}:\\d{2}:\\d{2} (GMT|UTC)$/", $x_fapi_auth_date)) {
            throw new \InvalidArgumentException("invalid value for \"x_fapi_auth_date\" when calling PagamentosApi.paymentsGetPixPaymentsPaymentId, must conform to the pattern /^(Mon|Tue|Wed|Thu|Fri|Sat|Sun), \\d{2} (Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec) \\d{4} \\d{2}:\\d{2}:\\d{2} (GMT|UTC)$/.");
        }
        
        if ($x_fapi_customer_ip_address !== null && strlen($x_fapi_customer_ip_address) > 100) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_customer_ip_address" when calling PagamentosApi.paymentsGetPixPaymentsPaymentId, must be smaller than or equal to 100.');
        }
        if ($x_fapi_customer_ip_address !== null && strlen($x_fapi_customer_ip_address) < 1) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_customer_ip_address" when calling PagamentosApi.paymentsGetPixPaymentsPaymentId, must be bigger than or equal to 1.');
        }
        if ($x_fapi_customer_ip_address !== null && !preg_match("/[\\w\\W\\s]*/", $x_fapi_customer_ip_address)) {
            throw new \InvalidArgumentException("invalid value for \"x_fapi_customer_ip_address\" when calling PagamentosApi.paymentsGetPixPaymentsPaymentId, must conform to the pattern /[\\w\\W\\s]*/.");
        }
        
        if ($x_customer_user_agent !== null && strlen($x_customer_user_agent) > 100) {
            throw new \InvalidArgumentException('invalid length for "$x_customer_user_agent" when calling PagamentosApi.paymentsGetPixPaymentsPaymentId, must be smaller than or equal to 100.');
        }
        if ($x_customer_user_agent !== null && strlen($x_customer_user_agent) < 1) {
            throw new \InvalidArgumentException('invalid length for "$x_customer_user_agent" when calling PagamentosApi.paymentsGetPixPaymentsPaymentId, must be bigger than or equal to 1.');
        }
        if ($x_customer_user_agent !== null && !preg_match("/[\\w\\W\\s]*/", $x_customer_user_agent)) {
            throw new \InvalidArgumentException("invalid value for \"x_customer_user_agent\" when calling PagamentosApi.paymentsGetPixPaymentsPaymentId, must conform to the pattern /[\\w\\W\\s]*/.");
        }
        

        $resourcePath = '/pix/payments/{paymentId}';
        $formParams = [];
        $queryParams = [];
        $headerParams = [];
        $httpBody = '';
        $multipart = false;


        // header params
        if ($authorization !== null) {
            $headerParams['Authorization'] = ObjectSerializer::toHeaderValue($authorization);
        }
        // header params
        if ($x_fapi_auth_date !== null) {
            $headerParams['x-fapi-auth-date'] = ObjectSerializer::toHeaderValue($x_fapi_auth_date);
        }
        // header params
        if ($x_fapi_customer_ip_address !== null) {
            $headerParams['x-fapi-customer-ip-address'] = ObjectSerializer::toHeaderValue($x_fapi_customer_ip_address);
        }
        // header params
        if ($x_fapi_interaction_id !== null) {
            $headerParams['x-fapi-interaction-id'] = ObjectSerializer::toHeaderValue($x_fapi_interaction_id);
        }
        // header params
        if ($x_customer_user_agent !== null) {
            $headerParams['x-customer-user-agent'] = ObjectSerializer::toHeaderValue($x_customer_user_agent);
        }

        // path params
        if ($payment_id !== null) {
            $resourcePath = str_replace(
                '{' . 'paymentId' . '}',
                ObjectSerializer::toPathValue($payment_id),
                $resourcePath
            );
        }


        $headers = $this->headerSelector->selectHeaders(
            ['application/jwt', 'application/json; charset=utf-8', 'application/json', ],
            $contentType,
            $multipart
        );

        // for model (json/xml)
        if (count($formParams) > 0) {
            if ($multipart) {
                $multipartContents = [];
                foreach ($formParams as $formParamName => $formParamValue) {
                    $formParamValueItems = is_array($formParamValue) ? $formParamValue : [$formParamValue];
                    foreach ($formParamValueItems as $formParamValueItem) {
                        $multipartContents[] = [
                            'name' => $formParamName,
                            'contents' => $formParamValueItem
                        ];
                    }
                }
                // for HTTP post (form)
                $httpBody = new MultipartStream($multipartContents);

            } elseif (stripos($headers['Content-Type'], 'application/json') !== false) {
                # if Content-Type contains "application/json", json_encode the form parameters
                $httpBody = \GuzzleHttp\Utils::jsonEncode($formParams);
            } else {
                // for HTTP post (form)
                $httpBody = ObjectSerializer::buildQuery($formParams);
            }
        }

        // this endpoint requires OAuth (access token)
        if (!empty($this->config->getAccessToken())) {
            $headers['Authorization'] = 'Bearer ' . $this->config->getAccessToken();
        }

        $defaultHeaders = [];
        if ($this->config->getUserAgent()) {
            $defaultHeaders['User-Agent'] = $this->config->getUserAgent();
        }

        $headers = array_merge(
            $defaultHeaders,
            $headerParams,
            $headers
        );

        $operationHost = $this->config->getHost();
        $query = ObjectSerializer::buildQuery($queryParams);
        return new Request(
            'GET',
            $operationHost . $resourcePath . ($query ? "?{$query}" : ''),
            $headers,
            $httpBody
        );
    }

    /**
     * Operation paymentsPatchPixPaymentsConsentId
     *
     * Cancelar todos os pagamentos referentes ao mesmo Consentimento.
     *
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser “espelhado” pela detentora (server) no cabeçalho de resposta. Caso não seja enviado pela iniciadora, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. Caso recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400 ou 422 (com o código PARAMETRO_INVALIDO). A iniciadora deve acatar o valor gerado pelo detentor e recebido na resposta. (required)
     * @param  string $consent_id O consentId é o identificador único do consentimento e deverá ser um URN - Uniform Resource Name.   Um URN, conforme definido na [RFC8141](https://tools.ietf.org/html/rfc8141) é um Uniform Resource  Identifier - URI - que é atribuído sob o URI scheme \&quot;urn\&quot; e um namespace URN específico, com a intenção de que o URN  seja um identificador de recurso persistente e independente da localização.   Considerando a string urn:bancoex:C1DD33123 como exemplo para consentId temos: - o namespace(urn) - o identificador associado ao namespace da instituição transnmissora (bancoex)  - o identificador específico dentro do namespace (C1DD33123).   Informações mais detalhadas sobre a construção de namespaces devem ser consultadas na [RFC8141](https://tools.ietf.org/html/rfc8141). (required)
     * @param  string $x_idempotency_key Cabeçalho HTTP personalizado. Identificador de solicitação exclusivo para suportar a idempotência. (required)
     * @param  \OpenAPI\Client\Model\PatchPixPayment $patch_pix_payment Payload para cancelamento do pagamento. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['paymentsPatchPixPaymentsConsentId'] to see the possible values for this operation
     *
     * @throws \OpenAPI\Client\ApiException on non-2xx response or if the response body is not in the expected format
     * @throws \InvalidArgumentException
     * @return \OpenAPI\Client\Model\ResponsePatchPixConsent|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\Model422ResponseErrorCreatePixPayment|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError
     */
    public function paymentsPatchPixPaymentsConsentId($authorization, $x_fapi_interaction_id, $consent_id, $x_idempotency_key, $patch_pix_payment, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, string $contentType = self::contentTypes['paymentsPatchPixPaymentsConsentId'][0])
    {
        list($response) = $this->paymentsPatchPixPaymentsConsentIdWithHttpInfo($authorization, $x_fapi_interaction_id, $consent_id, $x_idempotency_key, $patch_pix_payment, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $contentType);
        return $response;
    }

    /**
     * Operation paymentsPatchPixPaymentsConsentIdWithHttpInfo
     *
     * Cancelar todos os pagamentos referentes ao mesmo Consentimento.
     *
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser “espelhado” pela detentora (server) no cabeçalho de resposta. Caso não seja enviado pela iniciadora, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. Caso recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400 ou 422 (com o código PARAMETRO_INVALIDO). A iniciadora deve acatar o valor gerado pelo detentor e recebido na resposta. (required)
     * @param  string $consent_id O consentId é o identificador único do consentimento e deverá ser um URN - Uniform Resource Name.   Um URN, conforme definido na [RFC8141](https://tools.ietf.org/html/rfc8141) é um Uniform Resource  Identifier - URI - que é atribuído sob o URI scheme \&quot;urn\&quot; e um namespace URN específico, com a intenção de que o URN  seja um identificador de recurso persistente e independente da localização.   Considerando a string urn:bancoex:C1DD33123 como exemplo para consentId temos: - o namespace(urn) - o identificador associado ao namespace da instituição transnmissora (bancoex)  - o identificador específico dentro do namespace (C1DD33123).   Informações mais detalhadas sobre a construção de namespaces devem ser consultadas na [RFC8141](https://tools.ietf.org/html/rfc8141). (required)
     * @param  string $x_idempotency_key Cabeçalho HTTP personalizado. Identificador de solicitação exclusivo para suportar a idempotência. (required)
     * @param  \OpenAPI\Client\Model\PatchPixPayment $patch_pix_payment Payload para cancelamento do pagamento. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['paymentsPatchPixPaymentsConsentId'] to see the possible values for this operation
     *
     * @throws \OpenAPI\Client\ApiException on non-2xx response or if the response body is not in the expected format
     * @throws \InvalidArgumentException
     * @return array of \OpenAPI\Client\Model\ResponsePatchPixConsent|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\Model422ResponseErrorCreatePixPayment|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError, HTTP status code, HTTP response headers (array of strings)
     */
    public function paymentsPatchPixPaymentsConsentIdWithHttpInfo($authorization, $x_fapi_interaction_id, $consent_id, $x_idempotency_key, $patch_pix_payment, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, string $contentType = self::contentTypes['paymentsPatchPixPaymentsConsentId'][0])
    {
        $request = $this->paymentsPatchPixPaymentsConsentIdRequest($authorization, $x_fapi_interaction_id, $consent_id, $x_idempotency_key, $patch_pix_payment, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $contentType);

        try {
            $options = $this->createHttpClientOption();
            try {
                $response = $this->client->send($request, $options);
            } catch (RequestException $e) {
                throw new ApiException(
                    "[{$e->getCode()}] {$e->getMessage()}",
                    (int) $e->getCode(),
                    $e->getResponse() ? $e->getResponse()->getHeaders() : null,
                    $e->getResponse() ? (string) $e->getResponse()->getBody() : null
                );
            } catch (ConnectException $e) {
                throw new ApiException(
                    "[{$e->getCode()}] {$e->getMessage()}",
                    (int) $e->getCode(),
                    null,
                    null
                );
            }

            $statusCode = $response->getStatusCode();


            switch($statusCode) {
                case 200:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponsePatchPixConsent',
                        $request,
                        $response,
                    );
                case 400:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 401:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 403:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 404:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 405:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 406:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 422:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\Model422ResponseErrorCreatePixPayment',
                        $request,
                        $response,
                    );
                case 500:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 529:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                default:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
            }

            

            if ($statusCode < 200 || $statusCode > 299) {
                throw new ApiException(
                    sprintf(
                        '[%d] Error connecting to the API (%s)',
                        $statusCode,
                        (string) $request->getUri()
                    ),
                    $statusCode,
                    $response->getHeaders(),
                    (string) $response->getBody()
                );
            }

            return $this->handleResponseWithDataType(
                '\OpenAPI\Client\Model\ResponsePatchPixConsent',
                $request,
                $response,
            );
        } catch (ApiException $e) {
            switch ($e->getCode()) {
                case 200:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponsePatchPixConsent',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 400:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 401:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 403:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 404:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 405:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 406:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 422:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\Model422ResponseErrorCreatePixPayment',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 500:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 529:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                default:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
            }
        

            throw $e;
        }
    }

    /**
     * Operation paymentsPatchPixPaymentsConsentIdAsync
     *
     * Cancelar todos os pagamentos referentes ao mesmo Consentimento.
     *
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser “espelhado” pela detentora (server) no cabeçalho de resposta. Caso não seja enviado pela iniciadora, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. Caso recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400 ou 422 (com o código PARAMETRO_INVALIDO). A iniciadora deve acatar o valor gerado pelo detentor e recebido na resposta. (required)
     * @param  string $consent_id O consentId é o identificador único do consentimento e deverá ser um URN - Uniform Resource Name.   Um URN, conforme definido na [RFC8141](https://tools.ietf.org/html/rfc8141) é um Uniform Resource  Identifier - URI - que é atribuído sob o URI scheme \&quot;urn\&quot; e um namespace URN específico, com a intenção de que o URN  seja um identificador de recurso persistente e independente da localização.   Considerando a string urn:bancoex:C1DD33123 como exemplo para consentId temos: - o namespace(urn) - o identificador associado ao namespace da instituição transnmissora (bancoex)  - o identificador específico dentro do namespace (C1DD33123).   Informações mais detalhadas sobre a construção de namespaces devem ser consultadas na [RFC8141](https://tools.ietf.org/html/rfc8141). (required)
     * @param  string $x_idempotency_key Cabeçalho HTTP personalizado. Identificador de solicitação exclusivo para suportar a idempotência. (required)
     * @param  \OpenAPI\Client\Model\PatchPixPayment $patch_pix_payment Payload para cancelamento do pagamento. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['paymentsPatchPixPaymentsConsentId'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function paymentsPatchPixPaymentsConsentIdAsync($authorization, $x_fapi_interaction_id, $consent_id, $x_idempotency_key, $patch_pix_payment, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, string $contentType = self::contentTypes['paymentsPatchPixPaymentsConsentId'][0])
    {
        return $this->paymentsPatchPixPaymentsConsentIdAsyncWithHttpInfo($authorization, $x_fapi_interaction_id, $consent_id, $x_idempotency_key, $patch_pix_payment, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $contentType)
            ->then(
                function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation paymentsPatchPixPaymentsConsentIdAsyncWithHttpInfo
     *
     * Cancelar todos os pagamentos referentes ao mesmo Consentimento.
     *
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser “espelhado” pela detentora (server) no cabeçalho de resposta. Caso não seja enviado pela iniciadora, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. Caso recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400 ou 422 (com o código PARAMETRO_INVALIDO). A iniciadora deve acatar o valor gerado pelo detentor e recebido na resposta. (required)
     * @param  string $consent_id O consentId é o identificador único do consentimento e deverá ser um URN - Uniform Resource Name.   Um URN, conforme definido na [RFC8141](https://tools.ietf.org/html/rfc8141) é um Uniform Resource  Identifier - URI - que é atribuído sob o URI scheme \&quot;urn\&quot; e um namespace URN específico, com a intenção de que o URN  seja um identificador de recurso persistente e independente da localização.   Considerando a string urn:bancoex:C1DD33123 como exemplo para consentId temos: - o namespace(urn) - o identificador associado ao namespace da instituição transnmissora (bancoex)  - o identificador específico dentro do namespace (C1DD33123).   Informações mais detalhadas sobre a construção de namespaces devem ser consultadas na [RFC8141](https://tools.ietf.org/html/rfc8141). (required)
     * @param  string $x_idempotency_key Cabeçalho HTTP personalizado. Identificador de solicitação exclusivo para suportar a idempotência. (required)
     * @param  \OpenAPI\Client\Model\PatchPixPayment $patch_pix_payment Payload para cancelamento do pagamento. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['paymentsPatchPixPaymentsConsentId'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function paymentsPatchPixPaymentsConsentIdAsyncWithHttpInfo($authorization, $x_fapi_interaction_id, $consent_id, $x_idempotency_key, $patch_pix_payment, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, string $contentType = self::contentTypes['paymentsPatchPixPaymentsConsentId'][0])
    {
        $returnType = '\OpenAPI\Client\Model\ResponsePatchPixConsent';
        $request = $this->paymentsPatchPixPaymentsConsentIdRequest($authorization, $x_fapi_interaction_id, $consent_id, $x_idempotency_key, $patch_pix_payment, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $contentType);

        return $this->client
            ->sendAsync($request, $this->createHttpClientOption())
            ->then(
                function ($response) use ($returnType) {
                    if ($returnType === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ($returnType !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, $returnType, []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
                },
                function ($exception) {
                    $response = $exception->getResponse();
                    $statusCode = $response->getStatusCode();
                    throw new ApiException(
                        sprintf(
                            '[%d] Error connecting to the API (%s)',
                            $statusCode,
                            $exception->getRequest()->getUri()
                        ),
                        $statusCode,
                        $response->getHeaders(),
                        (string) $response->getBody()
                    );
                }
            );
    }

    /**
     * Create request for operation 'paymentsPatchPixPaymentsConsentId'
     *
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser “espelhado” pela detentora (server) no cabeçalho de resposta. Caso não seja enviado pela iniciadora, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. Caso recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400 ou 422 (com o código PARAMETRO_INVALIDO). A iniciadora deve acatar o valor gerado pelo detentor e recebido na resposta. (required)
     * @param  string $consent_id O consentId é o identificador único do consentimento e deverá ser um URN - Uniform Resource Name.   Um URN, conforme definido na [RFC8141](https://tools.ietf.org/html/rfc8141) é um Uniform Resource  Identifier - URI - que é atribuído sob o URI scheme \&quot;urn\&quot; e um namespace URN específico, com a intenção de que o URN  seja um identificador de recurso persistente e independente da localização.   Considerando a string urn:bancoex:C1DD33123 como exemplo para consentId temos: - o namespace(urn) - o identificador associado ao namespace da instituição transnmissora (bancoex)  - o identificador específico dentro do namespace (C1DD33123).   Informações mais detalhadas sobre a construção de namespaces devem ser consultadas na [RFC8141](https://tools.ietf.org/html/rfc8141). (required)
     * @param  string $x_idempotency_key Cabeçalho HTTP personalizado. Identificador de solicitação exclusivo para suportar a idempotência. (required)
     * @param  \OpenAPI\Client\Model\PatchPixPayment $patch_pix_payment Payload para cancelamento do pagamento. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['paymentsPatchPixPaymentsConsentId'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Psr7\Request
     */
    public function paymentsPatchPixPaymentsConsentIdRequest($authorization, $x_fapi_interaction_id, $consent_id, $x_idempotency_key, $patch_pix_payment, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, string $contentType = self::contentTypes['paymentsPatchPixPaymentsConsentId'][0])
    {

        // verify the required parameter 'authorization' is set
        if ($authorization === null || (is_array($authorization) && count($authorization) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $authorization when calling paymentsPatchPixPaymentsConsentId'
            );
        }
        if (strlen($authorization) > 2048) {
            throw new \InvalidArgumentException('invalid length for "$authorization" when calling PagamentosApi.paymentsPatchPixPaymentsConsentId, must be smaller than or equal to 2048.');
        }
        if (strlen($authorization) < 1) {
            throw new \InvalidArgumentException('invalid length for "$authorization" when calling PagamentosApi.paymentsPatchPixPaymentsConsentId, must be bigger than or equal to 1.');
        }
        if (!preg_match("/[\\w\\W\\s]*/", $authorization)) {
            throw new \InvalidArgumentException("invalid value for \"authorization\" when calling PagamentosApi.paymentsPatchPixPaymentsConsentId, must conform to the pattern /[\\w\\W\\s]*/.");
        }
        
        // verify the required parameter 'x_fapi_interaction_id' is set
        if ($x_fapi_interaction_id === null || (is_array($x_fapi_interaction_id) && count($x_fapi_interaction_id) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $x_fapi_interaction_id when calling paymentsPatchPixPaymentsConsentId'
            );
        }
        if (strlen($x_fapi_interaction_id) > 36) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_interaction_id" when calling PagamentosApi.paymentsPatchPixPaymentsConsentId, must be smaller than or equal to 36.');
        }
        if (strlen($x_fapi_interaction_id) < 1) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_interaction_id" when calling PagamentosApi.paymentsPatchPixPaymentsConsentId, must be bigger than or equal to 1.');
        }
        if (!preg_match("/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/", $x_fapi_interaction_id)) {
            throw new \InvalidArgumentException("invalid value for \"x_fapi_interaction_id\" when calling PagamentosApi.paymentsPatchPixPaymentsConsentId, must conform to the pattern /^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/.");
        }
        
        // verify the required parameter 'consent_id' is set
        if ($consent_id === null || (is_array($consent_id) && count($consent_id) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $consent_id when calling paymentsPatchPixPaymentsConsentId'
            );
        }
        if (strlen($consent_id) > 256) {
            throw new \InvalidArgumentException('invalid length for "$consent_id" when calling PagamentosApi.paymentsPatchPixPaymentsConsentId, must be smaller than or equal to 256.');
        }
        if (strlen($consent_id) < 1) {
            throw new \InvalidArgumentException('invalid length for "$consent_id" when calling PagamentosApi.paymentsPatchPixPaymentsConsentId, must be bigger than or equal to 1.');
        }
        if (!preg_match("/^urn:[a-zA-Z0-9][a-zA-Z0-9\\-]{0,31}:[a-zA-Z0-9()+,\\-.:=@;$_!*'%\/?#]+$/", $consent_id)) {
            throw new \InvalidArgumentException("invalid value for \"consent_id\" when calling PagamentosApi.paymentsPatchPixPaymentsConsentId, must conform to the pattern /^urn:[a-zA-Z0-9][a-zA-Z0-9\\-]{0,31}:[a-zA-Z0-9()+,\\-.:=@;$_!*'%\/?#]+$/.");
        }
        
        // verify the required parameter 'x_idempotency_key' is set
        if ($x_idempotency_key === null || (is_array($x_idempotency_key) && count($x_idempotency_key) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $x_idempotency_key when calling paymentsPatchPixPaymentsConsentId'
            );
        }
        if (strlen($x_idempotency_key) > 40) {
            throw new \InvalidArgumentException('invalid length for "$x_idempotency_key" when calling PagamentosApi.paymentsPatchPixPaymentsConsentId, must be smaller than or equal to 40.');
        }
        if (strlen($x_idempotency_key) < 1) {
            throw new \InvalidArgumentException('invalid length for "$x_idempotency_key" when calling PagamentosApi.paymentsPatchPixPaymentsConsentId, must be bigger than or equal to 1.');
        }
        if (!preg_match("/^(?!\\s)(.*)(\\S)$/", $x_idempotency_key)) {
            throw new \InvalidArgumentException("invalid value for \"x_idempotency_key\" when calling PagamentosApi.paymentsPatchPixPaymentsConsentId, must conform to the pattern /^(?!\\s)(.*)(\\S)$/.");
        }
        
        // verify the required parameter 'patch_pix_payment' is set
        if ($patch_pix_payment === null || (is_array($patch_pix_payment) && count($patch_pix_payment) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $patch_pix_payment when calling paymentsPatchPixPaymentsConsentId'
            );
        }

        if ($x_fapi_auth_date !== null && strlen($x_fapi_auth_date) > 29) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_auth_date" when calling PagamentosApi.paymentsPatchPixPaymentsConsentId, must be smaller than or equal to 29.');
        }
        if ($x_fapi_auth_date !== null && strlen($x_fapi_auth_date) < 29) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_auth_date" when calling PagamentosApi.paymentsPatchPixPaymentsConsentId, must be bigger than or equal to 29.');
        }
        if ($x_fapi_auth_date !== null && !preg_match("/^(Mon|Tue|Wed|Thu|Fri|Sat|Sun), \\d{2} (Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec) \\d{4} \\d{2}:\\d{2}:\\d{2} (GMT|UTC)$/", $x_fapi_auth_date)) {
            throw new \InvalidArgumentException("invalid value for \"x_fapi_auth_date\" when calling PagamentosApi.paymentsPatchPixPaymentsConsentId, must conform to the pattern /^(Mon|Tue|Wed|Thu|Fri|Sat|Sun), \\d{2} (Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec) \\d{4} \\d{2}:\\d{2}:\\d{2} (GMT|UTC)$/.");
        }
        
        if ($x_fapi_customer_ip_address !== null && strlen($x_fapi_customer_ip_address) > 100) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_customer_ip_address" when calling PagamentosApi.paymentsPatchPixPaymentsConsentId, must be smaller than or equal to 100.');
        }
        if ($x_fapi_customer_ip_address !== null && strlen($x_fapi_customer_ip_address) < 1) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_customer_ip_address" when calling PagamentosApi.paymentsPatchPixPaymentsConsentId, must be bigger than or equal to 1.');
        }
        if ($x_fapi_customer_ip_address !== null && !preg_match("/[\\w\\W\\s]*/", $x_fapi_customer_ip_address)) {
            throw new \InvalidArgumentException("invalid value for \"x_fapi_customer_ip_address\" when calling PagamentosApi.paymentsPatchPixPaymentsConsentId, must conform to the pattern /[\\w\\W\\s]*/.");
        }
        
        if ($x_customer_user_agent !== null && strlen($x_customer_user_agent) > 100) {
            throw new \InvalidArgumentException('invalid length for "$x_customer_user_agent" when calling PagamentosApi.paymentsPatchPixPaymentsConsentId, must be smaller than or equal to 100.');
        }
        if ($x_customer_user_agent !== null && strlen($x_customer_user_agent) < 1) {
            throw new \InvalidArgumentException('invalid length for "$x_customer_user_agent" when calling PagamentosApi.paymentsPatchPixPaymentsConsentId, must be bigger than or equal to 1.');
        }
        if ($x_customer_user_agent !== null && !preg_match("/[\\w\\W\\s]*/", $x_customer_user_agent)) {
            throw new \InvalidArgumentException("invalid value for \"x_customer_user_agent\" when calling PagamentosApi.paymentsPatchPixPaymentsConsentId, must conform to the pattern /[\\w\\W\\s]*/.");
        }
        

        $resourcePath = '/pix/payments/consents/{consentId}';
        $formParams = [];
        $queryParams = [];
        $headerParams = [];
        $httpBody = '';
        $multipart = false;


        // header params
        if ($authorization !== null) {
            $headerParams['Authorization'] = ObjectSerializer::toHeaderValue($authorization);
        }
        // header params
        if ($x_fapi_auth_date !== null) {
            $headerParams['x-fapi-auth-date'] = ObjectSerializer::toHeaderValue($x_fapi_auth_date);
        }
        // header params
        if ($x_fapi_customer_ip_address !== null) {
            $headerParams['x-fapi-customer-ip-address'] = ObjectSerializer::toHeaderValue($x_fapi_customer_ip_address);
        }
        // header params
        if ($x_fapi_interaction_id !== null) {
            $headerParams['x-fapi-interaction-id'] = ObjectSerializer::toHeaderValue($x_fapi_interaction_id);
        }
        // header params
        if ($x_customer_user_agent !== null) {
            $headerParams['x-customer-user-agent'] = ObjectSerializer::toHeaderValue($x_customer_user_agent);
        }
        // header params
        if ($x_idempotency_key !== null) {
            $headerParams['x-idempotency-key'] = ObjectSerializer::toHeaderValue($x_idempotency_key);
        }

        // path params
        if ($consent_id !== null) {
            $resourcePath = str_replace(
                '{' . 'consentId' . '}',
                ObjectSerializer::toPathValue($consent_id),
                $resourcePath
            );
        }


        $headers = $this->headerSelector->selectHeaders(
            ['application/jwt', 'application/json; charset=utf-8', 'application/json', ],
            $contentType,
            $multipart
        );

        // for model (json/xml)
        if (isset($patch_pix_payment)) {
            if (stripos($headers['Content-Type'], 'application/json') !== false) {
                # if Content-Type contains "application/json", json_encode the body
                $httpBody = \GuzzleHttp\Utils::jsonEncode(ObjectSerializer::sanitizeForSerialization($patch_pix_payment));
            } else {
                $httpBody = $patch_pix_payment;
            }
        } elseif (count($formParams) > 0) {
            if ($multipart) {
                $multipartContents = [];
                foreach ($formParams as $formParamName => $formParamValue) {
                    $formParamValueItems = is_array($formParamValue) ? $formParamValue : [$formParamValue];
                    foreach ($formParamValueItems as $formParamValueItem) {
                        $multipartContents[] = [
                            'name' => $formParamName,
                            'contents' => $formParamValueItem
                        ];
                    }
                }
                // for HTTP post (form)
                $httpBody = new MultipartStream($multipartContents);

            } elseif (stripos($headers['Content-Type'], 'application/json') !== false) {
                # if Content-Type contains "application/json", json_encode the form parameters
                $httpBody = \GuzzleHttp\Utils::jsonEncode($formParams);
            } else {
                // for HTTP post (form)
                $httpBody = ObjectSerializer::buildQuery($formParams);
            }
        }

        // this endpoint requires OAuth (access token)
        if (!empty($this->config->getAccessToken())) {
            $headers['Authorization'] = 'Bearer ' . $this->config->getAccessToken();
        }

        $defaultHeaders = [];
        if ($this->config->getUserAgent()) {
            $defaultHeaders['User-Agent'] = $this->config->getUserAgent();
        }

        $headers = array_merge(
            $defaultHeaders,
            $headerParams,
            $headers
        );

        $operationHost = $this->config->getHost();
        $query = ObjectSerializer::buildQuery($queryParams);
        return new Request(
            'PATCH',
            $operationHost . $resourcePath . ($query ? "?{$query}" : ''),
            $headers,
            $httpBody
        );
    }

    /**
     * Operation paymentsPatchPixPaymentsPaymentId
     *
     * Cancelar iniciação de pagamento.
     *
     * @param  string $payment_id Identificador da operação de pagamento. (required)
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser “espelhado” pela detentora (server) no cabeçalho de resposta. Caso não seja enviado pela iniciadora, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. Caso recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400 ou 422 (com o código PARAMETRO_INVALIDO). A iniciadora deve acatar o valor gerado pelo detentor e recebido na resposta. (required)
     * @param  \OpenAPI\Client\Model\PatchPixPayment $patch_pix_payment Payload para cancelamento do pagamento. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['paymentsPatchPixPaymentsPaymentId'] to see the possible values for this operation
     *
     * @throws \OpenAPI\Client\ApiException on non-2xx response or if the response body is not in the expected format
     * @throws \InvalidArgumentException
     * @return \OpenAPI\Client\Model\ResponsePatchPixPayment|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\Model422ResponseErrorCreatePixPayment|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError
     */
    public function paymentsPatchPixPaymentsPaymentId($payment_id, $authorization, $x_fapi_interaction_id, $patch_pix_payment, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, string $contentType = self::contentTypes['paymentsPatchPixPaymentsPaymentId'][0])
    {
        list($response) = $this->paymentsPatchPixPaymentsPaymentIdWithHttpInfo($payment_id, $authorization, $x_fapi_interaction_id, $patch_pix_payment, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $contentType);
        return $response;
    }

    /**
     * Operation paymentsPatchPixPaymentsPaymentIdWithHttpInfo
     *
     * Cancelar iniciação de pagamento.
     *
     * @param  string $payment_id Identificador da operação de pagamento. (required)
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser “espelhado” pela detentora (server) no cabeçalho de resposta. Caso não seja enviado pela iniciadora, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. Caso recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400 ou 422 (com o código PARAMETRO_INVALIDO). A iniciadora deve acatar o valor gerado pelo detentor e recebido na resposta. (required)
     * @param  \OpenAPI\Client\Model\PatchPixPayment $patch_pix_payment Payload para cancelamento do pagamento. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['paymentsPatchPixPaymentsPaymentId'] to see the possible values for this operation
     *
     * @throws \OpenAPI\Client\ApiException on non-2xx response or if the response body is not in the expected format
     * @throws \InvalidArgumentException
     * @return array of \OpenAPI\Client\Model\ResponsePatchPixPayment|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\Model422ResponseErrorCreatePixPayment|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError, HTTP status code, HTTP response headers (array of strings)
     */
    public function paymentsPatchPixPaymentsPaymentIdWithHttpInfo($payment_id, $authorization, $x_fapi_interaction_id, $patch_pix_payment, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, string $contentType = self::contentTypes['paymentsPatchPixPaymentsPaymentId'][0])
    {
        $request = $this->paymentsPatchPixPaymentsPaymentIdRequest($payment_id, $authorization, $x_fapi_interaction_id, $patch_pix_payment, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $contentType);

        try {
            $options = $this->createHttpClientOption();
            try {
                $response = $this->client->send($request, $options);
            } catch (RequestException $e) {
                throw new ApiException(
                    "[{$e->getCode()}] {$e->getMessage()}",
                    (int) $e->getCode(),
                    $e->getResponse() ? $e->getResponse()->getHeaders() : null,
                    $e->getResponse() ? (string) $e->getResponse()->getBody() : null
                );
            } catch (ConnectException $e) {
                throw new ApiException(
                    "[{$e->getCode()}] {$e->getMessage()}",
                    (int) $e->getCode(),
                    null,
                    null
                );
            }

            $statusCode = $response->getStatusCode();


            switch($statusCode) {
                case 200:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponsePatchPixPayment',
                        $request,
                        $response,
                    );
                case 400:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 401:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 403:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 404:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 405:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 406:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 422:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\Model422ResponseErrorCreatePixPayment',
                        $request,
                        $response,
                    );
                case 500:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 529:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                default:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
            }

            

            if ($statusCode < 200 || $statusCode > 299) {
                throw new ApiException(
                    sprintf(
                        '[%d] Error connecting to the API (%s)',
                        $statusCode,
                        (string) $request->getUri()
                    ),
                    $statusCode,
                    $response->getHeaders(),
                    (string) $response->getBody()
                );
            }

            return $this->handleResponseWithDataType(
                '\OpenAPI\Client\Model\ResponsePatchPixPayment',
                $request,
                $response,
            );
        } catch (ApiException $e) {
            switch ($e->getCode()) {
                case 200:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponsePatchPixPayment',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 400:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 401:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 403:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 404:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 405:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 406:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 422:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\Model422ResponseErrorCreatePixPayment',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 500:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 529:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                default:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
            }
        

            throw $e;
        }
    }

    /**
     * Operation paymentsPatchPixPaymentsPaymentIdAsync
     *
     * Cancelar iniciação de pagamento.
     *
     * @param  string $payment_id Identificador da operação de pagamento. (required)
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser “espelhado” pela detentora (server) no cabeçalho de resposta. Caso não seja enviado pela iniciadora, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. Caso recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400 ou 422 (com o código PARAMETRO_INVALIDO). A iniciadora deve acatar o valor gerado pelo detentor e recebido na resposta. (required)
     * @param  \OpenAPI\Client\Model\PatchPixPayment $patch_pix_payment Payload para cancelamento do pagamento. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['paymentsPatchPixPaymentsPaymentId'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function paymentsPatchPixPaymentsPaymentIdAsync($payment_id, $authorization, $x_fapi_interaction_id, $patch_pix_payment, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, string $contentType = self::contentTypes['paymentsPatchPixPaymentsPaymentId'][0])
    {
        return $this->paymentsPatchPixPaymentsPaymentIdAsyncWithHttpInfo($payment_id, $authorization, $x_fapi_interaction_id, $patch_pix_payment, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $contentType)
            ->then(
                function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation paymentsPatchPixPaymentsPaymentIdAsyncWithHttpInfo
     *
     * Cancelar iniciação de pagamento.
     *
     * @param  string $payment_id Identificador da operação de pagamento. (required)
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser “espelhado” pela detentora (server) no cabeçalho de resposta. Caso não seja enviado pela iniciadora, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. Caso recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400 ou 422 (com o código PARAMETRO_INVALIDO). A iniciadora deve acatar o valor gerado pelo detentor e recebido na resposta. (required)
     * @param  \OpenAPI\Client\Model\PatchPixPayment $patch_pix_payment Payload para cancelamento do pagamento. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['paymentsPatchPixPaymentsPaymentId'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function paymentsPatchPixPaymentsPaymentIdAsyncWithHttpInfo($payment_id, $authorization, $x_fapi_interaction_id, $patch_pix_payment, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, string $contentType = self::contentTypes['paymentsPatchPixPaymentsPaymentId'][0])
    {
        $returnType = '\OpenAPI\Client\Model\ResponsePatchPixPayment';
        $request = $this->paymentsPatchPixPaymentsPaymentIdRequest($payment_id, $authorization, $x_fapi_interaction_id, $patch_pix_payment, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $contentType);

        return $this->client
            ->sendAsync($request, $this->createHttpClientOption())
            ->then(
                function ($response) use ($returnType) {
                    if ($returnType === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ($returnType !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, $returnType, []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
                },
                function ($exception) {
                    $response = $exception->getResponse();
                    $statusCode = $response->getStatusCode();
                    throw new ApiException(
                        sprintf(
                            '[%d] Error connecting to the API (%s)',
                            $statusCode,
                            $exception->getRequest()->getUri()
                        ),
                        $statusCode,
                        $response->getHeaders(),
                        (string) $response->getBody()
                    );
                }
            );
    }

    /**
     * Create request for operation 'paymentsPatchPixPaymentsPaymentId'
     *
     * @param  string $payment_id Identificador da operação de pagamento. (required)
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser “espelhado” pela detentora (server) no cabeçalho de resposta. Caso não seja enviado pela iniciadora, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. Caso recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400 ou 422 (com o código PARAMETRO_INVALIDO). A iniciadora deve acatar o valor gerado pelo detentor e recebido na resposta. (required)
     * @param  \OpenAPI\Client\Model\PatchPixPayment $patch_pix_payment Payload para cancelamento do pagamento. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['paymentsPatchPixPaymentsPaymentId'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Psr7\Request
     */
    public function paymentsPatchPixPaymentsPaymentIdRequest($payment_id, $authorization, $x_fapi_interaction_id, $patch_pix_payment, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, string $contentType = self::contentTypes['paymentsPatchPixPaymentsPaymentId'][0])
    {

        // verify the required parameter 'payment_id' is set
        if ($payment_id === null || (is_array($payment_id) && count($payment_id) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $payment_id when calling paymentsPatchPixPaymentsPaymentId'
            );
        }
        if (strlen($payment_id) > 100) {
            throw new \InvalidArgumentException('invalid length for "$payment_id" when calling PagamentosApi.paymentsPatchPixPaymentsPaymentId, must be smaller than or equal to 100.');
        }
        if (!preg_match("/^[a-zA-Z0-9][a-zA-Z0-9\\-]{0,99}$/", $payment_id)) {
            throw new \InvalidArgumentException("invalid value for \"payment_id\" when calling PagamentosApi.paymentsPatchPixPaymentsPaymentId, must conform to the pattern /^[a-zA-Z0-9][a-zA-Z0-9\\-]{0,99}$/.");
        }
        
        // verify the required parameter 'authorization' is set
        if ($authorization === null || (is_array($authorization) && count($authorization) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $authorization when calling paymentsPatchPixPaymentsPaymentId'
            );
        }
        if (strlen($authorization) > 2048) {
            throw new \InvalidArgumentException('invalid length for "$authorization" when calling PagamentosApi.paymentsPatchPixPaymentsPaymentId, must be smaller than or equal to 2048.');
        }
        if (strlen($authorization) < 1) {
            throw new \InvalidArgumentException('invalid length for "$authorization" when calling PagamentosApi.paymentsPatchPixPaymentsPaymentId, must be bigger than or equal to 1.');
        }
        if (!preg_match("/[\\w\\W\\s]*/", $authorization)) {
            throw new \InvalidArgumentException("invalid value for \"authorization\" when calling PagamentosApi.paymentsPatchPixPaymentsPaymentId, must conform to the pattern /[\\w\\W\\s]*/.");
        }
        
        // verify the required parameter 'x_fapi_interaction_id' is set
        if ($x_fapi_interaction_id === null || (is_array($x_fapi_interaction_id) && count($x_fapi_interaction_id) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $x_fapi_interaction_id when calling paymentsPatchPixPaymentsPaymentId'
            );
        }
        if (strlen($x_fapi_interaction_id) > 36) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_interaction_id" when calling PagamentosApi.paymentsPatchPixPaymentsPaymentId, must be smaller than or equal to 36.');
        }
        if (strlen($x_fapi_interaction_id) < 1) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_interaction_id" when calling PagamentosApi.paymentsPatchPixPaymentsPaymentId, must be bigger than or equal to 1.');
        }
        if (!preg_match("/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/", $x_fapi_interaction_id)) {
            throw new \InvalidArgumentException("invalid value for \"x_fapi_interaction_id\" when calling PagamentosApi.paymentsPatchPixPaymentsPaymentId, must conform to the pattern /^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/.");
        }
        
        // verify the required parameter 'patch_pix_payment' is set
        if ($patch_pix_payment === null || (is_array($patch_pix_payment) && count($patch_pix_payment) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $patch_pix_payment when calling paymentsPatchPixPaymentsPaymentId'
            );
        }

        if ($x_fapi_auth_date !== null && strlen($x_fapi_auth_date) > 29) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_auth_date" when calling PagamentosApi.paymentsPatchPixPaymentsPaymentId, must be smaller than or equal to 29.');
        }
        if ($x_fapi_auth_date !== null && strlen($x_fapi_auth_date) < 29) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_auth_date" when calling PagamentosApi.paymentsPatchPixPaymentsPaymentId, must be bigger than or equal to 29.');
        }
        if ($x_fapi_auth_date !== null && !preg_match("/^(Mon|Tue|Wed|Thu|Fri|Sat|Sun), \\d{2} (Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec) \\d{4} \\d{2}:\\d{2}:\\d{2} (GMT|UTC)$/", $x_fapi_auth_date)) {
            throw new \InvalidArgumentException("invalid value for \"x_fapi_auth_date\" when calling PagamentosApi.paymentsPatchPixPaymentsPaymentId, must conform to the pattern /^(Mon|Tue|Wed|Thu|Fri|Sat|Sun), \\d{2} (Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec) \\d{4} \\d{2}:\\d{2}:\\d{2} (GMT|UTC)$/.");
        }
        
        if ($x_fapi_customer_ip_address !== null && strlen($x_fapi_customer_ip_address) > 100) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_customer_ip_address" when calling PagamentosApi.paymentsPatchPixPaymentsPaymentId, must be smaller than or equal to 100.');
        }
        if ($x_fapi_customer_ip_address !== null && strlen($x_fapi_customer_ip_address) < 1) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_customer_ip_address" when calling PagamentosApi.paymentsPatchPixPaymentsPaymentId, must be bigger than or equal to 1.');
        }
        if ($x_fapi_customer_ip_address !== null && !preg_match("/[\\w\\W\\s]*/", $x_fapi_customer_ip_address)) {
            throw new \InvalidArgumentException("invalid value for \"x_fapi_customer_ip_address\" when calling PagamentosApi.paymentsPatchPixPaymentsPaymentId, must conform to the pattern /[\\w\\W\\s]*/.");
        }
        
        if ($x_customer_user_agent !== null && strlen($x_customer_user_agent) > 100) {
            throw new \InvalidArgumentException('invalid length for "$x_customer_user_agent" when calling PagamentosApi.paymentsPatchPixPaymentsPaymentId, must be smaller than or equal to 100.');
        }
        if ($x_customer_user_agent !== null && strlen($x_customer_user_agent) < 1) {
            throw new \InvalidArgumentException('invalid length for "$x_customer_user_agent" when calling PagamentosApi.paymentsPatchPixPaymentsPaymentId, must be bigger than or equal to 1.');
        }
        if ($x_customer_user_agent !== null && !preg_match("/[\\w\\W\\s]*/", $x_customer_user_agent)) {
            throw new \InvalidArgumentException("invalid value for \"x_customer_user_agent\" when calling PagamentosApi.paymentsPatchPixPaymentsPaymentId, must conform to the pattern /[\\w\\W\\s]*/.");
        }
        

        $resourcePath = '/pix/payments/{paymentId}';
        $formParams = [];
        $queryParams = [];
        $headerParams = [];
        $httpBody = '';
        $multipart = false;


        // header params
        if ($authorization !== null) {
            $headerParams['Authorization'] = ObjectSerializer::toHeaderValue($authorization);
        }
        // header params
        if ($x_fapi_auth_date !== null) {
            $headerParams['x-fapi-auth-date'] = ObjectSerializer::toHeaderValue($x_fapi_auth_date);
        }
        // header params
        if ($x_fapi_customer_ip_address !== null) {
            $headerParams['x-fapi-customer-ip-address'] = ObjectSerializer::toHeaderValue($x_fapi_customer_ip_address);
        }
        // header params
        if ($x_fapi_interaction_id !== null) {
            $headerParams['x-fapi-interaction-id'] = ObjectSerializer::toHeaderValue($x_fapi_interaction_id);
        }
        // header params
        if ($x_customer_user_agent !== null) {
            $headerParams['x-customer-user-agent'] = ObjectSerializer::toHeaderValue($x_customer_user_agent);
        }

        // path params
        if ($payment_id !== null) {
            $resourcePath = str_replace(
                '{' . 'paymentId' . '}',
                ObjectSerializer::toPathValue($payment_id),
                $resourcePath
            );
        }


        $headers = $this->headerSelector->selectHeaders(
            ['application/jwt', 'application/json; charset=utf-8', 'application/json', ],
            $contentType,
            $multipart
        );

        // for model (json/xml)
        if (isset($patch_pix_payment)) {
            if (stripos($headers['Content-Type'], 'application/json') !== false) {
                # if Content-Type contains "application/json", json_encode the body
                $httpBody = \GuzzleHttp\Utils::jsonEncode(ObjectSerializer::sanitizeForSerialization($patch_pix_payment));
            } else {
                $httpBody = $patch_pix_payment;
            }
        } elseif (count($formParams) > 0) {
            if ($multipart) {
                $multipartContents = [];
                foreach ($formParams as $formParamName => $formParamValue) {
                    $formParamValueItems = is_array($formParamValue) ? $formParamValue : [$formParamValue];
                    foreach ($formParamValueItems as $formParamValueItem) {
                        $multipartContents[] = [
                            'name' => $formParamName,
                            'contents' => $formParamValueItem
                        ];
                    }
                }
                // for HTTP post (form)
                $httpBody = new MultipartStream($multipartContents);

            } elseif (stripos($headers['Content-Type'], 'application/json') !== false) {
                # if Content-Type contains "application/json", json_encode the form parameters
                $httpBody = \GuzzleHttp\Utils::jsonEncode($formParams);
            } else {
                // for HTTP post (form)
                $httpBody = ObjectSerializer::buildQuery($formParams);
            }
        }

        // this endpoint requires OAuth (access token)
        if (!empty($this->config->getAccessToken())) {
            $headers['Authorization'] = 'Bearer ' . $this->config->getAccessToken();
        }

        $defaultHeaders = [];
        if ($this->config->getUserAgent()) {
            $defaultHeaders['User-Agent'] = $this->config->getUserAgent();
        }

        $headers = array_merge(
            $defaultHeaders,
            $headerParams,
            $headers
        );

        $operationHost = $this->config->getHost();
        $query = ObjectSerializer::buildQuery($queryParams);
        return new Request(
            'PATCH',
            $operationHost . $resourcePath . ($query ? "?{$query}" : ''),
            $headers,
            $httpBody
        );
    }

    /**
     * Operation paymentsPostConsents
     *
     * Criar consentimento para a iniciação de pagamento.
     *
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser “espelhado” pela detentora (server) no cabeçalho de resposta. Caso não seja enviado pela iniciadora, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. Caso recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400 ou 422 (com o código PARAMETRO_INVALIDO). A iniciadora deve acatar o valor gerado pelo detentor e recebido na resposta. (required)
     * @param  string $x_idempotency_key Cabeçalho HTTP personalizado. Identificador de solicitação exclusivo para suportar a idempotência. (required)
     * @param  \OpenAPI\Client\Model\CreatePaymentConsent $create_payment_consent Payload para criação do consentimento para iniciação do pagamento. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['paymentsPostConsents'] to see the possible values for this operation
     *
     * @throws \OpenAPI\Client\ApiException on non-2xx response or if the response body is not in the expected format
     * @throws \InvalidArgumentException
     * @return \OpenAPI\Client\Model\ResponseCreatePaymentConsent|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\Model422ResponseErrorCreateConsent|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError
     */
    public function paymentsPostConsents($authorization, $x_fapi_interaction_id, $x_idempotency_key, $create_payment_consent, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, string $contentType = self::contentTypes['paymentsPostConsents'][0])
    {
        list($response) = $this->paymentsPostConsentsWithHttpInfo($authorization, $x_fapi_interaction_id, $x_idempotency_key, $create_payment_consent, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $contentType);
        return $response;
    }

    /**
     * Operation paymentsPostConsentsWithHttpInfo
     *
     * Criar consentimento para a iniciação de pagamento.
     *
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser “espelhado” pela detentora (server) no cabeçalho de resposta. Caso não seja enviado pela iniciadora, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. Caso recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400 ou 422 (com o código PARAMETRO_INVALIDO). A iniciadora deve acatar o valor gerado pelo detentor e recebido na resposta. (required)
     * @param  string $x_idempotency_key Cabeçalho HTTP personalizado. Identificador de solicitação exclusivo para suportar a idempotência. (required)
     * @param  \OpenAPI\Client\Model\CreatePaymentConsent $create_payment_consent Payload para criação do consentimento para iniciação do pagamento. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['paymentsPostConsents'] to see the possible values for this operation
     *
     * @throws \OpenAPI\Client\ApiException on non-2xx response or if the response body is not in the expected format
     * @throws \InvalidArgumentException
     * @return array of \OpenAPI\Client\Model\ResponseCreatePaymentConsent|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\Model422ResponseErrorCreateConsent|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError, HTTP status code, HTTP response headers (array of strings)
     */
    public function paymentsPostConsentsWithHttpInfo($authorization, $x_fapi_interaction_id, $x_idempotency_key, $create_payment_consent, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, string $contentType = self::contentTypes['paymentsPostConsents'][0])
    {
        $request = $this->paymentsPostConsentsRequest($authorization, $x_fapi_interaction_id, $x_idempotency_key, $create_payment_consent, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $contentType);

        try {
            $options = $this->createHttpClientOption();
            try {
                $response = $this->client->send($request, $options);
            } catch (RequestException $e) {
                throw new ApiException(
                    "[{$e->getCode()}] {$e->getMessage()}",
                    (int) $e->getCode(),
                    $e->getResponse() ? $e->getResponse()->getHeaders() : null,
                    $e->getResponse() ? (string) $e->getResponse()->getBody() : null
                );
            } catch (ConnectException $e) {
                throw new ApiException(
                    "[{$e->getCode()}] {$e->getMessage()}",
                    (int) $e->getCode(),
                    null,
                    null
                );
            }

            $statusCode = $response->getStatusCode();


            switch($statusCode) {
                case 201:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseCreatePaymentConsent',
                        $request,
                        $response,
                    );
                case 400:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 401:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 403:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 404:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 405:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 406:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 415:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 422:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\Model422ResponseErrorCreateConsent',
                        $request,
                        $response,
                    );
                case 500:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 529:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                default:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
            }

            

            if ($statusCode < 200 || $statusCode > 299) {
                throw new ApiException(
                    sprintf(
                        '[%d] Error connecting to the API (%s)',
                        $statusCode,
                        (string) $request->getUri()
                    ),
                    $statusCode,
                    $response->getHeaders(),
                    (string) $response->getBody()
                );
            }

            return $this->handleResponseWithDataType(
                '\OpenAPI\Client\Model\ResponseCreatePaymentConsent',
                $request,
                $response,
            );
        } catch (ApiException $e) {
            switch ($e->getCode()) {
                case 201:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseCreatePaymentConsent',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 400:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 401:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 403:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 404:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 405:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 406:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 415:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 422:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\Model422ResponseErrorCreateConsent',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 500:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 529:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                default:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
            }
        

            throw $e;
        }
    }

    /**
     * Operation paymentsPostConsentsAsync
     *
     * Criar consentimento para a iniciação de pagamento.
     *
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser “espelhado” pela detentora (server) no cabeçalho de resposta. Caso não seja enviado pela iniciadora, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. Caso recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400 ou 422 (com o código PARAMETRO_INVALIDO). A iniciadora deve acatar o valor gerado pelo detentor e recebido na resposta. (required)
     * @param  string $x_idempotency_key Cabeçalho HTTP personalizado. Identificador de solicitação exclusivo para suportar a idempotência. (required)
     * @param  \OpenAPI\Client\Model\CreatePaymentConsent $create_payment_consent Payload para criação do consentimento para iniciação do pagamento. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['paymentsPostConsents'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function paymentsPostConsentsAsync($authorization, $x_fapi_interaction_id, $x_idempotency_key, $create_payment_consent, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, string $contentType = self::contentTypes['paymentsPostConsents'][0])
    {
        return $this->paymentsPostConsentsAsyncWithHttpInfo($authorization, $x_fapi_interaction_id, $x_idempotency_key, $create_payment_consent, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $contentType)
            ->then(
                function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation paymentsPostConsentsAsyncWithHttpInfo
     *
     * Criar consentimento para a iniciação de pagamento.
     *
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser “espelhado” pela detentora (server) no cabeçalho de resposta. Caso não seja enviado pela iniciadora, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. Caso recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400 ou 422 (com o código PARAMETRO_INVALIDO). A iniciadora deve acatar o valor gerado pelo detentor e recebido na resposta. (required)
     * @param  string $x_idempotency_key Cabeçalho HTTP personalizado. Identificador de solicitação exclusivo para suportar a idempotência. (required)
     * @param  \OpenAPI\Client\Model\CreatePaymentConsent $create_payment_consent Payload para criação do consentimento para iniciação do pagamento. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['paymentsPostConsents'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function paymentsPostConsentsAsyncWithHttpInfo($authorization, $x_fapi_interaction_id, $x_idempotency_key, $create_payment_consent, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, string $contentType = self::contentTypes['paymentsPostConsents'][0])
    {
        $returnType = '\OpenAPI\Client\Model\ResponseCreatePaymentConsent';
        $request = $this->paymentsPostConsentsRequest($authorization, $x_fapi_interaction_id, $x_idempotency_key, $create_payment_consent, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $contentType);

        return $this->client
            ->sendAsync($request, $this->createHttpClientOption())
            ->then(
                function ($response) use ($returnType) {
                    if ($returnType === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ($returnType !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, $returnType, []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
                },
                function ($exception) {
                    $response = $exception->getResponse();
                    $statusCode = $response->getStatusCode();
                    throw new ApiException(
                        sprintf(
                            '[%d] Error connecting to the API (%s)',
                            $statusCode,
                            $exception->getRequest()->getUri()
                        ),
                        $statusCode,
                        $response->getHeaders(),
                        (string) $response->getBody()
                    );
                }
            );
    }

    /**
     * Create request for operation 'paymentsPostConsents'
     *
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser “espelhado” pela detentora (server) no cabeçalho de resposta. Caso não seja enviado pela iniciadora, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. Caso recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400 ou 422 (com o código PARAMETRO_INVALIDO). A iniciadora deve acatar o valor gerado pelo detentor e recebido na resposta. (required)
     * @param  string $x_idempotency_key Cabeçalho HTTP personalizado. Identificador de solicitação exclusivo para suportar a idempotência. (required)
     * @param  \OpenAPI\Client\Model\CreatePaymentConsent $create_payment_consent Payload para criação do consentimento para iniciação do pagamento. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['paymentsPostConsents'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Psr7\Request
     */
    public function paymentsPostConsentsRequest($authorization, $x_fapi_interaction_id, $x_idempotency_key, $create_payment_consent, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, string $contentType = self::contentTypes['paymentsPostConsents'][0])
    {

        // verify the required parameter 'authorization' is set
        if ($authorization === null || (is_array($authorization) && count($authorization) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $authorization when calling paymentsPostConsents'
            );
        }
        if (strlen($authorization) > 2048) {
            throw new \InvalidArgumentException('invalid length for "$authorization" when calling PagamentosApi.paymentsPostConsents, must be smaller than or equal to 2048.');
        }
        if (strlen($authorization) < 1) {
            throw new \InvalidArgumentException('invalid length for "$authorization" when calling PagamentosApi.paymentsPostConsents, must be bigger than or equal to 1.');
        }
        if (!preg_match("/[\\w\\W\\s]*/", $authorization)) {
            throw new \InvalidArgumentException("invalid value for \"authorization\" when calling PagamentosApi.paymentsPostConsents, must conform to the pattern /[\\w\\W\\s]*/.");
        }
        
        // verify the required parameter 'x_fapi_interaction_id' is set
        if ($x_fapi_interaction_id === null || (is_array($x_fapi_interaction_id) && count($x_fapi_interaction_id) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $x_fapi_interaction_id when calling paymentsPostConsents'
            );
        }
        if (strlen($x_fapi_interaction_id) > 36) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_interaction_id" when calling PagamentosApi.paymentsPostConsents, must be smaller than or equal to 36.');
        }
        if (strlen($x_fapi_interaction_id) < 1) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_interaction_id" when calling PagamentosApi.paymentsPostConsents, must be bigger than or equal to 1.');
        }
        if (!preg_match("/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/", $x_fapi_interaction_id)) {
            throw new \InvalidArgumentException("invalid value for \"x_fapi_interaction_id\" when calling PagamentosApi.paymentsPostConsents, must conform to the pattern /^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/.");
        }
        
        // verify the required parameter 'x_idempotency_key' is set
        if ($x_idempotency_key === null || (is_array($x_idempotency_key) && count($x_idempotency_key) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $x_idempotency_key when calling paymentsPostConsents'
            );
        }
        if (strlen($x_idempotency_key) > 40) {
            throw new \InvalidArgumentException('invalid length for "$x_idempotency_key" when calling PagamentosApi.paymentsPostConsents, must be smaller than or equal to 40.');
        }
        if (strlen($x_idempotency_key) < 1) {
            throw new \InvalidArgumentException('invalid length for "$x_idempotency_key" when calling PagamentosApi.paymentsPostConsents, must be bigger than or equal to 1.');
        }
        if (!preg_match("/^(?!\\s)(.*)(\\S)$/", $x_idempotency_key)) {
            throw new \InvalidArgumentException("invalid value for \"x_idempotency_key\" when calling PagamentosApi.paymentsPostConsents, must conform to the pattern /^(?!\\s)(.*)(\\S)$/.");
        }
        
        // verify the required parameter 'create_payment_consent' is set
        if ($create_payment_consent === null || (is_array($create_payment_consent) && count($create_payment_consent) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $create_payment_consent when calling paymentsPostConsents'
            );
        }

        if ($x_fapi_auth_date !== null && strlen($x_fapi_auth_date) > 29) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_auth_date" when calling PagamentosApi.paymentsPostConsents, must be smaller than or equal to 29.');
        }
        if ($x_fapi_auth_date !== null && strlen($x_fapi_auth_date) < 29) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_auth_date" when calling PagamentosApi.paymentsPostConsents, must be bigger than or equal to 29.');
        }
        if ($x_fapi_auth_date !== null && !preg_match("/^(Mon|Tue|Wed|Thu|Fri|Sat|Sun), \\d{2} (Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec) \\d{4} \\d{2}:\\d{2}:\\d{2} (GMT|UTC)$/", $x_fapi_auth_date)) {
            throw new \InvalidArgumentException("invalid value for \"x_fapi_auth_date\" when calling PagamentosApi.paymentsPostConsents, must conform to the pattern /^(Mon|Tue|Wed|Thu|Fri|Sat|Sun), \\d{2} (Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec) \\d{4} \\d{2}:\\d{2}:\\d{2} (GMT|UTC)$/.");
        }
        
        if ($x_fapi_customer_ip_address !== null && strlen($x_fapi_customer_ip_address) > 100) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_customer_ip_address" when calling PagamentosApi.paymentsPostConsents, must be smaller than or equal to 100.');
        }
        if ($x_fapi_customer_ip_address !== null && strlen($x_fapi_customer_ip_address) < 1) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_customer_ip_address" when calling PagamentosApi.paymentsPostConsents, must be bigger than or equal to 1.');
        }
        if ($x_fapi_customer_ip_address !== null && !preg_match("/[\\w\\W\\s]*/", $x_fapi_customer_ip_address)) {
            throw new \InvalidArgumentException("invalid value for \"x_fapi_customer_ip_address\" when calling PagamentosApi.paymentsPostConsents, must conform to the pattern /[\\w\\W\\s]*/.");
        }
        
        if ($x_customer_user_agent !== null && strlen($x_customer_user_agent) > 100) {
            throw new \InvalidArgumentException('invalid length for "$x_customer_user_agent" when calling PagamentosApi.paymentsPostConsents, must be smaller than or equal to 100.');
        }
        if ($x_customer_user_agent !== null && strlen($x_customer_user_agent) < 1) {
            throw new \InvalidArgumentException('invalid length for "$x_customer_user_agent" when calling PagamentosApi.paymentsPostConsents, must be bigger than or equal to 1.');
        }
        if ($x_customer_user_agent !== null && !preg_match("/[\\w\\W\\s]*/", $x_customer_user_agent)) {
            throw new \InvalidArgumentException("invalid value for \"x_customer_user_agent\" when calling PagamentosApi.paymentsPostConsents, must conform to the pattern /[\\w\\W\\s]*/.");
        }
        

        $resourcePath = '/consents';
        $formParams = [];
        $queryParams = [];
        $headerParams = [];
        $httpBody = '';
        $multipart = false;


        // header params
        if ($authorization !== null) {
            $headerParams['Authorization'] = ObjectSerializer::toHeaderValue($authorization);
        }
        // header params
        if ($x_fapi_auth_date !== null) {
            $headerParams['x-fapi-auth-date'] = ObjectSerializer::toHeaderValue($x_fapi_auth_date);
        }
        // header params
        if ($x_fapi_customer_ip_address !== null) {
            $headerParams['x-fapi-customer-ip-address'] = ObjectSerializer::toHeaderValue($x_fapi_customer_ip_address);
        }
        // header params
        if ($x_fapi_interaction_id !== null) {
            $headerParams['x-fapi-interaction-id'] = ObjectSerializer::toHeaderValue($x_fapi_interaction_id);
        }
        // header params
        if ($x_customer_user_agent !== null) {
            $headerParams['x-customer-user-agent'] = ObjectSerializer::toHeaderValue($x_customer_user_agent);
        }
        // header params
        if ($x_idempotency_key !== null) {
            $headerParams['x-idempotency-key'] = ObjectSerializer::toHeaderValue($x_idempotency_key);
        }



        $headers = $this->headerSelector->selectHeaders(
            ['application/jwt', 'application/json; charset=utf-8', 'application/json', ],
            $contentType,
            $multipart
        );

        // for model (json/xml)
        if (isset($create_payment_consent)) {
            if (stripos($headers['Content-Type'], 'application/json') !== false) {
                # if Content-Type contains "application/json", json_encode the body
                $httpBody = \GuzzleHttp\Utils::jsonEncode(ObjectSerializer::sanitizeForSerialization($create_payment_consent));
            } else {
                $httpBody = $create_payment_consent;
            }
        } elseif (count($formParams) > 0) {
            if ($multipart) {
                $multipartContents = [];
                foreach ($formParams as $formParamName => $formParamValue) {
                    $formParamValueItems = is_array($formParamValue) ? $formParamValue : [$formParamValue];
                    foreach ($formParamValueItems as $formParamValueItem) {
                        $multipartContents[] = [
                            'name' => $formParamName,
                            'contents' => $formParamValueItem
                        ];
                    }
                }
                // for HTTP post (form)
                $httpBody = new MultipartStream($multipartContents);

            } elseif (stripos($headers['Content-Type'], 'application/json') !== false) {
                # if Content-Type contains "application/json", json_encode the form parameters
                $httpBody = \GuzzleHttp\Utils::jsonEncode($formParams);
            } else {
                // for HTTP post (form)
                $httpBody = ObjectSerializer::buildQuery($formParams);
            }
        }

        // this endpoint requires OAuth (access token)
        if (!empty($this->config->getAccessToken())) {
            $headers['Authorization'] = 'Bearer ' . $this->config->getAccessToken();
        }

        $defaultHeaders = [];
        if ($this->config->getUserAgent()) {
            $defaultHeaders['User-Agent'] = $this->config->getUserAgent();
        }

        $headers = array_merge(
            $defaultHeaders,
            $headerParams,
            $headers
        );

        $operationHost = $this->config->getHost();
        $query = ObjectSerializer::buildQuery($queryParams);
        return new Request(
            'POST',
            $operationHost . $resourcePath . ($query ? "?{$query}" : ''),
            $headers,
            $httpBody
        );
    }

    /**
     * Operation paymentsPostPixPayments
     *
     * Criar iniciação de pagamento.
     *
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser “espelhado” pela detentora (server) no cabeçalho de resposta. Caso não seja enviado pela iniciadora, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. Caso recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400 ou 422 (com o código PARAMETRO_INVALIDO). A iniciadora deve acatar o valor gerado pelo detentor e recebido na resposta. (required)
     * @param  string $x_idempotency_key Cabeçalho HTTP personalizado. Identificador de solicitação exclusivo para suportar a idempotência. (required)
     * @param  \OpenAPI\Client\Model\CreatePixPayment $create_pix_payment Payload para criação da iniciação do pagamento Pix. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['paymentsPostPixPayments'] to see the possible values for this operation
     *
     * @throws \OpenAPI\Client\ApiException on non-2xx response or if the response body is not in the expected format
     * @throws \InvalidArgumentException
     * @return \OpenAPI\Client\Model\ResponseCreatePixPayment|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\Model422ResponseErrorCreatePixPayments|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError
     */
    public function paymentsPostPixPayments($authorization, $x_fapi_interaction_id, $x_idempotency_key, $create_pix_payment, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, string $contentType = self::contentTypes['paymentsPostPixPayments'][0])
    {
        list($response) = $this->paymentsPostPixPaymentsWithHttpInfo($authorization, $x_fapi_interaction_id, $x_idempotency_key, $create_pix_payment, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $contentType);
        return $response;
    }

    /**
     * Operation paymentsPostPixPaymentsWithHttpInfo
     *
     * Criar iniciação de pagamento.
     *
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser “espelhado” pela detentora (server) no cabeçalho de resposta. Caso não seja enviado pela iniciadora, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. Caso recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400 ou 422 (com o código PARAMETRO_INVALIDO). A iniciadora deve acatar o valor gerado pelo detentor e recebido na resposta. (required)
     * @param  string $x_idempotency_key Cabeçalho HTTP personalizado. Identificador de solicitação exclusivo para suportar a idempotência. (required)
     * @param  \OpenAPI\Client\Model\CreatePixPayment $create_pix_payment Payload para criação da iniciação do pagamento Pix. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['paymentsPostPixPayments'] to see the possible values for this operation
     *
     * @throws \OpenAPI\Client\ApiException on non-2xx response or if the response body is not in the expected format
     * @throws \InvalidArgumentException
     * @return array of \OpenAPI\Client\Model\ResponseCreatePixPayment|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\Model422ResponseErrorCreatePixPayments|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError, HTTP status code, HTTP response headers (array of strings)
     */
    public function paymentsPostPixPaymentsWithHttpInfo($authorization, $x_fapi_interaction_id, $x_idempotency_key, $create_pix_payment, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, string $contentType = self::contentTypes['paymentsPostPixPayments'][0])
    {
        $request = $this->paymentsPostPixPaymentsRequest($authorization, $x_fapi_interaction_id, $x_idempotency_key, $create_pix_payment, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $contentType);

        try {
            $options = $this->createHttpClientOption();
            try {
                $response = $this->client->send($request, $options);
            } catch (RequestException $e) {
                throw new ApiException(
                    "[{$e->getCode()}] {$e->getMessage()}",
                    (int) $e->getCode(),
                    $e->getResponse() ? $e->getResponse()->getHeaders() : null,
                    $e->getResponse() ? (string) $e->getResponse()->getBody() : null
                );
            } catch (ConnectException $e) {
                throw new ApiException(
                    "[{$e->getCode()}] {$e->getMessage()}",
                    (int) $e->getCode(),
                    null,
                    null
                );
            }

            $statusCode = $response->getStatusCode();


            switch($statusCode) {
                case 201:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseCreatePixPayment',
                        $request,
                        $response,
                    );
                case 400:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 401:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 403:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 404:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 405:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 406:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 415:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 422:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\Model422ResponseErrorCreatePixPayments',
                        $request,
                        $response,
                    );
                case 500:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 529:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                default:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
            }

            

            if ($statusCode < 200 || $statusCode > 299) {
                throw new ApiException(
                    sprintf(
                        '[%d] Error connecting to the API (%s)',
                        $statusCode,
                        (string) $request->getUri()
                    ),
                    $statusCode,
                    $response->getHeaders(),
                    (string) $response->getBody()
                );
            }

            return $this->handleResponseWithDataType(
                '\OpenAPI\Client\Model\ResponseCreatePixPayment',
                $request,
                $response,
            );
        } catch (ApiException $e) {
            switch ($e->getCode()) {
                case 201:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseCreatePixPayment',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 400:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 401:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 403:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 404:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 405:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 406:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 415:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 422:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\Model422ResponseErrorCreatePixPayments',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 500:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 529:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                default:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
            }
        

            throw $e;
        }
    }

    /**
     * Operation paymentsPostPixPaymentsAsync
     *
     * Criar iniciação de pagamento.
     *
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser “espelhado” pela detentora (server) no cabeçalho de resposta. Caso não seja enviado pela iniciadora, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. Caso recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400 ou 422 (com o código PARAMETRO_INVALIDO). A iniciadora deve acatar o valor gerado pelo detentor e recebido na resposta. (required)
     * @param  string $x_idempotency_key Cabeçalho HTTP personalizado. Identificador de solicitação exclusivo para suportar a idempotência. (required)
     * @param  \OpenAPI\Client\Model\CreatePixPayment $create_pix_payment Payload para criação da iniciação do pagamento Pix. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['paymentsPostPixPayments'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function paymentsPostPixPaymentsAsync($authorization, $x_fapi_interaction_id, $x_idempotency_key, $create_pix_payment, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, string $contentType = self::contentTypes['paymentsPostPixPayments'][0])
    {
        return $this->paymentsPostPixPaymentsAsyncWithHttpInfo($authorization, $x_fapi_interaction_id, $x_idempotency_key, $create_pix_payment, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $contentType)
            ->then(
                function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation paymentsPostPixPaymentsAsyncWithHttpInfo
     *
     * Criar iniciação de pagamento.
     *
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser “espelhado” pela detentora (server) no cabeçalho de resposta. Caso não seja enviado pela iniciadora, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. Caso recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400 ou 422 (com o código PARAMETRO_INVALIDO). A iniciadora deve acatar o valor gerado pelo detentor e recebido na resposta. (required)
     * @param  string $x_idempotency_key Cabeçalho HTTP personalizado. Identificador de solicitação exclusivo para suportar a idempotência. (required)
     * @param  \OpenAPI\Client\Model\CreatePixPayment $create_pix_payment Payload para criação da iniciação do pagamento Pix. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['paymentsPostPixPayments'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function paymentsPostPixPaymentsAsyncWithHttpInfo($authorization, $x_fapi_interaction_id, $x_idempotency_key, $create_pix_payment, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, string $contentType = self::contentTypes['paymentsPostPixPayments'][0])
    {
        $returnType = '\OpenAPI\Client\Model\ResponseCreatePixPayment';
        $request = $this->paymentsPostPixPaymentsRequest($authorization, $x_fapi_interaction_id, $x_idempotency_key, $create_pix_payment, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $contentType);

        return $this->client
            ->sendAsync($request, $this->createHttpClientOption())
            ->then(
                function ($response) use ($returnType) {
                    if ($returnType === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ($returnType !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, $returnType, []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
                },
                function ($exception) {
                    $response = $exception->getResponse();
                    $statusCode = $response->getStatusCode();
                    throw new ApiException(
                        sprintf(
                            '[%d] Error connecting to the API (%s)',
                            $statusCode,
                            $exception->getRequest()->getUri()
                        ),
                        $statusCode,
                        $response->getHeaders(),
                        (string) $response->getBody()
                    );
                }
            );
    }

    /**
     * Create request for operation 'paymentsPostPixPayments'
     *
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser “espelhado” pela detentora (server) no cabeçalho de resposta. Caso não seja enviado pela iniciadora, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. Caso recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400 ou 422 (com o código PARAMETRO_INVALIDO). A iniciadora deve acatar o valor gerado pelo detentor e recebido na resposta. (required)
     * @param  string $x_idempotency_key Cabeçalho HTTP personalizado. Identificador de solicitação exclusivo para suportar a idempotência. (required)
     * @param  \OpenAPI\Client\Model\CreatePixPayment $create_pix_payment Payload para criação da iniciação do pagamento Pix. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['paymentsPostPixPayments'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Psr7\Request
     */
    public function paymentsPostPixPaymentsRequest($authorization, $x_fapi_interaction_id, $x_idempotency_key, $create_pix_payment, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, string $contentType = self::contentTypes['paymentsPostPixPayments'][0])
    {

        // verify the required parameter 'authorization' is set
        if ($authorization === null || (is_array($authorization) && count($authorization) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $authorization when calling paymentsPostPixPayments'
            );
        }
        if (strlen($authorization) > 2048) {
            throw new \InvalidArgumentException('invalid length for "$authorization" when calling PagamentosApi.paymentsPostPixPayments, must be smaller than or equal to 2048.');
        }
        if (strlen($authorization) < 1) {
            throw new \InvalidArgumentException('invalid length for "$authorization" when calling PagamentosApi.paymentsPostPixPayments, must be bigger than or equal to 1.');
        }
        if (!preg_match("/[\\w\\W\\s]*/", $authorization)) {
            throw new \InvalidArgumentException("invalid value for \"authorization\" when calling PagamentosApi.paymentsPostPixPayments, must conform to the pattern /[\\w\\W\\s]*/.");
        }
        
        // verify the required parameter 'x_fapi_interaction_id' is set
        if ($x_fapi_interaction_id === null || (is_array($x_fapi_interaction_id) && count($x_fapi_interaction_id) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $x_fapi_interaction_id when calling paymentsPostPixPayments'
            );
        }
        if (strlen($x_fapi_interaction_id) > 36) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_interaction_id" when calling PagamentosApi.paymentsPostPixPayments, must be smaller than or equal to 36.');
        }
        if (strlen($x_fapi_interaction_id) < 1) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_interaction_id" when calling PagamentosApi.paymentsPostPixPayments, must be bigger than or equal to 1.');
        }
        if (!preg_match("/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/", $x_fapi_interaction_id)) {
            throw new \InvalidArgumentException("invalid value for \"x_fapi_interaction_id\" when calling PagamentosApi.paymentsPostPixPayments, must conform to the pattern /^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/.");
        }
        
        // verify the required parameter 'x_idempotency_key' is set
        if ($x_idempotency_key === null || (is_array($x_idempotency_key) && count($x_idempotency_key) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $x_idempotency_key when calling paymentsPostPixPayments'
            );
        }
        if (strlen($x_idempotency_key) > 40) {
            throw new \InvalidArgumentException('invalid length for "$x_idempotency_key" when calling PagamentosApi.paymentsPostPixPayments, must be smaller than or equal to 40.');
        }
        if (strlen($x_idempotency_key) < 1) {
            throw new \InvalidArgumentException('invalid length for "$x_idempotency_key" when calling PagamentosApi.paymentsPostPixPayments, must be bigger than or equal to 1.');
        }
        if (!preg_match("/^(?!\\s)(.*)(\\S)$/", $x_idempotency_key)) {
            throw new \InvalidArgumentException("invalid value for \"x_idempotency_key\" when calling PagamentosApi.paymentsPostPixPayments, must conform to the pattern /^(?!\\s)(.*)(\\S)$/.");
        }
        
        // verify the required parameter 'create_pix_payment' is set
        if ($create_pix_payment === null || (is_array($create_pix_payment) && count($create_pix_payment) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $create_pix_payment when calling paymentsPostPixPayments'
            );
        }

        if ($x_fapi_auth_date !== null && strlen($x_fapi_auth_date) > 29) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_auth_date" when calling PagamentosApi.paymentsPostPixPayments, must be smaller than or equal to 29.');
        }
        if ($x_fapi_auth_date !== null && strlen($x_fapi_auth_date) < 29) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_auth_date" when calling PagamentosApi.paymentsPostPixPayments, must be bigger than or equal to 29.');
        }
        if ($x_fapi_auth_date !== null && !preg_match("/^(Mon|Tue|Wed|Thu|Fri|Sat|Sun), \\d{2} (Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec) \\d{4} \\d{2}:\\d{2}:\\d{2} (GMT|UTC)$/", $x_fapi_auth_date)) {
            throw new \InvalidArgumentException("invalid value for \"x_fapi_auth_date\" when calling PagamentosApi.paymentsPostPixPayments, must conform to the pattern /^(Mon|Tue|Wed|Thu|Fri|Sat|Sun), \\d{2} (Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec) \\d{4} \\d{2}:\\d{2}:\\d{2} (GMT|UTC)$/.");
        }
        
        if ($x_fapi_customer_ip_address !== null && strlen($x_fapi_customer_ip_address) > 100) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_customer_ip_address" when calling PagamentosApi.paymentsPostPixPayments, must be smaller than or equal to 100.');
        }
        if ($x_fapi_customer_ip_address !== null && strlen($x_fapi_customer_ip_address) < 1) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_customer_ip_address" when calling PagamentosApi.paymentsPostPixPayments, must be bigger than or equal to 1.');
        }
        if ($x_fapi_customer_ip_address !== null && !preg_match("/[\\w\\W\\s]*/", $x_fapi_customer_ip_address)) {
            throw new \InvalidArgumentException("invalid value for \"x_fapi_customer_ip_address\" when calling PagamentosApi.paymentsPostPixPayments, must conform to the pattern /[\\w\\W\\s]*/.");
        }
        
        if ($x_customer_user_agent !== null && strlen($x_customer_user_agent) > 100) {
            throw new \InvalidArgumentException('invalid length for "$x_customer_user_agent" when calling PagamentosApi.paymentsPostPixPayments, must be smaller than or equal to 100.');
        }
        if ($x_customer_user_agent !== null && strlen($x_customer_user_agent) < 1) {
            throw new \InvalidArgumentException('invalid length for "$x_customer_user_agent" when calling PagamentosApi.paymentsPostPixPayments, must be bigger than or equal to 1.');
        }
        if ($x_customer_user_agent !== null && !preg_match("/[\\w\\W\\s]*/", $x_customer_user_agent)) {
            throw new \InvalidArgumentException("invalid value for \"x_customer_user_agent\" when calling PagamentosApi.paymentsPostPixPayments, must conform to the pattern /[\\w\\W\\s]*/.");
        }
        

        $resourcePath = '/pix/payments';
        $formParams = [];
        $queryParams = [];
        $headerParams = [];
        $httpBody = '';
        $multipart = false;


        // header params
        if ($authorization !== null) {
            $headerParams['Authorization'] = ObjectSerializer::toHeaderValue($authorization);
        }
        // header params
        if ($x_fapi_auth_date !== null) {
            $headerParams['x-fapi-auth-date'] = ObjectSerializer::toHeaderValue($x_fapi_auth_date);
        }
        // header params
        if ($x_fapi_customer_ip_address !== null) {
            $headerParams['x-fapi-customer-ip-address'] = ObjectSerializer::toHeaderValue($x_fapi_customer_ip_address);
        }
        // header params
        if ($x_fapi_interaction_id !== null) {
            $headerParams['x-fapi-interaction-id'] = ObjectSerializer::toHeaderValue($x_fapi_interaction_id);
        }
        // header params
        if ($x_customer_user_agent !== null) {
            $headerParams['x-customer-user-agent'] = ObjectSerializer::toHeaderValue($x_customer_user_agent);
        }
        // header params
        if ($x_idempotency_key !== null) {
            $headerParams['x-idempotency-key'] = ObjectSerializer::toHeaderValue($x_idempotency_key);
        }



        $headers = $this->headerSelector->selectHeaders(
            ['application/jwt', 'application/json; charset=utf-8', 'application/json', ],
            $contentType,
            $multipart
        );

        // for model (json/xml)
        if (isset($create_pix_payment)) {
            if (stripos($headers['Content-Type'], 'application/json') !== false) {
                # if Content-Type contains "application/json", json_encode the body
                $httpBody = \GuzzleHttp\Utils::jsonEncode(ObjectSerializer::sanitizeForSerialization($create_pix_payment));
            } else {
                $httpBody = $create_pix_payment;
            }
        } elseif (count($formParams) > 0) {
            if ($multipart) {
                $multipartContents = [];
                foreach ($formParams as $formParamName => $formParamValue) {
                    $formParamValueItems = is_array($formParamValue) ? $formParamValue : [$formParamValue];
                    foreach ($formParamValueItems as $formParamValueItem) {
                        $multipartContents[] = [
                            'name' => $formParamName,
                            'contents' => $formParamValueItem
                        ];
                    }
                }
                // for HTTP post (form)
                $httpBody = new MultipartStream($multipartContents);

            } elseif (stripos($headers['Content-Type'], 'application/json') !== false) {
                # if Content-Type contains "application/json", json_encode the form parameters
                $httpBody = \GuzzleHttp\Utils::jsonEncode($formParams);
            } else {
                // for HTTP post (form)
                $httpBody = ObjectSerializer::buildQuery($formParams);
            }
        }

        // this endpoint requires OAuth (access token)
        if (!empty($this->config->getAccessToken())) {
            $headers['Authorization'] = 'Bearer ' . $this->config->getAccessToken();
        }
        // this endpoint requires OAuth (access token)
        if (!empty($this->config->getAccessToken())) {
            $headers['Authorization'] = 'Bearer ' . $this->config->getAccessToken();
        }

        $defaultHeaders = [];
        if ($this->config->getUserAgent()) {
            $defaultHeaders['User-Agent'] = $this->config->getUserAgent();
        }

        $headers = array_merge(
            $defaultHeaders,
            $headerParams,
            $headers
        );

        $operationHost = $this->config->getHost();
        $query = ObjectSerializer::buildQuery($queryParams);
        return new Request(
            'POST',
            $operationHost . $resourcePath . ($query ? "?{$query}" : ''),
            $headers,
            $httpBody
        );
    }

    /**
     * Create http client option
     *
     * @throws \RuntimeException on file opening failure
     * @return array of http client options
     */
    protected function createHttpClientOption()
    {
        $options = [];
        if ($this->config->getDebug()) {
            $options[RequestOptions::DEBUG] = fopen($this->config->getDebugFile(), 'a');
            if (!$options[RequestOptions::DEBUG]) {
                throw new \RuntimeException('Failed to open the debug file: ' . $this->config->getDebugFile());
            }
        }

        if ($this->config->getCertFile()) {
            $options[RequestOptions::CERT] = $this->config->getCertFile();
        }

        if ($this->config->getKeyFile()) {
            $options[RequestOptions::SSL_KEY] = $this->config->getKeyFile();
        }

        return $options;
    }

    private function handleResponseWithDataType(
        string $dataType,
        RequestInterface $request,
        ResponseInterface $response
    ): array {
        if ($dataType === '\SplFileObject') {
            $content = $response->getBody(); //stream goes to serializer
        } else {
            $content = (string) $response->getBody();
            if ($dataType !== 'string') {
                try {
                    $content = json_decode($content, false, 512, JSON_THROW_ON_ERROR);
                } catch (\JsonException $exception) {
                    throw new ApiException(
                        sprintf(
                            'Error JSON decoding server response (%s)',
                            $request->getUri()
                        ),
                        $response->getStatusCode(),
                        $response->getHeaders(),
                        $content
                    );
                }
            }
        }

        return [
            ObjectSerializer::deserialize($content, $dataType, []),
            $response->getStatusCode(),
            $response->getHeaders()
        ];
    }

    private function responseWithinRangeCode(
        string $rangeCode,
        int $statusCode
    ): bool {
        $left = (int) ($rangeCode[0].'00');
        $right = (int) ($rangeCode[0].'99');

        return $statusCode >= $left && $statusCode <= $right;
    }
}
