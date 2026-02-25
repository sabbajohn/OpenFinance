<?php
/**
 * RequestCreditPortabilityDataContractIdentification
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
 * RequestCreditPortabilityDataContractIdentification Class Doc Comment
 *
 * @category Class
 * @package  OpenAPI\Client
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 * @implements \ArrayAccess<string, mixed>
 */
class RequestCreditPortabilityDataContractIdentification implements ModelInterface, ArrayAccess, \JsonSerializable
{
    public const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'RequestCreditPortability_data_contractIdentification';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'contract_id' => 'string',
        'contract_number' => 'string',
        'ipoc_code' => 'string'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      * @phpstan-var array<string, string|null>
      * @psalm-var array<string, string|null>
      */
    protected static $openAPIFormats = [
        'contract_id' => null,
        'contract_number' => null,
        'ipoc_code' => null
    ];

    /**
      * Array of nullable properties. Used for (de)serialization
      *
      * @var boolean[]
      */
    protected static array $openAPINullables = [
        'contract_id' => false,
        'contract_number' => false,
        'ipoc_code' => false
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
        'contract_id' => 'contractId',
        'contract_number' => 'contractNumber',
        'ipoc_code' => 'ipocCode'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'contract_id' => 'setContractId',
        'contract_number' => 'setContractNumber',
        'ipoc_code' => 'setIpocCode'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'contract_id' => 'getContractId',
        'contract_number' => 'getContractNumber',
        'ipoc_code' => 'getIpocCode'
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
        $this->setIfExists('contract_id', $data ?? [], null);
        $this->setIfExists('contract_number', $data ?? [], null);
        $this->setIfExists('ipoc_code', $data ?? [], null);
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

        if ($this->container['contract_id'] === null) {
            $invalidProperties[] = "'contract_id' can't be null";
        }
        if ((mb_strlen($this->container['contract_id']) > 100)) {
            $invalidProperties[] = "invalid value for 'contract_id', the character length must be smaller than or equal to 100.";
        }

        if ((mb_strlen($this->container['contract_id']) < 1)) {
            $invalidProperties[] = "invalid value for 'contract_id', the character length must be bigger than or equal to 1.";
        }

        if (!preg_match("/^[a-zA-Z0-9][a-zA-Z0-9-]{0,99}$/", $this->container['contract_id'])) {
            $invalidProperties[] = "invalid value for 'contract_id', must be conform to the pattern /^[a-zA-Z0-9][a-zA-Z0-9-]{0,99}$/.";
        }

        if ($this->container['contract_number'] === null) {
            $invalidProperties[] = "'contract_number' can't be null";
        }
        if ((mb_strlen($this->container['contract_number']) > 100)) {
            $invalidProperties[] = "invalid value for 'contract_number', the character length must be smaller than or equal to 100.";
        }

        if ((mb_strlen($this->container['contract_number']) < 1)) {
            $invalidProperties[] = "invalid value for 'contract_number', the character length must be bigger than or equal to 1.";
        }

        if (!preg_match("/^[\\w\\W]{1,100}$/", $this->container['contract_number'])) {
            $invalidProperties[] = "invalid value for 'contract_number', must be conform to the pattern /^[\\w\\W]{1,100}$/.";
        }

        if ($this->container['ipoc_code'] === null) {
            $invalidProperties[] = "'ipoc_code' can't be null";
        }
        if ((mb_strlen($this->container['ipoc_code']) > 67)) {
            $invalidProperties[] = "invalid value for 'ipoc_code', the character length must be smaller than or equal to 67.";
        }

        if ((mb_strlen($this->container['ipoc_code']) < 22)) {
            $invalidProperties[] = "invalid value for 'ipoc_code', the character length must be bigger than or equal to 22.";
        }

        if (!preg_match("/^[\\w\\W]{22,67}$/", $this->container['ipoc_code'])) {
            $invalidProperties[] = "invalid value for 'ipoc_code', must be conform to the pattern /^[\\w\\W]{22,67}$/.";
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
     * Gets contract_id
     *
     * @return string
     */
    public function getContractId()
    {
        return $this->container['contract_id'];
    }

    /**
     * Sets contract_id
     *
     * @param string $contract_id Identifica de forma única o contrato da operação de crédito do cliente, mantendo as regras de imutabilidade dentro da instituição transmissora.
     *
     * @return self
     */
    public function setContractId($contract_id)
    {
        if (is_null($contract_id)) {
            throw new \InvalidArgumentException('non-nullable contract_id cannot be null');
        }
        if ((mb_strlen($contract_id) > 100)) {
            throw new \InvalidArgumentException('invalid length for $contract_id when calling RequestCreditPortabilityDataContractIdentification., must be smaller than or equal to 100.');
        }
        if ((mb_strlen($contract_id) < 1)) {
            throw new \InvalidArgumentException('invalid length for $contract_id when calling RequestCreditPortabilityDataContractIdentification., must be bigger than or equal to 1.');
        }
        if ((!preg_match("/^[a-zA-Z0-9][a-zA-Z0-9-]{0,99}$/", ObjectSerializer::toString($contract_id)))) {
            throw new \InvalidArgumentException("invalid value for \$contract_id when calling RequestCreditPortabilityDataContractIdentification., must conform to the pattern /^[a-zA-Z0-9][a-zA-Z0-9-]{0,99}$/.");
        }

        $this->container['contract_id'] = $contract_id;

        return $this;
    }

    /**
     * Gets contract_number
     *
     * @return string
     */
    public function getContractNumber()
    {
        return $this->container['contract_number'];
    }

    /**
     * Sets contract_number
     *
     * @param string $contract_number Número do contrato dado pela instituição contratante.
     *
     * @return self
     */
    public function setContractNumber($contract_number)
    {
        if (is_null($contract_number)) {
            throw new \InvalidArgumentException('non-nullable contract_number cannot be null');
        }
        if ((mb_strlen($contract_number) > 100)) {
            throw new \InvalidArgumentException('invalid length for $contract_number when calling RequestCreditPortabilityDataContractIdentification., must be smaller than or equal to 100.');
        }
        if ((mb_strlen($contract_number) < 1)) {
            throw new \InvalidArgumentException('invalid length for $contract_number when calling RequestCreditPortabilityDataContractIdentification., must be bigger than or equal to 1.');
        }
        if ((!preg_match("/^[\\w\\W]{1,100}$/", ObjectSerializer::toString($contract_number)))) {
            throw new \InvalidArgumentException("invalid value for \$contract_number when calling RequestCreditPortabilityDataContractIdentification., must conform to the pattern /^[\\w\\W]{1,100}$/.");
        }

        $this->container['contract_number'] = $contract_number;

        return $this;
    }

    /**
     * Gets ipoc_code
     *
     * @return string
     */
    public function getIpocCode()
    {
        return $this->container['ipoc_code'];
    }

    /**
     * Sets ipoc_code
     *
     * @param string $ipoc_code Número padronizado do contrato - IPOC (Identificação Padronizada da Operação de Crédito). Segundo DOC 3040, composta por:   CNPJ da instituição: 8 (oito) posições iniciais; Modalidade da operação: 4 (quatro) posições; Tipo do cliente: 1 (uma) posição( 1 = pessoa natural - CPF, 2= pessoa jurídica  – CNPJ, 3 = pessoa física no exterior, 4 = pessoa jurídica no exterior, 5 = pessoa natural sem CPF e 6 = pessoa jurídica sem CNPJ);  - Código do cliente: O número de posições varia conforme o tipo do cliente: Para clientes pessoa física com CPF (tipo de cliente = 1), informar as 11 (onze) posições do CPF; Para clientes pessoa jurídica com CNPJ (tipo de cliente = 2), informar as 8 (oito) posições iniciais do CNPJ; Para os demais clientes (tipos de cliente 3, 4, 5 e 6), informar 14 (catorze) posições com complemento de zeros à esquerda se a identificação tiver tamanho inferior;  - Código do contrato: 1 (uma) até 40 (quarenta) posições, sem complemento de caracteres.
     *
     * @return self
     */
    public function setIpocCode($ipoc_code)
    {
        if (is_null($ipoc_code)) {
            throw new \InvalidArgumentException('non-nullable ipoc_code cannot be null');
        }
        if ((mb_strlen($ipoc_code) > 67)) {
            throw new \InvalidArgumentException('invalid length for $ipoc_code when calling RequestCreditPortabilityDataContractIdentification., must be smaller than or equal to 67.');
        }
        if ((mb_strlen($ipoc_code) < 22)) {
            throw new \InvalidArgumentException('invalid length for $ipoc_code when calling RequestCreditPortabilityDataContractIdentification., must be bigger than or equal to 22.');
        }
        if ((!preg_match("/^[\\w\\W]{22,67}$/", ObjectSerializer::toString($ipoc_code)))) {
            throw new \InvalidArgumentException("invalid value for \$ipoc_code when calling RequestCreditPortabilityDataContractIdentification., must conform to the pattern /^[\\w\\W]{22,67}$/.");
        }

        $this->container['ipoc_code'] = $ipoc_code;

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


