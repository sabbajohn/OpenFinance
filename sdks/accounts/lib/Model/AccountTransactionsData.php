<?php
/**
 * AccountTransactionsData
 *
 * PHP version 8.1
 *
 * @category Class
 * @package  OpenAPI\Client
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 */

/**
 * API Accounts - Open Finance Brasil
 *
 * API de contas de depósito à vista, contas de poupança e contas pré-pagas do Open Finance Brasil – Fase 2. API que retorna informações de contas de depósito à vista, contas de poupança e contas de pagamento pré-pagas mantidas nas instituições transmissoras por seus clientes, incluindo dados de identificação da conta, saldos, limites e transações.\\ Não possui segregação entre pessoa natural e pessoa jurídica.\\ Requer consentimento do cliente para todos os `endpoints`.  # Orientações A `Role`  do diretório de participantes relacionada à presente API é a `DADOS`.\\ Para todos os `endpoints` desta API é previsto o envio de um `token` através do header `Authorization`.\\ Este token deverá estar relacionado ao consentimento (`consentId`) mantido na instituição transmissora dos dados, o qual permitirá a pesquisa e retorno, na API em questão, dos  dados relacionados ao `consentId` específico relacionado.\\ Os dados serão devolvidos na consulta desde que o `consentId` relacionado corresponda a um consentimento válido e com o status `AUTHORISED`.\\ É também necessário que o recurso em questão (conta, contrato, etc) esteja disponível na instituição transmissora (ou seja, sem boqueios de qualquer natureza e com todas as autorizações/consentimentos já autorizados).\\ Além disso as `permissions` necessárias deverão ter sido solicitadas quando da criação do consentimento relacionado (`consentId`).\\ Relacionamos a seguir as `permissions` necessárias para a consulta de dados em cada `endpoint` da presente API.  ## Permissions necessárias para a API Accounts  Para cada um dos paths desta API, além dos escopos (`scopes`) indicados existem `permissions` que deverão ser observadas:  ### `/accounts`   - permissions:     - GET: **ACCOUNTS_READ** ### `/accounts/{accountId}`   - permissions:     - GET: **ACCOUNTS_READ** ### `/accounts/{accountId}/balances`   - permissions:     - GET: **ACCOUNTS_BALANCES_READ** ### `/accounts/{accountId}/transactions`   - permissions:     - GET: **ACCOUNTS_TRANSACTIONS_READ** ### `/accounts/{accountId}/transactions-current`   - permissions:     - GET: **ACCOUNTS_TRANSACTIONS_READ** ### `/accounts/{accountId}/overdraft-limits`   - permissions:     - GET: **ACCOUNTS_OVERDRAFT_LIMITS_READ**  ## Data de imutabilidade por tipo de transação​ O identificador de transações de contas é de envio obrigatório no Open Finance Brasil. De acordo com o tipo da transação deve haver o envio de um identificador único, estável e imutável em D0 ou D+1, conforme tabela abaixo ``` |---------------------------------------|-------------------------|-----------------------| | Tipo de Transação                     | Data da Obrigatoriedade | Data da Imutabilidade | |---------------------------------------|-------------------------|-----------------------| | TED                                   | DO                      | DO                    | |---------------------------------------|-------------------------|-----------------------| | PIX                                   | DO                      | DO                    | |---------------------------------------|-------------------------|-----------------------| | TRANSFERENCIA MESMA INSTITUIÇÃO (TEF) | DO                      | DO                    | |---------------------------------------|-------------------------|-----------------------| | TARIFA SERVIÇOS AVULSOS               | DO                      | DO                    | |---------------------------------------|-------------------------|-----------------------| | FOLHA DE PAGAMENTO                    | DO                      | DO                    | |---------------------------------------|-------------------------|-----------------------| | DOC                                   | DO                      | D+1                   | |---------------------------------------|-------------------------|-----------------------| | BOLETO                                | DO                      | D+1                   | |---------------------------------------|-------------------------|-----------------------| | CONVÊNIO ARRECADAÇÃO                  | DO                      | D+1                   | |---------------------------------------|-------------------------|-----------------------| | PACOTE TARIFA SERVIÇOS                | DO                      | D+1                   | |---------------------------------------|-------------------------|-----------------------| | DEPÓSITO                              | DO                      | D+1                   | |---------------------------------------|-------------------------|-----------------------| | SAQUE                                 | DO                      | D+1                   | |---------------------------------------|-------------------------|-----------------------| | CARTÃO                                | DO                      | D+1                   | |---------------------------------------|-------------------------|-----------------------| | ENCARGOS JUROS CHEQUE ESPECIAL        | DO                      | D+1                   | |---------------------------------------|-------------------------|-----------------------| | RENDIMENTO APLICAÇÃO FINANCEIRA       | DO                      | D+1                   | |---------------------------------------|-------------------------|-----------------------| | PORTABILIDADE SALÁRIO                 | DO                      | D+1                   | |---------------------------------------|-------------------------|-----------------------| | RESGATE APLICAÇÃO FINANCEIRA          | DO                      | D+1                   | |---------------------------------------|-------------------------|-----------------------| | OPERAÇÃO DE CRÉDITO                   | DO                      | D+1                   | |---------------------------------------|-------------------------|-----------------------| | OUTROS                                | DO                      | D+1                   | |---------------------------------------|-------------------------|-----------------------| ```  Para consultar as regras aplicáveis ao comportamento do transacionID de acordo com o status da transação, consultar a página [Orientações - Contas](https://openfinancebrasil.atlassian.net/wiki/spaces/OF/pages/193658890)
 *
 * The version of the OpenAPI document: 2.4.2
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
 * AccountTransactionsData Class Doc Comment
 *
 * @category Class
 * @package  OpenAPI\Client
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 * @implements \ArrayAccess<string, mixed>
 */
