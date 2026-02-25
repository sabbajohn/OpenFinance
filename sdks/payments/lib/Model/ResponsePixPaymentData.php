<?php
/**
 * ResponsePixPaymentData
 *
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

namespace OpenAPI\Client\Model;

use \ArrayAccess;
use \OpenAPI\Client\ObjectSerializer;

/**
 * ResponsePixPaymentData Class Doc Comment
 *
 * @category Class
 * @description Objeto contendo dados do pagamento e da conta do recebedor (creditor).
 * @package  OpenAPI\Client
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 * @implements \ArrayAccess<string, mixed>
 */
class ResponsePixPaymentData implements ModelInterface, ArrayAccess, \JsonSerializable
{
    public const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'ResponsePixPaymentData';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'payment_id' => 'string',
        'end_to_end_id' => 'string',
        'consent_id' => 'string',
        'creation_date_time' => '\DateTime',
        'status_update_date_time' => '\DateTime',
        'proxy' => 'string',
        'ibge_town_code' => 'string',
        'status' => '\OpenAPI\Client\Model\EnumPaymentStatusType',
        'rejection_reason' => '\OpenAPI\Client\Model\RejectionReasonGetPix',
        'local_instrument' => '\OpenAPI\Client\Model\EnumLocalInstrument',
        'cnpj_initiator' => 'string',
        'payment' => '\OpenAPI\Client\Model\PaymentPix',
        'transaction_identification' => 'string',
        'remittance_information' => 'string',
        'creditor_account' => '\OpenAPI\Client\Model\CreditorAccount',
        'cancellation' => '\OpenAPI\Client\Model\PixPaymentCancellation',
        'debtor_account' => '\OpenAPI\Client\Model\DebtorAccount',
        'authorisation_flow' => 'string'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      * @phpstan-var array<string, string|null>
      * @psalm-var array<string, string|null>
      */
    protected static $openAPIFormats = [
        'payment_id' => null,
        'end_to_end_id' => null,
        'consent_id' => null,
        'creation_date_time' => 'date-time',
        'status_update_date_time' => 'date-time',
        'proxy' => null,
        'ibge_town_code' => null,
        'status' => null,
        'rejection_reason' => null,
        'local_instrument' => null,
        'cnpj_initiator' => null,
        'payment' => null,
        'transaction_identification' => null,
        'remittance_information' => null,
        'creditor_account' => null,
        'cancellation' => null,
        'debtor_account' => null,
        'authorisation_flow' => null
    ];

    /**
      * Array of nullable properties. Used for (de)serialization
      *
      * @var boolean[]
      */
    protected static array $openAPINullables = [
        'payment_id' => false,
        'end_to_end_id' => false,
        'consent_id' => false,
        'creation_date_time' => false,
        'status_update_date_time' => false,
        'proxy' => false,
        'ibge_town_code' => false,
        'status' => false,
        'rejection_reason' => false,
        'local_instrument' => false,
        'cnpj_initiator' => false,
        'payment' => false,
        'transaction_identification' => false,
        'remittance_information' => false,
        'creditor_account' => false,
        'cancellation' => false,
        'debtor_account' => false,
        'authorisation_flow' => false
    ];

    /**
      * If a nullable field gets set to null, insert it here
      *
      * @var boolean[]
      */
    protected array $openAPINullablesSetToNull = [];

    /**
     * Array of property to type mappings. Used for (de)serialization
     *
     * @return array
     */
    public static function openAPITypes()
    {
        return self::$openAPITypes;
    }

    /**
     * Array of property to format mappings. Used for (de)serialization
     *
     * @return array
     */
    public static function openAPIFormats()
    {
        return self::$openAPIFormats;
    }

    /**
     * Array of nullable properties
     *
     * @return array
     */
    protected static function openAPINullables(): array
    {
        return self::$openAPINullables;
    }

    /**
     * Array of nullable field names deliberately set to null
     *
     * @return boolean[]
     */
    private function getOpenAPINullablesSetToNull(): array
    {
        return $this->openAPINullablesSetToNull;
    }

    /**
     * Setter - Array of nullable field names deliberately set to null
     *
     * @param boolean[] $openAPINullablesSetToNull
     */
    private function setOpenAPINullablesSetToNull(array $openAPINullablesSetToNull): void
    {
        $this->openAPINullablesSetToNull = $openAPINullablesSetToNull;
    }

    /**
     * Checks if a property is nullable
     *
     * @param string $property
     * @return bool
     */
    public static function isNullable(string $property): bool
    {
        return self::openAPINullables()[$property] ?? false;
    }

