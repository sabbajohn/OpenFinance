<?php
/**
 * ConsentsApi
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

namespace OpenAPI\Client\Api;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\MultipartStream;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use OpenAPI\Client\ApiException;
use OpenAPI\Client\Configuration;
use OpenAPI\Client\FormDataProcessor;
use OpenAPI\Client\HeaderSelector;
use OpenAPI\Client\ObjectSerializer;

/**
 * ConsentsApi Class Doc Comment
 *
 * @category Class
 * @package  OpenAPI\Client
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 */
class ConsentsApi
{
    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @var Configuration
     */
    protected $config;

    /**
     * @var HeaderSelector
     */
    protected $headerSelector;

    /**
     * @var int Host index
     */
    protected $hostIndex;

    /** @var string[] $contentTypes **/
    public const contentTypes = [
        'consentsDeleteConsentsConsentId' => [
            'application/json',
        ],
        'consentsGetConsentsConsentId' => [
            'application/json',
        ],
        'consentsGetConsentsConsentIdExtensions' => [
            'application/json',
        ],
        'consentsPostConsents' => [
            'application/json',
        ],
        'consentsPostConsentsConsentIdExtends' => [
            'application/json',
        ],
    ];

    /**
     * @param ClientInterface $client
     * @param Configuration   $config
     * @param HeaderSelector  $selector
     * @param int             $hostIndex (Optional) host index to select the list of hosts if defined in the OpenAPI spec
     */
    public function __construct(
        ?ClientInterface $client = null,
        ?Configuration $config = null,
        ?HeaderSelector $selector = null,
        int $hostIndex = 0
    ) {
        $this->client = $client ?: new Client();
        $this->config = $config ?: Configuration::getDefaultConfiguration();
        $this->headerSelector = $selector ?: new HeaderSelector();
        $this->hostIndex = $hostIndex;
    }

    /**
     * Set the host index
     *
     * @param int $hostIndex Host index (required)
     */
    public function setHostIndex($hostIndex): void
    {
        $this->hostIndex = $hostIndex;
    }

    /**
     * Get the host index
     *
     * @return int Host index
     */
    public function getHostIndex()
    {
        return $this->hostIndex;
    }

    /**
     * @return Configuration
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Operation consentsDeleteConsentsConsentId
     *
     * Deletar / Revogar o consentimento identificado por consentId.
     *
     * @param  string $consent_id O consentId é o identificador único do consentimento e deverá ser um URN - Uniform Resource Name.   Um URN, conforme definido na [RFC8141](https://tools.ietf.org/html/rfc8141) é um Uniform Resource  Identifier - URI - que é atribuído sob o URI scheme \&quot;urn\&quot; e um namespace URN específico, com a intenção de que o URN  seja um identificador de recurso persistente e independente da localização.   Considerando a string urn:bancoex:C1DD33123 como exemplo para consentId temos: - o namespace(urn) - o identificador associado ao namespace da instituição transnmissora (bancoex) - o identificador específico dentro do namespace (C1DD33123). Informações mais detalhadas sobre a construção de namespaces devem ser consultadas na [RFC8141](https://tools.ietf.org/html/rfc8141). (required)
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID RFC4122 usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser “espelhado” pela transmissora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a transmissora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A receptora deve acatar o valor recebido da transmissora. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['consentsDeleteConsentsConsentId'] to see the possible values for this operation
     *
     * @throws \OpenAPI\Client\ApiException on non-2xx response or if the response body is not in the expected format
     * @throws \InvalidArgumentException
     * @return void
     */
    public function consentsDeleteConsentsConsentId($consent_id, $authorization, $x_fapi_interaction_id, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, string $contentType = self::contentTypes['consentsDeleteConsentsConsentId'][0])
    {
        $this->consentsDeleteConsentsConsentIdWithHttpInfo($consent_id, $authorization, $x_fapi_interaction_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $contentType);
    }

    /**
     * Operation consentsDeleteConsentsConsentIdWithHttpInfo
     *
     * Deletar / Revogar o consentimento identificado por consentId.
     *
     * @param  string $consent_id O consentId é o identificador único do consentimento e deverá ser um URN - Uniform Resource Name.   Um URN, conforme definido na [RFC8141](https://tools.ietf.org/html/rfc8141) é um Uniform Resource  Identifier - URI - que é atribuído sob o URI scheme \&quot;urn\&quot; e um namespace URN específico, com a intenção de que o URN  seja um identificador de recurso persistente e independente da localização.   Considerando a string urn:bancoex:C1DD33123 como exemplo para consentId temos: - o namespace(urn) - o identificador associado ao namespace da instituição transnmissora (bancoex) - o identificador específico dentro do namespace (C1DD33123). Informações mais detalhadas sobre a construção de namespaces devem ser consultadas na [RFC8141](https://tools.ietf.org/html/rfc8141). (required)
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID RFC4122 usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser “espelhado” pela transmissora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a transmissora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A receptora deve acatar o valor recebido da transmissora. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['consentsDeleteConsentsConsentId'] to see the possible values for this operation
     *
     * @throws \OpenAPI\Client\ApiException on non-2xx response or if the response body is not in the expected format
     * @throws \InvalidArgumentException
     * @return array of null, HTTP status code, HTTP response headers (array of strings)
     */
    public function consentsDeleteConsentsConsentIdWithHttpInfo($consent_id, $authorization, $x_fapi_interaction_id, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, string $contentType = self::contentTypes['consentsDeleteConsentsConsentId'][0])
    {
        $request = $this->consentsDeleteConsentsConsentIdRequest($consent_id, $authorization, $x_fapi_interaction_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $contentType);

        try {
            $options = $this->createHttpClientOption();
            try {
                $response = $this->client->send($request, $options);
            } catch (RequestException $e) {
                throw new ApiException(
                    "[{$e->getCode()}] {$e->getMessage()}",
                    (int) $e->getCode(),
                    $e->getResponse() ? $e->getResponse()->getHeaders() : null,
                    $e->getResponse() ? (string) $e->getResponse()->getBody() : null
                );
            } catch (ConnectException $e) {
                throw new ApiException(
                    "[{$e->getCode()}] {$e->getMessage()}",
                    (int) $e->getCode(),
                    null,
                    null
                );
            }

            $statusCode = $response->getStatusCode();


            return [null, $statusCode, $response->getHeaders()];
        } catch (ApiException $e) {
            switch ($e->getCode()) {
                case 400:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 401:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 403:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 404:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 405:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 406:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 422:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseErrorUnprocessableEntityDelete',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 429:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 500:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 504:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 529:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ConsentsPostConsents529Response',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                default:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
            }
        

            throw $e;
        }
    }

