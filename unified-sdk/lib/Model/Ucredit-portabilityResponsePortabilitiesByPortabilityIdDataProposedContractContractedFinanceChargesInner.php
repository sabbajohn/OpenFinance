<?php
/**
 * ResponsePortabilitiesByPortabilityIdDataProposedContractContractedFinanceChargesInner
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
 * ResponsePortabilitiesByPortabilityIdDataProposedContractContractedFinanceChargesInner Class Doc Comment
 *
 * @category Class
 * @description Conjunto de informações referentes à identificação da operação de crédito
 * @package  OpenAPI\Client
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 * @implements \ArrayAccess<string, mixed>
 */
class Ucredit-portabilityResponsePortabilitiesByPortabilityIdDataProposedContractContractedFinanceChargesInner implements ModelInterface, ArrayAccess, \JsonSerializable
{
    public const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'ResponsePortabilitiesByPortabilityId_data_proposedContract_contractedFinanceCharges_inner';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'charge_type' => 'string',
        'charge_additional_info' => 'string',
        'charge_rate' => 'float'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      * @phpstan-var array<string, string|null>
      * @psalm-var array<string, string|null>
      */
    protected static $openAPIFormats = [
        'charge_type' => null,
        'charge_additional_info' => null,
        'charge_rate' => 'double'
    ];