    /**
     * Checks if a nullable property is set to null.
     *
     * @param string $property
     * @return bool
     */
    public function isNullableSetToNull(string $property): bool
    {
        return in_array($property, $this->getOpenAPINullablesSetToNull(), true);
    }

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @var string[]
     */
    protected static $attributeMap = [
        'payment_id' => 'paymentId',
        'end_to_end_id' => 'endToEndId',
        'consent_id' => 'consentId',
        'creation_date_time' => 'creationDateTime',
        'status_update_date_time' => 'statusUpdateDateTime',
        'proxy' => 'proxy',
        'ibge_town_code' => 'ibgeTownCode',
        'status' => 'status',
        'rejection_reason' => 'rejectionReason',
        'local_instrument' => 'localInstrument',
        'cnpj_initiator' => 'cnpjInitiator',
        'payment' => 'payment',
        'transaction_identification' => 'transactionIdentification',
        'remittance_information' => 'remittanceInformation',
        'creditor_account' => 'creditorAccount',
        'cancellation' => 'cancellation',
        'debtor_account' => 'debtorAccount',
        'authorisation_flow' => 'authorisationFlow'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'payment_id' => 'setPaymentId',
        'end_to_end_id' => 'setEndToEndId',
        'consent_id' => 'setConsentId',
        'creation_date_time' => 'setCreationDateTime',
        'status_update_date_time' => 'setStatusUpdateDateTime',
        'proxy' => 'setProxy',
        'ibge_town_code' => 'setIbgeTownCode',
        'status' => 'setStatus',
        'rejection_reason' => 'setRejectionReason',
        'local_instrument' => 'setLocalInstrument',
        'cnpj_initiator' => 'setCnpjInitiator',
        'payment' => 'setPayment',
        'transaction_identification' => 'setTransactionIdentification',
        'remittance_information' => 'setRemittanceInformation',
        'creditor_account' => 'setCreditorAccount',
        'cancellation' => 'setCancellation',
        'debtor_account' => 'setDebtorAccount',
        'authorisation_flow' => 'setAuthorisationFlow'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'payment_id' => 'getPaymentId',
        'end_to_end_id' => 'getEndToEndId',
        'consent_id' => 'getConsentId',
        'creation_date_time' => 'getCreationDateTime',
        'status_update_date_time' => 'getStatusUpdateDateTime',
        'proxy' => 'getProxy',
        'ibge_town_code' => 'getIbgeTownCode',
        'status' => 'getStatus',
        'rejection_reason' => 'getRejectionReason',
        'local_instrument' => 'getLocalInstrument',
        'cnpj_initiator' => 'getCnpjInitiator',
        'payment' => 'getPayment',
        'transaction_identification' => 'getTransactionIdentification',
        'remittance_information' => 'getRemittanceInformation',
        'creditor_account' => 'getCreditorAccount',
        'cancellation' => 'getCancellation',
        'debtor_account' => 'getDebtorAccount',
        'authorisation_flow' => 'getAuthorisationFlow'
    ];

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @return array
     */
    public static function attributeMap()
    {
        return self::$attributeMap;
    }

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @return array
     */
    public static function setters()
    {
        return self::$setters;
    }

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @return array
     */
    public static function getters()
    {
        return self::$getters;
    }

    /**
     * The original name of the model.
     *
     * @return string
     */
    public function getModelName()
    {
        return self::$openAPIModelName;
    }

    public const AUTHORISATION_FLOW_HYBRID_FLOW = 'HYBRID_FLOW';
    public const AUTHORISATION_FLOW_CIBA_FLOW = 'CIBA_FLOW';
    public const AUTHORISATION_FLOW_FIDO_FLOW = 'FIDO_FLOW';

    /**
     * Gets allowable values of the enum
     *
     * @return string[]
     */
    public function getAuthorisationFlowAllowableValues()
    {
        return [
            self::AUTHORISATION_FLOW_HYBRID_FLOW,
            self::AUTHORISATION_FLOW_CIBA_FLOW,
            self::AUTHORISATION_FLOW_FIDO_FLOW,
        ];
    }

    /**
     * Associative array for storing property values
     *
     * @var mixed[]
     */
    protected $container = [];

    /**
     * Constructor
     *
     * @param mixed[]|null $data Associated array of property values
     *                      initializing the model
     */
    public function __construct(?array $data = null)
    {
        $this->setIfExists('payment_id', $data ?? [], null);
        $this->setIfExists('end_to_end_id', $data ?? [], null);
        $this->setIfExists('consent_id', $data ?? [], null);
        $this->setIfExists('creation_date_time', $data ?? [], null);
        $this->setIfExists('status_update_date_time', $data ?? [], null);
        $this->setIfExists('proxy', $data ?? [], null);
        $this->setIfExists('ibge_town_code', $data ?? [], null);
        $this->setIfExists('status', $data ?? [], null);
        $this->setIfExists('rejection_reason', $data ?? [], null);
        $this->setIfExists('local_instrument', $data ?? [], null);
        $this->setIfExists('cnpj_initiator', $data ?? [], null);
        $this->setIfExists('payment', $data ?? [], null);
        $this->setIfExists('transaction_identification', $data ?? [], null);
        $this->setIfExists('remittance_information', $data ?? [], null);
        $this->setIfExists('creditor_account', $data ?? [], null);
        $this->setIfExists('cancellation', $data ?? [], null);
        $this->setIfExists('debtor_account', $data ?? [], null);
        $this->setIfExists('authorisation_flow', $data ?? [], null);
    }

    /**
    * Sets $this->container[$variableName] to the given data or to the given default Value; if $variableName
    * is nullable and its value is set to null in the $fields array, then mark it as "set to null" in the
    * $this->openAPINullablesSetToNull array
    *
    * @param string $variableName
    * @param array  $fields
    * @param mixed  $defaultValue
    */
    private function setIfExists(string $variableName, array $fields, $defaultValue): void
    {
        if (self::isNullable($variableName) && array_key_exists($variableName, $fields) && is_null($fields[$variableName])) {
            $this->openAPINullablesSetToNull[] = $variableName;
        }

        $this->container[$variableName] = $fields[$variableName] ?? $defaultValue;
    }

    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalidProperties = [];

