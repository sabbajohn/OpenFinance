<?php
/**
 * CreatePaymentConsentDataPayment
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
 * CreatePaymentConsentDataPayment Class Doc Comment
 *
 * @category Class
 * @description Objeto contendo dados de pagamento para consentimento.
 * @package  OpenAPI\Client
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 * @implements \ArrayAccess<string, mixed>
 */
class CreatePaymentConsentDataPayment implements ModelInterface, ArrayAccess, \JsonSerializable
{
    public const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'CreatePaymentConsent_data_payment';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'type' => '\OpenAPI\Client\Model\EnumPaymentType',
        'schedule' => '\OpenAPI\Client\Model\Schedule',
        'date' => '\DateTime',
        'currency' => 'string',
        'amount' => 'string',
        'ibge_town_code' => 'string',
        'details' => '\OpenAPI\Client\Model\Details'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      * @phpstan-var array<string, string|null>
      * @psalm-var array<string, string|null>
      */
    protected static $openAPIFormats = [
        'type' => null,
        'schedule' => null,
        'date' => 'date',
        'currency' => null,
        'amount' => null,
        'ibge_town_code' => null,
        'details' => null
    ];

    /**
      * Array of nullable properties. Used for (de)serialization
      *
      * @var boolean[]
      */
    protected static array $openAPINullables = [
        'type' => false,
        'schedule' => false,
        'date' => false,
        'currency' => false,
        'amount' => false,
        'ibge_town_code' => false,
        'details' => false
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
        'type' => 'type',
        'schedule' => 'schedule',
        'date' => 'date',
        'currency' => 'currency',
        'amount' => 'amount',
        'ibge_town_code' => 'ibgeTownCode',
        'details' => 'details'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'type' => 'setType',
        'schedule' => 'setSchedule',
        'date' => 'setDate',
        'currency' => 'setCurrency',
        'amount' => 'setAmount',
        'ibge_town_code' => 'setIbgeTownCode',
        'details' => 'setDetails'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'type' => 'getType',
        'schedule' => 'getSchedule',
        'date' => 'getDate',
        'currency' => 'getCurrency',
        'amount' => 'getAmount',
        'ibge_town_code' => 'getIbgeTownCode',
        'details' => 'getDetails'
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
        $this->setIfExists('type', $data ?? [], null);
        $this->setIfExists('schedule', $data ?? [], null);
        $this->setIfExists('date', $data ?? [], null);
        $this->setIfExists('currency', $data ?? [], null);
        $this->setIfExists('amount', $data ?? [], null);
        $this->setIfExists('ibge_town_code', $data ?? [], null);
        $this->setIfExists('details', $data ?? [], null);
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

        if ($this->container['type'] === null) {
            $invalidProperties[] = "'type' can't be null";
        }
        if (!is_null($this->container['date']) && (mb_strlen($this->container['date']) > 10)) {
            $invalidProperties[] = "invalid value for 'date', the character length must be smaller than or equal to 10.";
        }

        if (!is_null($this->container['date']) && !preg_match("/^(\\d{4})-(1[0-2]|0?[1-9])-(3[01]|[12][0-9]|0?[1-9])$/", $this->container['date'])) {
            $invalidProperties[] = "invalid value for 'date', must be conform to the pattern /^(\\d{4})-(1[0-2]|0?[1-9])-(3[01]|[12][0-9]|0?[1-9])$/.";
        }

        if ($this->container['currency'] === null) {
            $invalidProperties[] = "'currency' can't be null";
        }
        if ((mb_strlen($this->container['currency']) > 3)) {
            $invalidProperties[] = "invalid value for 'currency', the character length must be smaller than or equal to 3.";
        }

        if (!preg_match("/^([A-Z]{3})$/", $this->container['currency'])) {
            $invalidProperties[] = "invalid value for 'currency', must be conform to the pattern /^([A-Z]{3})$/.";
        }

        if ($this->container['amount'] === null) {
            $invalidProperties[] = "'amount' can't be null";
        }
        if ((mb_strlen($this->container['amount']) > 19)) {
            $invalidProperties[] = "invalid value for 'amount', the character length must be smaller than or equal to 19.";
        }

        if ((mb_strlen($this->container['amount']) < 4)) {
            $invalidProperties[] = "invalid value for 'amount', the character length must be bigger than or equal to 4.";
        }

        if (!preg_match("/^((\\d{1,16}\\.\\d{2}))$/", $this->container['amount'])) {
            $invalidProperties[] = "invalid value for 'amount', must be conform to the pattern /^((\\d{1,16}\\.\\d{2}))$/.";
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

        if ($this->container['details'] === null) {
            $invalidProperties[] = "'details' can't be null";
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
     * Gets type
     *
     * @return \OpenAPI\Client\Model\EnumPaymentType
     */
    public function getType()
    {
        return $this->container['type'];
    }

    /**
     * Sets type
     *
     * @param \OpenAPI\Client\Model\EnumPaymentType $type type
     *
     * @return self
     */
    public function setType($type)
    {
        if (is_null($type)) {
            throw new \InvalidArgumentException('non-nullable type cannot be null');
        }
        $this->container['type'] = $type;

        return $this;
    }

    /**
     * Gets schedule
     *
     * @return \OpenAPI\Client\Model\Schedule|null
     */
    public function getSchedule()
    {
        return $this->container['schedule'];
    }

    /**
     * Sets schedule
     *
     * @param \OpenAPI\Client\Model\Schedule|null $schedule schedule
     *
     * @return self
     */
    public function setSchedule($schedule)
    {
        if (is_null($schedule)) {
            throw new \InvalidArgumentException('non-nullable schedule cannot be null');
        }
        $this->container['schedule'] = $schedule;

        return $this;
    }

    /**
     * Gets date
     *
     * @return \DateTime|null
     */
    public function getDate()
    {
        return $this->container['date'];
    }

    /**
     * Sets date
     *
     * @param \DateTime|null $date [Restrição] Mutuamente excludente com o objeto schedule.   Este campo é obrigatório no caso de pagamento único.   Neste caso, o objeto schedule não deve ser informado.
     *
     * @return self
     */
    public function setDate($date)
    {
        if (is_null($date)) {
            throw new \InvalidArgumentException('non-nullable date cannot be null');
        }
        if ((mb_strlen($date) > 10)) {
            throw new \InvalidArgumentException('invalid length for $date when calling CreatePaymentConsentDataPayment., must be smaller than or equal to 10.');
        }
        if ((!preg_match("/^(\\d{4})-(1[0-2]|0?[1-9])-(3[01]|[12][0-9]|0?[1-9])$/", ObjectSerializer::toString($date)))) {
            throw new \InvalidArgumentException("invalid value for \$date when calling CreatePaymentConsentDataPayment., must conform to the pattern /^(\\d{4})-(1[0-2]|0?[1-9])-(3[01]|[12][0-9]|0?[1-9])$/.");
        }

        $this->container['date'] = $date;

        return $this;
    }

    /**
     * Gets currency
     *
     * @return string
     */
    public function getCurrency()
    {
        return $this->container['currency'];
    }

    /**
     * Sets currency
     *
     * @param string $currency Código da moeda nacional segundo modelo ISO-4217, ou seja, 'BRL'. Todos os valores monetários informados estão representados com a moeda vigente do Brasil.
     *
     * @return self
     */
    public function setCurrency($currency)
    {
        if (is_null($currency)) {
            throw new \InvalidArgumentException('non-nullable currency cannot be null');
        }
        if ((mb_strlen($currency) > 3)) {
            throw new \InvalidArgumentException('invalid length for $currency when calling CreatePaymentConsentDataPayment., must be smaller than or equal to 3.');
        }
        if ((!preg_match("/^([A-Z]{3})$/", ObjectSerializer::toString($currency)))) {
            throw new \InvalidArgumentException("invalid value for \$currency when calling CreatePaymentConsentDataPayment., must conform to the pattern /^([A-Z]{3})$/.");
        }

        $this->container['currency'] = $currency;

        return $this;
    }

    /**
     * Gets amount
     *
     * @return string
     */
    public function getAmount()
    {
        return $this->container['amount'];
    }

    /**
     * Sets amount
     *
     * @param string $amount Valor da transação com 2 casas decimais. O valor deve ser o mesmo enviado no consentimento.   Para QR Code estático com valor pré-determinado no QR Code ou para QR Code dinâmico com indicação de que o valor não pode ser alterado: O campo amount deve ser preenchido com o valor estabelecido no QR Code.  Caso seja preenchido com valor divergente do QR Code, deve ser retornado um erro HTTP Status 422.
     *
     * @return self
     */
    public function setAmount($amount)
    {
        if (is_null($amount)) {
            throw new \InvalidArgumentException('non-nullable amount cannot be null');
        }
        if ((mb_strlen($amount) > 19)) {
            throw new \InvalidArgumentException('invalid length for $amount when calling CreatePaymentConsentDataPayment., must be smaller than or equal to 19.');
        }
        if ((mb_strlen($amount) < 4)) {
            throw new \InvalidArgumentException('invalid length for $amount when calling CreatePaymentConsentDataPayment., must be bigger than or equal to 4.');
        }
        if ((!preg_match("/^((\\d{1,16}\\.\\d{2}))$/", ObjectSerializer::toString($amount)))) {
            throw new \InvalidArgumentException("invalid value for \$amount when calling CreatePaymentConsentDataPayment., must conform to the pattern /^((\\d{1,16}\\.\\d{2}))$/.");
        }

        $this->container['amount'] = $amount;

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
            throw new \InvalidArgumentException('invalid length for $ibge_town_code when calling CreatePaymentConsentDataPayment., must be smaller than or equal to 7.');
        }
        if ((mb_strlen($ibge_town_code) < 7)) {
            throw new \InvalidArgumentException('invalid length for $ibge_town_code when calling CreatePaymentConsentDataPayment., must be bigger than or equal to 7.');
        }
        if ((!preg_match("/^\\d{7}$/", ObjectSerializer::toString($ibge_town_code)))) {
            throw new \InvalidArgumentException("invalid value for \$ibge_town_code when calling CreatePaymentConsentDataPayment., must conform to the pattern /^\\d{7}$/.");
        }

        $this->container['ibge_town_code'] = $ibge_town_code;

        return $this;
    }

    /**
     * Gets details
     *
     * @return \OpenAPI\Client\Model\Details
     */
    public function getDetails()
    {
        return $this->container['details'];
    }

    /**
     * Sets details
     *
     * @param \OpenAPI\Client\Model\Details $details details
     *
     * @return self
     */
    public function setDetails($details)
    {
        if (is_null($details)) {
            throw new \InvalidArgumentException('non-nullable details cannot be null');
        }
        $this->container['details'] = $details;

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


