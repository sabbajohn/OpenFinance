<?php
/**
 * RequestCreditPortabilityDataProposedContract
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
 * RequestCreditPortabilityDataProposedContract Class Doc Comment
 *
 * @category Class
 * @description Proposta da Proponente para Portabilidade de Crédito
 * @package  OpenAPI\Client
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 * @implements \ArrayAccess<string, mixed>
 */
class Ucredit-portabilityRequestCreditPortabilityDataProposedContract implements ModelInterface, ArrayAccess, \JsonSerializable
{
    public const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'RequestCreditPortability_data_proposedContract';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'interest_rates' => '\OpenAPI\Client\Model\LoansContractInterestRate[]',
        'contracted_fees' => '\OpenAPI\Client\Model\RequestCreditPortabilityDataProposedContractContractedFeesInner[]',
        'contracted_finance_charges' => '\OpenAPI\Client\Model\RequestCreditPortabilityDataProposedContractContractedFinanceChargesInner[]',
        'digital_signature_proof' => '\OpenAPI\Client\Model\RequestCreditPortabilityDataProposedContractDigitalSignatureProof',
        'cet' => 'string',
        'amortization_scheduled' => 'string',
        'amortization_scheduled_additional_info' => 'string',
        'instalment_periodicity' => 'string',
        'total_number_of_installments' => 'float',
        'installment_amount' => '\OpenAPI\Client\Model\RequestCreditPortabilityDataProposedContractInstallmentAmount',
        'due_date' => 'string',
        'contract_amount' => '\OpenAPI\Client\Model\RequestCreditPortabilityDataProposedContractContractAmount'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      * @phpstan-var array<string, string|null>
      * @psalm-var array<string, string|null>
      */
    protected static $openAPIFormats = [
        'interest_rates' => null,
        'contracted_fees' => null,
        'contracted_finance_charges' => null,
        'digital_signature_proof' => null,
        'cet' => null,
        'amortization_scheduled' => null,
        'amortization_scheduled_additional_info' => null,
        'instalment_periodicity' => null,
        'total_number_of_installments' => null,
        'installment_amount' => null,
        'due_date' => null,
        'contract_amount' => null
    ];