        if ($this->container['payment_id'] === null) {
            $invalidProperties[] = "'payment_id' can't be null";
        }
        if ((mb_strlen($this->container['payment_id']) > 100)) {
            $invalidProperties[] = "invalid value for 'payment_id', the character length must be smaller than or equal to 100.";
        }

        if ((mb_strlen($this->container['payment_id']) < 1)) {
            $invalidProperties[] = "invalid value for 'payment_id', the character length must be bigger than or equal to 1.";
        }

        if (!preg_match("/^[a-zA-Z0-9][a-zA-Z0-9\\-]{0,99}$/", $this->container['payment_id'])) {
            $invalidProperties[] = "invalid value for 'payment_id', must be conform to the pattern /^[a-zA-Z0-9][a-zA-Z0-9\\-]{0,99}$/.";
        }

        if ($this->container['end_to_end_id'] === null) {
            $invalidProperties[] = "'end_to_end_id' can't be null";
        }
        if ((mb_strlen($this->container['end_to_end_id']) > 32)) {
            $invalidProperties[] = "invalid value for 'end_to_end_id', the character length must be smaller than or equal to 32.";
        }

        if ((mb_strlen($this->container['end_to_end_id']) < 32)) {
            $invalidProperties[] = "invalid value for 'end_to_end_id', the character length must be bigger than or equal to 32.";
        }

        if (!preg_match("/^([E])([0-9]{8})([0-9]{4})(0[1-9]|1[0-2])(0[1-9]|[1-2][0-9]|3[0-1])(2[0-3]|[01][0-9])([0-5][0-9])([a-zA-Z0-9]{11})$/", $this->container['end_to_end_id'])) {
            $invalidProperties[] = "invalid value for 'end_to_end_id', must be conform to the pattern /^([E])([0-9]{8})([0-9]{4})(0[1-9]|1[0-2])(0[1-9]|[1-2][0-9]|3[0-1])(2[0-3]|[01][0-9])([0-5][0-9])([a-zA-Z0-9]{11})$/.";
        }

        if ($this->container['consent_id'] === null) {
            $invalidProperties[] = "'consent_id' can't be null";
        }
        if ((mb_strlen($this->container['consent_id']) > 256)) {
            $invalidProperties[] = "invalid value for 'consent_id', the character length must be smaller than or equal to 256.";
        }

        if (!preg_match("/^urn:[a-zA-Z0-9][a-zA-Z0-9\\-]{0,31}:[a-zA-Z0-9()+,\\-.:=@;$_!*'%\/?#]+$/", $this->container['consent_id'])) {
            $invalidProperties[] = "invalid value for 'consent_id', must be conform to the pattern /^urn:[a-zA-Z0-9][a-zA-Z0-9\\-]{0,31}:[a-zA-Z0-9()+,\\-.:=@;$_!*'%\/?#]+$/.";
        }

        if ($this->container['creation_date_time'] === null) {
            $invalidProperties[] = "'creation_date_time' can't be null";
        }
        if (!preg_match("/^(\\d{4})-(1[0-2]|0?[1-9])-(3[01]|[12][0-9]|0?[1-9])T(?:[01]\\d|2[0123]):(?:[012345]\\d):(?:[012345]\\d)Z$/", $this->container['creation_date_time'])) {
            $invalidProperties[] = "invalid value for 'creation_date_time', must be conform to the pattern /^(\\d{4})-(1[0-2]|0?[1-9])-(3[01]|[12][0-9]|0?[1-9])T(?:[01]\\d|2[0123]):(?:[012345]\\d):(?:[012345]\\d)Z$/.";
        }

        if ($this->container['status_update_date_time'] === null) {
            $invalidProperties[] = "'status_update_date_time' can't be null";
        }
        if (!preg_match("/^(\\d{4})-(1[0-2]|0?[1-9])-(3[01]|[12][0-9]|0?[1-9])T(?:[01]\\d|2[0123]):(?:[012345]\\d):(?:[012345]\\d)Z$/", $this->container['status_update_date_time'])) {
            $invalidProperties[] = "invalid value for 'status_update_date_time', must be conform to the pattern /^(\\d{4})-(1[0-2]|0?[1-9])-(3[01]|[12][0-9]|0?[1-9])T(?:[01]\\d|2[0123]):(?:[012345]\\d):(?:[012345]\\d)Z$/.";
        }

        if (!is_null($this->container['proxy']) && (mb_strlen($this->container['proxy']) > 77)) {
            $invalidProperties[] = "invalid value for 'proxy', the character length must be smaller than or equal to 77.";
        }

        if (!is_null($this->container['proxy']) && !preg_match("/[\\w\\W\\s]*/", $this->container['proxy'])) {
            $invalidProperties[] = "invalid value for 'proxy', must be conform to the pattern /[\\w\\W\\s]*/.";
        }

        if (!is_null($this->container['ibge_town_code']) && (mb_strlen($this->container['ibge_town_code']) > 7)) {
            $invalidProperties[] = "invalid value for 'ibge_town_code', the character length must be smaller than or equal to 7.";
        }

