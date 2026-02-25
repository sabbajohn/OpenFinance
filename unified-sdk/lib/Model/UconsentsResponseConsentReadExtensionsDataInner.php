<?php
/**
 * ResponseConsentReadExtensionsDataInner
 *
 * PHP version 8.1
 *
 * @category Class
 * @package  OpenAPI\Client
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 */

/**
 * API Consents - Open Finance Brasil
 *
 * API que trata da criação, consulta, renovação e revogação de consentimentos para o Open Finance Brasil Dados cadastrais e transacionais - customer-data.   Não possui segregação entre pessoa natural e pessoa jurídica.      # Orientações importantes A API Consents trata exclusivamente dos consentimentos para Dados Cadastrais e Transacionais do Open Finance Brasil. - A API consents é composta de endpoints que permitem:   - Pedido de criação do consentimento pela receptora: `POST /consents`   - Devolução do pedido de criação pela transmissora: `GET /consents/{consentId}`   - Pedido de renovação de consentimento do cliente pela receptora: `POST /consents/{consentId}/extends`   - Devolução de lista com histórico de renovações efetuadas: `GET /consents/{consentId}/extensions`   - Pedido de revogação do consentimento: `DELETE /consents/{consentId}` - Recomenda-se fortemente a leitura da seção [Orientações - [DC] Consentimento](https://openfinancebrasil.atlassian.net/wiki/spaces/OF/pages/219480491) para maiores detalhes, regras e restrições referente aos endpoints da API Consents - As informações da instituição receptora não trafegam na API Consents – a autenticação da receptora se dá através do [DCR](https://openfinancebrasil.atlassian.net/wiki/spaces/OF/pages/17378307/Dynamic+Client+Registration). - Na chamada para a criação, consulta e revogação do consentimento deve-se utilizar um token gerado via `client_credentials`. Na chamada para renovação do consentimento deve-se utilizar um token gerado via `authorization_code`. - Após o `POST` de criação do consentimento, o `STATUS` devolvido na resposta deverá ser `AWAITING_AUTHORISATION`. - O `STATUS` será alterado para `AUTHORISED` somente após autenticação e confirmação por parte do usuário na instituição transmissora dos dados. - Caso não haja confirmação por parte do usuário na transmissora, o status do consentimento deve ser alterado de `AWAITING_AUTHORISATION` para `REJECTED` após 60 minutos. - Todas as datas trafegadas nesta API seguem o padrão da [RFC3339](https://tools.ietf.org/html/rfc3339) e formato \"zulu\". - A descrição do fluxo de consentimento encontra-se disponível no [Portal do desenvolvedor](https://openfinancebrasil.atlassian.net/wiki/spaces/OF/pages/17369300/Dados+Cadastrais+e+Transacionais#Fluxo-b%C3%A1sico-de-consentimento). - O arquivo com o mapeamento completo entre `Roles`, `scopes` e `permissions` está disponibilizado no Portal do desenvolvedor, no mesmo item acima - descrição do fluxo de consentimento. - A receptora deve enviar obrigatoriamente, no pedido de criação de consentimento, todas as permissions dos agrupamentos de dados as quais ela deseja consentimento, conforme tabela abaixo:    ```   |-------|----------------------|-------------------------------|----------------------------------------------------------|-------------------------------|   | ROLE  | CATEGORIA DE DADOS   | AGRUPAMENTO                   | PERMISSIONS                                              | SCOPE OAUTH 2.0               |   |-------|----------------------|-------------------------------|----------------------------------------------------------|-------------------------------|   |       |                      |                               | CUSTOMERS_PERSONAL_IDENTIFICATIONS_READ                  | customers                     |   |       | Cadastro             | Dados Cadastrais PF           |----------------------------------------------------------|                               |   |       |                      |                               | RESOURCES_READ                                           | resources                     |   |       |----------------------|-------------------------------|----------------------------------------------------------|-------------------------------|   |       |                      |                               | CUSTOMERS_PERSONAL_ADITTIONALINFO_READ                   | customers                     |   |       | Cadastro             | Informações complementares PF |----------------------------------------------------------|                               |   |       |                      |                               | RESOURCES_READ                                           | resources                     |   | DADOS |----------------------|-------------------------------|----------------------------------------------------------|-------------------------------|   |       |                      |                               | CUSTOMERS_BUSINESS_IDENTIFICATIONS_READ                  | customers                     |   |       | Cadastro             | Dados Cadastrais PJ           |----------------------------------------------------------|                               |   |       |                      |                               | RESOURCES_READ                                           | resources                     |   |       |----------------------|-------------------------------|----------------------------------------------------------|-------------------------------|   |       |                      |                               | CUSTOMERS_BUSINESS_ADITTIONALINFO_READ                   | customers                     |   |       | Cadastro             | Informações complementares PJ |----------------------------------------------------------|                               |   |       |                      |                               | RESOURCES_READ                                           | resources                     |   |-------|----------------------|-------------------------------|----------------------------------------------------------|-------------------------------|   |       |                      |                               | ACCOUNTS_READ                                            |                               |   |       |                      |                               |----------------------------------------------------------| accounts                      |   |       | Contas               | Saldos                        | ACCOUNTS_BALANCES_READ                                   |                               |   |       |                      |                               |----------------------------------------------------------| resources                     |   |       |                      |                               | RESOURCES_READ                                           |                               |   |       |----------------------|-------------------------------|----------------------------------------------------------|-------------------------------|   |       |                      |                               | ACCOUNTS_READ                                            |                               |   |       |                      |                               |----------------------------------------------------------| accounts                      |   | DADOS | Contas               | Limites                       | ACCOUNTS_OVERDRAFT_LIMITS_READ                           |                               |   |       |                      |                               |----------------------------------------------------------| resources                     |   |       |                      |                               | RESOURCES_READ                                           |                               |   |       |----------------------|-------------------------------|----------------------------------------------------------|-------------------------------|   |       |                      |                               | ACCOUNTS_READ                                            |                               |   |       |                      |                               |----------------------------------------------------------| accounts                      |   |       | Contas               | Extratos                      | ACCOUNTS_TRANSACTIONS_READ                               |                               |   |       |                      |                               |----------------------------------------------------------| resources                     |   |       |                      |                               | RESOURCES_READ                                           |                               |   |-------|----------------------|-------------------------------|----------------------------------------------------------|-------------------------------|   |       |                      |                               | CREDIT_CARDS_ACCOUNTS_READ                               |                               |   |       |                      |                               |----------------------------------------------------------| credit-cards-accounts         |   |       | Cartão de Crédito    | Limites                       | CREDIT_CARDS_ACCOUNTS_LIMITS_READ                        |                               |   |       |                      |                               |----------------------------------------------------------| resources                     |   |       |                      |                               | RESOURCES_READ                                           |                               |   |       |----------------------|-------------------------------|----------------------------------------------------------|-------------------------------|   |       |                      |                               | CREDIT_CARDS_ACCOUNTS_READ                               |                               |   |       |                      |                               |----------------------------------------------------------| credit-cards-accounts         |   |       | Cartão de Crédito    | Transações                    | CREDIT_CARDS_ACCOUNTS_TRANSACTIONS_READ                  |                               |   | DADOS |                      |                               |----------------------------------------------------------| resources                     |   |       |                      |                               | RESOURCES_READ                                           |                               |   |       |----------------------|-------------------------------|----------------------------------------------------------|-------------------------------|   |       |                      |                               | CREDIT_CARDS_ACCOUNTS_READ                               |                               |   |       |                      |                               |----------------------------------------------------------|                               |   |       |                      |                               | CREDIT_CARDS_ACCOUNTS_BILLS_READ                         | credit-cards-accounts         |   |       | Cartão de Crédito    | Faturas                       |----------------------------------------------------------|                               |   |       |                      |                               | CREDIT_CARDS_ACCOUNTS_BILLS_TRANSACTIONS_READ            | resources                     |   |       |                      |                               |----------------------------------------------------------|                               |   |       |                      |                               | RESOURCES_READ                                           |                               |   |-------|----------------------|-------------------------------|----------------------------------------------------------|-------------------------------|   |       |                      |                               | LOANS_READ                                               |                               |   |       |                      |                               |----------------------------------------------------------|                               |   |       |                      |                               | LOANS_WARRANTIES_READ                                    |                               |   |       |                      |                               |----------------------------------------------------------|                               |   |       |                      |                               | LOANS_SCHEDULED_INSTALMENTS_READ                         |                               |   |       |                      |                               |----------------------------------------------------------|                               |   |       |                      |                               | LOANS_PAYMENTS_READ                                      |                               |   |       |                      |                               |----------------------------------------------------------|                               |   |       |                      |                               | FINANCINGS_READ                                          |                               |   |       |                      |                               |----------------------------------------------------------|                               |   |       |                      |                               | FINANCINGS_WARRANTIES_READ                               |                               |   |       |                      |                               |----------------------------------------------------------|                               |   |       |                      |                               | FINANCINGS_SCHEDULED_INSTALMENTS_READ                    | loans                         |   |       |                      |                               |----------------------------------------------------------|                               |   |       |                      |                               | FINANCINGS_PAYMENTS_READ                                 | financings                    |   |       |                      |                               |----------------------------------------------------------|                               |   | DADOS | Operações de Crédito | Dados do Contrato             | UNARRANGED_ACCOUNTS_OVERDRAFT_READ                       | unarranged-accounts-overdraft |   |       |                      |                               |----------------------------------------------------------|                               |   |       |                      |                               | UNARRANGED_ACCOUNTS_OVERDRAFT_WARRANTIES_READ            | invoice-financings            |   |       |                      |                               |----------------------------------------------------------|                               |   |       |                      |                               | UNARRANGED_ACCOUNTS_OVERDRAFT_SCHEDULED_INSTALMENTS_READ | resources                     |   |       |                      |                               |----------------------------------------------------------|                               |   |       |                      |                               | UNARRANGED_ACCOUNTS_OVERDRAFT_PAYMENTS_READ              |                               |   |       |                      |                               |----------------------------------------------------------|                               |   |       |                      |                               | INVOICE_FINANCINGS_READ                                  |                               |   |       |                      |                               |----------------------------------------------------------|                               |   |       |                      |                               | INVOICE_FINANCINGS_WARRANTIES_READ                       |                               |   |       |                      |                               |----------------------------------------------------------|                               |   |       |                      |                               | INVOICE_FINANCINGS_SCHEDULED_INSTALMENTS_READ            |                               |   |       |                      |                               |----------------------------------------------------------|                               |   |       |                      |                               | INVOICE_FINANCINGS_PAYMENTS_READ                         |                               |   |       |                      |                               |----------------------------------------------------------|                               |   |       |                      |                               | RESOURCES_READ                                           |                               |   |-------|----------------------|-------------------------------|----------------------------------------------------------|-------------------------------|   |       |                      |                               | BANK_FIXED_INCOMES_READ                                  | bank-fixed-incomes            |   |       |                      |                               |----------------------------------------------------------|                               |   |       |                      |                               | CREDIT_FIXED_INCOMES_READ                                | credit-fixed-incomes          |   |       |                      |                               |----------------------------------------------------------|                               |   | DADOS | Investimento         | Dados da Operação             | FUNDS_READ                                               | variable-incomes              |   |       |                      |                               |----------------------------------------------------------|                               |   |       |                      |                               | VARIABLE_INCOMES_READ                                    | treasure-titles               |   |       |                      |                               |----------------------------------------------------------|                               |   |       |                      |                               | TREASURE_TITLES_READ                                     | funds                         |   |       |                      |                               |----------------------------------------------------------|                               |   |       |                      |                               | RESOURCES_READ                                           | resources                     |   |-------|----------------------|-------------------------------|----------------------------------------------------------|-------------------------------|   |       |                      |                               | EXCHANGES_READ                                           |                               |   | DADOS | Câmbio               | Dados da Operação             |----------------------------------------------------------| exchanges                     |   |       |                      |                               | RESOURCES_READ                                           |                               |   |-------|----------------------|-------------------------------|----------------------------------------------------------|-------------------------------|      ``` - A instituição transmissora deve validar o preenchimento correto dos agrupamentos acima no momento da geração do consentimento. - Caso a instiuição receptora envie permissões não existentes nos agrupamentos especificados na tabela, a transmissora deve rejeitar o pedido da receptora dando retorno HTTP Status Code 400. - A transmissora deve retornar, da lista de permissions requisitadas, apenas o subconjunto de permissions por ela suportada, removendo da lista as permissions de produtos não suportados e retornando HTTP Status Code 201. A única exceção a este comportamento são os casos de produtos agrupados, como Operações de Crédito, Investimentos e Câmbio, para os quais todas as permissões do agrupamento devem ser mantidas. Caso não restem permissões funcionais, a instituição transmissora deve retornar o erro HTTP Code \"422 Unprocessable Entity\". - A renovação de consentimento não pode ser efetuada em situações determinadas. É esperado status 401 ou 403 para situações em que o erro for tratado na camada de segurança. Para erros tratados em camada de negócio, a transmissora deve retornar 422 conforme mensagens especificadas na página [Orientações – [DC] Consentimento](https://openfinancebrasil.atlassian.net/wiki/spaces/DraftOF/pages/232915037) - Caso o método `DELETE` seja chamado para um consentimento que já se encontra no `STATUS REJECTED` deve se retornar o STATUS CODE 422. - Pedidos de renovação de consentimento somente podem alterar a data de validade (conforme as regras definidas em [Orientações – [DC] Consentimento](https://openfinancebrasil.atlassian.net/wiki/spaces/DraftOF/pages/232915037)) e a finalidade do consentimento, e aplica-se somente a consentimentos ativos (status `AUTHORISED`). - No caso de criação ou renovação de consentimentos com prazo indeterminado, a receptora não deve enviar o atributo expirationDateTime. Para prazos determinados o campo deve ser enviado. - A renovação de consentimento (`POST /consents/{consentId}/extends`) deve ser possível por apenas um cliente logado.  Isso implica que qualquer usuário (`loggedUser`) com permissão para o consentimento Pessoa Jurídica deve ser capaz de finalizar o fluxo de renovação sem redirecionamento. Para consentimentos Pessoa Natural apenas o `loggedUser` criador do consentimento consegue renovar sem redirecionamento.
 *
 * The version of the OpenAPI document: 3.3.0
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
 * ResponseConsentReadExtensionsDataInner Class Doc Comment
 *
 * @category Class
 * @package  OpenAPI\Client
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 * @implements \ArrayAccess<string, mixed>
 */