    /**
     * Operation consentsDeleteConsentsConsentIdAsync
     *
     * Deletar / Revogar o consentimento identificado por consentId.
     *
     * @param  string $consent_id O consentId é o identificador único do consentimento e deverá ser um URN - Uniform Resource Name.   Um URN, conforme definido na [RFC8141](https://tools.ietf.org/html/rfc8141) é um Uniform Resource  Identifier - URI - que é atribuído sob o URI scheme \&quot;urn\&quot; e um namespace URN específico, com a intenção de que o URN  seja um identificador de recurso persistente e independente da localização.   Considerando a string urn:bancoex:C1DD33123 como exemplo para consentId temos: - o namespace(urn) - o identificador associado ao namespace da instituição transnmissora (bancoex) - o identificador específico dentro do namespace (C1DD33123). Informações mais detalhadas sobre a construção de namespaces devem ser consultadas na [RFC8141](https://tools.ietf.org/html/rfc8141). (required)
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID RFC4122 usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser “espelhado” pela transmissora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a transmissora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A receptora deve acatar o valor recebido da transmissora. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['consentsDeleteConsentsConsentId'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function consentsDeleteConsentsConsentIdAsync($consent_id, $authorization, $x_fapi_interaction_id, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, string $contentType = self::contentTypes['consentsDeleteConsentsConsentId'][0])
    {
        return $this->consentsDeleteConsentsConsentIdAsyncWithHttpInfo($consent_id, $authorization, $x_fapi_interaction_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $contentType)
            ->then(
                function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation consentsDeleteConsentsConsentIdAsyncWithHttpInfo
     *
     * Deletar / Revogar o consentimento identificado por consentId.
     *
     * @param  string $consent_id O consentId é o identificador único do consentimento e deverá ser um URN - Uniform Resource Name.   Um URN, conforme definido na [RFC8141](https://tools.ietf.org/html/rfc8141) é um Uniform Resource  Identifier - URI - que é atribuído sob o URI scheme \&quot;urn\&quot; e um namespace URN específico, com a intenção de que o URN  seja um identificador de recurso persistente e independente da localização.   Considerando a string urn:bancoex:C1DD33123 como exemplo para consentId temos: - o namespace(urn) - o identificador associado ao namespace da instituição transnmissora (bancoex) - o identificador específico dentro do namespace (C1DD33123). Informações mais detalhadas sobre a construção de namespaces devem ser consultadas na [RFC8141](https://tools.ietf.org/html/rfc8141). (required)
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID RFC4122 usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser “espelhado” pela transmissora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a transmissora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A receptora deve acatar o valor recebido da transmissora. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['consentsDeleteConsentsConsentId'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function consentsDeleteConsentsConsentIdAsyncWithHttpInfo($consent_id, $authorization, $x_fapi_interaction_id, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, string $contentType = self::contentTypes['consentsDeleteConsentsConsentId'][0])
    {
        $returnType = '';
        $request = $this->consentsDeleteConsentsConsentIdRequest($consent_id, $authorization, $x_fapi_interaction_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $contentType);

        return $this->client
            ->sendAsync($request, $this->createHttpClientOption())
            ->then(
                function ($response) use ($returnType) {
                    return [null, $response->getStatusCode(), $response->getHeaders()];
                },
                function ($exception) {
                    $response = $exception->getResponse();
                    $statusCode = $response->getStatusCode();
                    throw new ApiException(
                        sprintf(
                            '[%d] Error connecting to the API (%s)',
                            $statusCode,
                            $exception->getRequest()->getUri()
                        ),
                        $statusCode,
                        $response->getHeaders(),
                        (string) $response->getBody()
                    );
                }
            );
    }

    /**
     * Create request for operation 'consentsDeleteConsentsConsentId'
     *
     * @param  string $consent_id O consentId é o identificador único do consentimento e deverá ser um URN - Uniform Resource Name.   Um URN, conforme definido na [RFC8141](https://tools.ietf.org/html/rfc8141) é um Uniform Resource  Identifier - URI - que é atribuído sob o URI scheme \&quot;urn\&quot; e um namespace URN específico, com a intenção de que o URN  seja um identificador de recurso persistente e independente da localização.   Considerando a string urn:bancoex:C1DD33123 como exemplo para consentId temos: - o namespace(urn) - o identificador associado ao namespace da instituição transnmissora (bancoex) - o identificador específico dentro do namespace (C1DD33123). Informações mais detalhadas sobre a construção de namespaces devem ser consultadas na [RFC8141](https://tools.ietf.org/html/rfc8141). (required)
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID RFC4122 usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser “espelhado” pela transmissora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a transmissora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A receptora deve acatar o valor recebido da transmissora. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['consentsDeleteConsentsConsentId'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Psr7\Request
     */
    public function consentsDeleteConsentsConsentIdRequest($consent_id, $authorization, $x_fapi_interaction_id, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, string $contentType = self::contentTypes['consentsDeleteConsentsConsentId'][0])
    {

        // verify the required parameter 'consent_id' is set
        if ($consent_id === null || (is_array($consent_id) && count($consent_id) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $consent_id when calling consentsDeleteConsentsConsentId'
            );
        }
        if (strlen($consent_id) > 256) {
            throw new \InvalidArgumentException('invalid length for "$consent_id" when calling ConsentsApi.consentsDeleteConsentsConsentId, must be smaller than or equal to 256.');
        }
        if (strlen($consent_id) < 6) {
            throw new \InvalidArgumentException('invalid length for "$consent_id" when calling ConsentsApi.consentsDeleteConsentsConsentId, must be bigger than or equal to 6.');
        }
        if (!preg_match("/^urn:[a-zA-Z0-9][a-zA-Z0-9-]{0,31}:[a-zA-Z0-9()+,\\-.:=@;$_!*'%\/?#]+$/", $consent_id)) {
            throw new \InvalidArgumentException("invalid value for \"consent_id\" when calling ConsentsApi.consentsDeleteConsentsConsentId, must conform to the pattern /^urn:[a-zA-Z0-9][a-zA-Z0-9-]{0,31}:[a-zA-Z0-9()+,\\-.:=@;$_!*'%\/?#]+$/.");
        }
        
        // verify the required parameter 'authorization' is set
        if ($authorization === null || (is_array($authorization) && count($authorization) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $authorization when calling consentsDeleteConsentsConsentId'
            );
        }
        if (strlen($authorization) > 2048) {
            throw new \InvalidArgumentException('invalid length for "$authorization" when calling ConsentsApi.consentsDeleteConsentsConsentId, must be smaller than or equal to 2048.');
        }
        if (strlen($authorization) < 1) {
            throw new \InvalidArgumentException('invalid length for "$authorization" when calling ConsentsApi.consentsDeleteConsentsConsentId, must be bigger than or equal to 1.');
        }
        if (!preg_match("/[\\w\\W\\s]*/", $authorization)) {
            throw new \InvalidArgumentException("invalid value for \"authorization\" when calling ConsentsApi.consentsDeleteConsentsConsentId, must conform to the pattern /[\\w\\W\\s]*/.");
        }
        
        // verify the required parameter 'x_fapi_interaction_id' is set
        if ($x_fapi_interaction_id === null || (is_array($x_fapi_interaction_id) && count($x_fapi_interaction_id) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $x_fapi_interaction_id when calling consentsDeleteConsentsConsentId'
            );
        }
        if (strlen($x_fapi_interaction_id) > 36) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_interaction_id" when calling ConsentsApi.consentsDeleteConsentsConsentId, must be smaller than or equal to 36.');
        }
        if (strlen($x_fapi_interaction_id) < 1) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_interaction_id" when calling ConsentsApi.consentsDeleteConsentsConsentId, must be bigger than or equal to 1.');
        }
        if (!preg_match("/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/", $x_fapi_interaction_id)) {
            throw new \InvalidArgumentException("invalid value for \"x_fapi_interaction_id\" when calling ConsentsApi.consentsDeleteConsentsConsentId, must conform to the pattern /^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/.");
        }
        
        if ($x_fapi_auth_date !== null && strlen($x_fapi_auth_date) > 29) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_auth_date" when calling ConsentsApi.consentsDeleteConsentsConsentId, must be smaller than or equal to 29.');
        }
        if ($x_fapi_auth_date !== null && strlen($x_fapi_auth_date) < 29) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_auth_date" when calling ConsentsApi.consentsDeleteConsentsConsentId, must be bigger than or equal to 29.');
        }
        if ($x_fapi_auth_date !== null && !preg_match("/^(Mon|Tue|Wed|Thu|Fri|Sat|Sun), \\d{2} (Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec) \\d{4} \\d{2}:\\d{2}:\\d{2} (GMT|UTC)$/", $x_fapi_auth_date)) {
            throw new \InvalidArgumentException("invalid value for \"x_fapi_auth_date\" when calling ConsentsApi.consentsDeleteConsentsConsentId, must conform to the pattern /^(Mon|Tue|Wed|Thu|Fri|Sat|Sun), \\d{2} (Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec) \\d{4} \\d{2}:\\d{2}:\\d{2} (GMT|UTC)$/.");
        }
        
        if ($x_fapi_customer_ip_address !== null && strlen($x_fapi_customer_ip_address) > 100) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_customer_ip_address" when calling ConsentsApi.consentsDeleteConsentsConsentId, must be smaller than or equal to 100.');
        }
        if ($x_fapi_customer_ip_address !== null && strlen($x_fapi_customer_ip_address) < 1) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_customer_ip_address" when calling ConsentsApi.consentsDeleteConsentsConsentId, must be bigger than or equal to 1.');
        }
        if ($x_fapi_customer_ip_address !== null && !preg_match("/[\\w\\W\\s]*/", $x_fapi_customer_ip_address)) {
            throw new \InvalidArgumentException("invalid value for \"x_fapi_customer_ip_address\" when calling ConsentsApi.consentsDeleteConsentsConsentId, must conform to the pattern /[\\w\\W\\s]*/.");
        }
        
        if ($x_customer_user_agent !== null && strlen($x_customer_user_agent) > 255) {
            throw new \InvalidArgumentException('invalid length for "$x_customer_user_agent" when calling ConsentsApi.consentsDeleteConsentsConsentId, must be smaller than or equal to 255.');
        }
        if ($x_customer_user_agent !== null && strlen($x_customer_user_agent) < 1) {
            throw new \InvalidArgumentException('invalid length for "$x_customer_user_agent" when calling ConsentsApi.consentsDeleteConsentsConsentId, must be bigger than or equal to 1.');
        }
        if ($x_customer_user_agent !== null && !preg_match("/^[^\\s](.*[^\\s])?$/", $x_customer_user_agent)) {
            throw new \InvalidArgumentException("invalid value for \"x_customer_user_agent\" when calling ConsentsApi.consentsDeleteConsentsConsentId, must conform to the pattern /^[^\\s](.*[^\\s])?$/.");
        }
        

        $resourcePath = '/consents/{consentId}';
        $formParams = [];
        $queryParams = [];
        $headerParams = [];
        $httpBody = '';
        $multipart = false;


        // header params
        if ($authorization !== null) {
            $headerParams['Authorization'] = ObjectSerializer::toHeaderValue($authorization);
        }
        // header params
        if ($x_fapi_auth_date !== null) {
            $headerParams['x-fapi-auth-date'] = ObjectSerializer::toHeaderValue($x_fapi_auth_date);
        }
        // header params
        if ($x_fapi_customer_ip_address !== null) {
            $headerParams['x-fapi-customer-ip-address'] = ObjectSerializer::toHeaderValue($x_fapi_customer_ip_address);
        }
        // header params
        if ($x_fapi_interaction_id !== null) {
            $headerParams['x-fapi-interaction-id'] = ObjectSerializer::toHeaderValue($x_fapi_interaction_id);
        }
        // header params
        if ($x_customer_user_agent !== null) {
            $headerParams['x-customer-user-agent'] = ObjectSerializer::toHeaderValue($x_customer_user_agent);
        }

        // path params
        if ($consent_id !== null) {
            $resourcePath = str_replace(
                '{' . 'consentId' . '}',
                ObjectSerializer::toPathValue($consent_id),
                $resourcePath
            );
        }


        $headers = $this->headerSelector->selectHeaders(
            ['application/json; charset=utf-8', 'application/json', ],
            $contentType,
            $multipart
        );

        // for model (json/xml)
        if (count($formParams) > 0) {
            if ($multipart) {
                $multipartContents = [];
                foreach ($formParams as $formParamName => $formParamValue) {
                    $formParamValueItems = is_array($formParamValue) ? $formParamValue : [$formParamValue];
                    foreach ($formParamValueItems as $formParamValueItem) {
                        $multipartContents[] = [
                            'name' => $formParamName,
                            'contents' => $formParamValueItem
                        ];
                    }
                }
                // for HTTP post (form)
                $httpBody = new MultipartStream($multipartContents);

            } elseif (stripos($headers['Content-Type'], 'application/json') !== false) {
                # if Content-Type contains "application/json", json_encode the form parameters
                $httpBody = \GuzzleHttp\Utils::jsonEncode($formParams);
            } else {
                // for HTTP post (form)
                $httpBody = ObjectSerializer::buildQuery($formParams);
            }
        }

        // this endpoint requires OAuth (access token)
        if (!empty($this->config->getAccessToken())) {
            $headers['Authorization'] = 'Bearer ' . $this->config->getAccessToken();
        }

        $defaultHeaders = [];
        if ($this->config->getUserAgent()) {
            $defaultHeaders['User-Agent'] = $this->config->getUserAgent();
        }

        $headers = array_merge(
            $defaultHeaders,
            $headerParams,
            $headers
        );

        $operationHost = $this->config->getHost();
        $query = ObjectSerializer::buildQuery($queryParams);
        return new Request(
            'DELETE',
            $operationHost . $resourcePath . ($query ? "?{$query}" : ''),
            $headers,
            $httpBody
        );
    }

    /**
     * Operation consentsGetConsentsConsentId
     *
     * Obter detalhes do consentimento identificado por consentId.
     *
     * @param  string $consent_id O consentId é o identificador único do consentimento e deverá ser um URN - Uniform Resource Name.   Um URN, conforme definido na [RFC8141](https://tools.ietf.org/html/rfc8141) é um Uniform Resource  Identifier - URI - que é atribuído sob o URI scheme \&quot;urn\&quot; e um namespace URN específico, com a intenção de que o URN  seja um identificador de recurso persistente e independente da localização.   Considerando a string urn:bancoex:C1DD33123 como exemplo para consentId temos: - o namespace(urn) - o identificador associado ao namespace da instituição transnmissora (bancoex) - o identificador específico dentro do namespace (C1DD33123). Informações mais detalhadas sobre a construção de namespaces devem ser consultadas na [RFC8141](https://tools.ietf.org/html/rfc8141). (required)
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID RFC4122 usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser “espelhado” pela transmissora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a transmissora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A receptora deve acatar o valor recebido da transmissora. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['consentsGetConsentsConsentId'] to see the possible values for this operation
     *
     * @throws \OpenAPI\Client\ApiException on non-2xx response or if the response body is not in the expected format
     * @throws \InvalidArgumentException
     * @return \OpenAPI\Client\Model\ResponseConsentRead|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ConsentsPostConsents529Response|\OpenAPI\Client\Model\ResponseError
     */
    public function consentsGetConsentsConsentId($consent_id, $authorization, $x_fapi_interaction_id, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, string $contentType = self::contentTypes['consentsGetConsentsConsentId'][0])
    {
        list($response) = $this->consentsGetConsentsConsentIdWithHttpInfo($consent_id, $authorization, $x_fapi_interaction_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $contentType);
        return $response;
    }

    /**
     * Operation consentsGetConsentsConsentIdWithHttpInfo
     *
     * Obter detalhes do consentimento identificado por consentId.
     *
     * @param  string $consent_id O consentId é o identificador único do consentimento e deverá ser um URN - Uniform Resource Name.   Um URN, conforme definido na [RFC8141](https://tools.ietf.org/html/rfc8141) é um Uniform Resource  Identifier - URI - que é atribuído sob o URI scheme \&quot;urn\&quot; e um namespace URN específico, com a intenção de que o URN  seja um identificador de recurso persistente e independente da localização.   Considerando a string urn:bancoex:C1DD33123 como exemplo para consentId temos: - o namespace(urn) - o identificador associado ao namespace da instituição transnmissora (bancoex) - o identificador específico dentro do namespace (C1DD33123). Informações mais detalhadas sobre a construção de namespaces devem ser consultadas na [RFC8141](https://tools.ietf.org/html/rfc8141). (required)
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID RFC4122 usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser “espelhado” pela transmissora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a transmissora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A receptora deve acatar o valor recebido da transmissora. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['consentsGetConsentsConsentId'] to see the possible values for this operation
     *
     * @throws \OpenAPI\Client\ApiException on non-2xx response or if the response body is not in the expected format
     * @throws \InvalidArgumentException
     * @return array of \OpenAPI\Client\Model\ResponseConsentRead|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ConsentsPostConsents529Response|\OpenAPI\Client\Model\ResponseError, HTTP status code, HTTP response headers (array of strings)
     */
    public function consentsGetConsentsConsentIdWithHttpInfo($consent_id, $authorization, $x_fapi_interaction_id, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, string $contentType = self::contentTypes['consentsGetConsentsConsentId'][0])
    {
        $request = $this->consentsGetConsentsConsentIdRequest($consent_id, $authorization, $x_fapi_interaction_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $contentType);

        try {
            $options = $this->createHttpClientOption();
            try {
                $response = $this->client->send($request, $options);
            } catch (RequestException $e) {
                throw new ApiException(
                    "[{$e->getCode()}] {$e->getMessage()}",
                    (int) $e->getCode(),
                    $e->getResponse() ? $e->getResponse()->getHeaders() : null,
                    $e->getResponse() ? (string) $e->getResponse()->getBody() : null
                );
            } catch (ConnectException $e) {
                throw new ApiException(
                    "[{$e->getCode()}] {$e->getMessage()}",
                    (int) $e->getCode(),
                    null,
                    null
                );
            }

            $statusCode = $response->getStatusCode();


            switch($statusCode) {
                case 200:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseConsentRead',
                        $request,
                        $response,
                    );
                case 400:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 401:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 403:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 404:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 405:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 406:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 429:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 500:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 504:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 529:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ConsentsPostConsents529Response',
                        $request,
                        $response,
                    );
                default:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
            }

            

            if ($statusCode < 200 || $statusCode > 299) {
                throw new ApiException(
                    sprintf(
                        '[%d] Error connecting to the API (%s)',
                        $statusCode,
                        (string) $request->getUri()
                    ),
                    $statusCode,
                    $response->getHeaders(),
                    (string) $response->getBody()
                );
            }

            return $this->handleResponseWithDataType(
                '\OpenAPI\Client\Model\ResponseConsentRead',
                $request,
                $response,
            );
        } catch (ApiException $e) {
            switch ($e->getCode()) {
                case 200:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseConsentRead',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 400:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 401:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 403:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 404:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 405:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 406:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 429:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 500:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 504:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 529:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ConsentsPostConsents529Response',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                default:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
            }
        

            throw $e;
        }
    }

    /**
     * Operation consentsGetConsentsConsentIdAsync
     *
     * Obter detalhes do consentimento identificado por consentId.
     *
     * @param  string $consent_id O consentId é o identificador único do consentimento e deverá ser um URN - Uniform Resource Name.   Um URN, conforme definido na [RFC8141](https://tools.ietf.org/html/rfc8141) é um Uniform Resource  Identifier - URI - que é atribuído sob o URI scheme \&quot;urn\&quot; e um namespace URN específico, com a intenção de que o URN  seja um identificador de recurso persistente e independente da localização.   Considerando a string urn:bancoex:C1DD33123 como exemplo para consentId temos: - o namespace(urn) - o identificador associado ao namespace da instituição transnmissora (bancoex) - o identificador específico dentro do namespace (C1DD33123). Informações mais detalhadas sobre a construção de namespaces devem ser consultadas na [RFC8141](https://tools.ietf.org/html/rfc8141). (required)
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID RFC4122 usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser “espelhado” pela transmissora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a transmissora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A receptora deve acatar o valor recebido da transmissora. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['consentsGetConsentsConsentId'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function consentsGetConsentsConsentIdAsync($consent_id, $authorization, $x_fapi_interaction_id, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, string $contentType = self::contentTypes['consentsGetConsentsConsentId'][0])
    {
        return $this->consentsGetConsentsConsentIdAsyncWithHttpInfo($consent_id, $authorization, $x_fapi_interaction_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $contentType)
            ->then(
                function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation consentsGetConsentsConsentIdAsyncWithHttpInfo
     *
     * Obter detalhes do consentimento identificado por consentId.
     *
     * @param  string $consent_id O consentId é o identificador único do consentimento e deverá ser um URN - Uniform Resource Name.   Um URN, conforme definido na [RFC8141](https://tools.ietf.org/html/rfc8141) é um Uniform Resource  Identifier - URI - que é atribuído sob o URI scheme \&quot;urn\&quot; e um namespace URN específico, com a intenção de que o URN  seja um identificador de recurso persistente e independente da localização.   Considerando a string urn:bancoex:C1DD33123 como exemplo para consentId temos: - o namespace(urn) - o identificador associado ao namespace da instituição transnmissora (bancoex) - o identificador específico dentro do namespace (C1DD33123). Informações mais detalhadas sobre a construção de namespaces devem ser consultadas na [RFC8141](https://tools.ietf.org/html/rfc8141). (required)
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID RFC4122 usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser “espelhado” pela transmissora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a transmissora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A receptora deve acatar o valor recebido da transmissora. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['consentsGetConsentsConsentId'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function consentsGetConsentsConsentIdAsyncWithHttpInfo($consent_id, $authorization, $x_fapi_interaction_id, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, string $contentType = self::contentTypes['consentsGetConsentsConsentId'][0])
    {
        $returnType = '\OpenAPI\Client\Model\ResponseConsentRead';
        $request = $this->consentsGetConsentsConsentIdRequest($consent_id, $authorization, $x_fapi_interaction_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $contentType);

        return $this->client
            ->sendAsync($request, $this->createHttpClientOption())
            ->then(
                function ($response) use ($returnType) {
                    if ($returnType === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ($returnType !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, $returnType, []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
                },
                function ($exception) {
                    $response = $exception->getResponse();
                    $statusCode = $response->getStatusCode();
                    throw new ApiException(
                        sprintf(
                            '[%d] Error connecting to the API (%s)',
                            $statusCode,
                            $exception->getRequest()->getUri()
                        ),
                        $statusCode,
                        $response->getHeaders(),
                        (string) $response->getBody()
                    );
                }
            );
    }

    /**
     * Create request for operation 'consentsGetConsentsConsentId'
     *
     * @param  string $consent_id O consentId é o identificador único do consentimento e deverá ser um URN - Uniform Resource Name.   Um URN, conforme definido na [RFC8141](https://tools.ietf.org/html/rfc8141) é um Uniform Resource  Identifier - URI - que é atribuído sob o URI scheme \&quot;urn\&quot; e um namespace URN específico, com a intenção de que o URN  seja um identificador de recurso persistente e independente da localização.   Considerando a string urn:bancoex:C1DD33123 como exemplo para consentId temos: - o namespace(urn) - o identificador associado ao namespace da instituição transnmissora (bancoex) - o identificador específico dentro do namespace (C1DD33123). Informações mais detalhadas sobre a construção de namespaces devem ser consultadas na [RFC8141](https://tools.ietf.org/html/rfc8141). (required)
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID RFC4122 usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser “espelhado” pela transmissora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a transmissora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A receptora deve acatar o valor recebido da transmissora. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['consentsGetConsentsConsentId'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Psr7\Request
     */
    public function consentsGetConsentsConsentIdRequest($consent_id, $authorization, $x_fapi_interaction_id, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, string $contentType = self::contentTypes['consentsGetConsentsConsentId'][0])
    {

        // verify the required parameter 'consent_id' is set
        if ($consent_id === null || (is_array($consent_id) && count($consent_id) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $consent_id when calling consentsGetConsentsConsentId'
            );
        }
        if (strlen($consent_id) > 256) {
            throw new \InvalidArgumentException('invalid length for "$consent_id" when calling ConsentsApi.consentsGetConsentsConsentId, must be smaller than or equal to 256.');
        }
        if (strlen($consent_id) < 6) {
            throw new \InvalidArgumentException('invalid length for "$consent_id" when calling ConsentsApi.consentsGetConsentsConsentId, must be bigger than or equal to 6.');
        }
        if (!preg_match("/^urn:[a-zA-Z0-9][a-zA-Z0-9-]{0,31}:[a-zA-Z0-9()+,\\-.:=@;$_!*'%\/?#]+$/", $consent_id)) {
            throw new \InvalidArgumentException("invalid value for \"consent_id\" when calling ConsentsApi.consentsGetConsentsConsentId, must conform to the pattern /^urn:[a-zA-Z0-9][a-zA-Z0-9-]{0,31}:[a-zA-Z0-9()+,\\-.:=@;$_!*'%\/?#]+$/.");
        }
        
        // verify the required parameter 'authorization' is set
        if ($authorization === null || (is_array($authorization) && count($authorization) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $authorization when calling consentsGetConsentsConsentId'
            );
        }
        if (strlen($authorization) > 2048) {
            throw new \InvalidArgumentException('invalid length for "$authorization" when calling ConsentsApi.consentsGetConsentsConsentId, must be smaller than or equal to 2048.');
        }
        if (strlen($authorization) < 1) {
            throw new \InvalidArgumentException('invalid length for "$authorization" when calling ConsentsApi.consentsGetConsentsConsentId, must be bigger than or equal to 1.');
        }
        if (!preg_match("/[\\w\\W\\s]*/", $authorization)) {
            throw new \InvalidArgumentException("invalid value for \"authorization\" when calling ConsentsApi.consentsGetConsentsConsentId, must conform to the pattern /[\\w\\W\\s]*/.");
        }
        
        // verify the required parameter 'x_fapi_interaction_id' is set
        if ($x_fapi_interaction_id === null || (is_array($x_fapi_interaction_id) && count($x_fapi_interaction_id) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $x_fapi_interaction_id when calling consentsGetConsentsConsentId'
            );
        }
        if (strlen($x_fapi_interaction_id) > 36) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_interaction_id" when calling ConsentsApi.consentsGetConsentsConsentId, must be smaller than or equal to 36.');
        }
        if (strlen($x_fapi_interaction_id) < 1) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_interaction_id" when calling ConsentsApi.consentsGetConsentsConsentId, must be bigger than or equal to 1.');
        }
        if (!preg_match("/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/", $x_fapi_interaction_id)) {
            throw new \InvalidArgumentException("invalid value for \"x_fapi_interaction_id\" when calling ConsentsApi.consentsGetConsentsConsentId, must conform to the pattern /^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/.");
        }
        
        if ($x_fapi_auth_date !== null && strlen($x_fapi_auth_date) > 29) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_auth_date" when calling ConsentsApi.consentsGetConsentsConsentId, must be smaller than or equal to 29.');
        }
        if ($x_fapi_auth_date !== null && strlen($x_fapi_auth_date) < 29) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_auth_date" when calling ConsentsApi.consentsGetConsentsConsentId, must be bigger than or equal to 29.');
        }
        if ($x_fapi_auth_date !== null && !preg_match("/^(Mon|Tue|Wed|Thu|Fri|Sat|Sun), \\d{2} (Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec) \\d{4} \\d{2}:\\d{2}:\\d{2} (GMT|UTC)$/", $x_fapi_auth_date)) {
            throw new \InvalidArgumentException("invalid value for \"x_fapi_auth_date\" when calling ConsentsApi.consentsGetConsentsConsentId, must conform to the pattern /^(Mon|Tue|Wed|Thu|Fri|Sat|Sun), \\d{2} (Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec) \\d{4} \\d{2}:\\d{2}:\\d{2} (GMT|UTC)$/.");
        }
        
        if ($x_fapi_customer_ip_address !== null && strlen($x_fapi_customer_ip_address) > 100) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_customer_ip_address" when calling ConsentsApi.consentsGetConsentsConsentId, must be smaller than or equal to 100.');
        }
        if ($x_fapi_customer_ip_address !== null && strlen($x_fapi_customer_ip_address) < 1) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_customer_ip_address" when calling ConsentsApi.consentsGetConsentsConsentId, must be bigger than or equal to 1.');
        }
        if ($x_fapi_customer_ip_address !== null && !preg_match("/[\\w\\W\\s]*/", $x_fapi_customer_ip_address)) {
            throw new \InvalidArgumentException("invalid value for \"x_fapi_customer_ip_address\" when calling ConsentsApi.consentsGetConsentsConsentId, must conform to the pattern /[\\w\\W\\s]*/.");
        }
        
        if ($x_customer_user_agent !== null && strlen($x_customer_user_agent) > 255) {
            throw new \InvalidArgumentException('invalid length for "$x_customer_user_agent" when calling ConsentsApi.consentsGetConsentsConsentId, must be smaller than or equal to 255.');
        }
        if ($x_customer_user_agent !== null && strlen($x_customer_user_agent) < 1) {
            throw new \InvalidArgumentException('invalid length for "$x_customer_user_agent" when calling ConsentsApi.consentsGetConsentsConsentId, must be bigger than or equal to 1.');
        }
        if ($x_customer_user_agent !== null && !preg_match("/^[^\\s](.*[^\\s])?$/", $x_customer_user_agent)) {
            throw new \InvalidArgumentException("invalid value for \"x_customer_user_agent\" when calling ConsentsApi.consentsGetConsentsConsentId, must conform to the pattern /^[^\\s](.*[^\\s])?$/.");
        }
        

        $resourcePath = '/consents/{consentId}';
        $formParams = [];
        $queryParams = [];
        $headerParams = [];
        $httpBody = '';
        $multipart = false;


        // header params
        if ($authorization !== null) {
            $headerParams['Authorization'] = ObjectSerializer::toHeaderValue($authorization);
        }
        // header params
        if ($x_fapi_auth_date !== null) {
            $headerParams['x-fapi-auth-date'] = ObjectSerializer::toHeaderValue($x_fapi_auth_date);
        }
        // header params
        if ($x_fapi_customer_ip_address !== null) {
            $headerParams['x-fapi-customer-ip-address'] = ObjectSerializer::toHeaderValue($x_fapi_customer_ip_address);
        }
        // header params
        if ($x_fapi_interaction_id !== null) {
            $headerParams['x-fapi-interaction-id'] = ObjectSerializer::toHeaderValue($x_fapi_interaction_id);
        }
        // header params
        if ($x_customer_user_agent !== null) {
            $headerParams['x-customer-user-agent'] = ObjectSerializer::toHeaderValue($x_customer_user_agent);
        }

        // path params
        if ($consent_id !== null) {
            $resourcePath = str_replace(
                '{' . 'consentId' . '}',
                ObjectSerializer::toPathValue($consent_id),
                $resourcePath
            );
        }


        $headers = $this->headerSelector->selectHeaders(
            ['application/json', 'application/json; charset=utf-8', ],
            $contentType,
            $multipart
        );

        // for model (json/xml)
        if (count($formParams) > 0) {
            if ($multipart) {
                $multipartContents = [];
                foreach ($formParams as $formParamName => $formParamValue) {
                    $formParamValueItems = is_array($formParamValue) ? $formParamValue : [$formParamValue];
                    foreach ($formParamValueItems as $formParamValueItem) {
                        $multipartContents[] = [
                            'name' => $formParamName,
                            'contents' => $formParamValueItem
                        ];
                    }
                }
                // for HTTP post (form)
                $httpBody = new MultipartStream($multipartContents);

            } elseif (stripos($headers['Content-Type'], 'application/json') !== false) {
                # if Content-Type contains "application/json", json_encode the form parameters
                $httpBody = \GuzzleHttp\Utils::jsonEncode($formParams);
            } else {
                // for HTTP post (form)
                $httpBody = ObjectSerializer::buildQuery($formParams);
            }
        }

        // this endpoint requires OAuth (access token)
        if (!empty($this->config->getAccessToken())) {
            $headers['Authorization'] = 'Bearer ' . $this->config->getAccessToken();
        }

        $defaultHeaders = [];
        if ($this->config->getUserAgent()) {
            $defaultHeaders['User-Agent'] = $this->config->getUserAgent();
        }

        $headers = array_merge(
            $defaultHeaders,
            $headerParams,
            $headers
        );

        $operationHost = $this->config->getHost();
        $query = ObjectSerializer::buildQuery($queryParams);
        return new Request(
            'GET',
            $operationHost . $resourcePath . ($query ? "?{$query}" : ''),
            $headers,
            $httpBody
        );
    }

    /**
     * Operation consentsGetConsentsConsentIdExtensions
     *
     * Obter detalhes de extensões feitas no consentimento identificado por consentId.
     *
     * @param  string $consent_id O consentId é o identificador único do consentimento e deverá ser um URN - Uniform Resource Name.   Um URN, conforme definido na [RFC8141](https://tools.ietf.org/html/rfc8141) é um Uniform Resource  Identifier - URI - que é atribuído sob o URI scheme \&quot;urn\&quot; e um namespace URN específico, com a intenção de que o URN  seja um identificador de recurso persistente e independente da localização.   Considerando a string urn:bancoex:C1DD33123 como exemplo para consentId temos: - o namespace(urn) - o identificador associado ao namespace da instituição transnmissora (bancoex) - o identificador específico dentro do namespace (C1DD33123). Informações mais detalhadas sobre a construção de namespaces devem ser consultadas na [RFC8141](https://tools.ietf.org/html/rfc8141). (required)
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID RFC4122 usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser “espelhado” pela transmissora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a transmissora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A receptora deve acatar o valor recebido da transmissora. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  int|null $page Número da página que está sendo requisitada (o valor da primeira página é 1). (optional, default to 1)
     * @param  int|null $page_size Quantidade total de registros por páginas. A transmissora deve considerar entrada como 25, caso seja informado algum valor menor pela receptora. Enquanto houver mais que 25 registros a enviar, a transmissora deve considerar o mínimo por página como 25. Somente a última página retornada (ou primeira, no caso de página única) pode conter menos de 25 registros. Mais informações, acesse Especificações de APIs &gt; Padrões &gt; Paginação. (optional, default to 25)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['consentsGetConsentsConsentIdExtensions'] to see the possible values for this operation
     *
     * @throws \OpenAPI\Client\ApiException on non-2xx response or if the response body is not in the expected format
     * @throws \InvalidArgumentException
     * @return \OpenAPI\Client\Model\ResponseConsentReadExtensions|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ConsentsPostConsents529Response|\OpenAPI\Client\Model\ResponseError
     */
    public function consentsGetConsentsConsentIdExtensions($consent_id, $authorization, $x_fapi_interaction_id, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, $page = 1, $page_size = 25, string $contentType = self::contentTypes['consentsGetConsentsConsentIdExtensions'][0])
    {
        list($response) = $this->consentsGetConsentsConsentIdExtensionsWithHttpInfo($consent_id, $authorization, $x_fapi_interaction_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $page, $page_size, $contentType);
        return $response;
    }

    /**
     * Operation consentsGetConsentsConsentIdExtensionsWithHttpInfo
     *
     * Obter detalhes de extensões feitas no consentimento identificado por consentId.
     *
     * @param  string $consent_id O consentId é o identificador único do consentimento e deverá ser um URN - Uniform Resource Name.   Um URN, conforme definido na [RFC8141](https://tools.ietf.org/html/rfc8141) é um Uniform Resource  Identifier - URI - que é atribuído sob o URI scheme \&quot;urn\&quot; e um namespace URN específico, com a intenção de que o URN  seja um identificador de recurso persistente e independente da localização.   Considerando a string urn:bancoex:C1DD33123 como exemplo para consentId temos: - o namespace(urn) - o identificador associado ao namespace da instituição transnmissora (bancoex) - o identificador específico dentro do namespace (C1DD33123). Informações mais detalhadas sobre a construção de namespaces devem ser consultadas na [RFC8141](https://tools.ietf.org/html/rfc8141). (required)
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID RFC4122 usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser “espelhado” pela transmissora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a transmissora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A receptora deve acatar o valor recebido da transmissora. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  int|null $page Número da página que está sendo requisitada (o valor da primeira página é 1). (optional, default to 1)
     * @param  int|null $page_size Quantidade total de registros por páginas. A transmissora deve considerar entrada como 25, caso seja informado algum valor menor pela receptora. Enquanto houver mais que 25 registros a enviar, a transmissora deve considerar o mínimo por página como 25. Somente a última página retornada (ou primeira, no caso de página única) pode conter menos de 25 registros. Mais informações, acesse Especificações de APIs &gt; Padrões &gt; Paginação. (optional, default to 25)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['consentsGetConsentsConsentIdExtensions'] to see the possible values for this operation
     *
     * @throws \OpenAPI\Client\ApiException on non-2xx response or if the response body is not in the expected format
     * @throws \InvalidArgumentException
     * @return array of \OpenAPI\Client\Model\ResponseConsentReadExtensions|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ConsentsPostConsents529Response|\OpenAPI\Client\Model\ResponseError, HTTP status code, HTTP response headers (array of strings)
     */
    public function consentsGetConsentsConsentIdExtensionsWithHttpInfo($consent_id, $authorization, $x_fapi_interaction_id, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, $page = 1, $page_size = 25, string $contentType = self::contentTypes['consentsGetConsentsConsentIdExtensions'][0])
    {
        $request = $this->consentsGetConsentsConsentIdExtensionsRequest($consent_id, $authorization, $x_fapi_interaction_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $page, $page_size, $contentType);

        try {
            $options = $this->createHttpClientOption();
            try {
                $response = $this->client->send($request, $options);
            } catch (RequestException $e) {
                throw new ApiException(
                    "[{$e->getCode()}] {$e->getMessage()}",
                    (int) $e->getCode(),
                    $e->getResponse() ? $e->getResponse()->getHeaders() : null,
                    $e->getResponse() ? (string) $e->getResponse()->getBody() : null
                );
            } catch (ConnectException $e) {
                throw new ApiException(
                    "[{$e->getCode()}] {$e->getMessage()}",
                    (int) $e->getCode(),
                    null,
                    null
                );
            }

            $statusCode = $response->getStatusCode();


            switch($statusCode) {
                case 200:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseConsentReadExtensions',
                        $request,
                        $response,
                    );
                case 400:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 401:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 403:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 404:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 405:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 406:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 429:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 500:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 504:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 529:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ConsentsPostConsents529Response',
                        $request,
                        $response,
                    );
                default:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
            }

            

            if ($statusCode < 200 || $statusCode > 299) {
                throw new ApiException(
                    sprintf(
                        '[%d] Error connecting to the API (%s)',
                        $statusCode,
                        (string) $request->getUri()
                    ),
                    $statusCode,
                    $response->getHeaders(),
                    (string) $response->getBody()
                );
            }

            return $this->handleResponseWithDataType(
                '\OpenAPI\Client\Model\ResponseConsentReadExtensions',
                $request,
                $response,
            );
        } catch (ApiException $e) {
            switch ($e->getCode()) {
                case 200:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseConsentReadExtensions',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 400:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 401:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 403:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 404:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 405:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 406:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 429:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 500:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 504:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 529:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ConsentsPostConsents529Response',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                default:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
            }
        

            throw $e;
        }
    }

    /**
     * Operation consentsGetConsentsConsentIdExtensionsAsync
     *
     * Obter detalhes de extensões feitas no consentimento identificado por consentId.
     *
     * @param  string $consent_id O consentId é o identificador único do consentimento e deverá ser um URN - Uniform Resource Name.   Um URN, conforme definido na [RFC8141](https://tools.ietf.org/html/rfc8141) é um Uniform Resource  Identifier - URI - que é atribuído sob o URI scheme \&quot;urn\&quot; e um namespace URN específico, com a intenção de que o URN  seja um identificador de recurso persistente e independente da localização.   Considerando a string urn:bancoex:C1DD33123 como exemplo para consentId temos: - o namespace(urn) - o identificador associado ao namespace da instituição transnmissora (bancoex) - o identificador específico dentro do namespace (C1DD33123). Informações mais detalhadas sobre a construção de namespaces devem ser consultadas na [RFC8141](https://tools.ietf.org/html/rfc8141). (required)
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID RFC4122 usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser “espelhado” pela transmissora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a transmissora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A receptora deve acatar o valor recebido da transmissora. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  int|null $page Número da página que está sendo requisitada (o valor da primeira página é 1). (optional, default to 1)
     * @param  int|null $page_size Quantidade total de registros por páginas. A transmissora deve considerar entrada como 25, caso seja informado algum valor menor pela receptora. Enquanto houver mais que 25 registros a enviar, a transmissora deve considerar o mínimo por página como 25. Somente a última página retornada (ou primeira, no caso de página única) pode conter menos de 25 registros. Mais informações, acesse Especificações de APIs &gt; Padrões &gt; Paginação. (optional, default to 25)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['consentsGetConsentsConsentIdExtensions'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function consentsGetConsentsConsentIdExtensionsAsync($consent_id, $authorization, $x_fapi_interaction_id, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, $page = 1, $page_size = 25, string $contentType = self::contentTypes['consentsGetConsentsConsentIdExtensions'][0])
    {
        return $this->consentsGetConsentsConsentIdExtensionsAsyncWithHttpInfo($consent_id, $authorization, $x_fapi_interaction_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $page, $page_size, $contentType)
            ->then(
                function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation consentsGetConsentsConsentIdExtensionsAsyncWithHttpInfo
     *
     * Obter detalhes de extensões feitas no consentimento identificado por consentId.
     *
     * @param  string $consent_id O consentId é o identificador único do consentimento e deverá ser um URN - Uniform Resource Name.   Um URN, conforme definido na [RFC8141](https://tools.ietf.org/html/rfc8141) é um Uniform Resource  Identifier - URI - que é atribuído sob o URI scheme \&quot;urn\&quot; e um namespace URN específico, com a intenção de que o URN  seja um identificador de recurso persistente e independente da localização.   Considerando a string urn:bancoex:C1DD33123 como exemplo para consentId temos: - o namespace(urn) - o identificador associado ao namespace da instituição transnmissora (bancoex) - o identificador específico dentro do namespace (C1DD33123). Informações mais detalhadas sobre a construção de namespaces devem ser consultadas na [RFC8141](https://tools.ietf.org/html/rfc8141). (required)
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID RFC4122 usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser “espelhado” pela transmissora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a transmissora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A receptora deve acatar o valor recebido da transmissora. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  int|null $page Número da página que está sendo requisitada (o valor da primeira página é 1). (optional, default to 1)
     * @param  int|null $page_size Quantidade total de registros por páginas. A transmissora deve considerar entrada como 25, caso seja informado algum valor menor pela receptora. Enquanto houver mais que 25 registros a enviar, a transmissora deve considerar o mínimo por página como 25. Somente a última página retornada (ou primeira, no caso de página única) pode conter menos de 25 registros. Mais informações, acesse Especificações de APIs &gt; Padrões &gt; Paginação. (optional, default to 25)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['consentsGetConsentsConsentIdExtensions'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function consentsGetConsentsConsentIdExtensionsAsyncWithHttpInfo($consent_id, $authorization, $x_fapi_interaction_id, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, $page = 1, $page_size = 25, string $contentType = self::contentTypes['consentsGetConsentsConsentIdExtensions'][0])
    {
        $returnType = '\OpenAPI\Client\Model\ResponseConsentReadExtensions';
        $request = $this->consentsGetConsentsConsentIdExtensionsRequest($consent_id, $authorization, $x_fapi_interaction_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $page, $page_size, $contentType);

        return $this->client
            ->sendAsync($request, $this->createHttpClientOption())
            ->then(
                function ($response) use ($returnType) {
                    if ($returnType === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ($returnType !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, $returnType, []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
                },
                function ($exception) {
                    $response = $exception->getResponse();
                    $statusCode = $response->getStatusCode();
                    throw new ApiException(
                        sprintf(
                            '[%d] Error connecting to the API (%s)',
                            $statusCode,
                            $exception->getRequest()->getUri()
                        ),
                        $statusCode,
                        $response->getHeaders(),
                        (string) $response->getBody()
                    );
                }
            );
    }

    /**
     * Create request for operation 'consentsGetConsentsConsentIdExtensions'
     *
     * @param  string $consent_id O consentId é o identificador único do consentimento e deverá ser um URN - Uniform Resource Name.   Um URN, conforme definido na [RFC8141](https://tools.ietf.org/html/rfc8141) é um Uniform Resource  Identifier - URI - que é atribuído sob o URI scheme \&quot;urn\&quot; e um namespace URN específico, com a intenção de que o URN  seja um identificador de recurso persistente e independente da localização.   Considerando a string urn:bancoex:C1DD33123 como exemplo para consentId temos: - o namespace(urn) - o identificador associado ao namespace da instituição transnmissora (bancoex) - o identificador específico dentro do namespace (C1DD33123). Informações mais detalhadas sobre a construção de namespaces devem ser consultadas na [RFC8141](https://tools.ietf.org/html/rfc8141). (required)
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID RFC4122 usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser “espelhado” pela transmissora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a transmissora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A receptora deve acatar o valor recebido da transmissora. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  int|null $page Número da página que está sendo requisitada (o valor da primeira página é 1). (optional, default to 1)
     * @param  int|null $page_size Quantidade total de registros por páginas. A transmissora deve considerar entrada como 25, caso seja informado algum valor menor pela receptora. Enquanto houver mais que 25 registros a enviar, a transmissora deve considerar o mínimo por página como 25. Somente a última página retornada (ou primeira, no caso de página única) pode conter menos de 25 registros. Mais informações, acesse Especificações de APIs &gt; Padrões &gt; Paginação. (optional, default to 25)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['consentsGetConsentsConsentIdExtensions'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Psr7\Request
     */
    public function consentsGetConsentsConsentIdExtensionsRequest($consent_id, $authorization, $x_fapi_interaction_id, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, $page = 1, $page_size = 25, string $contentType = self::contentTypes['consentsGetConsentsConsentIdExtensions'][0])
    {

        // verify the required parameter 'consent_id' is set
        if ($consent_id === null || (is_array($consent_id) && count($consent_id) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $consent_id when calling consentsGetConsentsConsentIdExtensions'
            );
        }
        if (strlen($consent_id) > 256) {
            throw new \InvalidArgumentException('invalid length for "$consent_id" when calling ConsentsApi.consentsGetConsentsConsentIdExtensions, must be smaller than or equal to 256.');
        }
        if (strlen($consent_id) < 6) {
            throw new \InvalidArgumentException('invalid length for "$consent_id" when calling ConsentsApi.consentsGetConsentsConsentIdExtensions, must be bigger than or equal to 6.');
        }
        if (!preg_match("/^urn:[a-zA-Z0-9][a-zA-Z0-9-]{0,31}:[a-zA-Z0-9()+,\\-.:=@;$_!*'%\/?#]+$/", $consent_id)) {
            throw new \InvalidArgumentException("invalid value for \"consent_id\" when calling ConsentsApi.consentsGetConsentsConsentIdExtensions, must conform to the pattern /^urn:[a-zA-Z0-9][a-zA-Z0-9-]{0,31}:[a-zA-Z0-9()+,\\-.:=@;$_!*'%\/?#]+$/.");
        }
        
        // verify the required parameter 'authorization' is set
        if ($authorization === null || (is_array($authorization) && count($authorization) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $authorization when calling consentsGetConsentsConsentIdExtensions'
            );
        }
        if (strlen($authorization) > 2048) {
            throw new \InvalidArgumentException('invalid length for "$authorization" when calling ConsentsApi.consentsGetConsentsConsentIdExtensions, must be smaller than or equal to 2048.');
        }
        if (strlen($authorization) < 1) {
            throw new \InvalidArgumentException('invalid length for "$authorization" when calling ConsentsApi.consentsGetConsentsConsentIdExtensions, must be bigger than or equal to 1.');
        }
        if (!preg_match("/[^\\s][\\w\\W\\s][^\\s]*/", $authorization)) {
            throw new \InvalidArgumentException("invalid value for \"authorization\" when calling ConsentsApi.consentsGetConsentsConsentIdExtensions, must conform to the pattern /[^\\s][\\w\\W\\s][^\\s]*/.");
        }
        
        // verify the required parameter 'x_fapi_interaction_id' is set
        if ($x_fapi_interaction_id === null || (is_array($x_fapi_interaction_id) && count($x_fapi_interaction_id) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $x_fapi_interaction_id when calling consentsGetConsentsConsentIdExtensions'
            );
        }
        if (strlen($x_fapi_interaction_id) > 36) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_interaction_id" when calling ConsentsApi.consentsGetConsentsConsentIdExtensions, must be smaller than or equal to 36.');
        }
        if (strlen($x_fapi_interaction_id) < 1) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_interaction_id" when calling ConsentsApi.consentsGetConsentsConsentIdExtensions, must be bigger than or equal to 1.');
        }
        if (!preg_match("/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/", $x_fapi_interaction_id)) {
            throw new \InvalidArgumentException("invalid value for \"x_fapi_interaction_id\" when calling ConsentsApi.consentsGetConsentsConsentIdExtensions, must conform to the pattern /^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/.");
        }
        
        if ($x_fapi_auth_date !== null && strlen($x_fapi_auth_date) > 29) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_auth_date" when calling ConsentsApi.consentsGetConsentsConsentIdExtensions, must be smaller than or equal to 29.');
        }
        if ($x_fapi_auth_date !== null && strlen($x_fapi_auth_date) < 29) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_auth_date" when calling ConsentsApi.consentsGetConsentsConsentIdExtensions, must be bigger than or equal to 29.');
        }
        if ($x_fapi_auth_date !== null && !preg_match("/^(Mon|Tue|Wed|Thu|Fri|Sat|Sun), \\d{2} (Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec) \\d{4} \\d{2}:\\d{2}:\\d{2} (GMT|UTC)$/", $x_fapi_auth_date)) {
            throw new \InvalidArgumentException("invalid value for \"x_fapi_auth_date\" when calling ConsentsApi.consentsGetConsentsConsentIdExtensions, must conform to the pattern /^(Mon|Tue|Wed|Thu|Fri|Sat|Sun), \\d{2} (Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec) \\d{4} \\d{2}:\\d{2}:\\d{2} (GMT|UTC)$/.");
        }
        
        if ($x_fapi_customer_ip_address !== null && strlen($x_fapi_customer_ip_address) > 100) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_customer_ip_address" when calling ConsentsApi.consentsGetConsentsConsentIdExtensions, must be smaller than or equal to 100.');
        }
        if ($x_fapi_customer_ip_address !== null && strlen($x_fapi_customer_ip_address) < 1) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_customer_ip_address" when calling ConsentsApi.consentsGetConsentsConsentIdExtensions, must be bigger than or equal to 1.');
        }
        if ($x_fapi_customer_ip_address !== null && !preg_match("/[\\w\\W\\s]*/", $x_fapi_customer_ip_address)) {
            throw new \InvalidArgumentException("invalid value for \"x_fapi_customer_ip_address\" when calling ConsentsApi.consentsGetConsentsConsentIdExtensions, must conform to the pattern /[\\w\\W\\s]*/.");
        }
        
        if ($x_customer_user_agent !== null && strlen($x_customer_user_agent) > 255) {
            throw new \InvalidArgumentException('invalid length for "$x_customer_user_agent" when calling ConsentsApi.consentsGetConsentsConsentIdExtensions, must be smaller than or equal to 255.');
        }
        if ($x_customer_user_agent !== null && strlen($x_customer_user_agent) < 1) {
            throw new \InvalidArgumentException('invalid length for "$x_customer_user_agent" when calling ConsentsApi.consentsGetConsentsConsentIdExtensions, must be bigger than or equal to 1.');
        }
        if ($x_customer_user_agent !== null && !preg_match("/^[^\\s](.*[^\\s])?$/", $x_customer_user_agent)) {
            throw new \InvalidArgumentException("invalid value for \"x_customer_user_agent\" when calling ConsentsApi.consentsGetConsentsConsentIdExtensions, must conform to the pattern /^[^\\s](.*[^\\s])?$/.");
        }
        
        if ($page !== null && $page > 2147483647) {
            throw new \InvalidArgumentException('invalid value for "$page" when calling ConsentsApi.consentsGetConsentsConsentIdExtensions, must be smaller than or equal to 2147483647.');
        }
        if ($page !== null && $page < 1) {
            throw new \InvalidArgumentException('invalid value for "$page" when calling ConsentsApi.consentsGetConsentsConsentIdExtensions, must be bigger than or equal to 1.');
        }
        
        if ($page_size !== null && $page_size > 1000) {
            throw new \InvalidArgumentException('invalid value for "$page_size" when calling ConsentsApi.consentsGetConsentsConsentIdExtensions, must be smaller than or equal to 1000.');
        }
        if ($page_size !== null && $page_size < 25) {
            throw new \InvalidArgumentException('invalid value for "$page_size" when calling ConsentsApi.consentsGetConsentsConsentIdExtensions, must be bigger than or equal to 25.');
        }
        

        $resourcePath = '/consents/{consentId}/extensions';
        $formParams = [];
        $queryParams = [];
        $headerParams = [];
        $httpBody = '';
        $multipart = false;

        // query params
        $queryParams = array_merge($queryParams, ObjectSerializer::toQueryValue(
            $page,
            'page', // param base name
            'integer', // openApiType
            'form', // style
            true, // explode
            false // required
        ) ?? []);
        // query params
        $queryParams = array_merge($queryParams, ObjectSerializer::toQueryValue(
            $page_size,
            'page-size', // param base name
            'integer', // openApiType
            'form', // style
            true, // explode
            false // required
        ) ?? []);

        // header params
        if ($authorization !== null) {
            $headerParams['Authorization'] = ObjectSerializer::toHeaderValue($authorization);
        }
        // header params
        if ($x_fapi_auth_date !== null) {
            $headerParams['x-fapi-auth-date'] = ObjectSerializer::toHeaderValue($x_fapi_auth_date);
        }
        // header params
        if ($x_fapi_customer_ip_address !== null) {
            $headerParams['x-fapi-customer-ip-address'] = ObjectSerializer::toHeaderValue($x_fapi_customer_ip_address);
        }
        // header params
        if ($x_fapi_interaction_id !== null) {
            $headerParams['x-fapi-interaction-id'] = ObjectSerializer::toHeaderValue($x_fapi_interaction_id);
        }
        // header params
        if ($x_customer_user_agent !== null) {
            $headerParams['x-customer-user-agent'] = ObjectSerializer::toHeaderValue($x_customer_user_agent);
        }

        // path params
        if ($consent_id !== null) {
            $resourcePath = str_replace(
                '{' . 'consentId' . '}',
                ObjectSerializer::toPathValue($consent_id),
                $resourcePath
            );
        }


        $headers = $this->headerSelector->selectHeaders(
            ['application/json', 'application/json; charset=utf-8', ],
            $contentType,
            $multipart
        );

        // for model (json/xml)
        if (count($formParams) > 0) {
            if ($multipart) {
                $multipartContents = [];
                foreach ($formParams as $formParamName => $formParamValue) {
                    $formParamValueItems = is_array($formParamValue) ? $formParamValue : [$formParamValue];
                    foreach ($formParamValueItems as $formParamValueItem) {
                        $multipartContents[] = [
                            'name' => $formParamName,
                            'contents' => $formParamValueItem
                        ];
                    }
                }
                // for HTTP post (form)
                $httpBody = new MultipartStream($multipartContents);

            } elseif (stripos($headers['Content-Type'], 'application/json') !== false) {
                # if Content-Type contains "application/json", json_encode the form parameters
                $httpBody = \GuzzleHttp\Utils::jsonEncode($formParams);
            } else {
                // for HTTP post (form)
                $httpBody = ObjectSerializer::buildQuery($formParams);
            }
        }

        // this endpoint requires OAuth (access token)
        if (!empty($this->config->getAccessToken())) {
            $headers['Authorization'] = 'Bearer ' . $this->config->getAccessToken();
        }

        $defaultHeaders = [];
        if ($this->config->getUserAgent()) {
            $defaultHeaders['User-Agent'] = $this->config->getUserAgent();
        }

        $headers = array_merge(
            $defaultHeaders,
            $headerParams,
            $headers
        );

        $operationHost = $this->config->getHost();
        $query = ObjectSerializer::buildQuery($queryParams);
        return new Request(
            'GET',
            $operationHost . $resourcePath . ($query ? "?{$query}" : ''),
            $headers,
            $httpBody
        );
    }

    /**
     * Operation consentsPostConsents
     *
     * Criar novo pedido de consentimento.
     *
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID RFC4122 usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser “espelhado” pela transmissora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a transmissora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A receptora deve acatar o valor recebido da transmissora. (required)
     * @param  \OpenAPI\Client\Model\CreateConsent $create_consent Payload para criação do consentimento. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['consentsPostConsents'] to see the possible values for this operation
     *
     * @throws \OpenAPI\Client\ApiException on non-2xx response or if the response body is not in the expected format
     * @throws \InvalidArgumentException
     * @return \OpenAPI\Client\Model\ResponseConsent|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseErrorUnprocessableEntity|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ConsentsPostConsents529Response|\OpenAPI\Client\Model\ResponseError
     */
    public function consentsPostConsents($authorization, $x_fapi_interaction_id, $create_consent, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, string $contentType = self::contentTypes['consentsPostConsents'][0])
    {
        list($response) = $this->consentsPostConsentsWithHttpInfo($authorization, $x_fapi_interaction_id, $create_consent, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $contentType);
        return $response;
    }

    /**
     * Operation consentsPostConsentsWithHttpInfo
     *
     * Criar novo pedido de consentimento.
     *
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID RFC4122 usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser “espelhado” pela transmissora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a transmissora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A receptora deve acatar o valor recebido da transmissora. (required)
     * @param  \OpenAPI\Client\Model\CreateConsent $create_consent Payload para criação do consentimento. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['consentsPostConsents'] to see the possible values for this operation
     *
     * @throws \OpenAPI\Client\ApiException on non-2xx response or if the response body is not in the expected format
     * @throws \InvalidArgumentException
     * @return array of \OpenAPI\Client\Model\ResponseConsent|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseErrorUnprocessableEntity|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ConsentsPostConsents529Response|\OpenAPI\Client\Model\ResponseError, HTTP status code, HTTP response headers (array of strings)
     */
    public function consentsPostConsentsWithHttpInfo($authorization, $x_fapi_interaction_id, $create_consent, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, string $contentType = self::contentTypes['consentsPostConsents'][0])
    {
        $request = $this->consentsPostConsentsRequest($authorization, $x_fapi_interaction_id, $create_consent, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $contentType);

        try {
            $options = $this->createHttpClientOption();
            try {
                $response = $this->client->send($request, $options);
            } catch (RequestException $e) {
                throw new ApiException(
                    "[{$e->getCode()}] {$e->getMessage()}",
                    (int) $e->getCode(),
                    $e->getResponse() ? $e->getResponse()->getHeaders() : null,
                    $e->getResponse() ? (string) $e->getResponse()->getBody() : null
                );
            } catch (ConnectException $e) {
                throw new ApiException(
                    "[{$e->getCode()}] {$e->getMessage()}",
                    (int) $e->getCode(),
                    null,
                    null
                );
            }

            $statusCode = $response->getStatusCode();


            switch($statusCode) {
                case 201:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseConsent',
                        $request,
                        $response,
                    );
                case 400:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 401:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 403:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 404:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 405:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 406:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 415:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 422:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseErrorUnprocessableEntity',
                        $request,
                        $response,
                    );
                case 429:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 500:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 504:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 529:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ConsentsPostConsents529Response',
                        $request,
                        $response,
                    );
                default:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
            }

            

            if ($statusCode < 200 || $statusCode > 299) {
                throw new ApiException(
                    sprintf(
                        '[%d] Error connecting to the API (%s)',
                        $statusCode,
                        (string) $request->getUri()
                    ),
                    $statusCode,
                    $response->getHeaders(),
                    (string) $response->getBody()
                );
            }

            return $this->handleResponseWithDataType(
                '\OpenAPI\Client\Model\ResponseConsent',
                $request,
                $response,
            );
        } catch (ApiException $e) {
            switch ($e->getCode()) {
                case 201:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseConsent',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 400:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 401:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 403:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 404:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 405:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 406:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 415:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 422:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseErrorUnprocessableEntity',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 429:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 500:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 504:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 529:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ConsentsPostConsents529Response',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                default:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
            }
        

            throw $e;
        }
    }

    /**
     * Operation consentsPostConsentsAsync
     *
     * Criar novo pedido de consentimento.
     *
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID RFC4122 usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser “espelhado” pela transmissora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a transmissora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A receptora deve acatar o valor recebido da transmissora. (required)
     * @param  \OpenAPI\Client\Model\CreateConsent $create_consent Payload para criação do consentimento. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['consentsPostConsents'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function consentsPostConsentsAsync($authorization, $x_fapi_interaction_id, $create_consent, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, string $contentType = self::contentTypes['consentsPostConsents'][0])
    {
        return $this->consentsPostConsentsAsyncWithHttpInfo($authorization, $x_fapi_interaction_id, $create_consent, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $contentType)
            ->then(
                function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation consentsPostConsentsAsyncWithHttpInfo
     *
     * Criar novo pedido de consentimento.
     *
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID RFC4122 usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser “espelhado” pela transmissora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a transmissora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A receptora deve acatar o valor recebido da transmissora. (required)
     * @param  \OpenAPI\Client\Model\CreateConsent $create_consent Payload para criação do consentimento. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['consentsPostConsents'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function consentsPostConsentsAsyncWithHttpInfo($authorization, $x_fapi_interaction_id, $create_consent, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, string $contentType = self::contentTypes['consentsPostConsents'][0])
    {
        $returnType = '\OpenAPI\Client\Model\ResponseConsent';
        $request = $this->consentsPostConsentsRequest($authorization, $x_fapi_interaction_id, $create_consent, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $contentType);

        return $this->client
            ->sendAsync($request, $this->createHttpClientOption())
            ->then(
                function ($response) use ($returnType) {
                    if ($returnType === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ($returnType !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, $returnType, []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
                },
                function ($exception) {
                    $response = $exception->getResponse();
                    $statusCode = $response->getStatusCode();
                    throw new ApiException(
                        sprintf(
                            '[%d] Error connecting to the API (%s)',
                            $statusCode,
                            $exception->getRequest()->getUri()
                        ),
                        $statusCode,
                        $response->getHeaders(),
                        (string) $response->getBody()
                    );
                }
            );
    }

    /**
     * Create request for operation 'consentsPostConsents'
     *
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID RFC4122 usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser “espelhado” pela transmissora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a transmissora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A receptora deve acatar o valor recebido da transmissora. (required)
     * @param  \OpenAPI\Client\Model\CreateConsent $create_consent Payload para criação do consentimento. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['consentsPostConsents'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Psr7\Request
     */
    public function consentsPostConsentsRequest($authorization, $x_fapi_interaction_id, $create_consent, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, string $contentType = self::contentTypes['consentsPostConsents'][0])
    {

        // verify the required parameter 'authorization' is set
        if ($authorization === null || (is_array($authorization) && count($authorization) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $authorization when calling consentsPostConsents'
            );
        }
        if (strlen($authorization) > 2048) {
            throw new \InvalidArgumentException('invalid length for "$authorization" when calling ConsentsApi.consentsPostConsents, must be smaller than or equal to 2048.');
        }
        if (strlen($authorization) < 1) {
            throw new \InvalidArgumentException('invalid length for "$authorization" when calling ConsentsApi.consentsPostConsents, must be bigger than or equal to 1.');
        }
        if (!preg_match("/[\\w\\W\\s]*/", $authorization)) {
            throw new \InvalidArgumentException("invalid value for \"authorization\" when calling ConsentsApi.consentsPostConsents, must conform to the pattern /[\\w\\W\\s]*/.");
        }
        
        // verify the required parameter 'x_fapi_interaction_id' is set
        if ($x_fapi_interaction_id === null || (is_array($x_fapi_interaction_id) && count($x_fapi_interaction_id) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $x_fapi_interaction_id when calling consentsPostConsents'
            );
        }
        if (strlen($x_fapi_interaction_id) > 36) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_interaction_id" when calling ConsentsApi.consentsPostConsents, must be smaller than or equal to 36.');
        }
        if (strlen($x_fapi_interaction_id) < 1) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_interaction_id" when calling ConsentsApi.consentsPostConsents, must be bigger than or equal to 1.');
        }
        if (!preg_match("/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/", $x_fapi_interaction_id)) {
            throw new \InvalidArgumentException("invalid value for \"x_fapi_interaction_id\" when calling ConsentsApi.consentsPostConsents, must conform to the pattern /^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/.");
        }
        
        // verify the required parameter 'create_consent' is set
        if ($create_consent === null || (is_array($create_consent) && count($create_consent) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $create_consent when calling consentsPostConsents'
            );
        }

        if ($x_fapi_auth_date !== null && strlen($x_fapi_auth_date) > 29) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_auth_date" when calling ConsentsApi.consentsPostConsents, must be smaller than or equal to 29.');
        }
        if ($x_fapi_auth_date !== null && strlen($x_fapi_auth_date) < 29) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_auth_date" when calling ConsentsApi.consentsPostConsents, must be bigger than or equal to 29.');
        }
        if ($x_fapi_auth_date !== null && !preg_match("/^(Mon|Tue|Wed|Thu|Fri|Sat|Sun), \\d{2} (Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec) \\d{4} \\d{2}:\\d{2}:\\d{2} (GMT|UTC)$/", $x_fapi_auth_date)) {
            throw new \InvalidArgumentException("invalid value for \"x_fapi_auth_date\" when calling ConsentsApi.consentsPostConsents, must conform to the pattern /^(Mon|Tue|Wed|Thu|Fri|Sat|Sun), \\d{2} (Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec) \\d{4} \\d{2}:\\d{2}:\\d{2} (GMT|UTC)$/.");
        }
        
        if ($x_fapi_customer_ip_address !== null && strlen($x_fapi_customer_ip_address) > 100) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_customer_ip_address" when calling ConsentsApi.consentsPostConsents, must be smaller than or equal to 100.');
        }
        if ($x_fapi_customer_ip_address !== null && strlen($x_fapi_customer_ip_address) < 1) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_customer_ip_address" when calling ConsentsApi.consentsPostConsents, must be bigger than or equal to 1.');
        }
        if ($x_fapi_customer_ip_address !== null && !preg_match("/[\\w\\W\\s]*/", $x_fapi_customer_ip_address)) {
            throw new \InvalidArgumentException("invalid value for \"x_fapi_customer_ip_address\" when calling ConsentsApi.consentsPostConsents, must conform to the pattern /[\\w\\W\\s]*/.");
        }
        
        if ($x_customer_user_agent !== null && strlen($x_customer_user_agent) > 255) {
            throw new \InvalidArgumentException('invalid length for "$x_customer_user_agent" when calling ConsentsApi.consentsPostConsents, must be smaller than or equal to 255.');
        }
        if ($x_customer_user_agent !== null && strlen($x_customer_user_agent) < 1) {
            throw new \InvalidArgumentException('invalid length for "$x_customer_user_agent" when calling ConsentsApi.consentsPostConsents, must be bigger than or equal to 1.');
        }
        if ($x_customer_user_agent !== null && !preg_match("/^[^\\s](.*[^\\s])?$/", $x_customer_user_agent)) {
            throw new \InvalidArgumentException("invalid value for \"x_customer_user_agent\" when calling ConsentsApi.consentsPostConsents, must conform to the pattern /^[^\\s](.*[^\\s])?$/.");
        }
        

        $resourcePath = '/consents';
        $formParams = [];
        $queryParams = [];
        $headerParams = [];
        $httpBody = '';
        $multipart = false;


        // header params
        if ($authorization !== null) {
            $headerParams['Authorization'] = ObjectSerializer::toHeaderValue($authorization);
        }
        // header params
        if ($x_fapi_auth_date !== null) {
            $headerParams['x-fapi-auth-date'] = ObjectSerializer::toHeaderValue($x_fapi_auth_date);
        }
        // header params
        if ($x_fapi_customer_ip_address !== null) {
            $headerParams['x-fapi-customer-ip-address'] = ObjectSerializer::toHeaderValue($x_fapi_customer_ip_address);
        }
        // header params
        if ($x_fapi_interaction_id !== null) {
            $headerParams['x-fapi-interaction-id'] = ObjectSerializer::toHeaderValue($x_fapi_interaction_id);
        }
        // header params
        if ($x_customer_user_agent !== null) {
            $headerParams['x-customer-user-agent'] = ObjectSerializer::toHeaderValue($x_customer_user_agent);
        }



        $headers = $this->headerSelector->selectHeaders(
            ['application/json', 'application/json; charset=utf-8', ],
            $contentType,
            $multipart
        );

        // for model (json/xml)
        if (isset($create_consent)) {
            if (stripos($headers['Content-Type'], 'application/json') !== false) {
                # if Content-Type contains "application/json", json_encode the body
                $httpBody = \GuzzleHttp\Utils::jsonEncode(ObjectSerializer::sanitizeForSerialization($create_consent));
            } else {
                $httpBody = $create_consent;
            }
        } elseif (count($formParams) > 0) {
            if ($multipart) {
                $multipartContents = [];
                foreach ($formParams as $formParamName => $formParamValue) {
                    $formParamValueItems = is_array($formParamValue) ? $formParamValue : [$formParamValue];
                    foreach ($formParamValueItems as $formParamValueItem) {
                        $multipartContents[] = [
                            'name' => $formParamName,
                            'contents' => $formParamValueItem
                        ];
                    }
                }
                // for HTTP post (form)
                $httpBody = new MultipartStream($multipartContents);

            } elseif (stripos($headers['Content-Type'], 'application/json') !== false) {
                # if Content-Type contains "application/json", json_encode the form parameters
                $httpBody = \GuzzleHttp\Utils::jsonEncode($formParams);
            } else {
                // for HTTP post (form)
                $httpBody = ObjectSerializer::buildQuery($formParams);
            }
        }

        // this endpoint requires OAuth (access token)
        if (!empty($this->config->getAccessToken())) {
            $headers['Authorization'] = 'Bearer ' . $this->config->getAccessToken();
        }

        $defaultHeaders = [];
        if ($this->config->getUserAgent()) {
            $defaultHeaders['User-Agent'] = $this->config->getUserAgent();
        }

        $headers = array_merge(
            $defaultHeaders,
            $headerParams,
            $headers
        );

        $operationHost = $this->config->getHost();
        $query = ObjectSerializer::buildQuery($queryParams);
        return new Request(
            'POST',
            $operationHost . $resourcePath . ($query ? "?{$query}" : ''),
            $headers,
            $httpBody
        );
    }

    /**
     * Operation consentsPostConsentsConsentIdExtends
     *
     * Renovar consentimento identificado por consentId.
     *
     * @param  string $consent_id O consentId é o identificador único do consentimento e deverá ser um URN - Uniform Resource Name.   Um URN, conforme definido na [RFC8141](https://tools.ietf.org/html/rfc8141) é um Uniform Resource  Identifier - URI - que é atribuído sob o URI scheme \&quot;urn\&quot; e um namespace URN específico, com a intenção de que o URN  seja um identificador de recurso persistente e independente da localização.   Considerando a string urn:bancoex:C1DD33123 como exemplo para consentId temos: - o namespace(urn) - o identificador associado ao namespace da instituição transnmissora (bancoex) - o identificador específico dentro do namespace (C1DD33123). Informações mais detalhadas sobre a construção de namespaces devem ser consultadas na [RFC8141](https://tools.ietf.org/html/rfc8141). (required)
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (required)
     * @param  string $x_fapi_interaction_id Um UUID RFC4122 usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser “espelhado” pela transmissora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a transmissora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A receptora deve acatar o valor recebido da transmissora. (required)
     * @param  string $x_customer_user_agent Indica o user-agent que o usuário utiliza. (required)
     * @param  \OpenAPI\Client\Model\CreateConsentExtensions $create_consent_extensions Payload para renovação do consentimento. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['consentsPostConsentsConsentIdExtends'] to see the possible values for this operation
     *
     * @throws \OpenAPI\Client\ApiException on non-2xx response or if the response body is not in the expected format
     * @throws \InvalidArgumentException
     * @return \OpenAPI\Client\Model\ResponseConsentExtensions|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\Model422ResponseErrorCreateConsent|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ConsentsPostConsents529Response|\OpenAPI\Client\Model\ResponseError
     */
    public function consentsPostConsentsConsentIdExtends($consent_id, $authorization, $x_fapi_customer_ip_address, $x_fapi_interaction_id, $x_customer_user_agent, $create_consent_extensions, $x_fapi_auth_date = null, string $contentType = self::contentTypes['consentsPostConsentsConsentIdExtends'][0])
    {
        list($response) = $this->consentsPostConsentsConsentIdExtendsWithHttpInfo($consent_id, $authorization, $x_fapi_customer_ip_address, $x_fapi_interaction_id, $x_customer_user_agent, $create_consent_extensions, $x_fapi_auth_date, $contentType);
        return $response;
    }

    /**
     * Operation consentsPostConsentsConsentIdExtendsWithHttpInfo
     *
     * Renovar consentimento identificado por consentId.
     *
     * @param  string $consent_id O consentId é o identificador único do consentimento e deverá ser um URN - Uniform Resource Name.   Um URN, conforme definido na [RFC8141](https://tools.ietf.org/html/rfc8141) é um Uniform Resource  Identifier - URI - que é atribuído sob o URI scheme \&quot;urn\&quot; e um namespace URN específico, com a intenção de que o URN  seja um identificador de recurso persistente e independente da localização.   Considerando a string urn:bancoex:C1DD33123 como exemplo para consentId temos: - o namespace(urn) - o identificador associado ao namespace da instituição transnmissora (bancoex) - o identificador específico dentro do namespace (C1DD33123). Informações mais detalhadas sobre a construção de namespaces devem ser consultadas na [RFC8141](https://tools.ietf.org/html/rfc8141). (required)
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (required)
     * @param  string $x_fapi_interaction_id Um UUID RFC4122 usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser “espelhado” pela transmissora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a transmissora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A receptora deve acatar o valor recebido da transmissora. (required)
     * @param  string $x_customer_user_agent Indica o user-agent que o usuário utiliza. (required)
     * @param  \OpenAPI\Client\Model\CreateConsentExtensions $create_consent_extensions Payload para renovação do consentimento. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['consentsPostConsentsConsentIdExtends'] to see the possible values for this operation
     *
     * @throws \OpenAPI\Client\ApiException on non-2xx response or if the response body is not in the expected format
     * @throws \InvalidArgumentException
     * @return array of \OpenAPI\Client\Model\ResponseConsentExtensions|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\Model422ResponseErrorCreateConsent|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ConsentsPostConsents529Response|\OpenAPI\Client\Model\ResponseError, HTTP status code, HTTP response headers (array of strings)
     */
    public function consentsPostConsentsConsentIdExtendsWithHttpInfo($consent_id, $authorization, $x_fapi_customer_ip_address, $x_fapi_interaction_id, $x_customer_user_agent, $create_consent_extensions, $x_fapi_auth_date = null, string $contentType = self::contentTypes['consentsPostConsentsConsentIdExtends'][0])
    {
        $request = $this->consentsPostConsentsConsentIdExtendsRequest($consent_id, $authorization, $x_fapi_customer_ip_address, $x_fapi_interaction_id, $x_customer_user_agent, $create_consent_extensions, $x_fapi_auth_date, $contentType);

        try {
            $options = $this->createHttpClientOption();
            try {
                $response = $this->client->send($request, $options);
            } catch (RequestException $e) {
                throw new ApiException(
                    "[{$e->getCode()}] {$e->getMessage()}",
                    (int) $e->getCode(),
                    $e->getResponse() ? $e->getResponse()->getHeaders() : null,
                    $e->getResponse() ? (string) $e->getResponse()->getBody() : null
                );
            } catch (ConnectException $e) {
                throw new ApiException(
                    "[{$e->getCode()}] {$e->getMessage()}",
                    (int) $e->getCode(),
                    null,
                    null
                );
            }

            $statusCode = $response->getStatusCode();


            switch($statusCode) {
                case 201:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseConsentExtensions',
                        $request,
                        $response,
                    );
                case 400:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 401:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 403:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 404:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 405:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 406:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 415:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 422:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\Model422ResponseErrorCreateConsent',
                        $request,
                        $response,
                    );
                case 429:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 500:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 504:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 529:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ConsentsPostConsents529Response',
                        $request,
                        $response,
                    );
                default:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
            }

            

            if ($statusCode < 200 || $statusCode > 299) {
                throw new ApiException(
                    sprintf(
                        '[%d] Error connecting to the API (%s)',
                        $statusCode,
                        (string) $request->getUri()
                    ),
                    $statusCode,
                    $response->getHeaders(),
                    (string) $response->getBody()
                );
            }

            return $this->handleResponseWithDataType(
                '\OpenAPI\Client\Model\ResponseConsentExtensions',
                $request,
                $response,
            );
        } catch (ApiException $e) {
            switch ($e->getCode()) {
                case 201:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseConsentExtensions',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 400:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 401:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 403:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 404:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 405:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 406:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 415:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 422:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\Model422ResponseErrorCreateConsent',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 429:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 500:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 504:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 529:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ConsentsPostConsents529Response',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                default:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
            }
        

            throw $e;
        }
    }

    /**
     * Operation consentsPostConsentsConsentIdExtendsAsync
     *
     * Renovar consentimento identificado por consentId.
     *
     * @param  string $consent_id O consentId é o identificador único do consentimento e deverá ser um URN - Uniform Resource Name.   Um URN, conforme definido na [RFC8141](https://tools.ietf.org/html/rfc8141) é um Uniform Resource  Identifier - URI - que é atribuído sob o URI scheme \&quot;urn\&quot; e um namespace URN específico, com a intenção de que o URN  seja um identificador de recurso persistente e independente da localização.   Considerando a string urn:bancoex:C1DD33123 como exemplo para consentId temos: - o namespace(urn) - o identificador associado ao namespace da instituição transnmissora (bancoex) - o identificador específico dentro do namespace (C1DD33123). Informações mais detalhadas sobre a construção de namespaces devem ser consultadas na [RFC8141](https://tools.ietf.org/html/rfc8141). (required)
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (required)
     * @param  string $x_fapi_interaction_id Um UUID RFC4122 usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser “espelhado” pela transmissora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a transmissora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A receptora deve acatar o valor recebido da transmissora. (required)
     * @param  string $x_customer_user_agent Indica o user-agent que o usuário utiliza. (required)
     * @param  \OpenAPI\Client\Model\CreateConsentExtensions $create_consent_extensions Payload para renovação do consentimento. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['consentsPostConsentsConsentIdExtends'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function consentsPostConsentsConsentIdExtendsAsync($consent_id, $authorization, $x_fapi_customer_ip_address, $x_fapi_interaction_id, $x_customer_user_agent, $create_consent_extensions, $x_fapi_auth_date = null, string $contentType = self::contentTypes['consentsPostConsentsConsentIdExtends'][0])
    {
        return $this->consentsPostConsentsConsentIdExtendsAsyncWithHttpInfo($consent_id, $authorization, $x_fapi_customer_ip_address, $x_fapi_interaction_id, $x_customer_user_agent, $create_consent_extensions, $x_fapi_auth_date, $contentType)
            ->then(
                function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation consentsPostConsentsConsentIdExtendsAsyncWithHttpInfo
     *
     * Renovar consentimento identificado por consentId.
     *
     * @param  string $consent_id O consentId é o identificador único do consentimento e deverá ser um URN - Uniform Resource Name.   Um URN, conforme definido na [RFC8141](https://tools.ietf.org/html/rfc8141) é um Uniform Resource  Identifier - URI - que é atribuído sob o URI scheme \&quot;urn\&quot; e um namespace URN específico, com a intenção de que o URN  seja um identificador de recurso persistente e independente da localização.   Considerando a string urn:bancoex:C1DD33123 como exemplo para consentId temos: - o namespace(urn) - o identificador associado ao namespace da instituição transnmissora (bancoex) - o identificador específico dentro do namespace (C1DD33123). Informações mais detalhadas sobre a construção de namespaces devem ser consultadas na [RFC8141](https://tools.ietf.org/html/rfc8141). (required)
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (required)
     * @param  string $x_fapi_interaction_id Um UUID RFC4122 usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser “espelhado” pela transmissora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a transmissora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A receptora deve acatar o valor recebido da transmissora. (required)
     * @param  string $x_customer_user_agent Indica o user-agent que o usuário utiliza. (required)
     * @param  \OpenAPI\Client\Model\CreateConsentExtensions $create_consent_extensions Payload para renovação do consentimento. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['consentsPostConsentsConsentIdExtends'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function consentsPostConsentsConsentIdExtendsAsyncWithHttpInfo($consent_id, $authorization, $x_fapi_customer_ip_address, $x_fapi_interaction_id, $x_customer_user_agent, $create_consent_extensions, $x_fapi_auth_date = null, string $contentType = self::contentTypes['consentsPostConsentsConsentIdExtends'][0])
    {
        $returnType = '\OpenAPI\Client\Model\ResponseConsentExtensions';
        $request = $this->consentsPostConsentsConsentIdExtendsRequest($consent_id, $authorization, $x_fapi_customer_ip_address, $x_fapi_interaction_id, $x_customer_user_agent, $create_consent_extensions, $x_fapi_auth_date, $contentType);

        return $this->client
            ->sendAsync($request, $this->createHttpClientOption())
            ->then(
                function ($response) use ($returnType) {
                    if ($returnType === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ($returnType !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, $returnType, []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
                },
                function ($exception) {
                    $response = $exception->getResponse();
                    $statusCode = $response->getStatusCode();
                    throw new ApiException(
                        sprintf(
                            '[%d] Error connecting to the API (%s)',
                            $statusCode,
                            $exception->getRequest()->getUri()
                        ),
                        $statusCode,
                        $response->getHeaders(),
                        (string) $response->getBody()
                    );
                }
            );
    }

    /**
     * Create request for operation 'consentsPostConsentsConsentIdExtends'
     *
     * @param  string $consent_id O consentId é o identificador único do consentimento e deverá ser um URN - Uniform Resource Name.   Um URN, conforme definido na [RFC8141](https://tools.ietf.org/html/rfc8141) é um Uniform Resource  Identifier - URI - que é atribuído sob o URI scheme \&quot;urn\&quot; e um namespace URN específico, com a intenção de que o URN  seja um identificador de recurso persistente e independente da localização.   Considerando a string urn:bancoex:C1DD33123 como exemplo para consentId temos: - o namespace(urn) - o identificador associado ao namespace da instituição transnmissora (bancoex) - o identificador específico dentro do namespace (C1DD33123). Informações mais detalhadas sobre a construção de namespaces devem ser consultadas na [RFC8141](https://tools.ietf.org/html/rfc8141). (required)
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (required)
     * @param  string $x_fapi_interaction_id Um UUID RFC4122 usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser “espelhado” pela transmissora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a transmissora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A receptora deve acatar o valor recebido da transmissora. (required)
     * @param  string $x_customer_user_agent Indica o user-agent que o usuário utiliza. (required)
     * @param  \OpenAPI\Client\Model\CreateConsentExtensions $create_consent_extensions Payload para renovação do consentimento. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['consentsPostConsentsConsentIdExtends'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Psr7\Request
     */
    public function consentsPostConsentsConsentIdExtendsRequest($consent_id, $authorization, $x_fapi_customer_ip_address, $x_fapi_interaction_id, $x_customer_user_agent, $create_consent_extensions, $x_fapi_auth_date = null, string $contentType = self::contentTypes['consentsPostConsentsConsentIdExtends'][0])
    {

        // verify the required parameter 'consent_id' is set
        if ($consent_id === null || (is_array($consent_id) && count($consent_id) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $consent_id when calling consentsPostConsentsConsentIdExtends'
            );
        }
        if (strlen($consent_id) > 256) {
            throw new \InvalidArgumentException('invalid length for "$consent_id" when calling ConsentsApi.consentsPostConsentsConsentIdExtends, must be smaller than or equal to 256.');
        }
        if (strlen($consent_id) < 6) {
            throw new \InvalidArgumentException('invalid length for "$consent_id" when calling ConsentsApi.consentsPostConsentsConsentIdExtends, must be bigger than or equal to 6.');
        }
        if (!preg_match("/^urn:[a-zA-Z0-9][a-zA-Z0-9-]{0,31}:[a-zA-Z0-9()+,\\-.:=@;$_!*'%\/?#]+$/", $consent_id)) {
            throw new \InvalidArgumentException("invalid value for \"consent_id\" when calling ConsentsApi.consentsPostConsentsConsentIdExtends, must conform to the pattern /^urn:[a-zA-Z0-9][a-zA-Z0-9-]{0,31}:[a-zA-Z0-9()+,\\-.:=@;$_!*'%\/?#]+$/.");
        }
        
        // verify the required parameter 'authorization' is set
        if ($authorization === null || (is_array($authorization) && count($authorization) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $authorization when calling consentsPostConsentsConsentIdExtends'
            );
        }
        if (strlen($authorization) > 2048) {
            throw new \InvalidArgumentException('invalid length for "$authorization" when calling ConsentsApi.consentsPostConsentsConsentIdExtends, must be smaller than or equal to 2048.');
        }
        if (strlen($authorization) < 1) {
            throw new \InvalidArgumentException('invalid length for "$authorization" when calling ConsentsApi.consentsPostConsentsConsentIdExtends, must be bigger than or equal to 1.');
        }
        if (!preg_match("/[^\\s][\\w\\W\\s][^\\s]*/", $authorization)) {
            throw new \InvalidArgumentException("invalid value for \"authorization\" when calling ConsentsApi.consentsPostConsentsConsentIdExtends, must conform to the pattern /[^\\s][\\w\\W\\s][^\\s]*/.");
        }
        
        // verify the required parameter 'x_fapi_customer_ip_address' is set
        if ($x_fapi_customer_ip_address === null || (is_array($x_fapi_customer_ip_address) && count($x_fapi_customer_ip_address) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $x_fapi_customer_ip_address when calling consentsPostConsentsConsentIdExtends'
            );
        }
        if (strlen($x_fapi_customer_ip_address) > 100) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_customer_ip_address" when calling ConsentsApi.consentsPostConsentsConsentIdExtends, must be smaller than or equal to 100.');
        }
        if (strlen($x_fapi_customer_ip_address) < 1) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_customer_ip_address" when calling ConsentsApi.consentsPostConsentsConsentIdExtends, must be bigger than or equal to 1.');
        }
        if (!preg_match("/[\\w\\W\\s]*/", $x_fapi_customer_ip_address)) {
            throw new \InvalidArgumentException("invalid value for \"x_fapi_customer_ip_address\" when calling ConsentsApi.consentsPostConsentsConsentIdExtends, must conform to the pattern /[\\w\\W\\s]*/.");
        }
        
        // verify the required parameter 'x_fapi_interaction_id' is set
        if ($x_fapi_interaction_id === null || (is_array($x_fapi_interaction_id) && count($x_fapi_interaction_id) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $x_fapi_interaction_id when calling consentsPostConsentsConsentIdExtends'
            );
        }
        if (strlen($x_fapi_interaction_id) > 36) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_interaction_id" when calling ConsentsApi.consentsPostConsentsConsentIdExtends, must be smaller than or equal to 36.');
        }
        if (strlen($x_fapi_interaction_id) < 1) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_interaction_id" when calling ConsentsApi.consentsPostConsentsConsentIdExtends, must be bigger than or equal to 1.');
        }
        if (!preg_match("/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/", $x_fapi_interaction_id)) {
            throw new \InvalidArgumentException("invalid value for \"x_fapi_interaction_id\" when calling ConsentsApi.consentsPostConsentsConsentIdExtends, must conform to the pattern /^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/.");
        }
        
        // verify the required parameter 'x_customer_user_agent' is set
        if ($x_customer_user_agent === null || (is_array($x_customer_user_agent) && count($x_customer_user_agent) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $x_customer_user_agent when calling consentsPostConsentsConsentIdExtends'
            );
        }
        if (strlen($x_customer_user_agent) > 255) {
            throw new \InvalidArgumentException('invalid length for "$x_customer_user_agent" when calling ConsentsApi.consentsPostConsentsConsentIdExtends, must be smaller than or equal to 255.');
        }
        if (strlen($x_customer_user_agent) < 1) {
            throw new \InvalidArgumentException('invalid length for "$x_customer_user_agent" when calling ConsentsApi.consentsPostConsentsConsentIdExtends, must be bigger than or equal to 1.');
        }
        if (!preg_match("/^[^\\s](.*[^\\s])?$/", $x_customer_user_agent)) {
            throw new \InvalidArgumentException("invalid value for \"x_customer_user_agent\" when calling ConsentsApi.consentsPostConsentsConsentIdExtends, must conform to the pattern /^[^\\s](.*[^\\s])?$/.");
        }
        
        // verify the required parameter 'create_consent_extensions' is set
        if ($create_consent_extensions === null || (is_array($create_consent_extensions) && count($create_consent_extensions) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $create_consent_extensions when calling consentsPostConsentsConsentIdExtends'
            );
        }

        if ($x_fapi_auth_date !== null && strlen($x_fapi_auth_date) > 29) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_auth_date" when calling ConsentsApi.consentsPostConsentsConsentIdExtends, must be smaller than or equal to 29.');
        }
        if ($x_fapi_auth_date !== null && strlen($x_fapi_auth_date) < 29) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_auth_date" when calling ConsentsApi.consentsPostConsentsConsentIdExtends, must be bigger than or equal to 29.');
        }
        if ($x_fapi_auth_date !== null && !preg_match("/^(Mon|Tue|Wed|Thu|Fri|Sat|Sun), \\d{2} (Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec) \\d{4} \\d{2}:\\d{2}:\\d{2} (GMT|UTC)$/", $x_fapi_auth_date)) {
            throw new \InvalidArgumentException("invalid value for \"x_fapi_auth_date\" when calling ConsentsApi.consentsPostConsentsConsentIdExtends, must conform to the pattern /^(Mon|Tue|Wed|Thu|Fri|Sat|Sun), \\d{2} (Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec) \\d{4} \\d{2}:\\d{2}:\\d{2} (GMT|UTC)$/.");
        }
        

        $resourcePath = '/consents/{consentId}/extends';
        $formParams = [];
        $queryParams = [];
        $headerParams = [];
        $httpBody = '';
        $multipart = false;


        // header params
        if ($authorization !== null) {
            $headerParams['Authorization'] = ObjectSerializer::toHeaderValue($authorization);
        }
        // header params
        if ($x_fapi_auth_date !== null) {
            $headerParams['x-fapi-auth-date'] = ObjectSerializer::toHeaderValue($x_fapi_auth_date);
        }
        // header params
        if ($x_fapi_customer_ip_address !== null) {
            $headerParams['x-fapi-customer-ip-address'] = ObjectSerializer::toHeaderValue($x_fapi_customer_ip_address);
        }
        // header params
        if ($x_fapi_interaction_id !== null) {
            $headerParams['x-fapi-interaction-id'] = ObjectSerializer::toHeaderValue($x_fapi_interaction_id);
        }
        // header params
        if ($x_customer_user_agent !== null) {
            $headerParams['x-customer-user-agent'] = ObjectSerializer::toHeaderValue($x_customer_user_agent);
        }

        // path params
        if ($consent_id !== null) {
            $resourcePath = str_replace(
                '{' . 'consentId' . '}',
                ObjectSerializer::toPathValue($consent_id),
                $resourcePath
            );
        }


        $headers = $this->headerSelector->selectHeaders(
            ['application/json', 'application/json; charset=utf-8', ],
            $contentType,
            $multipart
        );

        // for model (json/xml)
        if (isset($create_consent_extensions)) {
            if (stripos($headers['Content-Type'], 'application/json') !== false) {
                # if Content-Type contains "application/json", json_encode the body
                $httpBody = \GuzzleHttp\Utils::jsonEncode(ObjectSerializer::sanitizeForSerialization($create_consent_extensions));
            } else {
                $httpBody = $create_consent_extensions;
            }
        } elseif (count($formParams) > 0) {
            if ($multipart) {
                $multipartContents = [];
                foreach ($formParams as $formParamName => $formParamValue) {
                    $formParamValueItems = is_array($formParamValue) ? $formParamValue : [$formParamValue];
                    foreach ($formParamValueItems as $formParamValueItem) {
                        $multipartContents[] = [
                            'name' => $formParamName,
                            'contents' => $formParamValueItem
                        ];
                    }
                }
                // for HTTP post (form)
                $httpBody = new MultipartStream($multipartContents);

            } elseif (stripos($headers['Content-Type'], 'application/json') !== false) {
                # if Content-Type contains "application/json", json_encode the form parameters
                $httpBody = \GuzzleHttp\Utils::jsonEncode($formParams);
            } else {
                // for HTTP post (form)
                $httpBody = ObjectSerializer::buildQuery($formParams);
            }
        }

        // this endpoint requires OAuth (access token)
        if (!empty($this->config->getAccessToken())) {
            $headers['Authorization'] = 'Bearer ' . $this->config->getAccessToken();
        }

        $defaultHeaders = [];
        if ($this->config->getUserAgent()) {
            $defaultHeaders['User-Agent'] = $this->config->getUserAgent();
        }

        $headers = array_merge(
            $defaultHeaders,
            $headerParams,
            $headers
        );

        $operationHost = $this->config->getHost();
        $query = ObjectSerializer::buildQuery($queryParams);
        return new Request(
            'POST',
            $operationHost . $resourcePath . ($query ? "?{$query}" : ''),
            $headers,
            $httpBody
        );
    }

    /**
     * Create http client option
     *
     * @throws \RuntimeException on file opening failure
     * @return array of http client options
     */
    protected function createHttpClientOption()
    {
        $options = [];
        if ($this->config->getDebug()) {
            $options[RequestOptions::DEBUG] = fopen($this->config->getDebugFile(), 'a');
            if (!$options[RequestOptions::DEBUG]) {
                throw new \RuntimeException('Failed to open the debug file: ' . $this->config->getDebugFile());
            }
        }

        if ($this->config->getCertFile()) {
            $options[RequestOptions::CERT] = $this->config->getCertFile();
        }

        if ($this->config->getKeyFile()) {
            $options[RequestOptions::SSL_KEY] = $this->config->getKeyFile();
        }

        return $options;
    }

    private function handleResponseWithDataType(
        string $dataType,
        RequestInterface $request,
        ResponseInterface $response
    ): array {
        if ($dataType === '\SplFileObject') {
            $content = $response->getBody(); //stream goes to serializer
        } else {
            $content = (string) $response->getBody();
            if ($dataType !== 'string') {
                try {
                    $content = json_decode($content, false, 512, JSON_THROW_ON_ERROR);
                } catch (\JsonException $exception) {
                    throw new ApiException(
                        sprintf(
                            'Error JSON decoding server response (%s)',
                            $request->getUri()
                        ),
                        $response->getStatusCode(),
                        $response->getHeaders(),
                        $content
                    );
                }
            }
        }

        return [
            ObjectSerializer::deserialize($content, $dataType, []),
            $response->getStatusCode(),
            $response->getHeaders()
        ];
    }

    private function responseWithinRangeCode(
        string $rangeCode,
        int $statusCode
    ): bool {
        $left = (int) ($rangeCode[0].'00');
        $right = (int) ($rangeCode[0].'99');

        return $statusCode >= $left && $statusCode <= $right;
    }
}
