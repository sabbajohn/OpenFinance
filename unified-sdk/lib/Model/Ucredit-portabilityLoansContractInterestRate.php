<?php
/**
 * LoansContractInterestRate
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
 * LoansContractInterestRate Class Doc Comment
 *
 * @category Class
 * @description Objeto que traz o conjunto de informações necessárias para demonstrar a composição das taxas de juros remuneratórios da Modalidade de crédito
 * @package  OpenAPI\Client
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 * @implements \ArrayAccess<string, mixed>
 */
class Ucredit-portabilityLoansContractInterestRate implements ModelInterface, ArrayAccess, \JsonSerializable
{
    public const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'LoansContractInterestRate';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'tax_type' => 'string',
        'interest_rate_type' => 'string',
        'referential_rate_indexer_sub_type' => '\OpenAPI\Client\Model\EnumReferentialRateIndexerSubType',
        'tax_periodicity' => 'string',
        'calculation' => 'string',
        'referential_rate_indexer_type' => 'string',
        'referential_rate_indexer_additional_info' => 'string',
        'pre_fixed_rate' => 'float',
        'post_fixed_rate' => 'float',
        'additional_info' => 'string'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      * @phpstan-var array<string, string|null>
      * @psalm-var array<string, string|null>
      */
    protected static $openAPIFormats = [
        'tax_type' => null,
        'interest_rate_type' => null,
        'referential_rate_indexer_sub_type' => null,
        'tax_periodicity' => null,
        'calculation' => null,
        'referential_rate_indexer_type' => null,
        'referential_rate_indexer_additional_info' => null,
        'pre_fixed_rate' => 'double',
        'post_fixed_rate' => 'double',
        'additional_info' => null
    ];

