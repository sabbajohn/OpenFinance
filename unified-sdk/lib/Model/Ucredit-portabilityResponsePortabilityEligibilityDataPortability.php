<?php
/**
 * ResponsePortabilityEligibilityDataPortability
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
 * ResponsePortabilityEligibilityDataPortability Class Doc Comment
 *
 * @category Class
 * @package  OpenAPI\Client
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 * @implements \ArrayAccess<string, mixed>
 */
class Ucredit-portabilityResponsePortabilityEligibilityDataPortability implements ModelInterface, ArrayAccess, \JsonSerializable
{
    public const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'ResponsePortabilityEligibility_data_portability';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'is_eligible' => 'bool',
        'ineligible' => '\OpenAPI\Client\Model\ResponsePortabilityEligibilityDataPortabilityIneligible',
        'status' => 'string',
        'status_update_date_time' => 'string',
        'channel' => 'string',
        'company_name' => 'string',
        'company_cnpj' => 'string'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      * @phpstan-var array<string, string|null>
      * @psalm-var array<string, string|null>
      */
    protected static $openAPIFormats = [
        'is_eligible' => null,
        'ineligible' => null,
        'status' => null,
        'status_update_date_time' => null,
        'channel' => null,
        'company_name' => null,
        'company_cnpj' => null
    ];

