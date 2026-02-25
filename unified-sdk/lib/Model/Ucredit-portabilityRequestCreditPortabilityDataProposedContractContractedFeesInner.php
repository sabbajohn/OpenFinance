<?php
/**
 * RequestCreditPortabilityDataProposedContractContractedFeesInner
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
 * RequestCreditPortabilityDataProposedContractContractedFeesInner Class Doc Comment
 *
 * @category Class
 * @description Objeto que traz o conjunto de informações necessárias para demonstrar a composição das taxas de juros remuneratórios da Modalidade de crédito
 * @package  OpenAPI\Client
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 * @implements \ArrayAccess<string, mixed>
 */
class Ucredit-portabilityRequestCreditPortabilityDataProposedContractContractedFeesInner implements ModelInterface, ArrayAccess, \JsonSerializable
{
    public const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'RequestCreditPortability_data_proposedContract_contractedFees_inner';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'fee_name' => 'string',
        'fee_code' => 'string',
        'fee_charge_type' => 'string',
        'fee_charge' => 'string',
        'fee_amount' => '\OpenAPI\Client\Model\RequestCreditPortabilityDataProposedContractContractedFeesInnerFeeAmount',
        'fee_rate' => 'float'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      * @phpstan-var array<string, string|null>
      * @psalm-var array<string, string|null>
      */
    protected static $openAPIFormats = [
        'fee_name' => null,
        'fee_code' => null,
        'fee_charge_type' => null,
        'fee_charge' => null,
        'fee_amount' => null,
        'fee_rate' => 'double'
    ];