    /**
      * Array of nullable properties. Used for (de)serialization
      *
      * @var boolean[]
      */
    protected static array $openAPINullables = [
        'tax_type' => false,
        'interest_rate_type' => false,
        'referential_rate_indexer_sub_type' => false,
        'tax_periodicity' => false,
        'calculation' => false,
        'referential_rate_indexer_type' => false,
        'referential_rate_indexer_additional_info' => false,
        'pre_fixed_rate' => false,
        'post_fixed_rate' => false,
        'additional_info' => false
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
        'tax_type' => 'taxType',
        'interest_rate_type' => 'interestRateType',
        'referential_rate_indexer_sub_type' => 'referentialRateIndexerSubType',
        'tax_periodicity' => 'taxPeriodicity',
        'calculation' => 'calculation',
        'referential_rate_indexer_type' => 'referentialRateIndexerType',
        'referential_rate_indexer_additional_info' => 'referentialRateIndexerAdditionalInfo',
        'pre_fixed_rate' => 'preFixedRate',
        'post_fixed_rate' => 'postFixedRate',
        'additional_info' => 'additionalInfo'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'tax_type' => 'setTaxType',
        'interest_rate_type' => 'setInterestRateType',
        'referential_rate_indexer_sub_type' => 'setReferentialRateIndexerSubType',
        'tax_periodicity' => 'setTaxPeriodicity',
        'calculation' => 'setCalculation',
        'referential_rate_indexer_type' => 'setReferentialRateIndexerType',
        'referential_rate_indexer_additional_info' => 'setReferentialRateIndexerAdditionalInfo',
        'pre_fixed_rate' => 'setPreFixedRate',
        'post_fixed_rate' => 'setPostFixedRate',
        'additional_info' => 'setAdditionalInfo'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'tax_type' => 'getTaxType',
        'interest_rate_type' => 'getInterestRateType',
        'referential_rate_indexer_sub_type' => 'getReferentialRateIndexerSubType',
        'tax_periodicity' => 'getTaxPeriodicity',
        'calculation' => 'getCalculation',
        'referential_rate_indexer_type' => 'getReferentialRateIndexerType',
        'referential_rate_indexer_additional_info' => 'getReferentialRateIndexerAdditionalInfo',
        'pre_fixed_rate' => 'getPreFixedRate',
        'post_fixed_rate' => 'getPostFixedRate',
        'additional_info' => 'getAdditionalInfo'
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

    public const TAX_TYPE_NOMINAL = 'NOMINAL';
    public const TAX_TYPE_EFETIVA = 'EFETIVA';
    public const INTEREST_RATE_TYPE_SIMPLES = 'SIMPLES';
    public const INTEREST_RATE_TYPE_COMPOSTO = 'COMPOSTO';
    public const TAX_PERIODICITY_AM = 'AM';
    public const TAX_PERIODICITY_AA = 'AA';
    public const CALCULATION__21_252 = '21/252';
    public const CALCULATION__30_360 = '30/360';
    public const CALCULATION__30_365 = '30/365';
    public const REFERENTIAL_RATE_INDEXER_TYPE_SEM_TIPO_INDEXADOR = 'SEM_TIPO_INDEXADOR';
    public const REFERENTIAL_RATE_INDEXER_TYPE_PRE_FIXADO = 'PRE_FIXADO';
    public const REFERENTIAL_RATE_INDEXER_TYPE_POS_FIXADO = 'POS_FIXADO';
    public const REFERENTIAL_RATE_INDEXER_TYPE_FLUTUANTES = 'FLUTUANTES';
    public const REFERENTIAL_RATE_INDEXER_TYPE_INDICES_PRECOS = 'INDICES_PRECOS';
    public const REFERENTIAL_RATE_INDEXER_TYPE_CREDITO_RURAL = 'CREDITO_RURAL';
    public const REFERENTIAL_RATE_INDEXER_TYPE_OUTROS_INDEXADORES = 'OUTROS_INDEXADORES';

    /**
     * Gets allowable values of the enum
     *
     * @return string[]
     */
    public function getTaxTypeAllowableValues()
    {
        return [
            self::TAX_TYPE_NOMINAL,
            self::TAX_TYPE_EFETIVA,
        ];
    }

    /**
     * Gets allowable values of the enum
     *
     * @return string[]
     */
    public function getInterestRateTypeAllowableValues()
    {
        return [
            self::INTEREST_RATE_TYPE_SIMPLES,
            self::INTEREST_RATE_TYPE_COMPOSTO,
        ];
    }

    /**
     * Gets allowable values of the enum
     *
     * @return string[]
     */
    public function getTaxPeriodicityAllowableValues()
    {
        return [
            self::TAX_PERIODICITY_AM,
            self::TAX_PERIODICITY_AA,
        ];
    }

    /**
     * Gets allowable values of the enum
     *
     * @return string[]
     */
    public function getCalculationAllowableValues()
    {
        return [
            self::CALCULATION__21_252,
            self::CALCULATION__30_360,
            self::CALCULATION__30_365,
        ];
    }

    /**
     * Gets allowable values of the enum
     *
     * @return string[]
     */
    public function getReferentialRateIndexerTypeAllowableValues()
    {
        return [
            self::REFERENTIAL_RATE_INDEXER_TYPE_SEM_TIPO_INDEXADOR,
            self::REFERENTIAL_RATE_INDEXER_TYPE_PRE_FIXADO,
            self::REFERENTIAL_RATE_INDEXER_TYPE_POS_FIXADO,
            self::REFERENTIAL_RATE_INDEXER_TYPE_FLUTUANTES,
            self::REFERENTIAL_RATE_INDEXER_TYPE_INDICES_PRECOS,
            self::REFERENTIAL_RATE_INDEXER_TYPE_CREDITO_RURAL,
            self::REFERENTIAL_RATE_INDEXER_TYPE_OUTROS_INDEXADORES,
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
        $this->setIfExists('tax_type', $data ?? [], null);
        $this->setIfExists('interest_rate_type', $data ?? [], null);
        $this->setIfExists('referential_rate_indexer_sub_type', $data ?? [], null);
        $this->setIfExists('tax_periodicity', $data ?? [], null);
        $this->setIfExists('calculation', $data ?? [], null);
        $this->setIfExists('referential_rate_indexer_type', $data ?? [], null);
        $this->setIfExists('referential_rate_indexer_additional_info', $data ?? [], null);
        $this->setIfExists('pre_fixed_rate', $data ?? [], null);
        $this->setIfExists('post_fixed_rate', $data ?? [], null);
        $this->setIfExists('additional_info', $data ?? [], null);
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

        if ($this->container['tax_type'] === null) {
            $invalidProperties[] = "'tax_type' can't be null";
        }
        $allowedValues = $this->getTaxTypeAllowableValues();
        if (!is_null($this->container['tax_type']) && !in_array($this->container['tax_type'], $allowedValues, true)) {
            $invalidProperties[] = sprintf(
                "invalid value '%s' for 'tax_type', must be one of '%s'",
                $this->container['tax_type'],
                implode("', '", $allowedValues)
            );
        }

        if ($this->container['interest_rate_type'] === null) {
            $invalidProperties[] = "'interest_rate_type' can't be null";
        }
        $allowedValues = $this->getInterestRateTypeAllowableValues();
        if (!is_null($this->container['interest_rate_type']) && !in_array($this->container['interest_rate_type'], $allowedValues, true)) {
            $invalidProperties[] = sprintf(
                "invalid value '%s' for 'interest_rate_type', must be one of '%s'",
                $this->container['interest_rate_type'],
                implode("', '", $allowedValues)
            );
        }

        if ($this->container['tax_periodicity'] === null) {
            $invalidProperties[] = "'tax_periodicity' can't be null";
        }
        $allowedValues = $this->getTaxPeriodicityAllowableValues();
        if (!is_null($this->container['tax_periodicity']) && !in_array($this->container['tax_periodicity'], $allowedValues, true)) {
            $invalidProperties[] = sprintf(
                "invalid value '%s' for 'tax_periodicity', must be one of '%s'",
                $this->container['tax_periodicity'],
                implode("', '", $allowedValues)
            );
        }

        if ($this->container['calculation'] === null) {
            $invalidProperties[] = "'calculation' can't be null";
        }
        $allowedValues = $this->getCalculationAllowableValues();
        if (!is_null($this->container['calculation']) && !in_array($this->container['calculation'], $allowedValues, true)) {
            $invalidProperties[] = sprintf(
                "invalid value '%s' for 'calculation', must be one of '%s'",
                $this->container['calculation'],
                implode("', '", $allowedValues)
            );
        }

        if ($this->container['referential_rate_indexer_type'] === null) {
            $invalidProperties[] = "'referential_rate_indexer_type' can't be null";
        }
        $allowedValues = $this->getReferentialRateIndexerTypeAllowableValues();
        if (!is_null($this->container['referential_rate_indexer_type']) && !in_array($this->container['referential_rate_indexer_type'], $allowedValues, true)) {
            $invalidProperties[] = sprintf(
                "invalid value '%s' for 'referential_rate_indexer_type', must be one of '%s'",
                $this->container['referential_rate_indexer_type'],
                implode("', '", $allowedValues)
            );
        }

        if (!is_null($this->container['referential_rate_indexer_additional_info']) && (mb_strlen($this->container['referential_rate_indexer_additional_info']) > 140)) {
            $invalidProperties[] = "invalid value for 'referential_rate_indexer_additional_info', the character length must be smaller than or equal to 140.";
        }

        if (!is_null($this->container['referential_rate_indexer_additional_info']) && !preg_match("/^[^\\s](?:.*[^\\s])?$/", $this->container['referential_rate_indexer_additional_info'])) {
            $invalidProperties[] = "invalid value for 'referential_rate_indexer_additional_info', must be conform to the pattern /^[^\\s](?:.*[^\\s])?$/.";
        }

        if ($this->container['pre_fixed_rate'] === null) {
            $invalidProperties[] = "'pre_fixed_rate' can't be null";
        }
        if ((mb_strlen($this->container['pre_fixed_rate']) > 9)) {
            $invalidProperties[] = "invalid value for 'pre_fixed_rate', the character length must be smaller than or equal to 9.";
        }

        if ((mb_strlen($this->container['pre_fixed_rate']) < 8)) {
            $invalidProperties[] = "invalid value for 'pre_fixed_rate', the character length must be bigger than or equal to 8.";
        }

        if (!preg_match("/^\\d{1,2}\\.\\d{6}$/", $this->container['pre_fixed_rate'])) {
            $invalidProperties[] = "invalid value for 'pre_fixed_rate', must be conform to the pattern /^\\d{1,2}\\.\\d{6}$/.";
        }

        if ($this->container['post_fixed_rate'] === null) {
            $invalidProperties[] = "'post_fixed_rate' can't be null";
        }
        if ((mb_strlen($this->container['post_fixed_rate']) > 9)) {
            $invalidProperties[] = "invalid value for 'post_fixed_rate', the character length must be smaller than or equal to 9.";
        }

        if ((mb_strlen($this->container['post_fixed_rate']) < 8)) {
            $invalidProperties[] = "invalid value for 'post_fixed_rate', the character length must be bigger than or equal to 8.";
        }

        if (!preg_match("/^\\d{1,2}\\.\\d{6}$/", $this->container['post_fixed_rate'])) {
            $invalidProperties[] = "invalid value for 'post_fixed_rate', must be conform to the pattern /^\\d{1,2}\\.\\d{6}$/.";
        }

        if (!is_null($this->container['additional_info']) && (mb_strlen($this->container['additional_info']) > 1200)) {
            $invalidProperties[] = "invalid value for 'additional_info', the character length must be smaller than or equal to 1200.";
        }

        if (!is_null($this->container['additional_info']) && !preg_match("/^[^\\s](?:.*[^\\s])?$/", $this->container['additional_info'])) {
            $invalidProperties[] = "invalid value for 'additional_info', must be conform to the pattern /^[^\\s](?:.*[^\\s])?$/.";
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
     * Gets tax_type
     *
     * @return string
     */
    public function getTaxType()
    {
        return $this->container['tax_type'];
    }

    /**
     * Sets tax_type
     *
     * @param string $tax_type \"Tipo de Taxa (vide  Enum) - NOMINAL (taxa nominal é uma taxa de juros em que a unidade referencial não coincide com a unidade de tempo da capitalização. Ela é sempre fornecida em termos anuais, e seus períodos de capitalização podem ser diários, mensais, trimestrais ou semestrais. p.ex. Uma taxa de 12% ao ano com capitalização mensal) - EFETIVA (É a taxa de juros em que a unidade referencial coincide com a unidade de tempo da capitalização. Como as unidades de medida de tempo da taxa de juros e dos períodos de capitalização são iguais, usa-se exemplos simples como 1% ao mês, 60% ao ano)\"
     *
     * @return self
     */
    public function setTaxType($tax_type)
    {
        if (is_null($tax_type)) {
            throw new \InvalidArgumentException('non-nullable tax_type cannot be null');
        }
        $allowedValues = $this->getTaxTypeAllowableValues();
        if (!in_array($tax_type, $allowedValues, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid value '%s' for 'tax_type', must be one of '%s'",
                    $tax_type,
                    implode("', '", $allowedValues)
                )
            );
        }
        $this->container['tax_type'] = $tax_type;

        return $this;
    }

    /**
     * Gets interest_rate_type
     *
     * @return string
     */
    public function getInterestRateType()
    {
        return $this->container['interest_rate_type'];
    }

    /**
     * Sets interest_rate_type
     *
     * @param string $interest_rate_type \"Tipo de Juros  (vide  Enum) - SIMPLES (aplicada/cobrada sempre sobre o capital inicial, que é o valor emprestado/investido. Não há cobrança de juros sobre juros acumulados no(s) período(s) anterior(es). Exemplo: em um empréstimo de R$1.000, com taxa de juros simples de 8% a.a., com duração de 2 anos, o total de juros será R$80 no primeiro ano e R$ 80 no segundo ano. Ao final do contrato, o tomador irá devolver o principal e os juros simples de cada ano: R$1.000+R$80+R$80=R$1.160) - COMPOSTO (para cada período do contrato (diário, mensal, anual etc.), há um “novo capital” para a cobrança da taxa de juros contratada. Esse “novo capital” é a soma do capital e do juro cobrado no período anterior. Exemplo: em um empréstimo de R$1.000, com taxa de juros composta de 8% a.a., com duração de 2 anos, o total de juros será R$80 no primeiro ano. No segundo ano, os juros vão ser somados ao capital (R$1.000 + R$ 80 = R$ 1.080), resultando em juros de R$ 86 (8%de R$ 1.080))\"
     *
     * @return self
     */
    public function setInterestRateType($interest_rate_type)
    {
        if (is_null($interest_rate_type)) {
            throw new \InvalidArgumentException('non-nullable interest_rate_type cannot be null');
        }
        $allowedValues = $this->getInterestRateTypeAllowableValues();
        if (!in_array($interest_rate_type, $allowedValues, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid value '%s' for 'interest_rate_type', must be one of '%s'",
                    $interest_rate_type,
                    implode("', '", $allowedValues)
                )
            );
        }
        $this->container['interest_rate_type'] = $interest_rate_type;

        return $this;
    }

    /**
     * Gets referential_rate_indexer_sub_type
     *
     * @return \OpenAPI\Client\Model\EnumReferentialRateIndexerSubType|null
     */
    public function getReferentialRateIndexerSubType()
    {
        return $this->container['referential_rate_indexer_sub_type'];
    }

    /**
     * Sets referential_rate_indexer_sub_type
     *
     * @param \OpenAPI\Client\Model\EnumReferentialRateIndexerSubType|null $referential_rate_indexer_sub_type referential_rate_indexer_sub_type
     *
     * @return self
     */
    public function setReferentialRateIndexerSubType($referential_rate_indexer_sub_type)
    {
        if (is_null($referential_rate_indexer_sub_type)) {
            throw new \InvalidArgumentException('non-nullable referential_rate_indexer_sub_type cannot be null');
        }
        $this->container['referential_rate_indexer_sub_type'] = $referential_rate_indexer_sub_type;

        return $this;
    }

    /**
     * Gets tax_periodicity
     *
     * @return string
     */
    public function getTaxPeriodicity()
    {
        return $this->container['tax_periodicity'];
    }

    /**
     * Sets tax_periodicity
     *
     * @param string $tax_periodicity \"Periodicidade da taxa . (Vide  Enum) a.m - ao mês a.a. - ao ano\"
     *
     * @return self
     */
    public function setTaxPeriodicity($tax_periodicity)
    {
        if (is_null($tax_periodicity)) {
            throw new \InvalidArgumentException('non-nullable tax_periodicity cannot be null');
        }
        $allowedValues = $this->getTaxPeriodicityAllowableValues();
        if (!in_array($tax_periodicity, $allowedValues, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid value '%s' for 'tax_periodicity', must be one of '%s'",
                    $tax_periodicity,
                    implode("', '", $allowedValues)
                )
            );
        }
        $this->container['tax_periodicity'] = $tax_periodicity;

        return $this;
    }

    /**
     * Gets calculation
     *
     * @return string
     */
    public function getCalculation()
    {
        return $this->container['calculation'];
    }

    /**
     * Sets calculation
     *
     * @param string $calculation Base de cálculo
     *
     * @return self
     */
    public function setCalculation($calculation)
    {
        if (is_null($calculation)) {
            throw new \InvalidArgumentException('non-nullable calculation cannot be null');
        }
        $allowedValues = $this->getCalculationAllowableValues();
        if (!in_array($calculation, $allowedValues, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid value '%s' for 'calculation', must be one of '%s'",
                    $calculation,
                    implode("', '", $allowedValues)
                )
            );
        }
        $this->container['calculation'] = $calculation;

        return $this;
    }

