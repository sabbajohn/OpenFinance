<?php
/**
 * ResponsePortabilitiesByPortabilityIdData
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
 * ResponsePortabilitiesByPortabilityIdData Class Doc Comment
 *
 * @category Class
 * @description Conjunto de informações referentes à Proposta de Portabilidade de Crédito da Proponente para a Credora
 * @package  OpenAPI\Client
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 * @implements \ArrayAccess<string, mixed>
 */
class Ucredit-portabilityResponsePortabilitiesByPortabilityIdData implements ModelInterface, ArrayAccess, \JsonSerializable
{
    public const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'ResponsePortabilitiesByPortabilityId_data';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'portability_id' => 'string',
        'customer_contact' => '\OpenAPI\Client\Model\RequestCreditPortabilityDataCustomerContactInner[]',
        'institution' => '\OpenAPI\Client\Model\ResponsePortabilitiesByPortabilityIdDataInstitution',
        'contract_identification' => '\OpenAPI\Client\Model\RequestCreditPortabilityDataContractIdentification',
        'proposed_contract' => '\OpenAPI\Client\Model\ResponsePortabilitiesByPortabilityIdDataProposedContract',
        'status' => 'string',
        'status_update_date_time' => 'string',
        'status_reason' => '\OpenAPI\Client\Model\ResponsePortabilitiesByPortabilityIdDataStatusReason',
        'creation_date_time' => 'string',
        'rejection' => '\OpenAPI\Client\Model\ResponsePortabilitiesByPortabilityIdDataRejection',
        'loan_settlement_instruction' => '\OpenAPI\Client\Model\ResponsePortabilitiesByPortabilityIdDataLoanSettlementInstruction'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      * @phpstan-var array<string, string|null>
      * @psalm-var array<string, string|null>
      */
    protected static $openAPIFormats = [
        'portability_id' => null,
        'customer_contact' => null,
        'institution' => null,
        'contract_identification' => null,
        'proposed_contract' => null,
        'status' => null,
        'status_update_date_time' => null,
        'status_reason' => null,
        'creation_date_time' => null,
        'rejection' => null,
        'loan_settlement_instruction' => null
    ];

