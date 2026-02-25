<?php
/**
 * ResponseConsentExtensionsData
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
 * ResponseConsentExtensionsData Class Doc Comment
 *
 * @category Class
 * @package  OpenAPI\Client
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 * @implements \ArrayAccess<string, mixed>
 */
class UconsentsResponseConsentExtensionsData implements ModelInterface, ArrayAccess, \JsonSerializable
{
    public const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'ResponseConsentExtensions_data';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'consent_id' => 'string',
        'creation_date_time' => '\DateTime',
        'status' => 'string',
        'status_update_date_time' => '\DateTime',
        'permissions' => 'string[]',
        'expiration_date_time' => '\DateTime'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      * @phpstan-var array<string, string|null>
      * @psalm-var array<string, string|null>
      */
    protected static $openAPIFormats = [
        'consent_id' => null,
        'creation_date_time' => 'date-time',
        'status' => null,
        'status_update_date_time' => 'date-time',
        'permissions' => null,
        'expiration_date_time' => 'date-time'
    ];

    /**
      * Array of nullable properties. Used for (de)serialization
      *
      * @var boolean[]
      */
    protected static array $openAPINullables = [
        'consent_id' => false,
        'creation_date_time' => false,
        'status' => false,
        'status_update_date_time' => false,
        'permissions' => false,
        'expiration_date_time' => false
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
        'consent_id' => 'consentId',
        'creation_date_time' => 'creationDateTime',
        'status' => 'status',
        'status_update_date_time' => 'statusUpdateDateTime',
        'permissions' => 'permissions',
        'expiration_date_time' => 'expirationDateTime'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'consent_id' => 'setConsentId',
        'creation_date_time' => 'setCreationDateTime',
        'status' => 'setStatus',
        'status_update_date_time' => 'setStatusUpdateDateTime',
        'permissions' => 'setPermissions',
        'expiration_date_time' => 'setExpirationDateTime'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'consent_id' => 'getConsentId',
        'creation_date_time' => 'getCreationDateTime',
        'status' => 'getStatus',
        'status_update_date_time' => 'getStatusUpdateDateTime',
        'permissions' => 'getPermissions',
        'expiration_date_time' => 'getExpirationDateTime'
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

    public const STATUS_AUTHORISED = 'AUTHORISED';
    public const STATUS_AWAITING_AUTHORISATION = 'AWAITING_AUTHORISATION';
    public const STATUS_REJECTED = 'REJECTED';
    public const PERMISSIONS_ACCOUNTS_READ = 'ACCOUNTS_READ';
    public const PERMISSIONS_ACCOUNTS_BALANCES_READ = 'ACCOUNTS_BALANCES_READ';
    public const PERMISSIONS_ACCOUNTS_TRANSACTIONS_READ = 'ACCOUNTS_TRANSACTIONS_READ';
    public const PERMISSIONS_ACCOUNTS_OVERDRAFT_LIMITS_READ = 'ACCOUNTS_OVERDRAFT_LIMITS_READ';
    public const PERMISSIONS_CREDIT_CARDS_ACCOUNTS_READ = 'CREDIT_CARDS_ACCOUNTS_READ';
    public const PERMISSIONS_CREDIT_CARDS_ACCOUNTS_BILLS_READ = 'CREDIT_CARDS_ACCOUNTS_BILLS_READ';
    public const PERMISSIONS_CREDIT_CARDS_ACCOUNTS_BILLS_TRANSACTIONS_READ = 'CREDIT_CARDS_ACCOUNTS_BILLS_TRANSACTIONS_READ';
    public const PERMISSIONS_CREDIT_CARDS_ACCOUNTS_LIMITS_READ = 'CREDIT_CARDS_ACCOUNTS_LIMITS_READ';
    public const PERMISSIONS_CREDIT_CARDS_ACCOUNTS_TRANSACTIONS_READ = 'CREDIT_CARDS_ACCOUNTS_TRANSACTIONS_READ';
    public const PERMISSIONS_CUSTOMERS_PERSONAL_IDENTIFICATIONS_READ = 'CUSTOMERS_PERSONAL_IDENTIFICATIONS_READ';
    public const PERMISSIONS_CUSTOMERS_PERSONAL_ADITTIONALINFO_READ = 'CUSTOMERS_PERSONAL_ADITTIONALINFO_READ';
    public const PERMISSIONS_CUSTOMERS_BUSINESS_IDENTIFICATIONS_READ = 'CUSTOMERS_BUSINESS_IDENTIFICATIONS_READ';
    public const PERMISSIONS_CUSTOMERS_BUSINESS_ADITTIONALINFO_READ = 'CUSTOMERS_BUSINESS_ADITTIONALINFO_READ';
    public const PERMISSIONS_FINANCINGS_READ = 'FINANCINGS_READ';
    public const PERMISSIONS_FINANCINGS_SCHEDULED_INSTALMENTS_READ = 'FINANCINGS_SCHEDULED_INSTALMENTS_READ';
    public const PERMISSIONS_FINANCINGS_PAYMENTS_READ = 'FINANCINGS_PAYMENTS_READ';
    public const PERMISSIONS_FINANCINGS_WARRANTIES_READ = 'FINANCINGS_WARRANTIES_READ';
    public const PERMISSIONS_INVOICE_FINANCINGS_READ = 'INVOICE_FINANCINGS_READ';
    public const PERMISSIONS_INVOICE_FINANCINGS_SCHEDULED_INSTALMENTS_READ = 'INVOICE_FINANCINGS_SCHEDULED_INSTALMENTS_READ';
    public const PERMISSIONS_INVOICE_FINANCINGS_PAYMENTS_READ = 'INVOICE_FINANCINGS_PAYMENTS_READ';
    public const PERMISSIONS_INVOICE_FINANCINGS_WARRANTIES_READ = 'INVOICE_FINANCINGS_WARRANTIES_READ';
    public const PERMISSIONS_LOANS_READ = 'LOANS_READ';
    public const PERMISSIONS_LOANS_SCHEDULED_INSTALMENTS_READ = 'LOANS_SCHEDULED_INSTALMENTS_READ';
    public const PERMISSIONS_LOANS_PAYMENTS_READ = 'LOANS_PAYMENTS_READ';
    public const PERMISSIONS_LOANS_WARRANTIES_READ = 'LOANS_WARRANTIES_READ';
    public const PERMISSIONS_UNARRANGED_ACCOUNTS_OVERDRAFT_READ = 'UNARRANGED_ACCOUNTS_OVERDRAFT_READ';
    public const PERMISSIONS_UNARRANGED_ACCOUNTS_OVERDRAFT_SCHEDULED_INSTALMENTS_READ = 'UNARRANGED_ACCOUNTS_OVERDRAFT_SCHEDULED_INSTALMENTS_READ';
    public const PERMISSIONS_UNARRANGED_ACCOUNTS_OVERDRAFT_PAYMENTS_READ = 'UNARRANGED_ACCOUNTS_OVERDRAFT_PAYMENTS_READ';
    public const PERMISSIONS_UNARRANGED_ACCOUNTS_OVERDRAFT_WARRANTIES_READ = 'UNARRANGED_ACCOUNTS_OVERDRAFT_WARRANTIES_READ';
    public const PERMISSIONS_RESOURCES_READ = 'RESOURCES_READ';
    public const PERMISSIONS_BANK_FIXED_INCOMES_READ = 'BANK_FIXED_INCOMES_READ';
    public const PERMISSIONS_CREDIT_FIXED_INCOMES_READ = 'CREDIT_FIXED_INCOMES_READ';
    public const PERMISSIONS_FUNDS_READ = 'FUNDS_READ';
    public const PERMISSIONS_VARIABLE_INCOMES_READ = 'VARIABLE_INCOMES_READ';
    public const PERMISSIONS_TREASURE_TITLES_READ = 'TREASURE_TITLES_READ';

    /**
     * Gets allowable values of the enum
     *
     * @return string[]
     */
    public function getStatusAllowableValues()
    {
        return [
            self::STATUS_AUTHORISED,
            self::STATUS_AWAITING_AUTHORISATION,
            self::STATUS_REJECTED,
        ];
    }

    /**
     * Gets allowable values of the enum
     *
     * @return string[]
     */
    public function getPermissionsAllowableValues()
    {
        return [
            self::PERMISSIONS_ACCOUNTS_READ,
            self::PERMISSIONS_ACCOUNTS_BALANCES_READ,
            self::PERMISSIONS_ACCOUNTS_TRANSACTIONS_READ,
            self::PERMISSIONS_ACCOUNTS_OVERDRAFT_LIMITS_READ,
            self::PERMISSIONS_CREDIT_CARDS_ACCOUNTS_READ,
            self::PERMISSIONS_CREDIT_CARDS_ACCOUNTS_BILLS_READ,
            self::PERMISSIONS_CREDIT_CARDS_ACCOUNTS_BILLS_TRANSACTIONS_READ,
            self::PERMISSIONS_CREDIT_CARDS_ACCOUNTS_LIMITS_READ,
            self::PERMISSIONS_CREDIT_CARDS_ACCOUNTS_TRANSACTIONS_READ,
            self::PERMISSIONS_CUSTOMERS_PERSONAL_IDENTIFICATIONS_READ,
            self::PERMISSIONS_CUSTOMERS_PERSONAL_ADITTIONALINFO_READ,
            self::PERMISSIONS_CUSTOMERS_BUSINESS_IDENTIFICATIONS_READ,
            self::PERMISSIONS_CUSTOMERS_BUSINESS_ADITTIONALINFO_READ,
            self::PERMISSIONS_FINANCINGS_READ,
            self::PERMISSIONS_FINANCINGS_SCHEDULED_INSTALMENTS_READ,
            self::PERMISSIONS_FINANCINGS_PAYMENTS_READ,
            self::PERMISSIONS_FINANCINGS_WARRANTIES_READ,
            self::PERMISSIONS_INVOICE_FINANCINGS_READ,
            self::PERMISSIONS_INVOICE_FINANCINGS_SCHEDULED_INSTALMENTS_READ,
            self::PERMISSIONS_INVOICE_FINANCINGS_PAYMENTS_READ,
            self::PERMISSIONS_INVOICE_FINANCINGS_WARRANTIES_READ,
            self::PERMISSIONS_LOANS_READ,
            self::PERMISSIONS_LOANS_SCHEDULED_INSTALMENTS_READ,
            self::PERMISSIONS_LOANS_PAYMENTS_READ,
            self::PERMISSIONS_LOANS_WARRANTIES_READ,
            self::PERMISSIONS_UNARRANGED_ACCOUNTS_OVERDRAFT_READ,
            self::PERMISSIONS_UNARRANGED_ACCOUNTS_OVERDRAFT_SCHEDULED_INSTALMENTS_READ,
            self::PERMISSIONS_UNARRANGED_ACCOUNTS_OVERDRAFT_PAYMENTS_READ,
            self::PERMISSIONS_UNARRANGED_ACCOUNTS_OVERDRAFT_WARRANTIES_READ,
            self::PERMISSIONS_RESOURCES_READ,
            self::PERMISSIONS_BANK_FIXED_INCOMES_READ,
            self::PERMISSIONS_CREDIT_FIXED_INCOMES_READ,
            self::PERMISSIONS_FUNDS_READ,
            self::PERMISSIONS_VARIABLE_INCOMES_READ,
            self::PERMISSIONS_TREASURE_TITLES_READ,
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
        $this->setIfExists('consent_id', $data ?? [], null);
        $this->setIfExists('creation_date_time', $data ?? [], null);
        $this->setIfExists('status', $data ?? [], null);
        $this->setIfExists('status_update_date_time', $data ?? [], null);
        $this->setIfExists('permissions', $data ?? [], null);
        $this->setIfExists('expiration_date_time', $data ?? [], null);
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

        if ($this->container['consent_id'] === null) {
            $invalidProperties[] = "'consent_id' can't be null";
        }
        if ((mb_strlen($this->container['consent_id']) > 256)) {
            $invalidProperties[] = "invalid value for 'consent_id', the character length must be smaller than or equal to 256.";
        }

        if (!preg_match("/^urn:[a-zA-Z0-9][a-zA-Z0-9-]{0,31}:[a-zA-Z0-9()+,\\-.:=@;$_!*'%\/?#]+$/", $this->container['consent_id'])) {
            $invalidProperties[] = "invalid value for 'consent_id', must be conform to the pattern /^urn:[a-zA-Z0-9][a-zA-Z0-9-]{0,31}:[a-zA-Z0-9()+,\\-.:=@;$_!*'%\/?#]+$/.";
        }

        if ($this->container['creation_date_time'] === null) {
            $invalidProperties[] = "'creation_date_time' can't be null";
        }
        if ((mb_strlen($this->container['creation_date_time']) > 20)) {
            $invalidProperties[] = "invalid value for 'creation_date_time', the character length must be smaller than or equal to 20.";
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

        if ($this->container['permissions'] === null) {
            $invalidProperties[] = "'permissions' can't be null";
        }
        if ((count($this->container['permissions']) < 1)) {
            $invalidProperties[] = "invalid value for 'permissions', number of items must be greater than or equal to 1.";
        }

        if (!is_null($this->container['expiration_date_time']) && (mb_strlen($this->container['expiration_date_time']) > 20)) {
            $invalidProperties[] = "invalid value for 'expiration_date_time', the character length must be smaller than or equal to 20.";
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
     * Gets consent_id
     *
     * @return string
     */
    public function getConsentId()
    {
        return $this->container['consent_id'];
    }

    /**
     * Sets consent_id
     *
     * @param string $consent_id O consentId é o identificador único do consentimento e deverá ser um URN - Uniform Resource Name.   Um URN, conforme definido na [RFC8141](https://tools.ietf.org/html/rfc8141) é um Uniform Resource  Identifier - URI - que é atribuído sob o URI scheme \"urn\" e um namespace URN específico, com a intenção de que o URN  seja um identificador de recurso persistente e independente da localização.   Considerando a string urn:bancoex:C1DD33123 como exemplo para consentId temos: - o namespace(urn) - o identificador associado ao namespace da instituição transnmissora (bancoex)  - o identificador específico dentro do namespace (C1DD33123).   Informações mais detalhadas sobre a construção de namespaces devem ser consultadas na [RFC8141](https://tools.ietf.org/html/rfc8141).
     *
     * @return self
     */
    public function setConsentId($consent_id)
    {
        if (is_null($consent_id)) {
            throw new \InvalidArgumentException('non-nullable consent_id cannot be null');
        }
        if ((mb_strlen($consent_id) > 256)) {
            throw new \InvalidArgumentException('invalid length for $consent_id when calling ResponseConsentExtensionsData., must be smaller than or equal to 256.');
        }
        if ((!preg_match("/^urn:[a-zA-Z0-9][a-zA-Z0-9-]{0,31}:[a-zA-Z0-9()+,\\-.:=@;$_!*'%\/?#]+$/", ObjectSerializer::toString($consent_id)))) {
            throw new \InvalidArgumentException("invalid value for \$consent_id when calling ResponseConsentExtensionsData., must conform to the pattern /^urn:[a-zA-Z0-9][a-zA-Z0-9-]{0,31}:[a-zA-Z0-9()+,\\-.:=@;$_!*'%\/?#]+$/.");
        }

        $this->container['consent_id'] = $consent_id;

        return $this;
    }

    /**
     * Gets creation_date_time
     *
     * @return \DateTime
     */
    public function getCreationDateTime()
    {
        return $this->container['creation_date_time'];
    }

    /**
     * Sets creation_date_time
     *
     * @param \DateTime $creation_date_time Data e hora em que o recurso foi criado. Uma string com data e hora conforme especificação RFC-3339, sempre com a utilização de timezone UTC(UTC time format).
     *
     * @return self
     */
    public function setCreationDateTime($creation_date_time)
    {
        if (is_null($creation_date_time)) {
            throw new \InvalidArgumentException('non-nullable creation_date_time cannot be null');
        }
        if ((mb_strlen($creation_date_time) > 20)) {
            throw new \InvalidArgumentException('invalid length for $creation_date_time when calling ResponseConsentExtensionsData., must be smaller than or equal to 20.');
        }

        $this->container['creation_date_time'] = $creation_date_time;

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
     * @param string $status Estado atual do consentimento cadastrado.
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
     * @return \DateTime
     */
    public function getStatusUpdateDateTime()
    {
        return $this->container['status_update_date_time'];
    }

    /**
     * Sets status_update_date_time
     *
     * @param \DateTime $status_update_date_time Data e hora em que o recurso foi atualizado. Uma string com data e hora conforme especificação RFC-3339, sempre com a utilização de timezone UTC(UTC time format).
     *
     * @return self
     */
    public function setStatusUpdateDateTime($status_update_date_time)
    {
        if (is_null($status_update_date_time)) {
            throw new \InvalidArgumentException('non-nullable status_update_date_time cannot be null');
        }
        if ((mb_strlen($status_update_date_time) > 20)) {
            throw new \InvalidArgumentException('invalid length for $status_update_date_time when calling ResponseConsentExtensionsData., must be smaller than or equal to 20.');
        }

        $this->container['status_update_date_time'] = $status_update_date_time;

        return $this;
    }

    /**
     * Gets permissions
     *
     * @return string[]
     */
    public function getPermissions()
    {
        return $this->container['permissions'];
    }

    /**
     * Sets permissions
     *
     * @param string[] $permissions Especifica os tipos de permissões de acesso às APIs no escopo do Open Finance Brasil - Dados cadastrais e transacionais, de acordo com os blocos de consentimento fornecidos pelo usuário e necessários ao acesso a cada endpoint das APIs. Esse array não deve ter duplicidade de itens.
     *
     * @return self
     */
    public function setPermissions($permissions)
    {
        if (is_null($permissions)) {
            throw new \InvalidArgumentException('non-nullable permissions cannot be null');
        }
        $allowedValues = $this->getPermissionsAllowableValues();
        if (array_diff($permissions, $allowedValues)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid value for 'permissions', must be one of '%s'",
                    implode("', '", $allowedValues)
                )
            );
        }


        if ((count($permissions) < 1)) {
            throw new \InvalidArgumentException('invalid length for $permissions when calling ResponseConsentExtensionsData., number of items must be greater than or equal to 1.');
        }
        $this->container['permissions'] = $permissions;

        return $this;
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
     * @param \DateTime|null $expiration_date_time Data e hora de expiração da permissão. Reflete a data limite de validade do consentimento. Uma string com data e hora conforme especificação RFC-3339, sempre com a utilização de timezone UTC (UTC time format).  [Restrição] De preenchimento obrigatório nos casos em que houver validade determinada. Em casos de consentimento com prazo indeterminado o campo não deve ser enviado.  Quando preenchido, o valor do campo não pode ultrapassar 12 meses.
     *
     * @return self
     */
    public function setExpirationDateTime($expiration_date_time)
    {
        if (is_null($expiration_date_time)) {
            throw new \InvalidArgumentException('non-nullable expiration_date_time cannot be null');
        }
        if ((mb_strlen($expiration_date_time) > 20)) {
            throw new \InvalidArgumentException('invalid length for $expiration_date_time when calling ResponseConsentExtensionsData., must be smaller than or equal to 20.');
        }

        $this->container['expiration_date_time'] = $expiration_date_time;

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


