<?php
/**
 * RequestCreditPortabilityData
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
 * RequestCreditPortabilityData Class Doc Comment
 *
 * @category Class
 * @description Conjunto de informações referentes à Proposta de Portabilidade de Crédito da Proponente para a Credora
 * @package  OpenAPI\Client
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 * @implements \ArrayAccess<string, mixed>
 */
class RequestCreditPortabilityData implements ModelInterface, ArrayAccess, \JsonSerializable
{
    public const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'RequestCreditPortability_data';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'customer_contact' => '\OpenAPI\Client\Model\RequestCreditPortabilityDataCustomerContactInner[]',
        'institution' => '\OpenAPI\Client\Model\RequestCreditPortabilityDataInstitution',
        'contract_identification' => '\OpenAPI\Client\Model\RequestCreditPortabilityDataContractIdentification',
        'proposed_contract' => '\OpenAPI\Client\Model\RequestCreditPortabilityDataProposedContract',
        'creation_date_time' => 'string'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      * @phpstan-var array<string, string|null>
      * @psalm-var array<string, string|null>
      */
    protected static $openAPIFormats = [
        'customer_contact' => null,
        'institution' => null,
        'contract_identification' => null,
        'proposed_contract' => null,
        'creation_date_time' => null
    ];

    /**
      * Array of nullable properties. Used for (de)serialization
      *
      * @var boolean[]
      */
    protected static array $openAPINullables = [
        'customer_contact' => false,
        'institution' => false,
        'contract_identification' => false,
        'proposed_contract' => false,
        'creation_date_time' => false
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
        'customer_contact' => 'customerContact',
        'institution' => 'institution',
        'contract_identification' => 'contractIdentification',
        'proposed_contract' => 'proposedContract',
        'creation_date_time' => 'creationDateTime'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'customer_contact' => 'setCustomerContact',
        'institution' => 'setInstitution',
        'contract_identification' => 'setContractIdentification',
        'proposed_contract' => 'setProposedContract',
        'creation_date_time' => 'setCreationDateTime'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'customer_contact' => 'getCustomerContact',
        'institution' => 'getInstitution',
        'contract_identification' => 'getContractIdentification',
        'proposed_contract' => 'getProposedContract',
        'creation_date_time' => 'getCreationDateTime'
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
        $this->setIfExists('customer_contact', $data ?? [], null);
        $this->setIfExists('institution', $data ?? [], null);
        $this->setIfExists('contract_identification', $data ?? [], null);
        $this->setIfExists('proposed_contract', $data ?? [], null);
        $this->setIfExists('creation_date_time', $data ?? [], null);
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

        if ($this->container['customer_contact'] === null) {
            $invalidProperties[] = "'customer_contact' can't be null";
        }
        if ((count($this->container['customer_contact']) < 0)) {
            $invalidProperties[] = "invalid value for 'customer_contact', number of items must be greater than or equal to 0.";
        }

        if ($this->container['institution'] === null) {
            $invalidProperties[] = "'institution' can't be null";
        }
        if ($this->container['contract_identification'] === null) {
            $invalidProperties[] = "'contract_identification' can't be null";
        }
        if ($this->container['proposed_contract'] === null) {
            $invalidProperties[] = "'proposed_contract' can't be null";
        }
        if ($this->container['creation_date_time'] === null) {
            $invalidProperties[] = "'creation_date_time' can't be null";
        }
        if ((mb_strlen($this->container['creation_date_time']) > 20)) {
            $invalidProperties[] = "invalid value for 'creation_date_time', the character length must be smaller than or equal to 20.";
        }

        if ((mb_strlen($this->container['creation_date_time']) < 20)) {
            $invalidProperties[] = "invalid value for 'creation_date_time', the character length must be bigger than or equal to 20.";
        }

        if (!preg_match("/^(\\d{4})-(1[0-2]|0?[1-9])-(3[01]|[12][0-9]|0?[1-9])T(?:[01]\\d|2[0123]):(?:[012345]\\d):(?:[012345]\\d)Z$/", $this->container['creation_date_time'])) {
            $invalidProperties[] = "invalid value for 'creation_date_time', must be conform to the pattern /^(\\d{4})-(1[0-2]|0?[1-9])-(3[01]|[12][0-9]|0?[1-9])T(?:[01]\\d|2[0123]):(?:[012345]\\d):(?:[012345]\\d)Z$/.";
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
     * Gets customer_contact
     *
     * @return \OpenAPI\Client\Model\RequestCreditPortabilityDataCustomerContactInner[]
     */
    public function getCustomerContact()
    {
        return $this->container['customer_contact'];
    }

    /**
     * Sets customer_contact
     *
     * @param \OpenAPI\Client\Model\RequestCreditPortabilityDataCustomerContactInner[] $customer_contact Dados de contato do cliente
     *
     * @return self
     */
    public function setCustomerContact($customer_contact)
    {
        if (is_null($customer_contact)) {
            throw new \InvalidArgumentException('non-nullable customer_contact cannot be null');
        }


        if ((count($customer_contact) < 0)) {
            throw new \InvalidArgumentException('invalid length for $customer_contact when calling RequestCreditPortabilityData., number of items must be greater than or equal to 0.');
        }
        $this->container['customer_contact'] = $customer_contact;

        return $this;
    }

    /**
     * Gets institution
     *
     * @return \OpenAPI\Client\Model\RequestCreditPortabilityDataInstitution
     */
    public function getInstitution()
    {
        return $this->container['institution'];
    }

    /**
     * Sets institution
     *
     * @param \OpenAPI\Client\Model\RequestCreditPortabilityDataInstitution $institution institution
     *
     * @return self
     */
    public function setInstitution($institution)
    {
        if (is_null($institution)) {
            throw new \InvalidArgumentException('non-nullable institution cannot be null');
        }
        $this->container['institution'] = $institution;

        return $this;
    }

    /**
     * Gets contract_identification
     *
     * @return \OpenAPI\Client\Model\RequestCreditPortabilityDataContractIdentification
     */
    public function getContractIdentification()
    {
        return $this->container['contract_identification'];
    }

    /**
     * Sets contract_identification
     *
     * @param \OpenAPI\Client\Model\RequestCreditPortabilityDataContractIdentification $contract_identification contract_identification
     *
     * @return self
     */
    public function setContractIdentification($contract_identification)
    {
        if (is_null($contract_identification)) {
            throw new \InvalidArgumentException('non-nullable contract_identification cannot be null');
        }
        $this->container['contract_identification'] = $contract_identification;

        return $this;
    }

    /**
     * Gets proposed_contract
     *
     * @return \OpenAPI\Client\Model\RequestCreditPortabilityDataProposedContract
     */
    public function getProposedContract()
    {
        return $this->container['proposed_contract'];
    }

    /**
     * Sets proposed_contract
     *
     * @param \OpenAPI\Client\Model\RequestCreditPortabilityDataProposedContract $proposed_contract proposed_contract
     *
     * @return self
     */
    public function setProposedContract($proposed_contract)
    {
        if (is_null($proposed_contract)) {
            throw new \InvalidArgumentException('non-nullable proposed_contract cannot be null');
        }
        $this->container['proposed_contract'] = $proposed_contract;

        return $this;
    }

    /**
     * Gets creation_date_time
     *
     * @return string
     */
    public function getCreationDateTime()
    {
        return $this->container['creation_date_time'];
    }

    /**
     * Sets creation_date_time
     *
     * @param string $creation_date_time Data e hora em que a Proponente registrou a presente proposta (chamada ao POST /portabilities). Uma string com data e hora conforme especificação [RFC-3339](https://datatracker.ietf.org/doc/html/rfc3339), sempre com a utilização de timezone UTC-0 (UTC time format).
     *
     * @return self
     */
    public function setCreationDateTime($creation_date_time)
    {
        if (is_null($creation_date_time)) {
            throw new \InvalidArgumentException('non-nullable creation_date_time cannot be null');
        }
        if ((mb_strlen($creation_date_time) > 20)) {
            throw new \InvalidArgumentException('invalid length for $creation_date_time when calling RequestCreditPortabilityData., must be smaller than or equal to 20.');
        }
        if ((mb_strlen($creation_date_time) < 20)) {
            throw new \InvalidArgumentException('invalid length for $creation_date_time when calling RequestCreditPortabilityData., must be bigger than or equal to 20.');
        }
        if ((!preg_match("/^(\\d{4})-(1[0-2]|0?[1-9])-(3[01]|[12][0-9]|0?[1-9])T(?:[01]\\d|2[0123]):(?:[012345]\\d):(?:[012345]\\d)Z$/", ObjectSerializer::toString($creation_date_time)))) {
            throw new \InvalidArgumentException("invalid value for \$creation_date_time when calling RequestCreditPortabilityData., must conform to the pattern /^(\\d{4})-(1[0-2]|0?[1-9])-(3[01]|[12][0-9]|0?[1-9])T(?:[01]\\d|2[0123]):(?:[012345]\\d):(?:[012345]\\d)Z$/.");
        }

        $this->container['creation_date_time'] = $creation_date_time;

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