        if (!is_null($this->container['ibge_town_code']) && (mb_strlen($this->container['ibge_town_code']) < 7)) {
            $invalidProperties[] = "invalid value for 'ibge_town_code', the character length must be bigger than or equal to 7.";
        }

        if (!is_null($this->container['ibge_town_code']) && !preg_match("/^\\d{7}$/", $this->container['ibge_town_code'])) {
            $invalidProperties[] = "invalid value for 'ibge_town_code', must be conform to the pattern /^\\d{7}$/.";
        }

        if ($this->container['status'] === null) {
            $invalidProperties[] = "'status' can't be null";
        }
        if ($this->container['local_instrument'] === null) {
            $invalidProperties[] = "'local_instrument' can't be null";
        }
        if ($this->container['cnpj_initiator'] === null) {
            $invalidProperties[] = "'cnpj_initiator' can't be null";
        }
        if ((mb_strlen($this->container['cnpj_initiator']) > 14)) {
            $invalidProperties[] = "invalid value for 'cnpj_initiator', the character length must be smaller than or equal to 14.";
        }

        if (!preg_match("/^\\d{14}$/", $this->container['cnpj_initiator'])) {
            $invalidProperties[] = "invalid value for 'cnpj_initiator', must be conform to the pattern /^\\d{14}$/.";
        }

        if ($this->container['payment'] === null) {
            $invalidProperties[] = "'payment' can't be null";
        }
        if (!is_null($this->container['transaction_identification']) && (mb_strlen($this->container['transaction_identification']) > 35)) {
            $invalidProperties[] = "invalid value for 'transaction_identification', the character length must be smaller than or equal to 35.";
        }

        if (!is_null($this->container['transaction_identification']) && !preg_match("/^[a-zA-Z0-9]{1,35}$/", $this->container['transaction_identification'])) {
            $invalidProperties[] = "invalid value for 'transaction_identification', must be conform to the pattern /^[a-zA-Z0-9]{1,35}$/.";
        }

        if (!is_null($this->container['remittance_information']) && (mb_strlen($this->container['remittance_information']) > 140)) {
            $invalidProperties[] = "invalid value for 'remittance_information', the character length must be smaller than or equal to 140.";
        }

        if (!is_null($this->container['remittance_information']) && !preg_match("/[\\w\\W\\s]*/", $this->container['remittance_information'])) {
            $invalidProperties[] = "invalid value for 'remittance_information', must be conform to the pattern /[\\w\\W\\s]*/.";
        }

        if ($this->container['creditor_account'] === null) {
            $invalidProperties[] = "'creditor_account' can't be null";
        }
        if ($this->container['debtor_account'] === null) {
            $invalidProperties[] = "'debtor_account' can't be null";
        }
        $allowedValues = $this->getAuthorisationFlowAllowableValues();
        if (!is_null($this->container['authorisation_flow']) && !in_array($this->container['authorisation_flow'], $allowedValues, true)) {
            $invalidProperties[] = sprintf(
                "invalid value '%s' for 'authorisation_flow', must be one of '%s'",
                $this->container['authorisation_flow'],
                implode("', '", $allowedValues)
            );
        }