    /**
      * Array of nullable properties. Used for (de)serialization
      *
      * @var boolean[]
      */
    protected static array $openAPINullables = [
        'fee_name' => false,
        'fee_code' => false,
        'fee_charge_type' => false,
        'fee_charge' => false,
        'fee_amount' => false,
        'fee_rate' => false
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
        'fee_name' => 'feeName',
        'fee_code' => 'feeCode',
        'fee_charge_type' => 'feeChargeType',
        'fee_charge' => 'feeCharge',
        'fee_amount' => 'feeAmount',
        'fee_rate' => 'feeRate'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'fee_name' => 'setFeeName',
        'fee_code' => 'setFeeCode',
        'fee_charge_type' => 'setFeeChargeType',
        'fee_charge' => 'setFeeCharge',
        'fee_amount' => 'setFeeAmount',
        'fee_rate' => 'setFeeRate'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'fee_name' => 'getFeeName',
        'fee_code' => 'getFeeCode',
        'fee_charge_type' => 'getFeeChargeType',
        'fee_charge' => 'getFeeCharge',
        'fee_amount' => 'getFeeAmount',
        'fee_rate' => 'getFeeRate'
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

    public const FEE_CHARGE_TYPE_UNICA = 'UNICA';
    public const FEE_CHARGE_TYPE_POR_PARCELA = 'POR_PARCELA';
    public const FEE_CHARGE_MINIMO = 'MINIMO';
    public const FEE_CHARGE_MAXIMO = 'MAXIMO';
    public const FEE_CHARGE_FIXO = 'FIXO';
    public const FEE_CHARGE_PERCENTUAL = 'PERCENTUAL';

    /**
     * Gets allowable values of the enum
     *
     * @return string[]
     */
    public function getFeeChargeTypeAllowableValues()
    {
        return [
            self::FEE_CHARGE_TYPE_UNICA,
            self::FEE_CHARGE_TYPE_POR_PARCELA,
        ];
    }

    /**
     * Gets allowable values of the enum
     *
     * @return string[]
     */
    public function getFeeChargeAllowableValues()
    {
        return [
            self::FEE_CHARGE_MINIMO,
            self::FEE_CHARGE_MAXIMO,
            self::FEE_CHARGE_FIXO,
            self::FEE_CHARGE_PERCENTUAL,
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
        $this->setIfExists('fee_name', $data ?? [], null);
        $this->setIfExists('fee_code', $data ?? [], null);
        $this->setIfExists('fee_charge_type', $data ?? [], null);
        $this->setIfExists('fee_charge', $data ?? [], null);
        $this->setIfExists('fee_amount', $data ?? [], null);
        $this->setIfExists('fee_rate', $data ?? [], null);
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

        if ($this->container['fee_name'] === null) {
            $invalidProperties[] = "'fee_name' can't be null";
        }
        if ((mb_strlen($this->container['fee_name']) > 140)) {
            $invalidProperties[] = "invalid value for 'fee_name', the character length must be smaller than or equal to 140.";
        }

        if (!preg_match("/^[^\\s](?:.*[^\\s])?$/", $this->container['fee_name'])) {
            $invalidProperties[] = "invalid value for 'fee_name', must be conform to the pattern /^[^\\s](?:.*[^\\s])?$/.";
        }

        if ($this->container['fee_code'] === null) {
            $invalidProperties[] = "'fee_code' can't be null";
        }
        if ((mb_strlen($this->container['fee_code']) > 140)) {
            $invalidProperties[] = "invalid value for 'fee_code', the character length must be smaller than or equal to 140.";
        }

        if (!preg_match("/^[^\\s](?:.*[^\\s])?$/", $this->container['fee_code'])) {
            $invalidProperties[] = "invalid value for 'fee_code', must be conform to the pattern /^[^\\s](?:.*[^\\s])?$/.";
        }

        if ($this->container['fee_charge_type'] === null) {
            $invalidProperties[] = "'fee_charge_type' can't be null";
        }
        $allowedValues = $this->getFeeChargeTypeAllowableValues();
        if (!is_null($this->container['fee_charge_type']) && !in_array($this->container['fee_charge_type'], $allowedValues, true)) {
            $invalidProperties[] = sprintf(
                "invalid value '%s' for 'fee_charge_type', must be one of '%s'",
                $this->container['fee_charge_type'],
                implode("', '", $allowedValues)
            );
        }

        if ($this->container['fee_charge'] === null) {
            $invalidProperties[] = "'fee_charge' can't be null";
        }
        $allowedValues = $this->getFeeChargeAllowableValues();
        if (!is_null($this->container['fee_charge']) && !in_array($this->container['fee_charge'], $allowedValues, true)) {
            $invalidProperties[] = sprintf(
                "invalid value '%s' for 'fee_charge', must be one of '%s'",
                $this->container['fee_charge'],
                implode("', '", $allowedValues)
            );
        }

        if (!is_null($this->container['fee_rate']) && (mb_strlen($this->container['fee_rate']) > 8)) {
            $invalidProperties[] = "invalid value for 'fee_rate', the character length must be smaller than or equal to 8.";
        }

        if (!is_null($this->container['fee_rate']) && (mb_strlen($this->container['fee_rate']) < 8)) {
            $invalidProperties[] = "invalid value for 'fee_rate', the character length must be bigger than or equal to 8.";
        }

        if (!is_null($this->container['fee_rate']) && !preg_match("/^\\d{1}\\.\\d{6}$/", $this->container['fee_rate'])) {
            $invalidProperties[] = "invalid value for 'fee_rate', must be conform to the pattern /^\\d{1}\\.\\d{6}$/.";
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
     * Gets fee_name
     *
     * @return string
     */
    public function getFeeName()
    {
        return $this->container['fee_name'];
    }

    /**
     * Sets fee_name
     *
     * @param string $fee_name Denominação da Tarifa pactuada
     *
     * @return self
     */
    public function setFeeName($fee_name)
    {
        if (is_null($fee_name)) {
            throw new \InvalidArgumentException('non-nullable fee_name cannot be null');
        }
        if ((mb_strlen($fee_name) > 140)) {
            throw new \InvalidArgumentException('invalid length for $fee_name when calling RequestCreditPortabilityDataProposedContractContractedFeesInner., must be smaller than or equal to 140.');
        }
        if ((!preg_match("/^[^\\s](?:.*[^\\s])?$/", ObjectSerializer::toString($fee_name)))) {
            throw new \InvalidArgumentException("invalid value for \$fee_name when calling RequestCreditPortabilityDataProposedContractContractedFeesInner., must conform to the pattern /^[^\\s](?:.*[^\\s])?$/.");
        }

        $this->container['fee_name'] = $fee_name;

        return $this;
    }

    /**
     * Gets fee_code
     *
     * @return string
     */
    public function getFeeCode()
    {
        return $this->container['fee_code'];
    }

    /**
     * Sets fee_code
     *
     * @param string $fee_code Sigla identificadora da tarifa pactuada
     *
     * @return self
     */
    public function setFeeCode($fee_code)
    {
        if (is_null($fee_code)) {
            throw new \InvalidArgumentException('non-nullable fee_code cannot be null');
        }
        if ((mb_strlen($fee_code) > 140)) {
            throw new \InvalidArgumentException('invalid length for $fee_code when calling RequestCreditPortabilityDataProposedContractContractedFeesInner., must be smaller than or equal to 140.');
        }
        if ((!preg_match("/^[^\\s](?:.*[^\\s])?$/", ObjectSerializer::toString($fee_code)))) {
            throw new \InvalidArgumentException("invalid value for \$fee_code when calling RequestCreditPortabilityDataProposedContractContractedFeesInner., must conform to the pattern /^[^\\s](?:.*[^\\s])?$/.");
        }

        $this->container['fee_code'] = $fee_code;

        return $this;
    }

    /**
     * Gets fee_charge_type
     *
     * @return string
     */
    public function getFeeChargeType()
    {
        return $this->container['fee_charge_type'];
    }

    /**
     * Sets fee_charge_type
     *
     * @param string $fee_charge_type Tipo de cobrança para a tarifa pactuada no contrato.
     *
     * @return self
     */
    public function setFeeChargeType($fee_charge_type)
    {
        if (is_null($fee_charge_type)) {
            throw new \InvalidArgumentException('non-nullable fee_charge_type cannot be null');
        }
        $allowedValues = $this->getFeeChargeTypeAllowableValues();
        if (!in_array($fee_charge_type, $allowedValues, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid value '%s' for 'fee_charge_type', must be one of '%s'",
                    $fee_charge_type,
                    implode("', '", $allowedValues)
                )
            );
        }
        $this->container['fee_charge_type'] = $fee_charge_type;

        return $this;
    }

    /**
     * Gets fee_charge
     *
     * @return string
     */
    public function getFeeCharge()
    {
        return $this->container['fee_charge'];
    }

    /**
     * Sets fee_charge
     *
     * @param string $fee_charge \"Forma de cobrança relativa a tarifa pactuada no contrato. (Vide Enum) - Mínimo - Máximo - Fixo - Percentual\"
     *
     * @return self
     */
    public function setFeeCharge($fee_charge)
    {
        if (is_null($fee_charge)) {
            throw new \InvalidArgumentException('non-nullable fee_charge cannot be null');
        }
        $allowedValues = $this->getFeeChargeAllowableValues();
        if (!in_array($fee_charge, $allowedValues, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid value '%s' for 'fee_charge', must be one of '%s'",
                    $fee_charge,
                    implode("', '", $allowedValues)
                )
            );
        }
        $this->container['fee_charge'] = $fee_charge;

        return $this;
    }

    /**
     * Gets fee_amount
     *
     * @return \OpenAPI\Client\Model\RequestCreditPortabilityDataProposedContractContractedFeesInnerFeeAmount|null
     */
    public function getFeeAmount()
    {
        return $this->container['fee_amount'];
    }

    /**
     * Sets fee_amount
     *
     * @param \OpenAPI\Client\Model\RequestCreditPortabilityDataProposedContractContractedFeesInnerFeeAmount|null $fee_amount fee_amount
     *
     * @return self
     */
    public function setFeeAmount($fee_amount)
    {
        if (is_null($fee_amount)) {
            throw new \InvalidArgumentException('non-nullable fee_amount cannot be null');
        }
        $this->container['fee_amount'] = $fee_amount;

        return $this;
    }

    /**
     * Gets fee_rate
     *
     * @return float|null
     */
    public function getFeeRate()
    {
        return $this->container['fee_rate'];
    }

    /**
     * Sets fee_rate
     *
     * @param float|null $fee_rate É o valor da tarifa em percentual pactuada no contrato.  [Restrição] Preenchimento obrigatório quando a forma de cobrança for Percentual.
     *
     * @return self
     */
    public function setFeeRate($fee_rate)
    {
        if (is_null($fee_rate)) {
            throw new \InvalidArgumentException('non-nullable fee_rate cannot be null');
        }
        if ((mb_strlen($fee_rate) > 8)) {
            throw new \InvalidArgumentException('invalid length for $fee_rate when calling RequestCreditPortabilityDataProposedContractContractedFeesInner., must be smaller than or equal to 8.');
        }
        if ((mb_strlen($fee_rate) < 8)) {
            throw new \InvalidArgumentException('invalid length for $fee_rate when calling RequestCreditPortabilityDataProposedContractContractedFeesInner., must be bigger than or equal to 8.');
        }
        if ((!preg_match("/^\\d{1}\\.\\d{6}$/", ObjectSerializer::toString($fee_rate)))) {
            throw new \InvalidArgumentException("invalid value for \$fee_rate when calling RequestCreditPortabilityDataProposedContractContractedFeesInner., must conform to the pattern /^\\d{1}\\.\\d{6}$/.");
        }

        $this->container['fee_rate'] = $fee_rate;

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