class UconsentsResponseConsentReadExtensionsDataInner implements ModelInterface, ArrayAccess, \JsonSerializable
{
    public const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'ResponseConsentReadExtensions_data_inner';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'expiration_date_time' => '\DateTime',
        'logged_user' => '\OpenAPI\Client\Model\LoggedUserExtensions',
        'request_date_time' => '\DateTime',
        'previous_expiration_date_time' => '\DateTime',
        'x_fapi_customer_ip_address' => 'string',
        'x_customer_user_agent' => 'string'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      * @phpstan-var array<string, string|null>
      * @psalm-var array<string, string|null>
      */
    protected static $openAPIFormats = [
        'expiration_date_time' => 'date-time',
        'logged_user' => null,
        'request_date_time' => 'date-time',
        'previous_expiration_date_time' => 'date-time',
        'x_fapi_customer_ip_address' => null,
        'x_customer_user_agent' => null
    ];

    /**
      * Array of nullable properties. Used for (de)serialization
      *
      * @var boolean[]
      */
    protected static array $openAPINullables = [
        'expiration_date_time' => false,
        'logged_user' => false,
        'request_date_time' => false,
        'previous_expiration_date_time' => false,
        'x_fapi_customer_ip_address' => false,
        'x_customer_user_agent' => false
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
        'expiration_date_time' => 'expirationDateTime',
        'logged_user' => 'loggedUser',
        'request_date_time' => 'requestDateTime',
        'previous_expiration_date_time' => 'previousExpirationDateTime',
        'x_fapi_customer_ip_address' => 'xFapiCustomerIpAddress',
        'x_customer_user_agent' => 'xCustomerUserAgent'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'expiration_date_time' => 'setExpirationDateTime',
        'logged_user' => 'setLoggedUser',
        'request_date_time' => 'setRequestDateTime',
        'previous_expiration_date_time' => 'setPreviousExpirationDateTime',
        'x_fapi_customer_ip_address' => 'setXFapiCustomerIpAddress',
        'x_customer_user_agent' => 'setXCustomerUserAgent'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'expiration_date_time' => 'getExpirationDateTime',
        'logged_user' => 'getLoggedUser',
        'request_date_time' => 'getRequestDateTime',
        'previous_expiration_date_time' => 'getPreviousExpirationDateTime',
        'x_fapi_customer_ip_address' => 'getXFapiCustomerIpAddress',
        'x_customer_user_agent' => 'getXCustomerUserAgent'
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
        $this->setIfExists('expiration_date_time', $data ?? [], null);
        $this->setIfExists('logged_user', $data ?? [], null);
        $this->setIfExists('request_date_time', $data ?? [], null);
        $this->setIfExists('previous_expiration_date_time', $data ?? [], null);
        $this->setIfExists('x_fapi_customer_ip_address', $data ?? [], null);
        $this->setIfExists('x_customer_user_agent', $data ?? [], null);
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

        if (!is_null($this->container['expiration_date_time']) && (mb_strlen($this->container['expiration_date_time']) > 20)) {
            $invalidProperties[] = "invalid value for 'expiration_date_time', the character length must be smaller than or equal to 20.";
        }

        if (!is_null($this->container['expiration_date_time']) && !preg_match("/(^(\\d{4})-(1[0-2]|0?[1-9])-(3[01]|[12][0-9]|0?[1-9])T(?:[01]\\d|2[0123]):(?:[012345]\\d):(?:[012345]\\d)Z$)/", $this->container['expiration_date_time'])) {
            $invalidProperties[] = "invalid value for 'expiration_date_time', must be conform to the pattern /(^(\\d{4})-(1[0-2]|0?[1-9])-(3[01]|[12][0-9]|0?[1-9])T(?:[01]\\d|2[0123]):(?:[012345]\\d):(?:[012345]\\d)Z$)/.";
        }

        if ($this->container['logged_user'] === null) {
            $invalidProperties[] = "'logged_user' can't be null";
        }
        if ($this->container['request_date_time'] === null) {
            $invalidProperties[] = "'request_date_time' can't be null";
        }
        if ((mb_strlen($this->container['request_date_time']) > 20)) {
            $invalidProperties[] = "invalid value for 'request_date_time', the character length must be smaller than or equal to 20.";
        }

        if (!is_null($this->container['previous_expiration_date_time']) && (mb_strlen($this->container['previous_expiration_date_time']) > 20)) {
            $invalidProperties[] = "invalid value for 'previous_expiration_date_time', the character length must be smaller than or equal to 20.";
        }

        if (!is_null($this->container['previous_expiration_date_time']) && !preg_match("/(^(\\d{4})-(1[0-2]|0?[1-9])-(3[01]|[12][0-9]|0?[1-9])T(?:[01]\\d|2[0123]):(?:[012345]\\d):(?:[012345]\\d)Z$)/", $this->container['previous_expiration_date_time'])) {
            $invalidProperties[] = "invalid value for 'previous_expiration_date_time', must be conform to the pattern /(^(\\d{4})-(1[0-2]|0?[1-9])-(3[01]|[12][0-9]|0?[1-9])T(?:[01]\\d|2[0123]):(?:[012345]\\d):(?:[012345]\\d)Z$)/.";
        }

        if (!is_null($this->container['x_fapi_customer_ip_address']) && (mb_strlen($this->container['x_fapi_customer_ip_address']) > 100)) {
            $invalidProperties[] = "invalid value for 'x_fapi_customer_ip_address', the character length must be smaller than or equal to 100.";
        }

        if (!is_null($this->container['x_fapi_customer_ip_address']) && (mb_strlen($this->container['x_fapi_customer_ip_address']) < 1)) {
            $invalidProperties[] = "invalid value for 'x_fapi_customer_ip_address', the character length must be bigger than or equal to 1.";
        }

        if (!is_null($this->container['x_fapi_customer_ip_address']) && !preg_match("/^[^\\s](.*[^\\s])?$/", $this->container['x_fapi_customer_ip_address'])) {
            $invalidProperties[] = "invalid value for 'x_fapi_customer_ip_address', must be conform to the pattern /^[^\\s](.*[^\\s])?$/.";
        }

        if (!is_null($this->container['x_customer_user_agent']) && (mb_strlen($this->container['x_customer_user_agent']) > 255)) {
            $invalidProperties[] = "invalid value for 'x_customer_user_agent', the character length must be smaller than or equal to 255.";
        }

        if (!is_null($this->container['x_customer_user_agent']) && (mb_strlen($this->container['x_customer_user_agent']) < 1)) {
            $invalidProperties[] = "invalid value for 'x_customer_user_agent', the character length must be bigger than or equal to 1.";
        }

        if (!is_null($this->container['x_customer_user_agent']) && !preg_match("/^[^\\s](.*[^\\s])?$/", $this->container['x_customer_user_agent'])) {
            $invalidProperties[] = "invalid value for 'x_customer_user_agent', must be conform to the pattern /^[^\\s](.*[^\\s])?$/.";
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
     * Gets expiration_date_time
     *
     * @return \DateTime|null
     */
    public function getExpirationDateTime()
    {
        return $this->container['expiration_date_time'];
    }

    /**
     * Sets expiration_date_time
     *
     * @param \DateTime|null $expiration_date_time Data e hora de expiração da permissão. Reflete a data limite de validade do consentimento. Uma string com data e hora conforme especificação RFC-3339, sempre com a utilização de timezone UTC(UTC time format), utilizado apenas para consulta de alterações históricas de extensão do consentimento.  [Restrição] De preenchimento obrigatório nos casos em que houver validade determinada.  Em casos de consentimento com prazo indeterminada o campo não deve ser preenchido.
     *
     * @return self
     */
    public function setExpirationDateTime($expiration_date_time)
    {
        if (is_null($expiration_date_time)) {
            throw new \InvalidArgumentException('non-nullable expiration_date_time cannot be null');
        }
        if ((mb_strlen($expiration_date_time) > 20)) {
            throw new \InvalidArgumentException('invalid length for $expiration_date_time when calling ResponseConsentReadExtensionsDataInner., must be smaller than or equal to 20.');
        }
        if ((!preg_match("/(^(\\d{4})-(1[0-2]|0?[1-9])-(3[01]|[12][0-9]|0?[1-9])T(?:[01]\\d|2[0123]):(?:[012345]\\d):(?:[012345]\\d)Z$)/", ObjectSerializer::toString($expiration_date_time)))) {
            throw new \InvalidArgumentException("invalid value for \$expiration_date_time when calling ResponseConsentReadExtensionsDataInner., must conform to the pattern /(^(\\d{4})-(1[0-2]|0?[1-9])-(3[01]|[12][0-9]|0?[1-9])T(?:[01]\\d|2[0123]):(?:[012345]\\d):(?:[012345]\\d)Z$)/.");
        }

        $this->container['expiration_date_time'] = $expiration_date_time;

        return $this;
    }

    /**
     * Gets logged_user
     *
     * @return \OpenAPI\Client\Model\LoggedUserExtensions
     */
    public function getLoggedUser()
    {
        return $this->container['logged_user'];
    }

    /**
     * Sets logged_user
     *
     * @param \OpenAPI\Client\Model\LoggedUserExtensions $logged_user logged_user
     *
     * @return self
     */
    public function setLoggedUser($logged_user)
    {
        if (is_null($logged_user)) {
            throw new \InvalidArgumentException('non-nullable logged_user cannot be null');
        }
        $this->container['logged_user'] = $logged_user;

        return $this;
    }

    /**
     * Gets request_date_time
     *
     * @return \DateTime
     */
    public function getRequestDateTime()
    {
        return $this->container['request_date_time'];
    }

    /**
     * Sets request_date_time
     *
     * @param \DateTime $request_date_time Data e hora em que o recurso foi criado. Uma string com data e hora conforme especificação RFC-3339, sempre com a utilização de timezone UTC(UTC time format).
     *
     * @return self
     */
    public function setRequestDateTime($request_date_time)
    {
        if (is_null($request_date_time)) {
            throw new \InvalidArgumentException('non-nullable request_date_time cannot be null');
        }
        if ((mb_strlen($request_date_time) > 20)) {
            throw new \InvalidArgumentException('invalid length for $request_date_time when calling ResponseConsentReadExtensionsDataInner., must be smaller than or equal to 20.');
        }

        $this->container['request_date_time'] = $request_date_time;

        return $this;
    }

    /**
     * Gets previous_expiration_date_time
     *
     * @return \DateTime|null
     */
    public function getPreviousExpirationDateTime()
    {
        return $this->container['previous_expiration_date_time'];
    }

    /**
     * Sets previous_expiration_date_time
     *
     * @param \DateTime|null $previous_expiration_date_time Data e hora de expiração anteriores a renovação. Reflete a data limite anterior de validade do consentimento. Uma string com data e hora conforme especificação RFC-3339, sempre com a utilização de timezone UTC (UTC time format).  [Restrição] De preenchimento obrigatório nos casos em que houver validade determinada. Em casos de consentimento com prazo indeterminado, ou renovações feitas com a v2.2.0 em que não exista persistência dessa informação, o campo não deve ser preenchido.
     *
     * @return self
     */
    public function setPreviousExpirationDateTime($previous_expiration_date_time)
    {
        if (is_null($previous_expiration_date_time)) {
            throw new \InvalidArgumentException('non-nullable previous_expiration_date_time cannot be null');
        }
        if ((mb_strlen($previous_expiration_date_time) > 20)) {
            throw new \InvalidArgumentException('invalid length for $previous_expiration_date_time when calling ResponseConsentReadExtensionsDataInner., must be smaller than or equal to 20.');
        }
        if ((!preg_match("/(^(\\d{4})-(1[0-2]|0?[1-9])-(3[01]|[12][0-9]|0?[1-9])T(?:[01]\\d|2[0123]):(?:[012345]\\d):(?:[012345]\\d)Z$)/", ObjectSerializer::toString($previous_expiration_date_time)))) {
            throw new \InvalidArgumentException("invalid value for \$previous_expiration_date_time when calling ResponseConsentReadExtensionsDataInner., must conform to the pattern /(^(\\d{4})-(1[0-2]|0?[1-9])-(3[01]|[12][0-9]|0?[1-9])T(?:[01]\\d|2[0123]):(?:[012345]\\d):(?:[012345]\\d)Z$)/.");
        }

        $this->container['previous_expiration_date_time'] = $previous_expiration_date_time;

        return $this;
    }

    /**
     * Gets x_fapi_customer_ip_address
     *
     * @return string|null
     */
    public function getXFapiCustomerIpAddress()
    {
        return $this->container['x_fapi_customer_ip_address'];
    }

    /**
     * Sets x_fapi_customer_ip_address
     *
     * @param string|null $x_fapi_customer_ip_address O endereço IP do usuário logado com o receptor que solicitou a renovação sem redirecionamento.  [Restrição] De preenchimento obrigatório a partir da v3.0.0. Opcional para renovações feitas com a v2.2.0 quando não existir persistência dessa informação.
     *
     * @return self
     */
    public function setXFapiCustomerIpAddress($x_fapi_customer_ip_address)
    {
        if (is_null($x_fapi_customer_ip_address)) {
            throw new \InvalidArgumentException('non-nullable x_fapi_customer_ip_address cannot be null');
        }
        if ((mb_strlen($x_fapi_customer_ip_address) > 100)) {
            throw new \InvalidArgumentException('invalid length for $x_fapi_customer_ip_address when calling ResponseConsentReadExtensionsDataInner., must be smaller than or equal to 100.');
        }
        if ((mb_strlen($x_fapi_customer_ip_address) < 1)) {
            throw new \InvalidArgumentException('invalid length for $x_fapi_customer_ip_address when calling ResponseConsentReadExtensionsDataInner., must be bigger than or equal to 1.');
        }
        if ((!preg_match("/^[^\\s](.*[^\\s])?$/", ObjectSerializer::toString($x_fapi_customer_ip_address)))) {
            throw new \InvalidArgumentException("invalid value for \$x_fapi_customer_ip_address when calling ResponseConsentReadExtensionsDataInner., must conform to the pattern /^[^\\s](.*[^\\s])?$/.");
        }

        $this->container['x_fapi_customer_ip_address'] = $x_fapi_customer_ip_address;

        return $this;
    }

    /**
     * Gets x_customer_user_agent
     *
     * @return string|null
     */
    public function getXCustomerUserAgent()
    {
        return $this->container['x_customer_user_agent'];
    }

    /**
     * Sets x_customer_user_agent
     *
     * @param string|null $x_customer_user_agent Indica o user-agent que o usuário utilizou quando solicitou a renovação sem redirecionamento.  [Restrição] De preenchimento obrigatório a partir da v3.0.0. Opcional para renovações feitas com a v2.2.0 quando não existir persistência dessa informação.
     *
     * @return self
     */
    public function setXCustomerUserAgent($x_customer_user_agent)
    {
        if (is_null($x_customer_user_agent)) {
            throw new \InvalidArgumentException('non-nullable x_customer_user_agent cannot be null');
        }
        if ((mb_strlen($x_customer_user_agent) > 255)) {
            throw new \InvalidArgumentException('invalid length for $x_customer_user_agent when calling ResponseConsentReadExtensionsDataInner., must be smaller than or equal to 255.');
        }
        if ((mb_strlen($x_customer_user_agent) < 1)) {
            throw new \InvalidArgumentException('invalid length for $x_customer_user_agent when calling ResponseConsentReadExtensionsDataInner., must be bigger than or equal to 1.');
        }
        if ((!preg_match("/^[^\\s](.*[^\\s])?$/", ObjectSerializer::toString($x_customer_user_agent)))) {
            throw new \InvalidArgumentException("invalid value for \$x_customer_user_agent when calling ResponseConsentReadExtensionsDataInner., must conform to the pattern /^[^\\s](.*[^\\s])?$/.");
        }

        $this->container['x_customer_user_agent'] = $x_customer_user_agent;

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