    /**
      * Array of nullable properties. Used for (de)serialization
      *
      * @var boolean[]
      */
    protected static array $openAPINullables = [
        'portability_id' => false,
        'customer_contact' => false,
        'institution' => false,
        'contract_identification' => false,
        'proposed_contract' => false,
        'status' => false,
        'status_update_date_time' => false,
        'status_reason' => false,
        'creation_date_time' => false,
        'rejection' => false,
        'loan_settlement_instruction' => false
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
        'portability_id' => 'portabilityId',
        'customer_contact' => 'customerContact',
        'institution' => 'institution',
        'contract_identification' => 'contractIdentification',
        'proposed_contract' => 'proposedContract',
        'status' => 'status',
        'status_update_date_time' => 'statusUpdateDateTime',
        'status_reason' => 'statusReason',
        'creation_date_time' => 'creationDateTime',
        'rejection' => 'rejection',
        'loan_settlement_instruction' => 'loanSettlementInstruction'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'portability_id' => 'setPortabilityId',
        'customer_contact' => 'setCustomerContact',
        'institution' => 'setInstitution',
        'contract_identification' => 'setContractIdentification',
        'proposed_contract' => 'setProposedContract',
        'status' => 'setStatus',
        'status_update_date_time' => 'setStatusUpdateDateTime',
        'status_reason' => 'setStatusReason',
        'creation_date_time' => 'setCreationDateTime',
        'rejection' => 'setRejection',
        'loan_settlement_instruction' => 'setLoanSettlementInstruction'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'portability_id' => 'getPortabilityId',
        'customer_contact' => 'getCustomerContact',
        'institution' => 'getInstitution',
        'contract_identification' => 'getContractIdentification',
        'proposed_contract' => 'getProposedContract',
        'status' => 'getStatus',
        'status_update_date_time' => 'getStatusUpdateDateTime',
        'status_reason' => 'getStatusReason',
        'creation_date_time' => 'getCreationDateTime',
        'rejection' => 'getRejection',
        'loan_settlement_instruction' => 'getLoanSettlementInstruction'
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

    public const STATUS_RECEIVED = 'RECEIVED';
    public const STATUS_PENDING = 'PENDING';
    public const STATUS_ACCEPTED_SETTLEMENT_IN_PROGRESS = 'ACCEPTED_SETTLEMENT_IN_PROGRESS';
    public const STATUS_ACCEPTED_SETTLEMENT_COMPLETED = 'ACCEPTED_SETTLEMENT_COMPLETED';
    public const STATUS_PORTABILITY_COMPLETED = 'PORTABILITY_COMPLETED';
    public const STATUS_REJECTED = 'REJECTED';
    public const STATUS_CANCELLED = 'CANCELLED';
    public const STATUS_PAYMENT_ISSUE = 'PAYMENT_ISSUE';

    /**
     * Gets allowable values of the enum
     *
     * @return string[]
     */
    public function getStatusAllowableValues()
    {
        return [
            self::STATUS_RECEIVED,
            self::STATUS_PENDING,
            self::STATUS_ACCEPTED_SETTLEMENT_IN_PROGRESS,
            self::STATUS_ACCEPTED_SETTLEMENT_COMPLETED,
            self::STATUS_PORTABILITY_COMPLETED,
            self::STATUS_REJECTED,
            self::STATUS_CANCELLED,
            self::STATUS_PAYMENT_ISSUE,
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
        $this->setIfExists('portability_id', $data ?? [], null);
        $this->setIfExists('customer_contact', $data ?? [], null);
        $this->setIfExists('institution', $data ?? [], null);
        $this->setIfExists('contract_identification', $data ?? [], null);
        $this->setIfExists('proposed_contract', $data ?? [], null);
        $this->setIfExists('status', $data ?? [], null);
        $this->setIfExists('status_update_date_time', $data ?? [], null);
        $this->setIfExists('status_reason', $data ?? [], null);
        $this->setIfExists('creation_date_time', $data ?? [], null);
        $this->setIfExists('rejection', $data ?? [], null);
        $this->setIfExists('loan_settlement_instruction', $data ?? [], null);
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

        if ($this->container['portability_id'] === null) {
            $invalidProperties[] = "'portability_id' can't be null";
        }
        if ((mb_strlen($this->container['portability_id']) > 36)) {
            $invalidProperties[] = "invalid value for 'portability_id', the character length must be smaller than or equal to 36.";
        }

        if ((mb_strlen($this->container['portability_id']) < 36)) {
            $invalidProperties[] = "invalid value for 'portability_id', the character length must be bigger than or equal to 36.";
        }

        if (!preg_match("/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/", $this->container['portability_id'])) {
            $invalidProperties[] = "invalid value for 'portability_id', must be conform to the pattern /^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/.";
        }

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
        if ($this->container['status'] === null) {
            $invalidProperties[] = "'status' can't be null";
        }
        $allowedValues = $this->getStatusAllowableValues();
        if (!is_null($this->container['status']) && !in_array($this->container['status'], $allowedValues, true)) {
            $invalidProperties[] = sprintf(
                "invalid value '%s' for 'status', must be one of '%s'",
                $this->container['status'],
                implode("', '", $allowedValues)
            );
        }

        if ($this->container['status_update_date_time'] === null) {
            $invalidProperties[] = "'status_update_date_time' can't be null";
        }
        if ((mb_strlen($this->container['status_update_date_time']) > 20)) {
            $invalidProperties[] = "invalid value for 'status_update_date_time', the character length must be smaller than or equal to 20.";
        }

        if (!preg_match("/^(\\d{4})-(1[0-2]|0?[1-9])-(3[01]|[12][0-9]|0?[1-9])T(?:[01]\\d|2[0123]):(?:[012345]\\d):(?:[012345]\\d)Z$/", $this->container['status_update_date_time'])) {
            $invalidProperties[] = "invalid value for 'status_update_date_time', must be conform to the pattern /^(\\d{4})-(1[0-2]|0?[1-9])-(3[01]|[12][0-9]|0?[1-9])T(?:[01]\\d|2[0123]):(?:[012345]\\d):(?:[012345]\\d)Z$/.";
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
     * Gets portability_id
     *
     * @return string
     */
    public function getPortabilityId()
    {
        return $this->container['portability_id'];
    }

    /**
     * Sets portability_id
     *
     * @param string $portability_id Código identificador do pedido de portabilidade realizado.
     *
     * @return self
     */
    public function setPortabilityId($portability_id)
    {
        if (is_null($portability_id)) {
            throw new \InvalidArgumentException('non-nullable portability_id cannot be null');
        }
        if ((mb_strlen($portability_id) > 36)) {
            throw new \InvalidArgumentException('invalid length for $portability_id when calling ResponsePortabilitiesByPortabilityIdData., must be smaller than or equal to 36.');
        }
        if ((mb_strlen($portability_id) < 36)) {
            throw new \InvalidArgumentException('invalid length for $portability_id when calling ResponsePortabilitiesByPortabilityIdData., must be bigger than or equal to 36.');
        }
        if ((!preg_match("/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/", ObjectSerializer::toString($portability_id)))) {
            throw new \InvalidArgumentException("invalid value for \$portability_id when calling ResponsePortabilitiesByPortabilityIdData., must conform to the pattern /^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/.");
        }

        $this->container['portability_id'] = $portability_id;

        return $this;
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
     * @param \OpenAPI\Client\Model\RequestCreditPortabilityDataCustomerContactInner[] $customer_contact Dados de contato do cliente.
     *
     * @return self
     */
    public function setCustomerContact($customer_contact)
    {
        if (is_null($customer_contact)) {
            throw new \InvalidArgumentException('non-nullable customer_contact cannot be null');
        }


        if ((count($customer_contact) < 0)) {
            throw new \InvalidArgumentException('invalid length for $customer_contact when calling ResponsePortabilitiesByPortabilityIdData., number of items must be greater than or equal to 0.');
        }
        $this->container['customer_contact'] = $customer_contact;

        return $this;
    }

    /**
     * Gets institution
     *
     * @return \OpenAPI\Client\Model\ResponsePortabilitiesByPortabilityIdDataInstitution
     */
    public function getInstitution()
    {
        return $this->container['institution'];
    }

    /**
     * Sets institution
     *
     * @param \OpenAPI\Client\Model\ResponsePortabilitiesByPortabilityIdDataInstitution $institution institution
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
     * @return \OpenAPI\Client\Model\ResponsePortabilitiesByPortabilityIdDataProposedContract
     */
    public function getProposedContract()
    {
        return $this->container['proposed_contract'];
    }

    /**
     * Sets proposed_contract
     *
     * @param \OpenAPI\Client\Model\ResponsePortabilitiesByPortabilityIdDataProposedContract $proposed_contract proposed_contract
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
     * Gets status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->container['status'];
    }

    /**
     * Sets status
     *
     * @param string $status Informação sobre o status de um pedido de portabilidade de crédito, onde:  - `RECEIVED`: Estado inicial. Indica que o pedido de portabilidade foi solicitado junto a instituição credora. O pedido deve permanecer neste estado até que o próximo dia útil (D+1) aonde começará a contar o prazo de 3 dias úteis para a etapa de contraproposta e o pedido de portabilidade deverá ser movido para PENDING - `PENDING`: Indica que o pedido de portabilidade de crédito está na fase de contraproposta, onde a instituição credora poderá enviar uma contraproposta ou não para o cliente por qualquer canal (email, telefone, etc.) porém o aceite só deverá ser valido se o cliente aprovar no canal digital da instituição credora - `ACCEPTED_SETTLEMENT_IN_PROGRESS`: Indica que a contraproposta não foi aceita pelo cliente e a instituição proponente terá que quitar o valor do contrato no mesmo dia em que o estado foi ativado - `ACCEPTED_SETTLEMENT_COMPLETED`: Indica que a instituição proponente já liquidou o contrato e comunicou a respeito a credora que está validando os dados do contratos bem como valores recebidos para a quitação do mesmo (nesta etapa a instituição credora tem 2 dias úteis para fornecer a confirmação e o recibo de quitação do contrato de empréstimo) - `PORTABILITY_COMPLETED`: Indica que o pedido de portabilidade foi concluído com sucesso - `REJECTED`: Indica que o pedido de portabilidade de crédito foi rejeitado, seja porque o cliente aceitou a contraproposta, ou porque a proponente rejeitou a liquidação que excedeu em 15% o valor do contrato original, entre outras possibilidades - `CANCELLED`: Indica que o cliente cancelou o pedido de portabilidade de crédito - `PAYMENT_ISSUE`: Indica que a Instituição Credora encontrou alguma inconsistência na liquidação efetuada e que a Instituição Proponente deverá realizar ajustes conforme sugerido pela Instituição Credora para solucionar a pendencia antes do cancelamento do pedido de portabilidade de crédito
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
     * @return string
     */
    public function getStatusUpdateDateTime()
    {
        return $this->container['status_update_date_time'];
    }

    /**
     * Sets status_update_date_time
     *
     * @param string $status_update_date_time Data e hora em que o contrato teve o status atualizado. Uma string com data e hora conforme especificação [RFC-3339](https://datatracker.ietf.org/doc/html/rfc3339), sempre com a utilização de timezone UTC(UTC time format).
     *
     * @return self
     */
    public function setStatusUpdateDateTime($status_update_date_time)
    {
        if (is_null($status_update_date_time)) {
            throw new \InvalidArgumentException('non-nullable status_update_date_time cannot be null');
        }
        if ((mb_strlen($status_update_date_time) > 20)) {
            throw new \InvalidArgumentException('invalid length for $status_update_date_time when calling ResponsePortabilitiesByPortabilityIdData., must be smaller than or equal to 20.');
        }
        if ((!preg_match("/^(\\d{4})-(1[0-2]|0?[1-9])-(3[01]|[12][0-9]|0?[1-9])T(?:[01]\\d|2[0123]):(?:[012345]\\d):(?:[012345]\\d)Z$/", ObjectSerializer::toString($status_update_date_time)))) {
            throw new \InvalidArgumentException("invalid value for \$status_update_date_time when calling ResponsePortabilitiesByPortabilityIdData., must conform to the pattern /^(\\d{4})-(1[0-2]|0?[1-9])-(3[01]|[12][0-9]|0?[1-9])T(?:[01]\\d|2[0123]):(?:[012345]\\d):(?:[012345]\\d)Z$/.");
        }

        $this->container['status_update_date_time'] = $status_update_date_time;

        return $this;
    }

    /**
     * Gets status_reason
     *
     * @return \OpenAPI\Client\Model\ResponsePortabilitiesByPortabilityIdDataStatusReason|null
     */
    public function getStatusReason()
    {
        return $this->container['status_reason'];
    }

    /**
     * Sets status_reason
     *
     * @param \OpenAPI\Client\Model\ResponsePortabilitiesByPortabilityIdDataStatusReason|null $status_reason status_reason
     *
     * @return self
     */
    public function setStatusReason($status_reason)
    {
        if (is_null($status_reason)) {
            throw new \InvalidArgumentException('non-nullable status_reason cannot be null');
        }
        $this->container['status_reason'] = $status_reason;

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
            throw new \InvalidArgumentException('invalid length for $creation_date_time when calling ResponsePortabilitiesByPortabilityIdData., must be smaller than or equal to 20.');
        }
        if ((mb_strlen($creation_date_time) < 20)) {
            throw new \InvalidArgumentException('invalid length for $creation_date_time when calling ResponsePortabilitiesByPortabilityIdData., must be bigger than or equal to 20.');
        }
        if ((!preg_match("/^(\\d{4})-(1[0-2]|0?[1-9])-(3[01]|[12][0-9]|0?[1-9])T(?:[01]\\d|2[0123]):(?:[012345]\\d):(?:[012345]\\d)Z$/", ObjectSerializer::toString($creation_date_time)))) {
            throw new \InvalidArgumentException("invalid value for \$creation_date_time when calling ResponsePortabilitiesByPortabilityIdData., must conform to the pattern /^(\\d{4})-(1[0-2]|0?[1-9])-(3[01]|[12][0-9]|0?[1-9])T(?:[01]\\d|2[0123]):(?:[012345]\\d):(?:[012345]\\d)Z$/.");
        }

        $this->container['creation_date_time'] = $creation_date_time;

        return $this;
    }

    /**
     * Gets rejection
     *
     * @return \OpenAPI\Client\Model\ResponsePortabilitiesByPortabilityIdDataRejection|null
     */
    public function getRejection()
    {
        return $this->container['rejection'];
    }

    /**
     * Sets rejection
     *
     * @param \OpenAPI\Client\Model\ResponsePortabilitiesByPortabilityIdDataRejection|null $rejection rejection
     *
     * @return self
     */
    public function setRejection($rejection)
    {
        if (is_null($rejection)) {
            throw new \InvalidArgumentException('non-nullable rejection cannot be null');
        }
        $this->container['rejection'] = $rejection;

        return $this;
    }

    /**
     * Gets loan_settlement_instruction
     *
     * @return \OpenAPI\Client\Model\ResponsePortabilitiesByPortabilityIdDataLoanSettlementInstruction|null
     */
    public function getLoanSettlementInstruction()
    {
        return $this->container['loan_settlement_instruction'];
    }

    /**
     * Sets loan_settlement_instruction
     *
     * @param \OpenAPI\Client\Model\ResponsePortabilitiesByPortabilityIdDataLoanSettlementInstruction|null $loan_settlement_instruction loan_settlement_instruction
     *
     * @return self
     */
    public function setLoanSettlementInstruction($loan_settlement_instruction)
    {
        if (is_null($loan_settlement_instruction)) {
            throw new \InvalidArgumentException('non-nullable loan_settlement_instruction cannot be null');
        }
        $this->container['loan_settlement_instruction'] = $loan_settlement_instruction;

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


