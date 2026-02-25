<?php
/**
 * AccountData
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
 * AccountData Class Doc Comment
 *
 * @category Class
 * @package  OpenAPI\Client
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 * @implements \ArrayAccess<string, mixed>
 */
class UaccountsAccountData implements ModelInterface, ArrayAccess, \JsonSerializable
{
    public const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'AccountData';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'brand_name' => 'string',
        'company_cnpj' => 'string',
        'type' => '\OpenAPI\Client\Model\EnumAccountType',
        'compe_code' => 'string',
        'branch_code' => 'string',
        'number' => 'string',
        'check_digit' => 'string',
        'account_id' => 'string'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      * @phpstan-var array<string, string|null>
      * @psalm-var array<string, string|null>
      */
    protected static $openAPIFormats = [
        'brand_name' => null,
        'company_cnpj' => null,
        'type' => null,
        'compe_code' => null,
        'branch_code' => null,
        'number' => null,
        'check_digit' => null,
        'account_id' => null
    ];

    /**
      * Array of nullable properties. Used for (de)serialization
      *
      * @var boolean[]
      */
    protected static array $openAPINullables = [
        'brand_name' => false,
        'company_cnpj' => false,
        'type' => false,
        'compe_code' => false,
        'branch_code' => false,
        'number' => false,
        'check_digit' => false,
        'account_id' => false
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
        'brand_name' => 'brandName',
        'company_cnpj' => 'companyCnpj',
        'type' => 'type',
        'compe_code' => 'compeCode',
        'branch_code' => 'branchCode',
        'number' => 'number',
        'check_digit' => 'checkDigit',
        'account_id' => 'accountId'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'brand_name' => 'setBrandName',
        'company_cnpj' => 'setCompanyCnpj',
        'type' => 'setType',
        'compe_code' => 'setCompeCode',
        'branch_code' => 'setBranchCode',
        'number' => 'setNumber',
        'check_digit' => 'setCheckDigit',
        'account_id' => 'setAccountId'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'brand_name' => 'getBrandName',
        'company_cnpj' => 'getCompanyCnpj',
        'type' => 'getType',
        'compe_code' => 'getCompeCode',
        'branch_code' => 'getBranchCode',
        'number' => 'getNumber',
        'check_digit' => 'getCheckDigit',
        'account_id' => 'getAccountId'
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
        $this->setIfExists('brand_name', $data ?? [], null);
        $this->setIfExists('company_cnpj', $data ?? [], null);
        $this->setIfExists('type', $data ?? [], null);
        $this->setIfExists('compe_code', $data ?? [], null);
        $this->setIfExists('branch_code', $data ?? [], null);
        $this->setIfExists('number', $data ?? [], null);
        $this->setIfExists('check_digit', $data ?? [], null);
        $this->setIfExists('account_id', $data ?? [], null);
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

        if ($this->container['brand_name'] === null) {
            $invalidProperties[] = "'brand_name' can't be null";
        }
        if ((mb_strlen($this->container['brand_name']) > 80)) {
            $invalidProperties[] = "invalid value for 'brand_name', the character length must be smaller than or equal to 80.";
        }

        if (!preg_match("/[\\w\\W\\s]*/", $this->container['brand_name'])) {
            $invalidProperties[] = "invalid value for 'brand_name', must be conform to the pattern /[\\w\\W\\s]*/.";
        }

        if ($this->container['company_cnpj'] === null) {
            $invalidProperties[] = "'company_cnpj' can't be null";
        }
        if ((mb_strlen($this->container['company_cnpj']) > 14)) {
            $invalidProperties[] = "invalid value for 'company_cnpj', the character length must be smaller than or equal to 14.";
        }

        if (!preg_match("/^\\d{14}$/", $this->container['company_cnpj'])) {
            $invalidProperties[] = "invalid value for 'company_cnpj', must be conform to the pattern /^\\d{14}$/.";
        }

        if ($this->container['type'] === null) {
            $invalidProperties[] = "'type' can't be null";
        }
        if ($this->container['compe_code'] === null) {
            $invalidProperties[] = "'compe_code' can't be null";
        }
        if ((mb_strlen($this->container['compe_code']) > 3)) {
            $invalidProperties[] = "invalid value for 'compe_code', the character length must be smaller than or equal to 3.";
        }

        if (!preg_match("/^\\d{3}$/", $this->container['compe_code'])) {
            $invalidProperties[] = "invalid value for 'compe_code', must be conform to the pattern /^\\d{3}$/.";
        }

        if (!is_null($this->container['branch_code']) && (mb_strlen($this->container['branch_code']) > 4)) {
            $invalidProperties[] = "invalid value for 'branch_code', the character length must be smaller than or equal to 4.";
        }

        if (!is_null($this->container['branch_code']) && !preg_match("/^\\d{4}$/", $this->container['branch_code'])) {
            $invalidProperties[] = "invalid value for 'branch_code', must be conform to the pattern /^\\d{4}$/.";
        }

        if ($this->container['number'] === null) {
            $invalidProperties[] = "'number' can't be null";
        }
        if ((mb_strlen($this->container['number']) > 20)) {
            $invalidProperties[] = "invalid value for 'number', the character length must be smaller than or equal to 20.";
        }

        if (!preg_match("/^\\d{8,20}$/", $this->container['number'])) {
            $invalidProperties[] = "invalid value for 'number', must be conform to the pattern /^\\d{8,20}$/.";
        }

        if ($this->container['check_digit'] === null) {
            $invalidProperties[] = "'check_digit' can't be null";
        }
        if ((mb_strlen($this->container['check_digit']) > 1)) {
            $invalidProperties[] = "invalid value for 'check_digit', the character length must be smaller than or equal to 1.";
        }

        if (!preg_match("/[\\w\\W\\s]*/", $this->container['check_digit'])) {
            $invalidProperties[] = "invalid value for 'check_digit', must be conform to the pattern /[\\w\\W\\s]*/.";
        }

        if ($this->container['account_id'] === null) {
            $invalidProperties[] = "'account_id' can't be null";
        }
        if ((mb_strlen($this->container['account_id']) > 100)) {
            $invalidProperties[] = "invalid value for 'account_id', the character length must be smaller than or equal to 100.";
        }

        if ((mb_strlen($this->container['account_id']) < 1)) {
            $invalidProperties[] = "invalid value for 'account_id', the character length must be bigger than or equal to 1.";
        }

        if (!preg_match("/^[a-zA-Z0-9][a-zA-Z0-9-]{0,99}$/", $this->container['account_id'])) {
            $invalidProperties[] = "invalid value for 'account_id', must be conform to the pattern /^[a-zA-Z0-9][a-zA-Z0-9-]{0,99}$/.";
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
     * Gets brand_name
     *
     * @return string
     */
    public function getBrandName()
    {
        return $this->container['brand_name'];
    }

    /**
     * Sets brand_name
     *
     * @param string $brand_name Nome da Marca reportada pelo participante no Open Finance. Recomenda-se utilizar, sempre que possível, o mesmo nome de marca atribuído no campo do diretório Customer Friendly Server Name (Authorisation Server).
     *
     * @return self
     */
    public function setBrandName($brand_name)
    {
        if (is_null($brand_name)) {
            throw new \InvalidArgumentException('non-nullable brand_name cannot be null');
        }
        if ((mb_strlen($brand_name) > 80)) {
            throw new \InvalidArgumentException('invalid length for $brand_name when calling AccountData., must be smaller than or equal to 80.');
        }
        if ((!preg_match("/[\\w\\W\\s]*/", ObjectSerializer::toString($brand_name)))) {
            throw new \InvalidArgumentException("invalid value for \$brand_name when calling AccountData., must conform to the pattern /[\\w\\W\\s]*/.");
        }

        $this->container['brand_name'] = $brand_name;

        return $this;
    }

    /**
     * Gets company_cnpj
     *
     * @return string
     */
    public function getCompanyCnpj()
    {
        return $this->container['company_cnpj'];
    }

    /**
     * Sets company_cnpj
     *
     * @param string $company_cnpj Número completo do CNPJ da instituição responsável pelo Cadastro - o CNPJ corresponde ao número de inscrição no Cadastro de Pessoa Jurídica. Deve-se ter apenas os números do CNPJ, sem máscara
     *
     * @return self
     */
    public function setCompanyCnpj($company_cnpj)
    {
        if (is_null($company_cnpj)) {
            throw new \InvalidArgumentException('non-nullable company_cnpj cannot be null');
        }
        if ((mb_strlen($company_cnpj) > 14)) {
            throw new \InvalidArgumentException('invalid length for $company_cnpj when calling AccountData., must be smaller than or equal to 14.');
        }
        if ((!preg_match("/^\\d{14}$/", ObjectSerializer::toString($company_cnpj)))) {
            throw new \InvalidArgumentException("invalid value for \$company_cnpj when calling AccountData., must conform to the pattern /^\\d{14}$/.");
        }

        $this->container['company_cnpj'] = $company_cnpj;

        return $this;
    }

    /**
     * Gets type
     *
     * @return \OpenAPI\Client\Model\EnumAccountType
     */
    public function getType()
    {
        return $this->container['type'];
    }

    /**
     * Sets type
     *
     * @param \OpenAPI\Client\Model\EnumAccountType $type type
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
     * Gets compe_code
     *
     * @return string
     */
    public function getCompeCode()
    {
        return $this->container['compe_code'];
    }

    /**
     * Sets compe_code
     *
     * @param string $compe_code Código identificador atribuído pelo Banco Central do Brasil às instituições participantes do STR (Sistema de Transferência de reservas).O Compe (Sistema de Compensação de Cheques e Outros Papéis) é um sistema que identifica e processa as compensações bancárias. Ele é representado por um código de três dígitos que serve como identificador de bancos, sendo assim, cada instituição bancária possui um número exclusivo
     *
     * @return self
     */
    public function setCompeCode($compe_code)
    {
        if (is_null($compe_code)) {
            throw new \InvalidArgumentException('non-nullable compe_code cannot be null');
        }
        if ((mb_strlen($compe_code) > 3)) {
            throw new \InvalidArgumentException('invalid length for $compe_code when calling AccountData., must be smaller than or equal to 3.');
        }
        if ((!preg_match("/^\\d{3}$/", ObjectSerializer::toString($compe_code)))) {
            throw new \InvalidArgumentException("invalid value for \$compe_code when calling AccountData., must conform to the pattern /^\\d{3}$/.");
        }

        $this->container['compe_code'] = $compe_code;

        return $this;
    }

    /**
     * Gets branch_code
     *
     * @return string|null
     */
    public function getBranchCode()
    {
        return $this->container['branch_code'];
    }

    /**
     * Sets branch_code
     *
     * @param string|null $branch_code Código da Agência detentora da conta. (Agência é a dependência destinada ao atendimento aos clientes, ao público em geral e aos associados de cooperativas de crédito, no exercício de atividades da instituição, não podendo ser móvel ou transitória)  [Restrição] Obrigatoriamente deve ser preenchido quando o campo \"type\" for diferente de CONTA_PAGAMENTO_PRE_PAGA.
     *
     * @return self
     */
    public function setBranchCode($branch_code)
    {
        if (is_null($branch_code)) {
            throw new \InvalidArgumentException('non-nullable branch_code cannot be null');
        }
        if ((mb_strlen($branch_code) > 4)) {
            throw new \InvalidArgumentException('invalid length for $branch_code when calling AccountData., must be smaller than or equal to 4.');
        }
        if ((!preg_match("/^\\d{4}$/", ObjectSerializer::toString($branch_code)))) {
            throw new \InvalidArgumentException("invalid value for \$branch_code when calling AccountData., must conform to the pattern /^\\d{4}$/.");
        }

        $this->container['branch_code'] = $branch_code;

        return $this;
    }

    /**
     * Gets number
     *
     * @return string
     */
    public function getNumber()
    {
        return $this->container['number'];
    }

    /**
     * Sets number
     *
     * @param string $number Número da conta
     *
     * @return self
     */
    public function setNumber($number)
    {
        if (is_null($number)) {
            throw new \InvalidArgumentException('non-nullable number cannot be null');
        }
        if ((mb_strlen($number) > 20)) {
            throw new \InvalidArgumentException('invalid length for $number when calling AccountData., must be smaller than or equal to 20.');
        }
        if ((!preg_match("/^\\d{8,20}$/", ObjectSerializer::toString($number)))) {
            throw new \InvalidArgumentException("invalid value for \$number when calling AccountData., must conform to the pattern /^\\d{8,20}$/.");
        }

        $this->container['number'] = $number;

        return $this;
    }

    /**
     * Gets check_digit
     *
     * @return string
     */
    public function getCheckDigit()
    {
        return $this->container['check_digit'];
    }

    /**
     * Sets check_digit
     *
     * @param string $check_digit Dígito da conta
     *
     * @return self
     */
    public function setCheckDigit($check_digit)
    {
        if (is_null($check_digit)) {
            throw new \InvalidArgumentException('non-nullable check_digit cannot be null');
        }
        if ((mb_strlen($check_digit) > 1)) {
            throw new \InvalidArgumentException('invalid length for $check_digit when calling AccountData., must be smaller than or equal to 1.');
        }
        if ((!preg_match("/[\\w\\W\\s]*/", ObjectSerializer::toString($check_digit)))) {
            throw new \InvalidArgumentException("invalid value for \$check_digit when calling AccountData., must conform to the pattern /[\\w\\W\\s]*/.");
        }

        $this->container['check_digit'] = $check_digit;

        return $this;
    }

    /**
     * Gets account_id
     *
     * @return string
     */
    public function getAccountId()
    {
        return $this->container['account_id'];
    }

    /**
     * Sets account_id
     *
     * @param string $account_id Identifica de forma única  a conta do cliente, mantendo as regras de imutabilidade dentro da instituição transmissora.
     *
     * @return self
     */
    public function setAccountId($account_id)
    {
        if (is_null($account_id)) {
            throw new \InvalidArgumentException('non-nullable account_id cannot be null');
        }
        if ((mb_strlen($account_id) > 100)) {
            throw new \InvalidArgumentException('invalid length for $account_id when calling AccountData., must be smaller than or equal to 100.');
        }
        if ((mb_strlen($account_id) < 1)) {
            throw new \InvalidArgumentException('invalid length for $account_id when calling AccountData., must be bigger than or equal to 1.');
        }
        if ((!preg_match("/^[a-zA-Z0-9][a-zA-Z0-9-]{0,99}$/", ObjectSerializer::toString($account_id)))) {
            throw new \InvalidArgumentException("invalid value for \$account_id when calling AccountData., must conform to the pattern /^[a-zA-Z0-9][a-zA-Z0-9-]{0,99}$/.");
        }

        $this->container['account_id'] = $account_id;

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