    /**
      * Array of nullable properties. Used for (de)serialization
      *
      * @var boolean[]
      */
    protected static array $openAPINullables = [
        'charge_type' => false,
        'charge_additional_info' => false,
        'charge_rate' => false
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
        'charge_type' => 'chargeType',
        'charge_additional_info' => 'chargeAdditionalInfo',
        'charge_rate' => 'chargeRate'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'charge_type' => 'setChargeType',
        'charge_additional_info' => 'setChargeAdditionalInfo',
        'charge_rate' => 'setChargeRate'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'charge_type' => 'getChargeType',
        'charge_additional_info' => 'getChargeAdditionalInfo',
        'charge_rate' => 'getChargeRate'
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

    public const CHARGE_TYPE_JUROS_REMUNERATORIOS_POR_ATRASO = 'JUROS_REMUNERATORIOS_POR_ATRASO';
    public const CHARGE_TYPE_MULTA_ATRASO_PAGAMENTO = 'MULTA_ATRASO_PAGAMENTO';
    public const CHARGE_TYPE_JUROS_MORA_ATRASO = 'JUROS_MORA_ATRASO';
    public const CHARGE_TYPE_IOF_CONTRATACAO = 'IOF_CONTRATACAO';
    public const CHARGE_TYPE_IOF_POR_ATRASO = 'IOF_POR_ATRASO';
    public const CHARGE_TYPE_SEM_ENCARGO = 'SEM_ENCARGO';
    public const CHARGE_TYPE_OUTROS = 'OUTROS';

    /**
     * Gets allowable values of the enum
     *
     * @return string[]
     */
    public function getChargeTypeAllowableValues()
    {
        return [
            self::CHARGE_TYPE_JUROS_REMUNERATORIOS_POR_ATRASO,
            self::CHARGE_TYPE_MULTA_ATRASO_PAGAMENTO,
            self::CHARGE_TYPE_JUROS_MORA_ATRASO,
            self::CHARGE_TYPE_IOF_CONTRATACAO,
            self::CHARGE_TYPE_IOF_POR_ATRASO,
            self::CHARGE_TYPE_SEM_ENCARGO,
            self::CHARGE_TYPE_OUTROS,
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
        $this->setIfExists('charge_type', $data ?? [], null);
        $this->setIfExists('charge_additional_info', $data ?? [], null);
        $this->setIfExists('charge_rate', $data ?? [], null);
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

        if ($this->container['charge_type'] === null) {
            $invalidProperties[] = "'charge_type' can't be null";
        }
        $allowedValues = $this->getChargeTypeAllowableValues();
        if (!is_null($this->container['charge_type']) && !in_array($this->container['charge_type'], $allowedValues, true)) {
            $invalidProperties[] = sprintf(
                "invalid value '%s' for 'charge_type', must be one of '%s'",
                $this->container['charge_type'],
                implode("', '", $allowedValues)
            );
        }

        if (!is_null($this->container['charge_additional_info']) && (mb_strlen($this->container['charge_additional_info']) > 140)) {
            $invalidProperties[] = "invalid value for 'charge_additional_info', the character length must be smaller than or equal to 140.";
        }

        if (!is_null($this->container['charge_additional_info']) && !preg_match("/^[^\\s](?:.*[^\\s])?$/", $this->container['charge_additional_info'])) {
            $invalidProperties[] = "invalid value for 'charge_additional_info', must be conform to the pattern /^[^\\s](?:.*[^\\s])?$/.";
        }

        if (!is_null($this->container['charge_rate']) && (mb_strlen($this->container['charge_rate']) > 8)) {
            $invalidProperties[] = "invalid value for 'charge_rate', the character length must be smaller than or equal to 8.";
        }

        if (!is_null($this->container['charge_rate']) && (mb_strlen($this->container['charge_rate']) < 8)) {
            $invalidProperties[] = "invalid value for 'charge_rate', the character length must be bigger than or equal to 8.";
        }

        if (!is_null($this->container['charge_rate']) && !preg_match("/^\\d{1}\\.\\d{6}$/", $this->container['charge_rate'])) {
            $invalidProperties[] = "invalid value for 'charge_rate', must be conform to the pattern /^\\d{1}\\.\\d{6}$/.";
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
     * Gets charge_type
     *
     * @return string
     */
    public function getChargeType()
    {
        return $this->container['charge_type'];
    }

    /**
     * Sets charge_type
     *
     * @param string $charge_type Tipo de encargo pactuado no contrato.
     *
     * @return self
     */
    public function setChargeType($charge_type)
    {
        if (is_null($charge_type)) {
            throw new \InvalidArgumentException('non-nullable charge_type cannot be null');
        }
        $allowedValues = $this->getChargeTypeAllowableValues();
        if (!in_array($charge_type, $allowedValues, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid value '%s' for 'charge_type', must be one of '%s'",
                    $charge_type,
                    implode("', '", $allowedValues)
                )
            );
        }
        $this->container['charge_type'] = $charge_type;

        return $this;
    }

    /**
     * Gets charge_additional_info
     *
     * @return string|null
     */
    public function getChargeAdditionalInfo()
    {
        return $this->container['charge_additional_info'];
    }

    /**
     * Sets charge_additional_info
     *
     * @param string|null $charge_additional_info Campo para informações adicionais.  [Restrição] Obrigatório se selecionada a opção 'OUTROS' em Tipo de encargo pactuado no contrato.
     *
     * @return self
     */
    public function setChargeAdditionalInfo($charge_additional_info)
    {
        if (is_null($charge_additional_info)) {
            throw new \InvalidArgumentException('non-nullable charge_additional_info cannot be null');
        }
        if ((mb_strlen($charge_additional_info) > 140)) {
            throw new \InvalidArgumentException('invalid length for $charge_additional_info when calling ResponsePortabilitiesByPortabilityIdDataProposedContractContractedFinanceChargesInner., must be smaller than or equal to 140.');
        }
        if ((!preg_match("/^[^\\s](?:.*[^\\s])?$/", ObjectSerializer::toString($charge_additional_info)))) {
            throw new \InvalidArgumentException("invalid value for \$charge_additional_info when calling ResponsePortabilitiesByPortabilityIdDataProposedContractContractedFinanceChargesInner., must conform to the pattern /^[^\\s](?:.*[^\\s])?$/.");
        }

        $this->container['charge_additional_info'] = $charge_additional_info;

        return $this;
    }

    /**
     * Gets charge_rate
     *
     * @return float|null
     */
    public function getChargeRate()
    {
        return $this->container['charge_rate'];
    }

    /**
     * Sets charge_rate
     *
     * @param float|null $charge_rate Representa o valor do encargo em percentual pactuado no contrato.  O preenchimento deve respeitar as 6 casas decimais, mesmo que venham preenchidas com zeros(representação de porcentagem p.ex: 0.150000. Este valor representa 15%. O valor 1 representa 100%).
     *
     * @return self
     */
    public function setChargeRate($charge_rate)
    {
        if (is_null($charge_rate)) {
            throw new \InvalidArgumentException('non-nullable charge_rate cannot be null');
        }
        if ((mb_strlen($charge_rate) > 8)) {
            throw new \InvalidArgumentException('invalid length for $charge_rate when calling ResponsePortabilitiesByPortabilityIdDataProposedContractContractedFinanceChargesInner., must be smaller than or equal to 8.');
        }
        if ((mb_strlen($charge_rate) < 8)) {
            throw new \InvalidArgumentException('invalid length for $charge_rate when calling ResponsePortabilitiesByPortabilityIdDataProposedContractContractedFinanceChargesInner., must be bigger than or equal to 8.');
        }
        if ((!preg_match("/^\\d{1}\\.\\d{6}$/", ObjectSerializer::toString($charge_rate)))) {
            throw new \InvalidArgumentException("invalid value for \$charge_rate when calling ResponsePortabilitiesByPortabilityIdDataProposedContractContractedFinanceChargesInner., must conform to the pattern /^\\d{1}\\.\\d{6}$/.");
        }

        $this->container['charge_rate'] = $charge_rate;

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