        return $invalidProperties;
    }

    /**
     * Validate all the properties in the model
     * return true if all passed
     *
     * @return bool True if all properties are valid
     */
    public function valid()
    {
        return count($this->listInvalidProperties()) === 0;
    }


    /**
     * Gets payment_id
     *
     * @return string
     */
    public function getPaymentId()
    {
        return $this->container['payment_id'];
    }

    /**
     * Sets payment_id
     *
     * @param string $payment_id Código ou identificador único informado pela instituição detentora da conta para representar a iniciação de pagamento individual. O `paymentId` deve ser diferente do `endToEndId`. Este é o identificador que deverá ser utilizado na consulta ao status da iniciação de pagamento efetuada.
     *
     * @return self
     */
    public function setPaymentId($payment_id)
    {
        if (is_null($payment_id)) {
            throw new \InvalidArgumentException('non-nullable payment_id cannot be null');
        }
        if ((mb_strlen($payment_id) > 100)) {
            throw new \InvalidArgumentException('invalid length for $payment_id when calling ResponsePixPaymentData., must be smaller than or equal to 100.');
        }
        if ((mb_strlen($payment_id) < 1)) {
            throw new \InvalidArgumentException('invalid length for $payment_id when calling ResponsePixPaymentData., must be bigger than or equal to 1.');
        }
        if ((!preg_match("/^[a-zA-Z0-9][a-zA-Z0-9\\-]{0,99}$/", ObjectSerializer::toString($payment_id)))) {
            throw new \InvalidArgumentException("invalid value for \$payment_id when calling ResponsePixPaymentData., must conform to the pattern /^[a-zA-Z0-9][a-zA-Z0-9\\-]{0,99}$/.");
        }

        $this->container['payment_id'] = $payment_id;

        return $this;
    }

    /**
     * Gets end_to_end_id
     *
     * @return string
     */
    public function getEndToEndId()
    {
        return $this->container['end_to_end_id'];
    }

    /**
     * Sets end_to_end_id
     *
     * @param string $end_to_end_id Trata-se de um identificador único, gerado na instituição iniciadora de pagamento e recebido na instituição detentora de conta, permeando toda a jornada do pagamento Pix.  [Restrição] A detentora deve obrigatoriamente retornar o campo Com o mesmo valor recebido da iniciadora.
     *
     * @return self
     */
    public function setEndToEndId($end_to_end_id)
    {
        if (is_null($end_to_end_id)) {
            throw new \InvalidArgumentException('non-nullable end_to_end_id cannot be null');
        }
        if ((mb_strlen($end_to_end_id) > 32)) {
            throw new \InvalidArgumentException('invalid length for $end_to_end_id when calling ResponsePixPaymentData., must be smaller than or equal to 32.');
        }
        if ((mb_strlen($end_to_end_id) < 32)) {
            throw new \InvalidArgumentException('invalid length for $end_to_end_id when calling ResponsePixPaymentData., must be bigger than or equal to 32.');
        }
        if ((!preg_match("/^([E])([0-9]{8})([0-9]{4})(0[1-9]|1[0-2])(0[1-9]|[1-2][0-9]|3[0-1])(2[0-3]|[01][0-9])([0-5][0-9])([a-zA-Z0-9]{11})$/", ObjectSerializer::toString($end_to_end_id)))) {
            throw new \InvalidArgumentException("invalid value for \$end_to_end_id when calling ResponsePixPaymentData., must conform to the pattern /^([E])([0-9]{8})([0-9]{4})(0[1-9]|1[0-2])(0[1-9]|[1-2][0-9]|3[0-1])(2[0-3]|[01][0-9])([0-5][0-9])([a-zA-Z0-9]{11})$/.");
        }

        $this->container['end_to_end_id'] = $end_to_end_id;

        return $this;
    }

    /**
     * Gets consent_id
     *
     * @return string
     */
    public function getConsentId()
    {
        return $this->container['consent_id'];
    }

    /**
     * Sets consent_id
     *
     * @param string $consent_id Identificador único do consentimento criado para a iniciação de pagamento solicitada. Deverá ser um URN - Uniform Resource Name. Um URN, conforme definido na [RFC8141](https://tools.ietf.org/html/rfc8141) é um Uniform Resource Identifier - URI - que é atribuído sob o URI scheme \"urn\" e um namespace URN específico, com a intenção de que o URN seja um identificador de recurso persistente e independente da localização. Considerando a string urn:bancoex:C1DD33123 como exemplo para consentId temos: - o namespace(urn) - o identificador associado ao namespace da instituição transnmissora (bancoex) - o identificador específico dentro do namespace (C1DD33123). Informações mais detalhadas sobre a construção de namespaces devem ser consultadas na [RFC8141](https://tools.ietf.org/html/rfc8141).
     *
     * @return self
     */
    public function setConsentId($consent_id)
    {
        if (is_null($consent_id)) {
            throw new \InvalidArgumentException('non-nullable consent_id cannot be null');
        }
        if ((mb_strlen($consent_id) > 256)) {
            throw new \InvalidArgumentException('invalid length for $consent_id when calling ResponsePixPaymentData., must be smaller than or equal to 256.');
        }
        if ((!preg_match("/^urn:[a-zA-Z0-9][a-zA-Z0-9\\-]{0,31}:[a-zA-Z0-9()+,\\-.:=@;$_!*'%\/?#]+$/", ObjectSerializer::toString($consent_id)))) {
            throw new \InvalidArgumentException("invalid value for \$consent_id when calling ResponsePixPaymentData., must conform to the pattern /^urn:[a-zA-Z0-9][a-zA-Z0-9\\-]{0,31}:[a-zA-Z0-9()+,\\-.:=@;$_!*'%\/?#]+$/.");
        }

        $this->container['consent_id'] = $consent_id;

        return $this;
    }

    /**
     * Gets creation_date_time
     *
     * @return \DateTime
     */
    public function getCreationDateTime()
    {
        return $this->container['creation_date_time'];
    }

    /**
     * Sets creation_date_time
     *
     * @param \DateTime $creation_date_time Data e hora em que o recurso foi criado. Uma string com data e hora conforme especificação RFC-3339, sempre com a utilização de timezone UTC(UTC time format).
     *
     * @return self
     */
    public function setCreationDateTime($creation_date_time)
    {
        if (is_null($creation_date_time)) {
            throw new \InvalidArgumentException('non-nullable creation_date_time cannot be null');
        }

        if ((!preg_match("/^(\\d{4})-(1[0-2]|0?[1-9])-(3[01]|[12][0-9]|0?[1-9])T(?:[01]\\d|2[0123]):(?:[012345]\\d):(?:[012345]\\d)Z$/", ObjectSerializer::toString($creation_date_time)))) {
            throw new \InvalidArgumentException("invalid value for \$creation_date_time when calling ResponsePixPaymentData., must conform to the pattern /^(\\d{4})-(1[0-2]|0?[1-9])-(3[01]|[12][0-9]|0?[1-9])T(?:[01]\\d|2[0123]):(?:[012345]\\d):(?:[012345]\\d)Z$/.");
        }

        $this->container['creation_date_time'] = $creation_date_time;

        return $this;
    }

    /**
     * Gets status_update_date_time
     *
     * @return \DateTime
     */
    public function getStatusUpdateDateTime()
    {
        return $this->container['status_update_date_time'];
    }

    /**
     * Sets status_update_date_time
     *
     * @param \DateTime $status_update_date_time Data e hora da última atualização da iniciação de pagamento. Uma string com data e hora conforme especificação RFC-3339, sempre com a utilização de timezone UTC(UTC time format).
     *
     * @return self
     */
    public function setStatusUpdateDateTime($status_update_date_time)
    {
        if (is_null($status_update_date_time)) {
            throw new \InvalidArgumentException('non-nullable status_update_date_time cannot be null');
        }

        if ((!preg_match("/^(\\d{4})-(1[0-2]|0?[1-9])-(3[01]|[12][0-9]|0?[1-9])T(?:[01]\\d|2[0123]):(?:[012345]\\d):(?:[012345]\\d)Z$/", ObjectSerializer::toString($status_update_date_time)))) {
            throw new \InvalidArgumentException("invalid value for \$status_update_date_time when calling ResponsePixPaymentData., must conform to the pattern /^(\\d{4})-(1[0-2]|0?[1-9])-(3[01]|[12][0-9]|0?[1-9])T(?:[01]\\d|2[0123]):(?:[012345]\\d):(?:[012345]\\d)Z$/.");
        }

        $this->container['status_update_date_time'] = $status_update_date_time;

        return $this;
    }

    /**
     * Gets proxy
     *
     * @return string|null
     */
    public function getProxy()
    {
        return $this->container['proxy'];
    }

    /**
     * Sets proxy
     *
     * @param string|null $proxy Chave cadastrada no DICT pertencente ao recebedor. Os tipos de chaves podem ser: telefone, e-mail, cpf/cnpj ou chave aleatória. No caso de telefone celular deve ser informado no padrão E.1641. Para e-mail deve ter o formato xxxxxxxx@xxxxxxx.xxx(.xx) e no máximo 77 caracteres. No caso de CPF deverá ser informado com 11 números, sem pontos ou traços. Para o caso de CNPJ deverá ser informado com 14 números, sem pontos ou traços. No caso de chave aleatória deve ser informado o UUID gerado pelo DICT, conforme formato especificado na RFC41223. Se informado, a detentora da conta deve validar o proxy no DICT quando localInstrument for igual a DICT, QRDN ou QRES e validar o campo creditorAccount. Esta validação é opcional caso o localInstrument for igual a INIC. [Restrição] Se localInstrument for igual a MANU, o campo proxy não deve ser preenchido. Se localInstrument for igual INIC, DICT, QRDN ou QRES, o campo proxy deve ser sempre preenchido com a chave Pix.
     *
     * @return self
     */
    public function setProxy($proxy)
    {
        if (is_null($proxy)) {
            throw new \InvalidArgumentException('non-nullable proxy cannot be null');
        }
        if ((mb_strlen($proxy) > 77)) {
            throw new \InvalidArgumentException('invalid length for $proxy when calling ResponsePixPaymentData., must be smaller than or equal to 77.');
        }
        if ((!preg_match("/[\\w\\W\\s]*/", ObjectSerializer::toString($proxy)))) {
            throw new \InvalidArgumentException("invalid value for \$proxy when calling ResponsePixPaymentData., must conform to the pattern /[\\w\\W\\s]*/.");
        }

        $this->container['proxy'] = $proxy;

        return $this;
    }

    /**
     * Gets ibge_town_code
     *
     * @return string|null
     */
    public function getIbgeTownCode()
    {
        return $this->container['ibge_town_code'];
    }

    /**
     * Sets ibge_town_code
     *
     * @param string|null $ibge_town_code O campo ibgetowncode no arranjo PIX, tem o mesmo comportamento que o campo codMun descrito no item 1.6.6 do manual do PIX, conforme segue:  1. Caso a informação referente ao município não seja enviada; o PSP do recebedor assumirá que não existem feriados estaduais e municipais no período em questão;
     *
     * @return self
     */
    public function setIbgeTownCode($ibge_town_code)
    {
        if (is_null($ibge_town_code)) {
            throw new \InvalidArgumentException('non-nullable ibge_town_code cannot be null');
        }
        if ((mb_strlen($ibge_town_code) > 7)) {
            throw new \InvalidArgumentException('invalid length for $ibge_town_code when calling ResponsePixPaymentData., must be smaller than or equal to 7.');
        }
        if ((mb_strlen($ibge_town_code) < 7)) {
            throw new \InvalidArgumentException('invalid length for $ibge_town_code when calling ResponsePixPaymentData., must be bigger than or equal to 7.');
        }
        if ((!preg_match("/^\\d{7}$/", ObjectSerializer::toString($ibge_town_code)))) {
            throw new \InvalidArgumentException("invalid value for \$ibge_town_code when calling ResponsePixPaymentData., must conform to the pattern /^\\d{7}$/.");
        }

        $this->container['ibge_town_code'] = $ibge_town_code;

        return $this;
    }

    /**
     * Gets status
     *
     * @return \OpenAPI\Client\Model\EnumPaymentStatusType
     */
    public function getStatus()
    {
        return $this->container['status'];
    }

    /**
     * Sets status
     *
     * @param \OpenAPI\Client\Model\EnumPaymentStatusType $status status
     *
     * @return self
     */
    public function setStatus($status)
    {
        if (is_null($status)) {
            throw new \InvalidArgumentException('non-nullable status cannot be null');
        }
        $this->container['status'] = $status;

        return $this;
    }

    /**
     * Gets rejection_reason
     *
     * @return \OpenAPI\Client\Model\RejectionReasonGetPix|null
     */
    public function getRejectionReason()
    {
        return $this->container['rejection_reason'];
    }

    /**
     * Sets rejection_reason
     *
     * @param \OpenAPI\Client\Model\RejectionReasonGetPix|null $rejection_reason rejection_reason
     *
     * @return self
     */
    public function setRejectionReason($rejection_reason)
    {
        if (is_null($rejection_reason)) {
            throw new \InvalidArgumentException('non-nullable rejection_reason cannot be null');
        }
        $this->container['rejection_reason'] = $rejection_reason;

        return $this;
    }

    /**
     * Gets local_instrument
     *
     * @return \OpenAPI\Client\Model\EnumLocalInstrument
     */
    public function getLocalInstrument()
    {
        return $this->container['local_instrument'];
    }

    /**
     * Sets local_instrument
     *
     * @param \OpenAPI\Client\Model\EnumLocalInstrument $local_instrument local_instrument
     *
     * @return self
     */
    public function setLocalInstrument($local_instrument)
    {
        if (is_null($local_instrument)) {
            throw new \InvalidArgumentException('non-nullable local_instrument cannot be null');
        }
        $this->container['local_instrument'] = $local_instrument;

        return $this;
    }

    /**
     * Gets cnpj_initiator
     *
     * @return string
     */
    public function getCnpjInitiator()
    {
        return $this->container['cnpj_initiator'];
    }

    /**
     * Sets cnpj_initiator
     *
     * @param string $cnpj_initiator CNPJ do Iniciador de Pagamento devidamente habilitado para a prestação de Serviço de Iniciação no Pix.
     *
     * @return self
     */
    public function setCnpjInitiator($cnpj_initiator)
    {
        if (is_null($cnpj_initiator)) {
            throw new \InvalidArgumentException('non-nullable cnpj_initiator cannot be null');
        }
        if ((mb_strlen($cnpj_initiator) > 14)) {
            throw new \InvalidArgumentException('invalid length for $cnpj_initiator when calling ResponsePixPaymentData., must be smaller than or equal to 14.');
        }
        if ((!preg_match("/^\\d{14}$/", ObjectSerializer::toString($cnpj_initiator)))) {
            throw new \InvalidArgumentException("invalid value for \$cnpj_initiator when calling ResponsePixPaymentData., must conform to the pattern /^\\d{14}$/.");
        }

        $this->container['cnpj_initiator'] = $cnpj_initiator;

        return $this;
    }

    /**
     * Gets payment
     *
     * @return \OpenAPI\Client\Model\PaymentPix
     */
    public function getPayment()
    {
        return $this->container['payment'];
    }

    /**
     * Sets payment
     *
     * @param \OpenAPI\Client\Model\PaymentPix $payment payment
     *
     * @return self
     */
    public function setPayment($payment)
    {
        if (is_null($payment)) {
            throw new \InvalidArgumentException('non-nullable payment cannot be null');
        }
        $this->container['payment'] = $payment;

        return $this;
    }

    /**
     * Gets transaction_identification
     *
     * @return string|null
     */
    public function getTransactionIdentification()
    {
        return $this->container['transaction_identification'];
    }

    /**
     * Sets transaction_identification
     *
     * @param string|null $transaction_identification Trata-se de um identificador de transação que deve ser retransmitido intacto pelo PSP do pagador ao gerar a ordem de pagamento.  [Restrição] A detentora deve obrigatoriamente retornar o campo com o mesmo valor recebido da iniciadora, caso ele tenha sido enviado na requisição da iniciação do pagamento.
     *
     * @return self
     */
    public function setTransactionIdentification($transaction_identification)
    {
        if (is_null($transaction_identification)) {
            throw new \InvalidArgumentException('non-nullable transaction_identification cannot be null');
        }
        if ((mb_strlen($transaction_identification) > 35)) {
            throw new \InvalidArgumentException('invalid length for $transaction_identification when calling ResponsePixPaymentData., must be smaller than or equal to 35.');
        }
        if ((!preg_match("/^[a-zA-Z0-9]{1,35}$/", ObjectSerializer::toString($transaction_identification)))) {
            throw new \InvalidArgumentException("invalid value for \$transaction_identification when calling ResponsePixPaymentData., must conform to the pattern /^[a-zA-Z0-9]{1,35}$/.");
        }

        $this->container['transaction_identification'] = $transaction_identification;

        return $this;
    }

    /**
     * Gets remittance_information
     *
     * @return string|null
     */
    public function getRemittanceInformation()
    {
        return $this->container['remittance_information'];
    }

    /**
     * Sets remittance_information
     *
     * @param string|null $remittance_information Deve ser preenchido sempre que o usuário pagador inserir alguma informação adicional em um pagamento, a ser enviada ao recebedor.
     *
     * @return self
     */
    public function setRemittanceInformation($remittance_information)
    {
        if (is_null($remittance_information)) {
            throw new \InvalidArgumentException('non-nullable remittance_information cannot be null');
        }
        if ((mb_strlen($remittance_information) > 140)) {
            throw new \InvalidArgumentException('invalid length for $remittance_information when calling ResponsePixPaymentData., must be smaller than or equal to 140.');
        }
        if ((!preg_match("/[\\w\\W\\s]*/", ObjectSerializer::toString($remittance_information)))) {
            throw new \InvalidArgumentException("invalid value for \$remittance_information when calling ResponsePixPaymentData., must conform to the pattern /[\\w\\W\\s]*/.");
        }

        $this->container['remittance_information'] = $remittance_information;

        return $this;
    }

    /**
     * Gets creditor_account
     *
     * @return \OpenAPI\Client\Model\CreditorAccount
     */
    public function getCreditorAccount()
    {
        return $this->container['creditor_account'];
    }

    /**
     * Sets creditor_account
     *
     * @param \OpenAPI\Client\Model\CreditorAccount $creditor_account creditor_account
     *
     * @return self
     */
    public function setCreditorAccount($creditor_account)
    {
        if (is_null($creditor_account)) {
            throw new \InvalidArgumentException('non-nullable creditor_account cannot be null');
        }
        $this->container['creditor_account'] = $creditor_account;

        return $this;
    }

    /**
     * Gets cancellation
     *
     * @return \OpenAPI\Client\Model\PixPaymentCancellation|null
     */
    public function getCancellation()
    {
        return $this->container['cancellation'];
    }

    /**
     * Sets cancellation
     *
     * @param \OpenAPI\Client\Model\PixPaymentCancellation|null $cancellation cancellation
     *
     * @return self
     */
    public function setCancellation($cancellation)
    {
        if (is_null($cancellation)) {
            throw new \InvalidArgumentException('non-nullable cancellation cannot be null');
        }
        $this->container['cancellation'] = $cancellation;

        return $this;
    }

    /**
     * Gets debtor_account
     *
     * @return \OpenAPI\Client\Model\DebtorAccount
     */
    public function getDebtorAccount()
    {
        return $this->container['debtor_account'];
    }

    /**
     * Sets debtor_account
     *
     * @param \OpenAPI\Client\Model\DebtorAccount $debtor_account debtor_account
     *
     * @return self
     */
    public function setDebtorAccount($debtor_account)
    {
        if (is_null($debtor_account)) {
            throw new \InvalidArgumentException('non-nullable debtor_account cannot be null');
        }
        $this->container['debtor_account'] = $debtor_account;

        return $this;
    }

    /**
     * Gets authorisation_flow
     *
     * @return string|null
     */
    public function getAuthorisationFlow()
    {
        return $this->container['authorisation_flow'];
    }

    /**
     * Sets authorisation_flow
     *
     * @param string|null $authorisation_flow Campo condicional utilizado para identificar o fluxo de autorização em que o pagamento foi solicitado.  [Restrição] Se CIBA ou FIDO, preenchimento obrigatório. Caso o campo não esteja presente no payload, subentende-se que o fluxo de autorização utilizado é o HYBRID_FLOW.
     *
     * @return self
     */
    public function setAuthorisationFlow($authorisation_flow)
    {
        if (is_null($authorisation_flow)) {
            throw new \InvalidArgumentException('non-nullable authorisation_flow cannot be null');
        }
        $allowedValues = $this->getAuthorisationFlowAllowableValues();
        if (!in_array($authorisation_flow, $allowedValues, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid value '%s' for 'authorisation_flow', must be one of '%s'",
                    $authorisation_flow,
                    implode("', '", $allowedValues)
                )
            );
        }
        $this->container['authorisation_flow'] = $authorisation_flow;

        return $this;
    }
    /**
     * Returns true if offset exists. False otherwise.
     *
     * @param integer|string $offset Offset
     *
     * @return boolean
     */
    public function offsetExists(mixed $offset): bool
    {
        return isset($this->container[$offset]);
    }

    /**
     * Gets offset.
     *
     * @param integer|string $offset Offset
     *
     * @return mixed|null
     */
    #[\ReturnTypeWillChange]
    public function offsetGet(mixed $offset)
    {
        return $this->container[$offset] ?? null;
    }

    /**
     * Sets value based on offset.
     *
     * @param int|null $offset Offset
     * @param mixed    $value  Value to be set
     *
     * @return void
     */
    public function offsetSet($offset, $value): void
    {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }

    /**
     * Unsets offset.
     *
     * @param integer|string $offset Offset
     *
     * @return void
     */
    public function offsetUnset(mixed $offset): void
    {
        unset($this->container[$offset]);
    }

    /**
     * Serializes the object to a value that can be serialized natively by json_encode().
     * @link https://www.php.net/manual/en/jsonserializable.jsonserialize.php
     *
     * @return mixed Returns data which can be serialized by json_encode(), which is a value
     * of any type other than a resource.
     */
    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
       return ObjectSerializer::sanitizeForSerialization($this);
    }

    /**
     * Gets the string presentation of the object
     *
     * @return string
     */
    public function __toString()
    {
        return json_encode(
            ObjectSerializer::sanitizeForSerialization($this),
            JSON_PRETTY_PRINT
        );
    }

    /**
     * Gets a header-safe presentation of the object
     *
     * @return string
     */
    public function toHeaderValue()
    {
        return json_encode(ObjectSerializer::sanitizeForSerialization($this));
    }
}


