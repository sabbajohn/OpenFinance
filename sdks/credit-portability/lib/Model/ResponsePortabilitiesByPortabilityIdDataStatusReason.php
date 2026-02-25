<?php
/**
 * ResponsePortabilitiesByPortabilityIdDataStatusReason
 *
 * PHP version 8.1
 *
 * @category Class
 * @package  OpenAPI\Client
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 */

/**
 * API Credit Portability - Open Finance Brasil
 *
 * A API de Portabilidade de Crédito permite que usuários transfiram suas operações de crédito e arrendamento mercantil entre instituições financeiras em busca de melhores condições para o Open Finance Brasil.  # Orientações  ## Assinatura de payloads:   No contexto da API de Portabilidade de crédito, os payloads de mensagem que trafegam tanto por parte da instituição credora quanto por parte da instituição proponente devem estar assinados. Para o processo de assinatura destes payloads as instituições devem seguir as especificações de segurança publicadas no Portal do desenvolvedor       &nbsp;&nbsp;- Certificados exigidos para assinatura de mensagens: [[PT] Padrão de Certificados Open Finance Brasil 2.1](https://openfinancebrasil.atlassian.net/wiki/spaces/OF/pages/245694518/PT+Padr+o+de+Certificados+Open+Finance+Brasil+2.1)       &nbsp;&nbsp;- Como assinar o payload JWS: [Como Assinar o Payload](https://openfinancebrasil.atlassian.net/wiki/spaces/OF/pages/905740608/Como+assinar+o+payload+-+PC+Portabilidade+de+Cr+dito+-+CPC)  ## Controle de Acesso - Os endpoints [GET] /portabilities/{portabilityId}, [GET] /portabilities/{portabilityId}/account-data, [POST] /portabilities/{portabilityId}/payment, [PATCH] / portabilities/{portabilityId}/cancel da API de Portabilidade de crédito devem utilizar o escopo client_credentials - Os endpoints [GET] /credit-operations/{contractId}/portability-eligibility e [POST] /portabilities devem utilizar o escopo authorization_code para validar a permissions de LOANS  ## Validações para Portabilidade de Crédito **- Validações** (após o processo de DCR e obtenção de token client credential - não escopo dessa documentação):      &nbsp;Durante o processo de portabilidade de crédito, diferentes validações são necessárias pela instituição credora e devem ocorrer conforme a seguir:     **- Casos de erro relacionados às permissões de segurança para acesso à API** (ex. certificado, access_token, jwt, assinatura):       Validação de Certificado: Valida utilização de certificado correto durante processo de DCR - HTTP Code 401 (INVALID_CLIENT);      Validação de Access_Token: Verifica se Access_Token utilizado está correto - HTTP Code 401 (UNAUTHORIZED);      Validação de assinatura da mensagem: Valida se assinatura das mensagens enviadas está correta – HTTP Code 400 (BAD_SIGNATURE);      Validação de Claims (exceto data);        &emsp;- Valida se dados (aud, iss, iat e jti) são válidos - HTTP status code 403 - (INVALID_CLIENT);       &emsp;- Valida reuso de jti - HTTP Code 403 (INVALID_CLIENT).      ## Validações de erros sintáticos e semânticos, previstas com retorno HTTP Code 422 - Unprocessable Entity   **- Para todos os endpoints:**        &nbsp;&nbsp;**Sintáticos**          &emsp;- Envio de campos obrigatórios: Valida se todos os campos obrigatórios são informados (PARAMETRO_NAO_INFORMADO);          &emsp;- Formatação de parâmetros: Valida se parâmetros informados obedecem a formatação especificada (PARAMETRO_INVALIDO).          &emsp;- Demais validações não explicitamente informadas (NAO_INFORMADO)    **- Para endpoint ([POST] /portabilities):**        &nbsp;&nbsp;**Semânticos**        &emsp;- Portabilidade em andamento: Valida se já existe um pedido de portabilidade de crédito para o contrato solicitado pelo trilho do OFB ou da Registradora (EM_ANDAMENTO);        &emsp;- Prazo do empréstimo maior ao restante das parcelas a serem liquidadas no contrato original (PRAZO_ACIMA_LIMITE);        &emsp;- ID de contrato inválida (CONTRATO_INVALIDO);        &emsp;- Contrato não elegível para portabilidade dentro do trilho do OFB (CONTRATO_NAO_ELEGIVEL);        &emsp;- Idempotência: Valida se há divergência entre chave de idempotência e informações enviadas (ERRO_IDEMPOTENCIA);        &emsp;- Evidência de assinatura do contrato: Valida se o objeto de assinatura do contrato foi preenchido pela instituição proponente devidamente, em caso de ausência (SEM_EVIDENCIA_ASSINATURA);        &emsp;- Periodicidade: Valida se não houve mudança na periodicidade entre o novo contrato e o contrato original, caso tenha sido alterado a periodicidade (PERIODICIDADE_INVALIDA);        &emsp;- Campo com preenchimento incorreto: Valida se o preenchimento de alguns campos estão corretos      Ex.: CNPJ da instituição credora deve ser o mesmo retornado pela API de Empréstimos (CAMPO_INCONSISTENTE)    **- Para endpoint ([POST] /portabilities/{portabilityId}/payment):**      &nbsp;&nbsp;**Semânticos**        &emsp;- Estado da portabilidade diferente de ACCEPTED_SETTLEMENT_IN_PROGRESS ou PAYMENT_ISSUE (PAGAMENTO_EFETUADO_FORA_PRAZO). Obs.: Caso o pagamento tenha sido feito por engano a Instituição Proponente deve solicitar o estorno.    **- Para endpoint ([PATCH] /portabilities/{portabilityId}/cancel):**      &nbsp;&nbsp;**Semânticos**        &emsp;- Estado da portabilidade diferente de RECEIVED, PENDING ou ACCEPTED_SETTLEMENT_IN_PROGRESS (CANCELAMENTO_NÃO_EFETUADO). Obs.: De acordo com o PRD o usuário poderá cancelar o pedido de portabilidade até a etapa de liquidação, após esta etapa não será mais permitido o cancelamento da portabilidade
 *
 * The version of the OpenAPI document: 1.0.0
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
 * ResponsePortabilitiesByPortabilityIdDataStatusReason Class Doc Comment
 *
 * @category Class
 * @description Motivo de recusa do pedido de portabilidade  [RESTRIÇÃO] Campo de preenchimento obrigatório quando campo &#x60;status&#x60; for igual a &#x60;REJECTED&#x60; ou &#x60;CANCELADO&#x60; ou &#x60;PAYMENT_ISSUE&#x60;
 * @package  OpenAPI\Client
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 * @implements \ArrayAccess<string, mixed>
 */
