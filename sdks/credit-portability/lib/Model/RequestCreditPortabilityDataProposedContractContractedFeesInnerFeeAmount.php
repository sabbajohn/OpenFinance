<?php
/**
 * RequestCreditPortabilityDataProposedContractContractedFeesInnerFeeAmount
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
 * RequestCreditPortabilityDataProposedContractContractedFeesInnerFeeAmount Class Doc Comment
 *
 * @category Class
 * @description Objeto para representar o valor monetário da tarifa pactuada no contrato.  [Restrição] Preenchimento obrigatório quando a forma de cobrança for diferente de Percentual.
 * @package  OpenAPI\Client
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 * @implements \ArrayAccess<string, mixed>
 */
class RequestCreditPortabilityDataProposedContractContractedFeesInnerFeeAmount implements ModelInterface, ArrayAccess, \JsonSerializable
{
    public const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'RequestCreditPortability_data_proposedContract_contractedFees_inner_feeAmount';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'amount' => 'float',
        'currency' => 'string'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      * @phpstan-var array<string, string|null>
      * @psalm-var array<string, string|null>
      */
    protected static $openAPIFormats = [
        'amount' => 'double',
        'currency' => null
    ];

    /**
      * Array of nullable properties. Used for (de)serialization
      *
      * @var boolean[]
      */
    protected static array $openAPINullables = [
        'amount' => false,
        'currency' => false
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
        'amount' => 'amount',
        'currency' => 'currency'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'amount' => 'setAmount',
        'currency' => 'setCurrency'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'amount' => 'getAmount',
        'currency' => 'getCurrency'
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
        $this->setIfExists('amount', $data ?? [], null);
        $this->setIfExists('currency', $data ?? [], null);
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

        if ($this->container['amount'] === null) {
            $invalidProperties[] = "'amount' can't be null";
        }
        if ((mb_strlen($this->container['amount']) > 20)) {
            $invalidProperties[] = "invalid value for 'amount', the character length must be smaller than or equal to 20.";
        }

        if ((mb_strlen($this->container['amount']) < 4)) {
            $invalidProperties[] = "invalid value for 'amount', the character length must be bigger than or equal to 4.";
        }

        if (!preg_match("/^\\d{1,15}\\.\\d{2,4}$/", $this->container['amount'])) {
            $invalidProperties[] = "invalid value for 'amount', must be conform to the pattern /^\\d{1,15}\\.\\d{2,4}$/.";
        }

        if ($this->container['currency'] === null) {
            $invalidProperties[] = "'currency' can't be null";
        }
        if ((mb_strlen($this->container['currency']) > 3)) {
            $invalidProperties[] = "invalid value for 'currency', the character length must be smaller than or equal to 3.";
        }

        if ((mb_strlen($this->container['currency']) < 3)) {
            $invalidProperties[] = "invalid value for 'currency', the character length must be bigger than or equal to 3.";
        }

        if (!preg_match("/^(\\w{3}){1}$/", $this->container['currency'])) {
            $invalidProperties[] = "invalid value for 'currency', must be conform to the pattern /^(\\w{3}){1}$/.";
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
     * Gets amount
     *
     * @return float
     */
    public function getAmount()
    {
        return $this->container['amount'];
    }

    /**
     * Sets amount
     *
     * @param float $amount Valor monetário da tarifa pactuada no contrato.
     *
     * @return self
     */
    public function setAmount($amount)
    {
        if (is_null($amount)) {
            throw new \InvalidArgumentException('non-nullable amount cannot be null');
        }
        if ((mb_strlen($amount) > 20)) {
            throw new \InvalidArgumentException('invalid length for $amount when calling RequestCreditPortabilityDataProposedContractContractedFeesInnerFeeAmount., must be smaller than or equal to 20.');
        }
        if ((mb_strlen($amount) < 4)) {
            throw new \InvalidArgumentException('invalid length for $amount when calling RequestCreditPortabilityDataProposedContractContractedFeesInnerFeeAmount., must be bigger than or equal to 4.');
        }
        if ((!preg_match("/^\\d{1,15}\\.\\d{2,4}$/", ObjectSerializer::toString($amount)))) {
            throw new \InvalidArgumentException("invalid value for \$amount when calling RequestCreditPortabilityDataProposedContractContractedFeesInnerFeeAmount., must conform to the pattern /^\\d{1,15}\\.\\d{2,4}$/.");
        }

        $this->container['amount'] = $amount;

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
     * @param string $currency Moeda referenciada ao campo `amount`, segundo modelo ISO-4217.
     *
     * @return self
     */
    public function setCurrency($currency)
    {
        if (is_null($currency)) {
            throw new \InvalidArgumentException('non-nullable currency cannot be null');
        }
        if ((mb_strlen($currency) > 3)) {
            throw new \InvalidArgumentException('invalid length for $currency when calling RequestCreditPortabilityDataProposedContractContractedFeesInnerFeeAmount., must be smaller than or equal to 3.');
        }
        if ((mb_strlen($currency) < 3)) {
            throw new \InvalidArgumentException('invalid length for $currency when calling RequestCreditPortabilityDataProposedContractContractedFeesInnerFeeAmount., must be bigger than or equal to 3.');
        }
        if ((!preg_match("/^(\\w{3}){1}$/", ObjectSerializer::toString($currency)))) {
            throw new \InvalidArgumentException("invalid value for \$currency when calling RequestCreditPortabilityDataProposedContractContractedFeesInnerFeeAmount., must conform to the pattern /^(\\w{3}){1}$/.");
        }

        $this->container['currency'] = $currency;

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