    /**
     * Gets referential_rate_indexer_type
     *
     * @return string
     */
    public function getReferentialRateIndexerType()
    {
        return $this->container['referential_rate_indexer_type'];
    }

    /**
     * Sets referential_rate_indexer_type
     *
     * @param string $referential_rate_indexer_type \"Tipos de taxas referenciais ou indexadores, conforme Anexo 5: Taxa referencial ou Indexador (Indx), do Documento 3040\"
     *
     * @return self
     */
    public function setReferentialRateIndexerType($referential_rate_indexer_type)
    {
        if (is_null($referential_rate_indexer_type)) {
            throw new \InvalidArgumentException('non-nullable referential_rate_indexer_type cannot be null');
        }
        $allowedValues = $this->getReferentialRateIndexerTypeAllowableValues();
        if (!in_array($referential_rate_indexer_type, $allowedValues, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid value '%s' for 'referential_rate_indexer_type', must be one of '%s'",
                    $referential_rate_indexer_type,
                    implode("', '", $allowedValues)
                )
            );
        }
        $this->container['referential_rate_indexer_type'] = $referential_rate_indexer_type;

        return $this;
    }

    /**
     * Gets referential_rate_indexer_additional_info
     *
     * @return string|null
     */
    public function getReferentialRateIndexerAdditionalInfo()
    {
        return $this->container['referential_rate_indexer_additional_info'];
    }

    /**
     * Sets referential_rate_indexer_additional_info
     *
     * @param string|null $referential_rate_indexer_additional_info Campo livre para complementar a informação relativa ao Tipo de taxa referencial ou indexador. [Restrição] Obrigatório para complementar a informação relativa ao Tipo de taxa referencial ou indexador, quando selecionado o tipo ou subtipo `OUTRO`.
     *
     * @return self
     */
    public function setReferentialRateIndexerAdditionalInfo($referential_rate_indexer_additional_info)
    {
        if (is_null($referential_rate_indexer_additional_info)) {
            throw new \InvalidArgumentException('non-nullable referential_rate_indexer_additional_info cannot be null');
        }
        if ((mb_strlen($referential_rate_indexer_additional_info) > 140)) {
            throw new \InvalidArgumentException('invalid length for $referential_rate_indexer_additional_info when calling LoansContractInterestRate., must be smaller than or equal to 140.');
        }
        if ((!preg_match("/^[^\\s](?:.*[^\\s])?$/", ObjectSerializer::toString($referential_rate_indexer_additional_info)))) {
            throw new \InvalidArgumentException("invalid value for \$referential_rate_indexer_additional_info when calling LoansContractInterestRate., must conform to the pattern /^[^\\s](?:.*[^\\s])?$/.");
        }

        $this->container['referential_rate_indexer_additional_info'] = $referential_rate_indexer_additional_info;

        return $this;
    }

    /**
     * Gets pre_fixed_rate
     *
     * @return float
     */
    public function getPreFixedRate()
    {
        return $this->container['pre_fixed_rate'];
    }

    /**
     * Sets pre_fixed_rate
     *
     * @param float $pre_fixed_rate Taxa pré fixada aplicada sob o contrato da modalidade crédito. p.ex. 0.014500. O preenchimento deve respeitar as 6 casas decimais, mesmo que venham preenchidas com zeros(representação de porcentagem p.ex: 0.150000. Este valor representa 15%. O valor 1 representa 100%). Preencher o campo não aplicável ao contrato com zeros, seguindo o pattern (0.000000).
     *
     * @return self
     */
    public function setPreFixedRate($pre_fixed_rate)
    {
        if (is_null($pre_fixed_rate)) {
            throw new \InvalidArgumentException('non-nullable pre_fixed_rate cannot be null');
        }
        if ((mb_strlen($pre_fixed_rate) > 9)) {
            throw new \InvalidArgumentException('invalid length for $pre_fixed_rate when calling LoansContractInterestRate., must be smaller than or equal to 9.');
        }
        if ((mb_strlen($pre_fixed_rate) < 8)) {
            throw new \InvalidArgumentException('invalid length for $pre_fixed_rate when calling LoansContractInterestRate., must be bigger than or equal to 8.');
        }
        if ((!preg_match("/^\\d{1,2}\\.\\d{6}$/", ObjectSerializer::toString($pre_fixed_rate)))) {
            throw new \InvalidArgumentException("invalid value for \$pre_fixed_rate when calling LoansContractInterestRate., must conform to the pattern /^\\d{1,2}\\.\\d{6}$/.");
        }

        $this->container['pre_fixed_rate'] = $pre_fixed_rate;

        return $this;
    }

    /**
     * Gets post_fixed_rate
     *
     * @return float
     */
    public function getPostFixedRate()
    {
        return $this->container['post_fixed_rate'];
    }

    /**
     * Sets post_fixed_rate
     *
     * @param float $post_fixed_rate Taxa pós fixada aplicada sob o contrato da modalidade crédito. p.ex. 0.0045 .O preenchimento deve respeitar as 6 casas decimais, mesmo que venham preenchidas com zeros (representação de porcentagem p.ex: 0.1500. Este valor representa 15%. O valor 1 representa 100%). Preencher o campo não aplicável ao contrato com zeros, seguindo o pattern (0.000000)
     *
     * @return self
     */
    public function setPostFixedRate($post_fixed_rate)
    {
        if (is_null($post_fixed_rate)) {
            throw new \InvalidArgumentException('non-nullable post_fixed_rate cannot be null');
        }
        if ((mb_strlen($post_fixed_rate) > 9)) {
            throw new \InvalidArgumentException('invalid length for $post_fixed_rate when calling LoansContractInterestRate., must be smaller than or equal to 9.');
        }
        if ((mb_strlen($post_fixed_rate) < 8)) {
            throw new \InvalidArgumentException('invalid length for $post_fixed_rate when calling LoansContractInterestRate., must be bigger than or equal to 8.');
        }
        if ((!preg_match("/^\\d{1,2}\\.\\d{6}$/", ObjectSerializer::toString($post_fixed_rate)))) {
            throw new \InvalidArgumentException("invalid value for \$post_fixed_rate when calling LoansContractInterestRate., must conform to the pattern /^\\d{1,2}\\.\\d{6}$/.");
        }

        $this->container['post_fixed_rate'] = $post_fixed_rate;

        return $this;
    }

    /**
     * Gets additional_info
     *
     * @return string|null
     */
    public function getAdditionalInfo()
    {
        return $this->container['additional_info'];
    }

    /**
     * Sets additional_info
     *
     * @param string|null $additional_info Texto com informações adicionais sobre a composição das taxas de juros pactuadas.   [Restrição] Caso a instituição possua a informação para compartilhamento, esta deverá ser informada.
     *
     * @return self
     */
    public function setAdditionalInfo($additional_info)
    {
        if (is_null($additional_info)) {
            throw new \InvalidArgumentException('non-nullable additional_info cannot be null');
        }
        if ((mb_strlen($additional_info) > 1200)) {
            throw new \InvalidArgumentException('invalid length for $additional_info when calling LoansContractInterestRate., must be smaller than or equal to 1200.');
        }
        if ((!preg_match("/^[^\\s](?:.*[^\\s])?$/", ObjectSerializer::toString($additional_info)))) {
            throw new \InvalidArgumentException("invalid value for \$additional_info when calling LoansContractInterestRate., must conform to the pattern /^[^\\s](?:.*[^\\s])?$/.");
        }

        $this->container['additional_info'] = $additional_info;

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