class ResponsePortabilitiesByPortabilityIdDataStatusReason implements ModelInterface, ArrayAccess, \JsonSerializable
{
    public const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'ResponsePortabilitiesByPortabilityId_data_statusReason';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'reason_type' => 'string',
        'reason_type_additional_info' => 'string',
        'digital_signature_proof' => '\OpenAPI\Client\Model\ResponsePortabilitiesByPortabilityIdDataStatusReasonDigitalSignatureProof'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      * @phpstan-var array<string, string|null>
      * @psalm-var array<string, string|null>
      */
    protected static $openAPIFormats = [
        'reason_type' => null,
        'reason_type_additional_info' => null,
        'digital_signature_proof' => null
    ];

    /**
      * Array of nullable properties. Used for (de)serialization
      *
      * @var boolean[]
      */
    protected static array $openAPINullables = [
        'reason_type' => false,
        'reason_type_additional_info' => false,
        'digital_signature_proof' => false
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
        'reason_type' => 'reasonType',
        'reason_type_additional_info' => 'reasonTypeAdditionalInfo',
        'digital_signature_proof' => 'digitalSignatureProof'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'reason_type' => 'setReasonType',
        'reason_type_additional_info' => 'setReasonTypeAdditionalInfo',
        'digital_signature_proof' => 'setDigitalSignatureProof'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'reason_type' => 'getReasonType',
        'reason_type_additional_info' => 'getReasonTypeAdditionalInfo',
        'digital_signature_proof' => 'getDigitalSignatureProof'
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

    public const REASON_TYPE_CANCELADO_PELO_CLIENTE = 'CANCELADO_PELO_CLIENTE';
    public const REASON_TYPE_SALDO_DEVEDOR_ATUALIZADO_SUBSTANCIALMENTE_DIVERGENTE = 'SALDO_DEVEDOR_ATUALIZADO_SUBSTANCIALMENTE_DIVERGENTE';
    public const REASON_TYPE_POLITICA_DE_CREDITO = 'POLITICA_DE_CREDITO';
    public const REASON_TYPE_RETENCAO_DO_CLIENTE = 'RETENCAO_DO_CLIENTE';
    public const REASON_TYPE_CONTRATO_JA_LIQUIDADO = 'CONTRATO_JA_LIQUIDADO';
    public const REASON_TYPE_DIVERGENCIA_DE_PAGAMENTO_EFETUADO = 'DIVERGENCIA_DE_PAGAMENTO_EFETUADO';
    public const REASON_TYPE_DECURSO_DO_PRAZO_PARA_PAGAMENTO = 'DECURSO_DO_PRAZO_PARA_PAGAMENTO';
    public const REASON_TYPE_PORTABILIDADE_CANCELADA_POR_FALTA_DE_LIQUIDACAO = 'PORTABILIDADE_CANCELADA_POR_FALTA_DE_LIQUIDACAO';
    public const REASON_TYPE_PORTABILIDADE_EM_ANDAMENTO = 'PORTABILIDADE_EM_ANDAMENTO';
    public const REASON_TYPE_CLIENTE_COM_ACAO_JUDICIAL = 'CLIENTE_COM_ACAO_JUDICIAL';
    public const REASON_TYPE_MODALIDADE_DA_OPERACAO_INCOMPATIVEL = 'MODALIDADE_DA_OPERACAO_INCOMPATIVEL';
    public const REASON_TYPE_OUTROS = 'OUTROS';

    /**
     * Gets allowable values of the enum
     *
     * @return string[]
     */
    public function getReasonTypeAllowableValues()
    {
        return [
            self::REASON_TYPE_CANCELADO_PELO_CLIENTE,
            self::REASON_TYPE_SALDO_DEVEDOR_ATUALIZADO_SUBSTANCIALMENTE_DIVERGENTE,
            self::REASON_TYPE_POLITICA_DE_CREDITO,
            self::REASON_TYPE_RETENCAO_DO_CLIENTE,
            self::REASON_TYPE_CONTRATO_JA_LIQUIDADO,
            self::REASON_TYPE_DIVERGENCIA_DE_PAGAMENTO_EFETUADO,
            self::REASON_TYPE_DECURSO_DO_PRAZO_PARA_PAGAMENTO,
            self::REASON_TYPE_PORTABILIDADE_CANCELADA_POR_FALTA_DE_LIQUIDACAO,
            self::REASON_TYPE_PORTABILIDADE_EM_ANDAMENTO,
            self::REASON_TYPE_CLIENTE_COM_ACAO_JUDICIAL,
            self::REASON_TYPE_MODALIDADE_DA_OPERACAO_INCOMPATIVEL,
            self::REASON_TYPE_OUTROS,
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
        $this->setIfExists('reason_type', $data ?? [], null);
        $this->setIfExists('reason_type_additional_info', $data ?? [], null);
        $this->setIfExists('digital_signature_proof', $data ?? [], null);
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

        $allowedValues = $this->getReasonTypeAllowableValues();
        if (!is_null($this->container['reason_type']) && !in_array($this->container['reason_type'], $allowedValues, true)) {
            $invalidProperties[] = sprintf(
                "invalid value '%s' for 'reason_type', must be one of '%s'",
                $this->container['reason_type'],
                implode("', '", $allowedValues)
            );
        }

        if (!is_null($this->container['reason_type_additional_info']) && (mb_strlen($this->container['reason_type_additional_info']) > 144)) {
            $invalidProperties[] = "invalid value for 'reason_type_additional_info', the character length must be smaller than or equal to 144.";
        }

        if (!is_null($this->container['reason_type_additional_info']) && !preg_match("/^[^\\s](?:.*[^\\s])?$/", $this->container['reason_type_additional_info'])) {
            $invalidProperties[] = "invalid value for 'reason_type_additional_info', must be conform to the pattern /^[^\\s](?:.*[^\\s])?$/.";
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
     * Gets reason_type
     *
     * @return string|null
     */
    public function getReasonType()
    {
        return $this->container['reason_type'];
    }

    /**
     * Sets reason_type
     *
     * @param string|null $reason_type Motivo de recusa do pedido de portabilidade, onde:  `CANCELADO_PELO_CLIENTE` - Cliente desiste do pedido da portabilidade   `SALDO_DEVEDOR_ATUALIZADO_SUBSTANCIALMENTE_DIVERGENTE` - Saldo devedor atualizado divergente (superior a 15%) do informado inicialmente   `POLITICA_DE_CREDITO` - Proponente desiste da oferta ao cliente por políticas internas   `RETENCAO_DO_CLIENTE` - Cliente aceitou contraproposta da instituição credora (dentro do prazo)  `CONTRATO_JA_LIQUIDADO` - Contrato liquidado pelo cliente.   `DIVERGENCIA_DE_PAGAMENTO_EFETUADO` - Proponente realizou a liquidação com valor divergente   `DECURSO_DO_PRAZO_PARA_PAGAMENTO` - Proponente realizou a liquidação fora do prazo   `PORTABILIDADE_CANCELADA_POR_FALTA_DE_LIQUIDACAO` - Proponente não realizou a liquidação do contrato   `PORTABILIDADE_EM_ANDAMENTO` - Posteriormente à efetivação do pedido de portabilidade, a IF credora identificou que o cliente já possui outro pedido de portabilidade em andamento para o mesmo contrato.   `CLIENTE_COM_ACAO_JUDICIAL` - Possui ação judicial   `MODALIDADE_DA_OPERACAO_INCOMPATIVEL` - Modalidade divergente da indicada pela instituição proponente  `OUTROS` - Motivo da rejeição não se encaixa nas opções disponíveis
     *
     * @return self
     */
    public function setReasonType($reason_type)
    {
        if (is_null($reason_type)) {
            throw new \InvalidArgumentException('non-nullable reason_type cannot be null');
        }
        $allowedValues = $this->getReasonTypeAllowableValues();
        if (!in_array($reason_type, $allowedValues, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid value '%s' for 'reason_type', must be one of '%s'",
                    $reason_type,
                    implode("', '", $allowedValues)
                )
            );
        }
        $this->container['reason_type'] = $reason_type;

        return $this;
    }

    /**
     * Gets reason_type_additional_info
     *
     * @return string|null
     */
    public function getReasonTypeAdditionalInfo()
    {
        return $this->container['reason_type_additional_info'];
    }

    /**
     * Sets reason_type_additional_info
     *
     * @param string|null $reason_type_additional_info Informação sobre a disponibilidade ou não de um contrato para a portabilidade de crédito. Ao utilizar essa opção, é fortemente recomendável enviar um ticket como sugestão da estrutura Open Finance  para discussão e mapeamento em futuras versões.  [RESTRIÇÃO] Campo de preenchimento obrigatório quando campo `reasonType` for igual `OUTROS`
     *
     * @return self
     */
    public function setReasonTypeAdditionalInfo($reason_type_additional_info)
    {
        if (is_null($reason_type_additional_info)) {
            throw new \InvalidArgumentException('non-nullable reason_type_additional_info cannot be null');
        }
        if ((mb_strlen($reason_type_additional_info) > 144)) {
            throw new \InvalidArgumentException('invalid length for $reason_type_additional_info when calling ResponsePortabilitiesByPortabilityIdDataStatusReason., must be smaller than or equal to 144.');
        }
        if ((!preg_match("/^[^\\s](?:.*[^\\s])?$/", ObjectSerializer::toString($reason_type_additional_info)))) {
            throw new \InvalidArgumentException("invalid value for \$reason_type_additional_info when calling ResponsePortabilitiesByPortabilityIdDataStatusReason., must conform to the pattern /^[^\\s](?:.*[^\\s])?$/.");
        }

        $this->container['reason_type_additional_info'] = $reason_type_additional_info;

        return $this;
    }

    /**
     * Gets digital_signature_proof
     *
     * @return \OpenAPI\Client\Model\ResponsePortabilitiesByPortabilityIdDataStatusReasonDigitalSignatureProof|null
     */
    public function getDigitalSignatureProof()
    {
        return $this->container['digital_signature_proof'];
    }

    /**
     * Sets digital_signature_proof
     *
     * @param \OpenAPI\Client\Model\ResponsePortabilitiesByPortabilityIdDataStatusReasonDigitalSignatureProof|null $digital_signature_proof digital_signature_proof
     *
     * @return self
     */
    public function setDigitalSignatureProof($digital_signature_proof)
    {
        if (is_null($digital_signature_proof)) {
            throw new \InvalidArgumentException('non-nullable digital_signature_proof cannot be null');
        }
        $this->container['digital_signature_proof'] = $digital_signature_proof;

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