    /**
      * Array of nullable properties. Used for (de)serialization
      *
      * @var boolean[]
      */
    protected static array $openAPINullables = [
        'interest_rates' => false,
        'contracted_fees' => false,
        'contracted_finance_charges' => false,
        'digital_signature_proof' => false,
        'cet' => false,
        'amortization_scheduled' => false,
        'amortization_scheduled_additional_info' => false,
        'instalment_periodicity' => false,
        'total_number_of_installments' => false,
        'installment_amount' => false,
        'due_date' => false,
        'contract_amount' => false
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
        'interest_rates' => 'interestRates',
        'contracted_fees' => 'contractedFees',
        'contracted_finance_charges' => 'contractedFinanceCharges',
        'digital_signature_proof' => 'digitalSignatureProof',
        'cet' => 'CET',
        'amortization_scheduled' => 'amortizationScheduled',
        'amortization_scheduled_additional_info' => 'amortizationScheduledAdditionalInfo',
        'instalment_periodicity' => 'instalmentPeriodicity',
        'total_number_of_installments' => 'totalNumberOfInstallments',
        'installment_amount' => 'installmentAmount',
        'due_date' => 'dueDate',
        'contract_amount' => 'contractAmount'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'interest_rates' => 'setInterestRates',
        'contracted_fees' => 'setContractedFees',
        'contracted_finance_charges' => 'setContractedFinanceCharges',
        'digital_signature_proof' => 'setDigitalSignatureProof',
        'cet' => 'setCet',
        'amortization_scheduled' => 'setAmortizationScheduled',
        'amortization_scheduled_additional_info' => 'setAmortizationScheduledAdditionalInfo',
        'instalment_periodicity' => 'setInstalmentPeriodicity',
        'total_number_of_installments' => 'setTotalNumberOfInstallments',
        'installment_amount' => 'setInstallmentAmount',
        'due_date' => 'setDueDate',
        'contract_amount' => 'setContractAmount'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'interest_rates' => 'getInterestRates',
        'contracted_fees' => 'getContractedFees',
        'contracted_finance_charges' => 'getContractedFinanceCharges',
        'digital_signature_proof' => 'getDigitalSignatureProof',
        'cet' => 'getCet',
        'amortization_scheduled' => 'getAmortizationScheduled',
        'amortization_scheduled_additional_info' => 'getAmortizationScheduledAdditionalInfo',
        'instalment_periodicity' => 'getInstalmentPeriodicity',
        'total_number_of_installments' => 'getTotalNumberOfInstallments',
        'installment_amount' => 'getInstallmentAmount',
        'due_date' => 'getDueDate',
        'contract_amount' => 'getContractAmount'
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

    public const AMORTIZATION_SCHEDULED_SAC = 'SAC';
    public const AMORTIZATION_SCHEDULED_PRICE = 'PRICE';
    public const AMORTIZATION_SCHEDULED_SAM = 'SAM';
    public const AMORTIZATION_SCHEDULED_SEM_SISTEMA_AMORTIZACAO = 'SEM_SISTEMA_AMORTIZACAO';
    public const AMORTIZATION_SCHEDULED_OUTROS = 'OUTROS';
    public const INSTALMENT_PERIODICITY_SEM_PERIODICIDADE_REGULAR = 'SEM_PERIODICIDADE_REGULAR';
    public const INSTALMENT_PERIODICITY_DIARIO = 'DIARIO';
    public const INSTALMENT_PERIODICITY_SEMANAL = 'SEMANAL';
    public const INSTALMENT_PERIODICITY_QUINZENAL = 'QUINZENAL';
    public const INSTALMENT_PERIODICITY_MENSAL = 'MENSAL';
    public const INSTALMENT_PERIODICITY_BIMESTRAL = 'BIMESTRAL';
    public const INSTALMENT_PERIODICITY_TRIMESTRAL = 'TRIMESTRAL';
    public const INSTALMENT_PERIODICITY_SEMESTRAL = 'SEMESTRAL';
    public const INSTALMENT_PERIODICITY_ANUAL = 'ANUAL';

    /**
     * Gets allowable values of the enum
     *
     * @return string[]
     */
    public function getAmortizationScheduledAllowableValues()
    {
        return [
            self::AMORTIZATION_SCHEDULED_SAC,
            self::AMORTIZATION_SCHEDULED_PRICE,
            self::AMORTIZATION_SCHEDULED_SAM,
            self::AMORTIZATION_SCHEDULED_SEM_SISTEMA_AMORTIZACAO,
            self::AMORTIZATION_SCHEDULED_OUTROS,
        ];
    }

    /**
     * Gets allowable values of the enum
     *
     * @return string[]
     */
    public function getInstalmentPeriodicityAllowableValues()
    {
        return [
            self::INSTALMENT_PERIODICITY_SEM_PERIODICIDADE_REGULAR,
            self::INSTALMENT_PERIODICITY_DIARIO,
            self::INSTALMENT_PERIODICITY_SEMANAL,
            self::INSTALMENT_PERIODICITY_QUINZENAL,
            self::INSTALMENT_PERIODICITY_MENSAL,
            self::INSTALMENT_PERIODICITY_BIMESTRAL,
            self::INSTALMENT_PERIODICITY_TRIMESTRAL,
            self::INSTALMENT_PERIODICITY_SEMESTRAL,
            self::INSTALMENT_PERIODICITY_ANUAL,
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
        $this->setIfExists('interest_rates', $data ?? [], null);
        $this->setIfExists('contracted_fees', $data ?? [], null);
        $this->setIfExists('contracted_finance_charges', $data ?? [], null);
        $this->setIfExists('digital_signature_proof', $data ?? [], null);
        $this->setIfExists('cet', $data ?? [], null);
        $this->setIfExists('amortization_scheduled', $data ?? [], null);
        $this->setIfExists('amortization_scheduled_additional_info', $data ?? [], null);
        $this->setIfExists('instalment_periodicity', $data ?? [], null);
        $this->setIfExists('total_number_of_installments', $data ?? [], null);
        $this->setIfExists('installment_amount', $data ?? [], null);
        $this->setIfExists('due_date', $data ?? [], null);
        $this->setIfExists('contract_amount', $data ?? [], null);
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

        if ($this->container['interest_rates'] === null) {
            $invalidProperties[] = "'interest_rates' can't be null";
        }
        if ((count($this->container['interest_rates']) < 0)) {
            $invalidProperties[] = "invalid value for 'interest_rates', number of items must be greater than or equal to 0.";
        }

        if ($this->container['contracted_fees'] === null) {
            $invalidProperties[] = "'contracted_fees' can't be null";
        }
        if ((count($this->container['contracted_fees']) < 0)) {
            $invalidProperties[] = "invalid value for 'contracted_fees', number of items must be greater than or equal to 0.";
        }

        if ($this->container['contracted_finance_charges'] === null) {
            $invalidProperties[] = "'contracted_finance_charges' can't be null";
        }
        if ((count($this->container['contracted_finance_charges']) < 0)) {
            $invalidProperties[] = "invalid value for 'contracted_finance_charges', number of items must be greater than or equal to 0.";
        }

        if ($this->container['digital_signature_proof'] === null) {
            $invalidProperties[] = "'digital_signature_proof' can't be null";
        }
        if ($this->container['cet'] === null) {
            $invalidProperties[] = "'cet' can't be null";
        }
        if ((mb_strlen($this->container['cet']) > 13)) {
            $invalidProperties[] = "invalid value for 'cet', the character length must be smaller than or equal to 13.";
        }

        if ((mb_strlen($this->container['cet']) < 8)) {
            $invalidProperties[] = "invalid value for 'cet', the character length must be bigger than or equal to 8.";
        }

        if (!preg_match("/^\\d{1,6}\\.\\d{6}$/", $this->container['cet'])) {
            $invalidProperties[] = "invalid value for 'cet', must be conform to the pattern /^\\d{1,6}\\.\\d{6}$/.";
        }

        if ($this->container['amortization_scheduled'] === null) {
            $invalidProperties[] = "'amortization_scheduled' can't be null";
        }
        $allowedValues = $this->getAmortizationScheduledAllowableValues();
        if (!is_null($this->container['amortization_scheduled']) && !in_array($this->container['amortization_scheduled'], $allowedValues, true)) {
            $invalidProperties[] = sprintf(
                "invalid value '%s' for 'amortization_scheduled', must be one of '%s'",
                $this->container['amortization_scheduled'],
                implode("', '", $allowedValues)
            );
        }

        if (!is_null($this->container['amortization_scheduled_additional_info']) && (mb_strlen($this->container['amortization_scheduled_additional_info']) > 200)) {
            $invalidProperties[] = "invalid value for 'amortization_scheduled_additional_info', the character length must be smaller than or equal to 200.";
        }

        if (!is_null($this->container['amortization_scheduled_additional_info']) && !preg_match("/^[^\\s](?:.*[^\\s])?$/", $this->container['amortization_scheduled_additional_info'])) {
            $invalidProperties[] = "invalid value for 'amortization_scheduled_additional_info', must be conform to the pattern /^[^\\s](?:.*[^\\s])?$/.";
        }

        if ($this->container['instalment_periodicity'] === null) {
            $invalidProperties[] = "'instalment_periodicity' can't be null";
        }
        $allowedValues = $this->getInstalmentPeriodicityAllowableValues();
        if (!is_null($this->container['instalment_periodicity']) && !in_array($this->container['instalment_periodicity'], $allowedValues, true)) {
            $invalidProperties[] = sprintf(
                "invalid value '%s' for 'instalment_periodicity', must be one of '%s'",
                $this->container['instalment_periodicity'],
                implode("', '", $allowedValues)
            );
        }

        if ($this->container['total_number_of_installments'] === null) {
            $invalidProperties[] = "'total_number_of_installments' can't be null";
        }
        if (($this->container['total_number_of_installments'] > 999999999)) {
            $invalidProperties[] = "invalid value for 'total_number_of_installments', must be smaller than or equal to 999999999.";
        }

        if ($this->container['installment_amount'] === null) {
            $invalidProperties[] = "'installment_amount' can't be null";
        }
        if ($this->container['due_date'] === null) {
            $invalidProperties[] = "'due_date' can't be null";
        }
        if ((mb_strlen($this->container['due_date']) > 20)) {
            $invalidProperties[] = "invalid value for 'due_date', the character length must be smaller than or equal to 20.";
        }

        if ((mb_strlen($this->container['due_date']) < 20)) {
            $invalidProperties[] = "invalid value for 'due_date', the character length must be bigger than or equal to 20.";
        }

        if (!preg_match("/^(\\d{4})-(1[0-2]|0?[1-9])-(3[01]|[12][0-9]|0?[1-9])T(?:[01]\\d|2[0123]):(?:[012345]\\d):(?:[012345]\\d)Z$/", $this->container['due_date'])) {
            $invalidProperties[] = "invalid value for 'due_date', must be conform to the pattern /^(\\d{4})-(1[0-2]|0?[1-9])-(3[01]|[12][0-9]|0?[1-9])T(?:[01]\\d|2[0123]):(?:[012345]\\d):(?:[012345]\\d)Z$/.";
        }

        if ($this->container['contract_amount'] === null) {
            $invalidProperties[] = "'contract_amount' can't be null";
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
     * Gets interest_rates
     *
     * @return \OpenAPI\Client\Model\LoansContractInterestRate[]
     */
    public function getInterestRates()
    {
        return $this->container['interest_rates'];
    }

    /**
     * Sets interest_rates
     *
     * @param \OpenAPI\Client\Model\LoansContractInterestRate[] $interest_rates Objeto que traz o conjunto de informações necessárias para demonstrar a composição das taxas de juros remuneratórios da Modalidade de crédito.   Caso o contrato não possua taxas de juros, deve ser compartilhada uma lista vazia. Caso o contrato possua uma taxa de juros com valor 0, deve ser compartilhado um objeto com o valor 0 de forma explícita.
     *
     * @return self
     */
    public function setInterestRates($interest_rates)
    {
        if (is_null($interest_rates)) {
            throw new \InvalidArgumentException('non-nullable interest_rates cannot be null');
        }


        if ((count($interest_rates) < 0)) {
            throw new \InvalidArgumentException('invalid length for $interest_rates when calling RequestCreditPortabilityDataProposedContract., number of items must be greater than or equal to 0.');
        }
        $this->container['interest_rates'] = $interest_rates;

        return $this;
    }

    /**
     * Gets contracted_fees
     *
     * @return \OpenAPI\Client\Model\RequestCreditPortabilityDataProposedContractContractedFeesInner[]
     */
    public function getContractedFees()
    {
        return $this->container['contracted_fees'];
    }

    /**
     * Sets contracted_fees
     *
     * @param \OpenAPI\Client\Model\RequestCreditPortabilityDataProposedContractContractedFeesInner[] $contracted_fees Lista que traz as informações das tarifas pactuadas no contrato.
     *
     * @return self
     */
    public function setContractedFees($contracted_fees)
    {
        if (is_null($contracted_fees)) {
            throw new \InvalidArgumentException('non-nullable contracted_fees cannot be null');
        }


        if ((count($contracted_fees) < 0)) {
            throw new \InvalidArgumentException('invalid length for $contracted_fees when calling RequestCreditPortabilityDataProposedContract., number of items must be greater than or equal to 0.');
        }
        $this->container['contracted_fees'] = $contracted_fees;

        return $this;
    }

    /**
     * Gets contracted_finance_charges
     *
     * @return \OpenAPI\Client\Model\RequestCreditPortabilityDataProposedContractContractedFinanceChargesInner[]
     */
    public function getContractedFinanceCharges()
    {
        return $this->container['contracted_finance_charges'];
    }

    /**
     * Sets contracted_finance_charges
     *
     * @param \OpenAPI\Client\Model\RequestCreditPortabilityDataProposedContractContractedFinanceChargesInner[] $contracted_finance_charges Lista que traz os encargos pactuados no contrato
     *
     * @return self
     */
    public function setContractedFinanceCharges($contracted_finance_charges)
    {
        if (is_null($contracted_finance_charges)) {
            throw new \InvalidArgumentException('non-nullable contracted_finance_charges cannot be null');
        }


        if ((count($contracted_finance_charges) < 0)) {
            throw new \InvalidArgumentException('invalid length for $contracted_finance_charges when calling RequestCreditPortabilityDataProposedContract., number of items must be greater than or equal to 0.');
        }
        $this->container['contracted_finance_charges'] = $contracted_finance_charges;

        return $this;
    }

    /**
     * Gets digital_signature_proof
     *
     * @return \OpenAPI\Client\Model\RequestCreditPortabilityDataProposedContractDigitalSignatureProof
     */
    public function getDigitalSignatureProof()
    {
        return $this->container['digital_signature_proof'];
    }

    /**
     * Sets digital_signature_proof
     *
     * @param \OpenAPI\Client\Model\RequestCreditPortabilityDataProposedContractDigitalSignatureProof $digital_signature_proof digital_signature_proof
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
     * Gets cet
     *
     * @return string
     */
    public function getCet()
    {
        return $this->container['cet'];
    }

    /**
     * Sets cet
     *
     * @param string $cet CET – Custo Efetivo Total deve ser expresso na forma de taxa percentual anual e incorpora todos os encargos e despesas incidentes nas operações de crédito (taxa de juro, mas também tarifas, tributos, seguros e outras despesas cobradas). O preenchimento deve respeitar as 6 casas decimais, mesmo que venham preenchidas com zeros (representação de porcentagem p.ex: 0.150000. Este valor representa 15%. O valor 1 representa 100%). Para o público PF (pessoa física) o campo é de envio obrigatório para contratos firmados a partir de 2008, conforme Resolução CMN 3.517. Para o público PJ (pessoa jurídica) o campo é de envio obrigatório para contratos firmados a partir de 2011, conforme Resolução CMN 3.909. O campo poderá ser preenchido com 0.00 em cenários nos quais a casa não tenha a informação de CET (Custo efetivo total) apenas para as exceções listadas abaixo:    - Em contratos anteriores a 2008 (para o público PF);    - Em contratos anteriores a 2011 (para o público PJ);    - Público PJ de médio ou grande porte.
     *
     * @return self
     */
    public function setCet($cet)
    {
        if (is_null($cet)) {
            throw new \InvalidArgumentException('non-nullable cet cannot be null');
        }
        if ((mb_strlen($cet) > 13)) {
            throw new \InvalidArgumentException('invalid length for $cet when calling RequestCreditPortabilityDataProposedContract., must be smaller than or equal to 13.');
        }
        if ((mb_strlen($cet) < 8)) {
            throw new \InvalidArgumentException('invalid length for $cet when calling RequestCreditPortabilityDataProposedContract., must be bigger than or equal to 8.');
        }
        if ((!preg_match("/^\\d{1,6}\\.\\d{6}$/", ObjectSerializer::toString($cet)))) {
            throw new \InvalidArgumentException("invalid value for \$cet when calling RequestCreditPortabilityDataProposedContract., must conform to the pattern /^\\d{1,6}\\.\\d{6}$/.");
        }

        $this->container['cet'] = $cet;

        return $this;
    }

    /**
     * Gets amortization_scheduled
     *
     * @return string
     */
    public function getAmortizationScheduled()
    {
        return $this->container['amortization_scheduled'];
    }

    /**
     * Sets amortization_scheduled
     *
     * @param string $amortization_scheduled Sistema de amortização (Vide Enum):  - SAC (Sistema de Amortização Constante): É aquele em que o valor da amortização permanece igual até o final. Os juros cobrados sobre o parcelamento não entram nesta conta.  - PRICE (Sistema Francês de Amortização): As parcelas são fixas do início ao fim do contrato. Ou seja, todas as parcelas terão o mesmo valor, desde a primeira até a última. Nos primeiros pagamentos, a maior parte do valor da prestação corresponde aos juros. Ao longo do tempo, a taxa de juros vai decrescendo. Como o valor da prestação é fixo, com o passar das parcelas, o valor de amortização vai aumentando.  - SAM (Sistema de Amortização Misto): Cada prestação (pagamento) é a média aritmética das prestações respectivas no Sistemas Price e no Sistema de Amortização Constante (SAC).  - SEM SISTEMA DE AMORTIZAÇÃO
     *
     * @return self
     */
    public function setAmortizationScheduled($amortization_scheduled)
    {
        if (is_null($amortization_scheduled)) {
            throw new \InvalidArgumentException('non-nullable amortization_scheduled cannot be null');
        }
        $allowedValues = $this->getAmortizationScheduledAllowableValues();
        if (!in_array($amortization_scheduled, $allowedValues, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid value '%s' for 'amortization_scheduled', must be one of '%s'",
                    $amortization_scheduled,
                    implode("', '", $allowedValues)
                )
            );
        }
        $this->container['amortization_scheduled'] = $amortization_scheduled;

        return $this;
    }

    /**
     * Gets amortization_scheduled_additional_info
     *
     * @return string|null
     */
    public function getAmortizationScheduledAdditionalInfo()
    {
        return $this->container['amortization_scheduled_additional_info'];
    }

    /**
     * Sets amortization_scheduled_additional_info
     *
     * @param string|null $amortization_scheduled_additional_info Informação relativa ao complemento da amortização  [Restrição] Campo de preenchimento obrigatório quando o campo amortizationScheduled for igual `OUTROS`
     *
     * @return self
     */
    public function setAmortizationScheduledAdditionalInfo($amortization_scheduled_additional_info)
    {
        if (is_null($amortization_scheduled_additional_info)) {
            throw new \InvalidArgumentException('non-nullable amortization_scheduled_additional_info cannot be null');
        }
        if ((mb_strlen($amortization_scheduled_additional_info) > 200)) {
            throw new \InvalidArgumentException('invalid length for $amortization_scheduled_additional_info when calling RequestCreditPortabilityDataProposedContract., must be smaller than or equal to 200.');
        }
        if ((!preg_match("/^[^\\s](?:.*[^\\s])?$/", ObjectSerializer::toString($amortization_scheduled_additional_info)))) {
            throw new \InvalidArgumentException("invalid value for \$amortization_scheduled_additional_info when calling RequestCreditPortabilityDataProposedContract., must conform to the pattern /^[^\\s](?:.*[^\\s])?$/.");
        }

        $this->container['amortization_scheduled_additional_info'] = $amortization_scheduled_additional_info;

        return $this;
    }

    /**
     * Gets instalment_periodicity
     *
     * @return string
     */
    public function getInstalmentPeriodicity()
    {
        return $this->container['instalment_periodicity'];
    }

    /**
     * Sets instalment_periodicity
     *
     * @param string $instalment_periodicity Informação relativa à periodicidade regular das parcelas. (Vide Enum) sem periodicidade regular, diário, semanal, quinzenal, mensal, bimestral, trimestral, semestral, anual.
     *
     * @return self
     */
    public function setInstalmentPeriodicity($instalment_periodicity)
    {
        if (is_null($instalment_periodicity)) {
            throw new \InvalidArgumentException('non-nullable instalment_periodicity cannot be null');
        }
        $allowedValues = $this->getInstalmentPeriodicityAllowableValues();
        if (!in_array($instalment_periodicity, $allowedValues, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid value '%s' for 'instalment_periodicity', must be one of '%s'",
                    $instalment_periodicity,
                    implode("', '", $allowedValues)
                )
            );
        }
        $this->container['instalment_periodicity'] = $instalment_periodicity;

        return $this;
    }

    /**
     * Gets total_number_of_installments
     *
     * @return float
     */
    public function getTotalNumberOfInstallments()
    {
        return $this->container['total_number_of_installments'];
    }

    /**
     * Sets total_number_of_installments
     *
     * @param float $total_number_of_installments Total de parcelas, segundo a periodicidade regular das parcelas referente à Modalidade de Crédito informada.
     *
     * @return self
     */
    public function setTotalNumberOfInstallments($total_number_of_installments)
    {
        if (is_null($total_number_of_installments)) {
            throw new \InvalidArgumentException('non-nullable total_number_of_installments cannot be null');
        }

        if (($total_number_of_installments > 999999999)) {
            throw new \InvalidArgumentException('invalid value for $total_number_of_installments when calling RequestCreditPortabilityDataProposedContract., must be smaller than or equal to 999999999.');
        }

        $this->container['total_number_of_installments'] = $total_number_of_installments;

        return $this;
    }

    /**
     * Gets installment_amount
     *
     * @return \OpenAPI\Client\Model\RequestCreditPortabilityDataProposedContractInstallmentAmount
     */
    public function getInstallmentAmount()
    {
        return $this->container['installment_amount'];
    }

    /**
     * Sets installment_amount
     *
     * @param \OpenAPI\Client\Model\RequestCreditPortabilityDataProposedContractInstallmentAmount $installment_amount installment_amount
     *
     * @return self
     */
    public function setInstallmentAmount($installment_amount)
    {
        if (is_null($installment_amount)) {
            throw new \InvalidArgumentException('non-nullable installment_amount cannot be null');
        }
        $this->container['installment_amount'] = $installment_amount;

        return $this;
    }

    /**
     * Gets due_date
     *
     * @return string
     */
    public function getDueDate()
    {
        return $this->container['due_date'];
    }

    /**
     * Sets due_date
     *
     * @param string $due_date Prazo (data de vencimento final) da operação. Especificação RFC-3339.
     *
     * @return self
     */
    public function setDueDate($due_date)
    {
        if (is_null($due_date)) {
            throw new \InvalidArgumentException('non-nullable due_date cannot be null');
        }
        if ((mb_strlen($due_date) > 20)) {
            throw new \InvalidArgumentException('invalid length for $due_date when calling RequestCreditPortabilityDataProposedContract., must be smaller than or equal to 20.');
        }
        if ((mb_strlen($due_date) < 20)) {
            throw new \InvalidArgumentException('invalid length for $due_date when calling RequestCreditPortabilityDataProposedContract., must be bigger than or equal to 20.');
        }
        if ((!preg_match("/^(\\d{4})-(1[0-2]|0?[1-9])-(3[01]|[12][0-9]|0?[1-9])T(?:[01]\\d|2[0123]):(?:[012345]\\d):(?:[012345]\\d)Z$/", ObjectSerializer::toString($due_date)))) {
            throw new \InvalidArgumentException("invalid value for \$due_date when calling RequestCreditPortabilityDataProposedContract., must conform to the pattern /^(\\d{4})-(1[0-2]|0?[1-9])-(3[01]|[12][0-9]|0?[1-9])T(?:[01]\\d|2[0123]):(?:[012345]\\d):(?:[012345]\\d)Z$/.");
        }

        $this->container['due_date'] = $due_date;

        return $this;
    }

    /**
     * Gets contract_amount
     *
     * @return \OpenAPI\Client\Model\RequestCreditPortabilityDataProposedContractContractAmount
     */
    public function getContractAmount()
    {
        return $this->container['contract_amount'];
    }

    /**
     * Sets contract_amount
     *
     * @param \OpenAPI\Client\Model\RequestCreditPortabilityDataProposedContractContractAmount $contract_amount contract_amount
     *
     * @return self
     */
    public function setContractAmount($contract_amount)
    {
        if (is_null($contract_amount)) {
            throw new \InvalidArgumentException('non-nullable contract_amount cannot be null');
        }
        $this->container['contract_amount'] = $contract_amount;

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