class AccountTransactionsData implements ModelInterface, ArrayAccess, \JsonSerializable
{
    public const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'AccountTransactionsData';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'transaction_id' => 'string',
        'completed_authorised_payment_type' => '\OpenAPI\Client\Model\EnumCompletedAuthorisedPaymentIndicator',
        'credit_debit_type' => '\OpenAPI\Client\Model\EnumCreditDebitIndicator',
        'transaction_name' => 'string',
        'type' => '\OpenAPI\Client\Model\EnumTransactionTypes',
        'transaction_amount' => '\OpenAPI\Client\Model\AccountTransactionsDataAmount',
        'transaction_date_time' => 'string',
        'partie_cnpj_cpf' => 'string',
        'partie_person_type' => '\OpenAPI\Client\Model\EnumPartiePersonType',
        'partie_compe_code' => 'string',
        'partie_branch_code' => 'string',
        'partie_number' => 'string',
        'partie_check_digit' => 'string'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      * @phpstan-var array<string, string|null>
      * @psalm-var array<string, string|null>
      */
    protected static $openAPIFormats = [
        'transaction_id' => null,
        'completed_authorised_payment_type' => null,
        'credit_debit_type' => null,
        'transaction_name' => null,
        'type' => null,
        'transaction_amount' => null,
        'transaction_date_time' => null,
        'partie_cnpj_cpf' => null,
        'partie_person_type' => null,
        'partie_compe_code' => null,
        'partie_branch_code' => null,
        'partie_number' => null,
        'partie_check_digit' => null
    ];

