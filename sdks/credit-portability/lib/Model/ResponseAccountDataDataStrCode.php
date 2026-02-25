<?php
/**
 * ResponseAccountDataDataStrCode
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
 * ResponseAccountDataDataStrCode Class Doc Comment
 *
 * @category Class
 * @package  OpenAPI\Client
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 * @implements \ArrayAccess<string, mixed>
 */
class ResponseAccountDataDataStrCode implements ModelInterface, ArrayAccess, \JsonSerializable
{
    public const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'ResponseAccountData_data_strCode';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'ispb' => 'string',
        'name' => 'string',
        'company_cnpj' => 'string',
        'branch_code' => 'float',
        'has_financial_agent' => 'bool',
        'account_number' => 'float'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      * @phpstan-var array<string, string|null>
      * @psalm-var array<string, string|null>
      */
    protected static $openAPIFormats = [
        'ispb' => null,
        'name' => null,
        'company_cnpj' => null,
        'branch_code' => null,
        'has_financial_agent' => null,
        'account_number' => null
    ];

    /**
      * Array of nullable properties. Used for (de)serialization
      *
      * @var boolean[]
      */
    protected static array $openAPINullables = [
        'ispb' => false,
        'name' => false,
        'company_cnpj' => false,
        'branch_code' => false,
        'has_financial_agent' => false,
        'account_number' => false
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
        'ispb' => 'ispb',
        'name' => 'name',
        'company_cnpj' => 'companyCnpj',
        'branch_code' => 'branchCode',
        'has_financial_agent' => 'hasFinancialAgent',
        'account_number' => 'accountNumber'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'ispb' => 'setIspb',
        'name' => 'setName',
        'company_cnpj' => 'setCompanyCnpj',
        'branch_code' => 'setBranchCode',
        'has_financial_agent' => 'setHasFinancialAgent',
        'account_number' => 'setAccountNumber'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'ispb' => 'getIspb',
        'name' => 'getName',
        'company_cnpj' => 'getCompanyCnpj',
        'branch_code' => 'getBranchCode',
        'has_financial_agent' => 'getHasFinancialAgent',
        'account_number' => 'getAccountNumber'
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
        $this->setIfExists('ispb', $data ?? [], null);
        $this->setIfExists('name', $data ?? [], null);
        $this->setIfExists('company_cnpj', $data ?? [], null);
        $this->setIfExists('branch_code', $data ?? [], null);
        $this->setIfExists('has_financial_agent', $data ?? [], null);
        $this->setIfExists('account_number', $data ?? [], null);
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

        if ($this->container['ispb'] === null) {
            $invalidProperties[] = "'ispb' can't be null";
        }
        if ((mb_strlen($this->container['ispb']) > 8)) {
            $invalidProperties[] = "invalid value for 'ispb', the character length must be smaller than or equal to 8.";
        }

        if ((mb_strlen($this->container['ispb']) < 8)) {
            $invalidProperties[] = "invalid value for 'ispb', the character length must be bigger than or equal to 8.";
        }

        if (!preg_match("/^[0-9A-Z]{8}$/", $this->container['ispb'])) {
            $invalidProperties[] = "invalid value for 'ispb', must be conform to the pattern /^[0-9A-Z]{8}$/.";
        }

        if (!is_null($this->container['name']) && (mb_strlen($this->container['name']) > 80)) {
            $invalidProperties[] = "invalid value for 'name', the character length must be smaller than or equal to 80.";
        }

        if (!is_null($this->container['name']) && (mb_strlen($this->container['name']) < 1)) {
            $invalidProperties[] = "invalid value for 'name', the character length must be bigger than or equal to 1.";
        }

        if (!is_null($this->container['name']) && !preg_match("/^[^\\s](?:.*[^\\s])?$/", $this->container['name'])) {
            $invalidProperties[] = "invalid value for 'name', must be conform to the pattern /^[^\\s](?:.*[^\\s])?$/.";
        }

        if (!is_null($this->container['company_cnpj']) && (mb_strlen($this->container['company_cnpj']) > 14)) {
            $invalidProperties[] = "invalid value for 'company_cnpj', the character length must be smaller than or equal to 14.";
        }

        if (!is_null($this->container['company_cnpj']) && (mb_strlen($this->container['company_cnpj']) < 14)) {
            $invalidProperties[] = "invalid value for 'company_cnpj', the character length must be bigger than or equal to 14.";
        }

        if (!is_null($this->container['company_cnpj']) && !preg_match("/^[0-9A-Z]{12}[0-9]{2}$/", $this->container['company_cnpj'])) {
            $invalidProperties[] = "invalid value for 'company_cnpj', must be conform to the pattern /^[0-9A-Z]{12}[0-9]{2}$/.";
        }

        if ($this->container['branch_code'] === null) {
            $invalidProperties[] = "'branch_code' can't be null";
        }
        if ($this->container['has_financial_agent'] === null) {
            $invalidProperties[] = "'has_financial_agent' can't be null";
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
     * Gets ispb
     *
     * @return string
     */
    public function getIspb()
    {
        return $this->container['ispb'];
    }

    /**
     * Sets ispb
     *
     * @param string $ispb Número do ISPB da Instituição credora a ser usada na STR para pagamento de portabilidade de crédito exclusiva para o OFB.
     *
     * @return self
     */
    public function setIspb($ispb)
    {
        if (is_null($ispb)) {
            throw new \InvalidArgumentException('non-nullable ispb cannot be null');
        }
        if ((mb_strlen($ispb) > 8)) {
            throw new \InvalidArgumentException('invalid length for $ispb when calling ResponseAccountDataDataStrCode., must be smaller than or equal to 8.');
        }
        if ((mb_strlen($ispb) < 8)) {
            throw new \InvalidArgumentException('invalid length for $ispb when calling ResponseAccountDataDataStrCode., must be bigger than or equal to 8.');
        }
        if ((!preg_match("/^[0-9A-Z]{8}$/", ObjectSerializer::toString($ispb)))) {
            throw new \InvalidArgumentException("invalid value for \$ispb when calling ResponseAccountDataDataStrCode., must conform to the pattern /^[0-9A-Z]{8}$/.");
        }

        $this->container['ispb'] = $ispb;

        return $this;
    }

    /**
     * Gets name
     *
     * @return string|null
     */
    public function getName()
    {
        return $this->container['name'];
    }

    /**
     * Sets name
     *
     * @param string|null $name Nome do proprietário da conta a ser usada na STR para pagamento de portabilidade de crédito exclusiva para o OFB.  [RESTRIÇÃO] campo de preenchimento obrigatório quando campo `hasFinancialAgent` for igual a true
     *
     * @return self
     */
    public function setName($name)
    {
        if (is_null($name)) {
            throw new \InvalidArgumentException('non-nullable name cannot be null');
        }
        if ((mb_strlen($name) > 80)) {
            throw new \InvalidArgumentException('invalid length for $name when calling ResponseAccountDataDataStrCode., must be smaller than or equal to 80.');
        }
        if ((mb_strlen($name) < 1)) {
            throw new \InvalidArgumentException('invalid length for $name when calling ResponseAccountDataDataStrCode., must be bigger than or equal to 1.');
        }
        if ((!preg_match("/^[^\\s](?:.*[^\\s])?$/", ObjectSerializer::toString($name)))) {
            throw new \InvalidArgumentException("invalid value for \$name when calling ResponseAccountDataDataStrCode., must conform to the pattern /^[^\\s](?:.*[^\\s])?$/.");
        }

        $this->container['name'] = $name;

        return $this;
    }

    /**
     * Gets company_cnpj
     *
     * @return string|null
     */
    public function getCompanyCnpj()
    {
        return $this->container['company_cnpj'];
    }

    /**
     * Sets company_cnpj
     *
     * @param string|null $company_cnpj CNPJ do proprietário da conta a ser usada na STR para pagamento de portabilidade de crédito exclusiva para o OFB.  [RESTRIÇÃO] campo de preenchimento obrigatório quando campo `hasFinancialAgent` for igual a true
     *
     * @return self
     */
    public function setCompanyCnpj($company_cnpj)
    {
        if (is_null($company_cnpj)) {
            throw new \InvalidArgumentException('non-nullable company_cnpj cannot be null');
        }
        if ((mb_strlen($company_cnpj) > 14)) {
            throw new \InvalidArgumentException('invalid length for $company_cnpj when calling ResponseAccountDataDataStrCode., must be smaller than or equal to 14.');
        }
        if ((mb_strlen($company_cnpj) < 14)) {
            throw new \InvalidArgumentException('invalid length for $company_cnpj when calling ResponseAccountDataDataStrCode., must be bigger than or equal to 14.');
        }
        if ((!preg_match("/^[0-9A-Z]{12}[0-9]{2}$/", ObjectSerializer::toString($company_cnpj)))) {
            throw new \InvalidArgumentException("invalid value for \$company_cnpj when calling ResponseAccountDataDataStrCode., must conform to the pattern /^[0-9A-Z]{12}[0-9]{2}$/.");
        }

        $this->container['company_cnpj'] = $company_cnpj;

        return $this;
    }

    /**
     * Gets branch_code
     *
     * @return float
     */
    public function getBranchCode()
    {
        return $this->container['branch_code'];
    }

    /**
     * Sets branch_code
     *
     * @param float $branch_code Número da Agência creditada a ser usada na STR para pagamento de portabilidade de crédito exclusiva para o OFB.
     *
     * @return self
     */
    public function setBranchCode($branch_code)
    {
        if (is_null($branch_code)) {
            throw new \InvalidArgumentException('non-nullable branch_code cannot be null');
        }
        $this->container['branch_code'] = $branch_code;

        return $this;
    }

    /**
     * Gets has_financial_agent
     *
     * @return bool
     */
    public function getHasFinancialAgent()
    {
        return $this->container['has_financial_agent'];
    }

    /**
     * Sets has_financial_agent
     *
     * @param bool $has_financial_agent Instituição trabalha com agente financeiro ao invés da conta reserva?
     *
     * @return self
     */
    public function setHasFinancialAgent($has_financial_agent)
    {
        if (is_null($has_financial_agent)) {
            throw new \InvalidArgumentException('non-nullable has_financial_agent cannot be null');
        }
        $this->container['has_financial_agent'] = $has_financial_agent;

        return $this;
    }

    /**
     * Gets account_number
     *
     * @return float|null
     */
    public function getAccountNumber()
    {
        return $this->container['account_number'];
    }

    /**
     * Sets account_number
     *
     * @param float|null $account_number Número da conta bancária da credora a ser usada na STR para pagamento de portabilidade de crédito exclusiva para o OFB.  [RESTRIÇÃO] campo de preenchimento obrigatório quando campo `hasFinancialAgent` for igual a true
     *
     * @return self
     */
    public function setAccountNumber($account_number)
    {
        if (is_null($account_number)) {
            throw new \InvalidArgumentException('non-nullable account_number cannot be null');
        }
        $this->container['account_number'] = $account_number;

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