    /**
      * Array of nullable properties. Used for (de)serialization
      *
      * @var boolean[]
      */
    protected static array $openAPINullables = [
        'is_eligible' => false,
        'ineligible' => false,
        'status' => false,
        'status_update_date_time' => false,
        'channel' => false,
        'company_name' => false,
        'company_cnpj' => false
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
        'is_eligible' => 'isEligible',
        'ineligible' => 'ineligible',
        'status' => 'status',
        'status_update_date_time' => 'statusUpdateDateTime',
        'channel' => 'channel',
        'company_name' => 'companyName',
        'company_cnpj' => 'companyCnpj'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'is_eligible' => 'setIsEligible',
        'ineligible' => 'setIneligible',
        'status' => 'setStatus',
        'status_update_date_time' => 'setStatusUpdateDateTime',
        'channel' => 'setChannel',
        'company_name' => 'setCompanyName',
        'company_cnpj' => 'setCompanyCnpj'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'is_eligible' => 'getIsEligible',
        'ineligible' => 'getIneligible',
        'status' => 'getStatus',
        'status_update_date_time' => 'getStatusUpdateDateTime',
        'channel' => 'getChannel',
        'company_name' => 'getCompanyName',
        'company_cnpj' => 'getCompanyCnpj'
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

    public const STATUS_DISPONIVEL = 'DISPONIVEL';
    public const STATUS_EM_ANDAMENTO = 'EM_ANDAMENTO';
    public const CHANNEL_OFB = 'OFB';
    public const CHANNEL_REGISTRADORA = 'REGISTRADORA';

    /**
     * Gets allowable values of the enum
     *
     * @return string[]
     */
    public function getStatusAllowableValues()
    {
        return [
            self::STATUS_DISPONIVEL,
            self::STATUS_EM_ANDAMENTO,
        ];
    }

    /**
     * Gets allowable values of the enum
     *
     * @return string[]
     */
    public function getChannelAllowableValues()
    {
        return [
            self::CHANNEL_OFB,
            self::CHANNEL_REGISTRADORA,
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
        $this->setIfExists('is_eligible', $data ?? [], null);
        $this->setIfExists('ineligible', $data ?? [], null);
        $this->setIfExists('status', $data ?? [], null);
        $this->setIfExists('status_update_date_time', $data ?? [], null);
        $this->setIfExists('channel', $data ?? [], null);
        $this->setIfExists('company_name', $data ?? [], null);
        $this->setIfExists('company_cnpj', $data ?? [], null);
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

        if ($this->container['is_eligible'] === null) {
            $invalidProperties[] = "'is_eligible' can't be null";
        }
        $allowedValues = $this->getStatusAllowableValues();
        if (!is_null($this->container['status']) && !in_array($this->container['status'], $allowedValues, true)) {
            $invalidProperties[] = sprintf(
                "invalid value '%s' for 'status', must be one of '%s'",
                $this->container['status'],
                implode("', '", $allowedValues)
            );
        }

        if (!is_null($this->container['status_update_date_time']) && (mb_strlen($this->container['status_update_date_time']) > 20)) {
            $invalidProperties[] = "invalid value for 'status_update_date_time', the character length must be smaller than or equal to 20.";
        }

        if (!is_null($this->container['status_update_date_time']) && !preg_match("/^(\\d{4})-(1[0-2]|0?[1-9])-(3[01]|[12][0-9]|0?[1-9])T(?:[01]\\d|2[0123]):(?:[012345]\\d):(?:[012345]\\d)Z$/", $this->container['status_update_date_time'])) {
            $invalidProperties[] = "invalid value for 'status_update_date_time', must be conform to the pattern /^(\\d{4})-(1[0-2]|0?[1-9])-(3[01]|[12][0-9]|0?[1-9])T(?:[01]\\d|2[0123]):(?:[012345]\\d):(?:[012345]\\d)Z$/.";
        }

        $allowedValues = $this->getChannelAllowableValues();
        if (!is_null($this->container['channel']) && !in_array($this->container['channel'], $allowedValues, true)) {
            $invalidProperties[] = sprintf(
                "invalid value '%s' for 'channel', must be one of '%s'",
                $this->container['channel'],
                implode("', '", $allowedValues)
            );
        }

        if (!is_null($this->container['company_name']) && (mb_strlen($this->container['company_name']) > 80)) {
            $invalidProperties[] = "invalid value for 'company_name', the character length must be smaller than or equal to 80.";
        }

        if (!is_null($this->container['company_name']) && !preg_match("/^[^\\s](?:.*[^\\s])?$/", $this->container['company_name'])) {
            $invalidProperties[] = "invalid value for 'company_name', must be conform to the pattern /^[^\\s](?:.*[^\\s])?$/.";
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
     * Gets is_eligible
     *
     * @return bool
     */
    public function getIsEligible()
    {
        return $this->container['is_eligible'];
    }

    /**
     * Sets is_eligible
     *
     * @param bool $is_eligible Sinaliza se as características do contrato é elegível para pedido de portabilidade de crédito via OFB (sem considerar a disponibilidade da portabilidade de crédito)
     *
     * @return self
     */
    public function setIsEligible($is_eligible)
    {
        if (is_null($is_eligible)) {
            throw new \InvalidArgumentException('non-nullable is_eligible cannot be null');
        }
        $this->container['is_eligible'] = $is_eligible;

        return $this;
    }

    /**
     * Gets ineligible
     *
     * @return \OpenAPI\Client\Model\ResponsePortabilityEligibilityDataPortabilityIneligible|null
     */
    public function getIneligible()
    {
        return $this->container['ineligible'];
    }

    /**
     * Sets ineligible
     *
     * @param \OpenAPI\Client\Model\ResponsePortabilityEligibilityDataPortabilityIneligible|null $ineligible ineligible
     *
     * @return self
     */
    public function setIneligible($ineligible)
    {
        if (is_null($ineligible)) {
            throw new \InvalidArgumentException('non-nullable ineligible cannot be null');
        }
        $this->container['ineligible'] = $ineligible;

        return $this;
    }

    /**
     * Gets status
     *
     * @return string|null
     */
    public function getStatus()
    {
        return $this->container['status'];
    }

    /**
     * Sets status
     *
     * @param string|null $status Informação sobre a disponibilidade ou não de um contrato para a portabilidade de crédito  [RESTRIÇÃO] Campo de preenchimento obrigatório quando o campo `isEligible` for igual a `TRUE`
     *
     * @return self
     */
    public function setStatus($status)
    {
        if (is_null($status)) {
            throw new \InvalidArgumentException('non-nullable status cannot be null');
        }
        $allowedValues = $this->getStatusAllowableValues();
        if (!in_array($status, $allowedValues, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid value '%s' for 'status', must be one of '%s'",
                    $status,
                    implode("', '", $allowedValues)
                )
            );
        }
        $this->container['status'] = $status;

        return $this;
    }

    /**
     * Gets status_update_date_time
     *
     * @return string|null
     */
    public function getStatusUpdateDateTime()
    {
        return $this->container['status_update_date_time'];
    }

    /**
     * Sets status_update_date_time
     *
     * @param string|null $status_update_date_time Data e hora em que o contrato teve o status atualizado. Uma string com data e hora conforme especificação [RFC-3339](https://datatracker.ietf.org/doc/html/rfc3339), sempre com a utilização de timezone UTC(UTC time format).  [RESTRIÇÃO] Campo de preenchimento obrigatório quando o campo `isEligible` for igual a `TRUE`
     *
     * @return self
     */
    public function setStatusUpdateDateTime($status_update_date_time)
    {
        if (is_null($status_update_date_time)) {
            throw new \InvalidArgumentException('non-nullable status_update_date_time cannot be null');
        }
        if ((mb_strlen($status_update_date_time) > 20)) {
            throw new \InvalidArgumentException('invalid length for $status_update_date_time when calling ResponsePortabilityEligibilityDataPortability., must be smaller than or equal to 20.');
        }
        if ((!preg_match("/^(\\d{4})-(1[0-2]|0?[1-9])-(3[01]|[12][0-9]|0?[1-9])T(?:[01]\\d|2[0123]):(?:[012345]\\d):(?:[012345]\\d)Z$/", ObjectSerializer::toString($status_update_date_time)))) {
            throw new \InvalidArgumentException("invalid value for \$status_update_date_time when calling ResponsePortabilityEligibilityDataPortability., must conform to the pattern /^(\\d{4})-(1[0-2]|0?[1-9])-(3[01]|[12][0-9]|0?[1-9])T(?:[01]\\d|2[0123]):(?:[012345]\\d):(?:[012345]\\d)Z$/.");
        }

        $this->container['status_update_date_time'] = $status_update_date_time;

        return $this;
    }

    /**
     * Gets channel
     *
     * @return string|null
     */
    public function getChannel()
    {
        return $this->container['channel'];
    }

    /**
     * Sets channel
     *
     * @param string|null $channel Informação sobre a disponibilidade ou não de um contrato para a portabilidade de crédito  [RESTRIÇÃO] Campo de preenchimento obrigatório quando o campo `status` for igual a `EM_ANDAMENTO`
     *
     * @return self
     */
    public function setChannel($channel)
    {
        if (is_null($channel)) {
            throw new \InvalidArgumentException('non-nullable channel cannot be null');
        }
        $allowedValues = $this->getChannelAllowableValues();
        if (!in_array($channel, $allowedValues, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid value '%s' for 'channel', must be one of '%s'",
                    $channel,
                    implode("', '", $allowedValues)
                )
            );
        }
        $this->container['channel'] = $channel;

        return $this;
    }

    /**
     * Gets company_name
     *
     * @return string|null
     */
    public function getCompanyName()
    {
        return $this->container['company_name'];
    }

    /**
     * Sets company_name
     *
     * @param string|null $company_name Nome da Instituição Proponente responsável pelo pedido de portabilidade de credito anterior a atual consulta p.ex.Empresa A.  [RESTRIÇÃO] Campo de preenchimento obrigatório quando o campo `status` for igual a `EM_ANDAMENTO`
     *
     * @return self
     */
    public function setCompanyName($company_name)
    {
        if (is_null($company_name)) {
            throw new \InvalidArgumentException('non-nullable company_name cannot be null');
        }
        if ((mb_strlen($company_name) > 80)) {
            throw new \InvalidArgumentException('invalid length for $company_name when calling ResponsePortabilityEligibilityDataPortability., must be smaller than or equal to 80.');
        }
        if ((!preg_match("/^[^\\s](?:.*[^\\s])?$/", ObjectSerializer::toString($company_name)))) {
            throw new \InvalidArgumentException("invalid value for \$company_name when calling ResponsePortabilityEligibilityDataPortability., must conform to the pattern /^[^\\s](?:.*[^\\s])?$/.");
        }

        $this->container['company_name'] = $company_name;

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
     * @param string|null $company_cnpj Número completo do CNPJ da instituição O CNPJ corresponde ao número de inscrição no Cadastro de Pessoa Jurídica. Deve-se ter apenas números do CNPJ, sem máscara  [RESTRIÇÃO] Campo de preenchimento obrigatório quando o campo `status` for igual a `EM_ANDAMENTO` e o campo `channel` for igual a `OFB`.
     *
     * @return self
     */
    public function setCompanyCnpj($company_cnpj)
    {
        if (is_null($company_cnpj)) {
            throw new \InvalidArgumentException('non-nullable company_cnpj cannot be null');
        }
        if ((mb_strlen($company_cnpj) > 14)) {
            throw new \InvalidArgumentException('invalid length for $company_cnpj when calling ResponsePortabilityEligibilityDataPortability., must be smaller than or equal to 14.');
        }
        if ((mb_strlen($company_cnpj) < 14)) {
            throw new \InvalidArgumentException('invalid length for $company_cnpj when calling ResponsePortabilityEligibilityDataPortability., must be bigger than or equal to 14.');
        }
        if ((!preg_match("/^[0-9A-Z]{12}[0-9]{2}$/", ObjectSerializer::toString($company_cnpj)))) {
            throw new \InvalidArgumentException("invalid value for \$company_cnpj when calling ResponsePortabilityEligibilityDataPortability., must conform to the pattern /^[0-9A-Z]{12}[0-9]{2}$/.");
        }

        $this->container['company_cnpj'] = $company_cnpj;

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