    /**
      * Array of nullable properties. Used for (de)serialization
      *
      * @var boolean[]
      */
    protected static array $openAPINullables = [
        'transaction_id' => false,
        'completed_authorised_payment_type' => false,
        'credit_debit_type' => false,
        'transaction_name' => false,
        'type' => false,
        'transaction_amount' => false,
        'transaction_date_time' => false,
        'partie_cnpj_cpf' => false,
        'partie_person_type' => false,
        'partie_compe_code' => false,
        'partie_branch_code' => false,
        'partie_number' => false,
        'partie_check_digit' => false
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
        'transaction_id' => 'transactionId',
        'completed_authorised_payment_type' => 'completedAuthorisedPaymentType',
        'credit_debit_type' => 'creditDebitType',
        'transaction_name' => 'transactionName',
        'type' => 'type',
        'transaction_amount' => 'transactionAmount',
        'transaction_date_time' => 'transactionDateTime',
        'partie_cnpj_cpf' => 'partieCnpjCpf',
        'partie_person_type' => 'partiePersonType',
        'partie_compe_code' => 'partieCompeCode',
        'partie_branch_code' => 'partieBranchCode',
        'partie_number' => 'partieNumber',
        'partie_check_digit' => 'partieCheckDigit'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'transaction_id' => 'setTransactionId',
        'completed_authorised_payment_type' => 'setCompletedAuthorisedPaymentType',
        'credit_debit_type' => 'setCreditDebitType',
        'transaction_name' => 'setTransactionName',
        'type' => 'setType',
        'transaction_amount' => 'setTransactionAmount',
        'transaction_date_time' => 'setTransactionDateTime',
        'partie_cnpj_cpf' => 'setPartieCnpjCpf',
        'partie_person_type' => 'setPartiePersonType',
        'partie_compe_code' => 'setPartieCompeCode',
        'partie_branch_code' => 'setPartieBranchCode',
        'partie_number' => 'setPartieNumber',
        'partie_check_digit' => 'setPartieCheckDigit'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'transaction_id' => 'getTransactionId',
        'completed_authorised_payment_type' => 'getCompletedAuthorisedPaymentType',
        'credit_debit_type' => 'getCreditDebitType',
        'transaction_name' => 'getTransactionName',
        'type' => 'getType',
        'transaction_amount' => 'getTransactionAmount',
        'transaction_date_time' => 'getTransactionDateTime',
        'partie_cnpj_cpf' => 'getPartieCnpjCpf',
        'partie_person_type' => 'getPartiePersonType',
        'partie_compe_code' => 'getPartieCompeCode',
        'partie_branch_code' => 'getPartieBranchCode',
        'partie_number' => 'getPartieNumber',
        'partie_check_digit' => 'getPartieCheckDigit'
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
        $this->setIfExists('transaction_id', $data ?? [], null);
        $this->setIfExists('completed_authorised_payment_type', $data ?? [], null);
        $this->setIfExists('credit_debit_type', $data ?? [], null);
        $this->setIfExists('transaction_name', $data ?? [], null);
        $this->setIfExists('type', $data ?? [], null);
        $this->setIfExists('transaction_amount', $data ?? [], null);
        $this->setIfExists('transaction_date_time', $data ?? [], null);
        $this->setIfExists('partie_cnpj_cpf', $data ?? [], null);
        $this->setIfExists('partie_person_type', $data ?? [], null);
        $this->setIfExists('partie_compe_code', $data ?? [], null);
        $this->setIfExists('partie_branch_code', $data ?? [], null);
        $this->setIfExists('partie_number', $data ?? [], null);
        $this->setIfExists('partie_check_digit', $data ?? [], null);
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

        if ($this->container['transaction_id'] === null) {
            $invalidProperties[] = "'transaction_id' can't be null";
        }
        if ((mb_strlen($this->container['transaction_id']) > 100)) {
            $invalidProperties[] = "invalid value for 'transaction_id', the character length must be smaller than or equal to 100.";
        }

        if ((mb_strlen($this->container['transaction_id']) < 1)) {
            $invalidProperties[] = "invalid value for 'transaction_id', the character length must be bigger than or equal to 1.";
        }

        if (!preg_match("/^[a-zA-Z0-9][a-zA-Z0-9-]{0,99}$/", $this->container['transaction_id'])) {
            $invalidProperties[] = "invalid value for 'transaction_id', must be conform to the pattern /^[a-zA-Z0-9][a-zA-Z0-9-]{0,99}$/.";
        }

        if ($this->container['completed_authorised_payment_type'] === null) {
            $invalidProperties[] = "'completed_authorised_payment_type' can't be null";
        }
        if ($this->container['credit_debit_type'] === null) {
            $invalidProperties[] = "'credit_debit_type' can't be null";
        }
        if ($this->container['transaction_name'] === null) {
            $invalidProperties[] = "'transaction_name' can't be null";
        }
        if ((mb_strlen($this->container['transaction_name']) > 200)) {
            $invalidProperties[] = "invalid value for 'transaction_name', the character length must be smaller than or equal to 200.";
        }

        if (!preg_match("/[\\w\\W\\s]*/", $this->container['transaction_name'])) {
            $invalidProperties[] = "invalid value for 'transaction_name', must be conform to the pattern /[\\w\\W\\s]*/.";
        }

        if ($this->container['type'] === null) {
            $invalidProperties[] = "'type' can't be null";
        }
        if ($this->container['transaction_amount'] === null) {
            $invalidProperties[] = "'transaction_amount' can't be null";
        }
        if ($this->container['transaction_date_time'] === null) {
            $invalidProperties[] = "'transaction_date_time' can't be null";
        }
        if ((mb_strlen($this->container['transaction_date_time']) > 24)) {
            $invalidProperties[] = "invalid value for 'transaction_date_time', the character length must be smaller than or equal to 24.";
        }

        if (!preg_match("/(^(\\d{4})-(1[0-2]|0?[1-9])-(3[01]|[12][0-9]|0?[1-9])T(?:[01]\\d|2[0123]):(?:[012345]\\d):(?:[012345]\\d)\\.(?:[0-9]){3}Z$)/", $this->container['transaction_date_time'])) {
            $invalidProperties[] = "invalid value for 'transaction_date_time', must be conform to the pattern /(^(\\d{4})-(1[0-2]|0?[1-9])-(3[01]|[12][0-9]|0?[1-9])T(?:[01]\\d|2[0123]):(?:[012345]\\d):(?:[012345]\\d)\\.(?:[0-9]){3}Z$)/.";
        }

        if (!is_null($this->container['partie_cnpj_cpf']) && (mb_strlen($this->container['partie_cnpj_cpf']) > 14)) {
            $invalidProperties[] = "invalid value for 'partie_cnpj_cpf', the character length must be smaller than or equal to 14.";
        }

        if (!is_null($this->container['partie_cnpj_cpf']) && !preg_match("/^\\d{11}$|^\\d{14}$/", $this->container['partie_cnpj_cpf'])) {
            $invalidProperties[] = "invalid value for 'partie_cnpj_cpf', must be conform to the pattern /^\\d{11}$|^\\d{14}$/.";
        }

        if (!is_null($this->container['partie_compe_code']) && (mb_strlen($this->container['partie_compe_code']) > 3)) {
            $invalidProperties[] = "invalid value for 'partie_compe_code', the character length must be smaller than or equal to 3.";
        }

        if (!is_null($this->container['partie_compe_code']) && !preg_match("/^\\d{3}$/", $this->container['partie_compe_code'])) {
            $invalidProperties[] = "invalid value for 'partie_compe_code', must be conform to the pattern /^\\d{3}$/.";
        }

        if (!is_null($this->container['partie_branch_code']) && (mb_strlen($this->container['partie_branch_code']) > 4)) {
            $invalidProperties[] = "invalid value for 'partie_branch_code', the character length must be smaller than or equal to 4.";
        }

        if (!is_null($this->container['partie_branch_code']) && !preg_match("/^\\d{4}$/", $this->container['partie_branch_code'])) {
            $invalidProperties[] = "invalid value for 'partie_branch_code', must be conform to the pattern /^\\d{4}$/.";
        }

        if (!is_null($this->container['partie_number']) && (mb_strlen($this->container['partie_number']) > 20)) {
            $invalidProperties[] = "invalid value for 'partie_number', the character length must be smaller than or equal to 20.";
        }

        if (!is_null($this->container['partie_number']) && !preg_match("/^\\d{8,20}$/", $this->container['partie_number'])) {
            $invalidProperties[] = "invalid value for 'partie_number', must be conform to the pattern /^\\d{8,20}$/.";
        }

        if (!is_null($this->container['partie_check_digit']) && (mb_strlen($this->container['partie_check_digit']) > 1)) {
            $invalidProperties[] = "invalid value for 'partie_check_digit', the character length must be smaller than or equal to 1.";
        }

        if (!is_null($this->container['partie_check_digit']) && !preg_match("/[\\w\\W\\s]*/", $this->container['partie_check_digit'])) {
            $invalidProperties[] = "invalid value for 'partie_check_digit', must be conform to the pattern /[\\w\\W\\s]*/.";
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
     * Gets transaction_id
     *
     * @return string
     */
    public function getTransactionId()
    {
        return $this->container['transaction_id'];
    }

    /**
     * Sets transaction_id
     *
     * @param string $transaction_id Código ou identificador único prestado pela instituição que mantém a conta para representar a transação individual.  O ideal é que o `transactionId` seja imutável.  No entanto, o `transactionId` deve obedecer, no mínimo, as regras de imutabilidade propostas conforme tabela “Data de imutabilidade por tipo de transação” presente nas orientações desta API.
     *
     * @return self
     */
    public function setTransactionId($transaction_id)
    {
        if (is_null($transaction_id)) {
            throw new \InvalidArgumentException('non-nullable transaction_id cannot be null');
        }
        if ((mb_strlen($transaction_id) > 100)) {
            throw new \InvalidArgumentException('invalid length for $transaction_id when calling AccountTransactionsData., must be smaller than or equal to 100.');
        }
        if ((mb_strlen($transaction_id) < 1)) {
            throw new \InvalidArgumentException('invalid length for $transaction_id when calling AccountTransactionsData., must be bigger than or equal to 1.');
        }
        if ((!preg_match("/^[a-zA-Z0-9][a-zA-Z0-9-]{0,99}$/", ObjectSerializer::toString($transaction_id)))) {
            throw new \InvalidArgumentException("invalid value for \$transaction_id when calling AccountTransactionsData., must conform to the pattern /^[a-zA-Z0-9][a-zA-Z0-9-]{0,99}$/.");
        }

        $this->container['transaction_id'] = $transaction_id;

        return $this;
    }

    /**
     * Gets completed_authorised_payment_type
     *
     * @return \OpenAPI\Client\Model\EnumCompletedAuthorisedPaymentIndicator
     */
    public function getCompletedAuthorisedPaymentType()
    {
        return $this->container['completed_authorised_payment_type'];
    }

    /**
     * Sets completed_authorised_payment_type
     *
     * @param \OpenAPI\Client\Model\EnumCompletedAuthorisedPaymentIndicator $completed_authorised_payment_type completed_authorised_payment_type
     *
     * @return self
     */
    public function setCompletedAuthorisedPaymentType($completed_authorised_payment_type)
    {
        if (is_null($completed_authorised_payment_type)) {
            throw new \InvalidArgumentException('non-nullable completed_authorised_payment_type cannot be null');
        }
        $this->container['completed_authorised_payment_type'] = $completed_authorised_payment_type;

        return $this;
    }

    /**
     * Gets credit_debit_type
     *
     * @return \OpenAPI\Client\Model\EnumCreditDebitIndicator
     */
    public function getCreditDebitType()
    {
        return $this->container['credit_debit_type'];
    }

    /**
     * Sets credit_debit_type
     *
     * @param \OpenAPI\Client\Model\EnumCreditDebitIndicator $credit_debit_type credit_debit_type
     *
     * @return self
     */
    public function setCreditDebitType($credit_debit_type)
    {
        if (is_null($credit_debit_type)) {
            throw new \InvalidArgumentException('non-nullable credit_debit_type cannot be null');
        }
        $this->container['credit_debit_type'] = $credit_debit_type;

        return $this;
    }

    /**
     * Gets transaction_name
     *
     * @return string
     */
    public function getTransactionName()
    {
        return $this->container['transaction_name'];
    }

    /**
     * Sets transaction_name
     *
     * @param string $transaction_name Literal usada na instituição financeira para identificar a transação.  A informação apresentada precisa ser a mesma utilizada nos canais digitais da instituição (assim como o histórico de transações apresentado na tela do aplicativo ou do navegador).  Caso a instituição possua mais de um canal digital, a informação compartilhada deve ser a do canal que apresenta a descrição mais completa possível da transação.  Em casos onde a descrição da transação é apresentada com múltiplas linhas, todas as linhas devem ser enviadas (concatenadas) neste atributo, não sendo obrigatória a concatenação das informações já enviadas em outros atributos (ex: valor, data) do mesmo endpoint.  Adicionalmente, o Banco Central pode determinar o formato de compartilhamento a ser adotado por uma instituição participante específica.
     *
     * @return self
     */
    public function setTransactionName($transaction_name)
    {
        if (is_null($transaction_name)) {
            throw new \InvalidArgumentException('non-nullable transaction_name cannot be null');
        }
        if ((mb_strlen($transaction_name) > 200)) {
            throw new \InvalidArgumentException('invalid length for $transaction_name when calling AccountTransactionsData., must be smaller than or equal to 200.');
        }
        if ((!preg_match("/[\\w\\W\\s]*/", ObjectSerializer::toString($transaction_name)))) {
            throw new \InvalidArgumentException("invalid value for \$transaction_name when calling AccountTransactionsData., must conform to the pattern /[\\w\\W\\s]*/.");
        }

        $this->container['transaction_name'] = $transaction_name;

        return $this;
    }

    /**
     * Gets type
     *
     * @return \OpenAPI\Client\Model\EnumTransactionTypes
     */
    public function getType()
    {
        return $this->container['type'];
    }

    /**
     * Sets type
     *
     * @param \OpenAPI\Client\Model\EnumTransactionTypes $type type
     *
     * @return self
     */
    public function setType($type)
    {
        if (is_null($type)) {
            throw new \InvalidArgumentException('non-nullable type cannot be null');
        }
        $this->container['type'] = $type;

        return $this;
    }

    /**
     * Gets transaction_amount
     *
     * @return \OpenAPI\Client\Model\AccountTransactionsDataAmount
     */
    public function getTransactionAmount()
    {
        return $this->container['transaction_amount'];
    }

    /**
     * Sets transaction_amount
     *
     * @param \OpenAPI\Client\Model\AccountTransactionsDataAmount $transaction_amount transaction_amount
     *
     * @return self
     */
    public function setTransactionAmount($transaction_amount)
    {
        if (is_null($transaction_amount)) {
            throw new \InvalidArgumentException('non-nullable transaction_amount cannot be null');
        }
        $this->container['transaction_amount'] = $transaction_amount;

        return $this;
    }

    /**
     * Gets transaction_date_time
     *
     * @return string
     */
    public function getTransactionDateTime()
    {
        return $this->container['transaction_date_time'];
    }

    /**
     * Sets transaction_date_time
     *
     * @param string $transaction_date_time Data e hora original da transação.
     *
     * @return self
     */
    public function setTransactionDateTime($transaction_date_time)
    {
        if (is_null($transaction_date_time)) {
            throw new \InvalidArgumentException('non-nullable transaction_date_time cannot be null');
        }
        if ((mb_strlen($transaction_date_time) > 24)) {
            throw new \InvalidArgumentException('invalid length for $transaction_date_time when calling AccountTransactionsData., must be smaller than or equal to 24.');
        }
        if ((!preg_match("/(^(\\d{4})-(1[0-2]|0?[1-9])-(3[01]|[12][0-9]|0?[1-9])T(?:[01]\\d|2[0123]):(?:[012345]\\d):(?:[012345]\\d)\\.(?:[0-9]){3}Z$)/", ObjectSerializer::toString($transaction_date_time)))) {
            throw new \InvalidArgumentException("invalid value for \$transaction_date_time when calling AccountTransactionsData., must conform to the pattern /(^(\\d{4})-(1[0-2]|0?[1-9])-(3[01]|[12][0-9]|0?[1-9])T(?:[01]\\d|2[0123]):(?:[012345]\\d):(?:[012345]\\d)\\.(?:[0-9]){3}Z$)/.");
        }

        $this->container['transaction_date_time'] = $transaction_date_time;

        return $this;
    }

    /**
     * Gets partie_cnpj_cpf
     *
     * @return string|null
     */
    public function getPartieCnpjCpf()
    {
        return $this->container['partie_cnpj_cpf'];
    }

    /**
     * Sets partie_cnpj_cpf
     *
     * @param string|null $partie_cnpj_cpf Identificação da pessoa envolvida na transação: pagador ou recebedor (Preencher com o CPF ou CNPJ, sem formatação). Com a IN BCB nº 371, a partir de 02/05/23, o envio das informações de identificação de contraparte tornou-se obrigatória para transações de pagamento. Para maiores detalhes, favor consultar a página `Orientações - Contas`.  [Restrição] Quando o \"type“ for preenchido com valor FOLHA_PAGAMENTO e a transmissora for a responsável pelo pagamento de salário (banco-folha), o partieCnpjCpf informado deve ser do empregador relacionado.
     *
     * @return self
     */
    public function setPartieCnpjCpf($partie_cnpj_cpf)
    {
        if (is_null($partie_cnpj_cpf)) {
            throw new \InvalidArgumentException('non-nullable partie_cnpj_cpf cannot be null');
        }
        if ((mb_strlen($partie_cnpj_cpf) > 14)) {
            throw new \InvalidArgumentException('invalid length for $partie_cnpj_cpf when calling AccountTransactionsData., must be smaller than or equal to 14.');
        }
        if ((!preg_match("/^\\d{11}$|^\\d{14}$/", ObjectSerializer::toString($partie_cnpj_cpf)))) {
            throw new \InvalidArgumentException("invalid value for \$partie_cnpj_cpf when calling AccountTransactionsData., must conform to the pattern /^\\d{11}$|^\\d{14}$/.");
        }

        $this->container['partie_cnpj_cpf'] = $partie_cnpj_cpf;

        return $this;
    }

    /**
     * Gets partie_person_type
     *
     * @return \OpenAPI\Client\Model\EnumPartiePersonType|null
     */
    public function getPartiePersonType()
    {
        return $this->container['partie_person_type'];
    }

    /**
     * Sets partie_person_type
     *
     * @param \OpenAPI\Client\Model\EnumPartiePersonType|null $partie_person_type partie_person_type
     *
     * @return self
     */
    public function setPartiePersonType($partie_person_type)
    {
        if (is_null($partie_person_type)) {
            throw new \InvalidArgumentException('non-nullable partie_person_type cannot be null');
        }
        $this->container['partie_person_type'] = $partie_person_type;

        return $this;
    }

    /**
     * Gets partie_compe_code
     *
     * @return string|null
     */
    public function getPartieCompeCode()
    {
        return $this->container['partie_compe_code'];
    }

    /**
     * Sets partie_compe_code
     *
     * @param string|null $partie_compe_code Código identificador atribuído pelo Banco Central do Brasil às instituições participantes do STR (Sistema de Transferência de reservas) referente à pessoa envolvida na transação. O número-código substituiu o antigo código COMPE. Todos os participantes do STR, exceto as Infraestruturas do Mercado Financeiro (IMF) e a Secretaria do Tesouro Nacional, possuem um número-código independentemente de participarem da Centralizadora da Compensação de Cheques (Compe).
     *
     * @return self
     */
    public function setPartieCompeCode($partie_compe_code)
    {
        if (is_null($partie_compe_code)) {
            throw new \InvalidArgumentException('non-nullable partie_compe_code cannot be null');
        }
        if ((mb_strlen($partie_compe_code) > 3)) {
            throw new \InvalidArgumentException('invalid length for $partie_compe_code when calling AccountTransactionsData., must be smaller than or equal to 3.');
        }
        if ((!preg_match("/^\\d{3}$/", ObjectSerializer::toString($partie_compe_code)))) {
            throw new \InvalidArgumentException("invalid value for \$partie_compe_code when calling AccountTransactionsData., must conform to the pattern /^\\d{3}$/.");
        }

        $this->container['partie_compe_code'] = $partie_compe_code;

        return $this;
    }

    /**
     * Gets partie_branch_code
     *
     * @return string|null
     */
    public function getPartieBranchCode()
    {
        return $this->container['partie_branch_code'];
    }

    /**
     * Sets partie_branch_code
     *
     * @param string|null $partie_branch_code Código da Agência detentora da conta da pessoa envolvida na transação. (Agência é a dependência destinada ao atendimento aos clientes, ao público em geral e aos associados de cooperativas de crédito, no exercício de atividades da instituição, não podendo ser móvel ou transitória)
     *
     * @return self
     */
    public function setPartieBranchCode($partie_branch_code)
    {
        if (is_null($partie_branch_code)) {
            throw new \InvalidArgumentException('non-nullable partie_branch_code cannot be null');
        }
        if ((mb_strlen($partie_branch_code) > 4)) {
            throw new \InvalidArgumentException('invalid length for $partie_branch_code when calling AccountTransactionsData., must be smaller than or equal to 4.');
        }
        if ((!preg_match("/^\\d{4}$/", ObjectSerializer::toString($partie_branch_code)))) {
            throw new \InvalidArgumentException("invalid value for \$partie_branch_code when calling AccountTransactionsData., must conform to the pattern /^\\d{4}$/.");
        }

        $this->container['partie_branch_code'] = $partie_branch_code;

        return $this;
    }

    /**
     * Gets partie_number
     *
     * @return string|null
     */
    public function getPartieNumber()
    {
        return $this->container['partie_number'];
    }

    /**
     * Sets partie_number
     *
     * @param string|null $partie_number Número da conta da pessoa envolvida na transação
     *
     * @return self
     */
    public function setPartieNumber($partie_number)
    {
        if (is_null($partie_number)) {
            throw new \InvalidArgumentException('non-nullable partie_number cannot be null');
        }
        if ((mb_strlen($partie_number) > 20)) {
            throw new \InvalidArgumentException('invalid length for $partie_number when calling AccountTransactionsData., must be smaller than or equal to 20.');
        }
        if ((!preg_match("/^\\d{8,20}$/", ObjectSerializer::toString($partie_number)))) {
            throw new \InvalidArgumentException("invalid value for \$partie_number when calling AccountTransactionsData., must conform to the pattern /^\\d{8,20}$/.");
        }

        $this->container['partie_number'] = $partie_number;

        return $this;
    }

    /**
     * Gets partie_check_digit
     *
     * @return string|null
     */
    public function getPartieCheckDigit()
    {
        return $this->container['partie_check_digit'];
    }

    /**
     * Sets partie_check_digit
     *
     * @param string|null $partie_check_digit Dígito da conta da pessoa envolvida na transação
     *
     * @return self
     */
    public function setPartieCheckDigit($partie_check_digit)
    {
        if (is_null($partie_check_digit)) {
            throw new \InvalidArgumentException('non-nullable partie_check_digit cannot be null');
        }
        if ((mb_strlen($partie_check_digit) > 1)) {
            throw new \InvalidArgumentException('invalid length for $partie_check_digit when calling AccountTransactionsData., must be smaller than or equal to 1.');
        }
        if ((!preg_match("/[\\w\\W\\s]*/", ObjectSerializer::toString($partie_check_digit)))) {
            throw new \InvalidArgumentException("invalid value for \$partie_check_digit when calling AccountTransactionsData., must conform to the pattern /[\\w\\W\\s]*/.");
        }

        $this->container['partie_check_digit'] = $partie_check_digit;

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


