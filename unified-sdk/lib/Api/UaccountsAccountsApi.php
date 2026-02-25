<?php
/**
 * AccountsApi
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
 * AccountsApi Class Doc Comment
 *
 * @category Class
 * @package  OpenAPI\Client
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 */
class UaccountsAccountsApi
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
        'accountsGetAccounts' => [
            'application/json',
        ],
        'accountsGetAccountsAccountId' => [
            'application/json',
        ],
        'accountsGetAccountsAccountIdBalances' => [
            'application/json',
        ],
        'accountsGetAccountsAccountIdOverdraftLimits' => [
            'application/json',
        ],
        'accountsGetAccountsAccountIdTransactions' => [
            'application/json',
        ],
        'accountsGetAccountsAccountIdTransactionsCurrent' => [
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
     * Operation accountsGetAccounts
     *
     * Obtém a lista de contas consentidas pelo cliente.
     *
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser “espelhado” pela transmissora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a transmissora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A receptora deve acatar o valor recebido da transmissora. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  int|null $page Número da página que está sendo requisitada (o valor da primeira página é 1). (optional, default to 1)
     * @param  int|null $page_size Quantidade total de registros por páginas. (optional, default to 25)
     * @param  \OpenAPI\Client\Model\EnumAccountType|null $account_type Tipos de contas. Modalidades tradicionais previstas pela Resolução 4.753, não contemplando contas vinculadas, conta de domiciliados no exterior, contas em moedas estrangeiras e conta correspondente moeda eletrônica. Vide Enum. (optional)
     * @param  string|null $pagination_key Identificador de rechamada, utilizado para evitar a contagem de chamadas ao endpoint durante a paginação. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['accountsGetAccounts'] to see the possible values for this operation
     *
     * @throws \OpenAPI\Client\ApiException on non-2xx response or if the response body is not in the expected format
     * @throws \InvalidArgumentException
     * @return \OpenAPI\Client\Model\ResponseAccountList|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError
     */
    public function accountsGetAccounts($authorization, $x_fapi_interaction_id, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, $page = 1, $page_size = 25, $account_type = null, $pagination_key = null, string $contentType = self::contentTypes['accountsGetAccounts'][0])
    {
        list($response) = $this->accountsGetAccountsWithHttpInfo($authorization, $x_fapi_interaction_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $page, $page_size, $account_type, $pagination_key, $contentType);
        return $response;
    }

    /**
     * Operation accountsGetAccountsWithHttpInfo
     *
     * Obtém a lista de contas consentidas pelo cliente.
     *
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser “espelhado” pela transmissora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a transmissora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A receptora deve acatar o valor recebido da transmissora. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  int|null $page Número da página que está sendo requisitada (o valor da primeira página é 1). (optional, default to 1)
     * @param  int|null $page_size Quantidade total de registros por páginas. (optional, default to 25)
     * @param  \OpenAPI\Client\Model\EnumAccountType|null $account_type Tipos de contas. Modalidades tradicionais previstas pela Resolução 4.753, não contemplando contas vinculadas, conta de domiciliados no exterior, contas em moedas estrangeiras e conta correspondente moeda eletrônica. Vide Enum. (optional)
     * @param  string|null $pagination_key Identificador de rechamada, utilizado para evitar a contagem de chamadas ao endpoint durante a paginação. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['accountsGetAccounts'] to see the possible values for this operation
     *
     * @throws \OpenAPI\Client\ApiException on non-2xx response or if the response body is not in the expected format
     * @throws \InvalidArgumentException
     * @return array of \OpenAPI\Client\Model\ResponseAccountList|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError, HTTP status code, HTTP response headers (array of strings)
     */
    public function accountsGetAccountsWithHttpInfo($authorization, $x_fapi_interaction_id, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, $page = 1, $page_size = 25, $account_type = null, $pagination_key = null, string $contentType = self::contentTypes['accountsGetAccounts'][0])
    {
        $request = $this->accountsGetAccountsRequest($authorization, $x_fapi_interaction_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $page, $page_size, $account_type, $pagination_key, $contentType);

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
                        '\OpenAPI\Client\Model\ResponseAccountList',
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
                case 422:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 423:
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
                        '\OpenAPI\Client\Model\ResponseError',
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
                '\OpenAPI\Client\Model\ResponseAccountList',
                $request,
                $response,
            );
        } catch (ApiException $e) {
            switch ($e->getCode()) {
                case 200:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseAccountList',
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
                case 422:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 423:
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
                        '\OpenAPI\Client\Model\ResponseError',
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
     * Operation accountsGetAccountsAsync
     *
     * Obtém a lista de contas consentidas pelo cliente.
     *
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser “espelhado” pela transmissora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a transmissora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A receptora deve acatar o valor recebido da transmissora. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  int|null $page Número da página que está sendo requisitada (o valor da primeira página é 1). (optional, default to 1)
     * @param  int|null $page_size Quantidade total de registros por páginas. (optional, default to 25)
     * @param  \OpenAPI\Client\Model\EnumAccountType|null $account_type Tipos de contas. Modalidades tradicionais previstas pela Resolução 4.753, não contemplando contas vinculadas, conta de domiciliados no exterior, contas em moedas estrangeiras e conta correspondente moeda eletrônica. Vide Enum. (optional)
     * @param  string|null $pagination_key Identificador de rechamada, utilizado para evitar a contagem de chamadas ao endpoint durante a paginação. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['accountsGetAccounts'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function accountsGetAccountsAsync($authorization, $x_fapi_interaction_id, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, $page = 1, $page_size = 25, $account_type = null, $pagination_key = null, string $contentType = self::contentTypes['accountsGetAccounts'][0])
    {
        return $this->accountsGetAccountsAsyncWithHttpInfo($authorization, $x_fapi_interaction_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $page, $page_size, $account_type, $pagination_key, $contentType)
            ->then(
                function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation accountsGetAccountsAsyncWithHttpInfo
     *
     * Obtém a lista de contas consentidas pelo cliente.
     *
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser “espelhado” pela transmissora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a transmissora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A receptora deve acatar o valor recebido da transmissora. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  int|null $page Número da página que está sendo requisitada (o valor da primeira página é 1). (optional, default to 1)
     * @param  int|null $page_size Quantidade total de registros por páginas. (optional, default to 25)
     * @param  \OpenAPI\Client\Model\EnumAccountType|null $account_type Tipos de contas. Modalidades tradicionais previstas pela Resolução 4.753, não contemplando contas vinculadas, conta de domiciliados no exterior, contas em moedas estrangeiras e conta correspondente moeda eletrônica. Vide Enum. (optional)
     * @param  string|null $pagination_key Identificador de rechamada, utilizado para evitar a contagem de chamadas ao endpoint durante a paginação. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['accountsGetAccounts'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function accountsGetAccountsAsyncWithHttpInfo($authorization, $x_fapi_interaction_id, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, $page = 1, $page_size = 25, $account_type = null, $pagination_key = null, string $contentType = self::contentTypes['accountsGetAccounts'][0])
    {
        $returnType = '\OpenAPI\Client\Model\ResponseAccountList';
        $request = $this->accountsGetAccountsRequest($authorization, $x_fapi_interaction_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $page, $page_size, $account_type, $pagination_key, $contentType);

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
     * Create request for operation 'accountsGetAccounts'
     *
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser “espelhado” pela transmissora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a transmissora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A receptora deve acatar o valor recebido da transmissora. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  int|null $page Número da página que está sendo requisitada (o valor da primeira página é 1). (optional, default to 1)
     * @param  int|null $page_size Quantidade total de registros por páginas. (optional, default to 25)
     * @param  \OpenAPI\Client\Model\EnumAccountType|null $account_type Tipos de contas. Modalidades tradicionais previstas pela Resolução 4.753, não contemplando contas vinculadas, conta de domiciliados no exterior, contas em moedas estrangeiras e conta correspondente moeda eletrônica. Vide Enum. (optional)
     * @param  string|null $pagination_key Identificador de rechamada, utilizado para evitar a contagem de chamadas ao endpoint durante a paginação. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['accountsGetAccounts'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Psr7\Request
     */
    public function accountsGetAccountsRequest($authorization, $x_fapi_interaction_id, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, $page = 1, $page_size = 25, $account_type = null, $pagination_key = null, string $contentType = self::contentTypes['accountsGetAccounts'][0])
    {

        // verify the required parameter 'authorization' is set
        if ($authorization === null || (is_array($authorization) && count($authorization) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $authorization when calling accountsGetAccounts'
            );
        }
        if (strlen($authorization) > 2048) {
            throw new \InvalidArgumentException('invalid length for "$authorization" when calling AccountsApi.accountsGetAccounts, must be smaller than or equal to 2048.');
        }
        if (!preg_match("/[\\w\\W\\s]*/", $authorization)) {
            throw new \InvalidArgumentException("invalid value for \"authorization\" when calling AccountsApi.accountsGetAccounts, must conform to the pattern /[\\w\\W\\s]*/.");
        }
        
        // verify the required parameter 'x_fapi_interaction_id' is set
        if ($x_fapi_interaction_id === null || (is_array($x_fapi_interaction_id) && count($x_fapi_interaction_id) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $x_fapi_interaction_id when calling accountsGetAccounts'
            );
        }
        if (strlen($x_fapi_interaction_id) > 36) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_interaction_id" when calling AccountsApi.accountsGetAccounts, must be smaller than or equal to 36.');
        }
        if (strlen($x_fapi_interaction_id) < 1) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_interaction_id" when calling AccountsApi.accountsGetAccounts, must be bigger than or equal to 1.');
        }
        if (!preg_match("/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/", $x_fapi_interaction_id)) {
            throw new \InvalidArgumentException("invalid value for \"x_fapi_interaction_id\" when calling AccountsApi.accountsGetAccounts, must conform to the pattern /^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/.");
        }
        
        if ($x_fapi_auth_date !== null && strlen($x_fapi_auth_date) > 29) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_auth_date" when calling AccountsApi.accountsGetAccounts, must be smaller than or equal to 29.');
        }
        if ($x_fapi_auth_date !== null && strlen($x_fapi_auth_date) < 29) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_auth_date" when calling AccountsApi.accountsGetAccounts, must be bigger than or equal to 29.');
        }
        if ($x_fapi_auth_date !== null && !preg_match("/^(Mon|Tue|Wed|Thu|Fri|Sat|Sun), \\d{2} (Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec) \\d{4} \\d{2}:\\d{2}:\\d{2} (GMT|UTC)$/", $x_fapi_auth_date)) {
            throw new \InvalidArgumentException("invalid value for \"x_fapi_auth_date\" when calling AccountsApi.accountsGetAccounts, must conform to the pattern /^(Mon|Tue|Wed|Thu|Fri|Sat|Sun), \\d{2} (Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec) \\d{4} \\d{2}:\\d{2}:\\d{2} (GMT|UTC)$/.");
        }
        
        if ($x_fapi_customer_ip_address !== null && strlen($x_fapi_customer_ip_address) > 100) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_customer_ip_address" when calling AccountsApi.accountsGetAccounts, must be smaller than or equal to 100.');
        }
        if ($x_fapi_customer_ip_address !== null && strlen($x_fapi_customer_ip_address) < 1) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_customer_ip_address" when calling AccountsApi.accountsGetAccounts, must be bigger than or equal to 1.');
        }
        if ($x_fapi_customer_ip_address !== null && !preg_match("/[\\w\\W\\s]*/", $x_fapi_customer_ip_address)) {
            throw new \InvalidArgumentException("invalid value for \"x_fapi_customer_ip_address\" when calling AccountsApi.accountsGetAccounts, must conform to the pattern /[\\w\\W\\s]*/.");
        }
        
        if ($x_customer_user_agent !== null && strlen($x_customer_user_agent) > 100) {
            throw new \InvalidArgumentException('invalid length for "$x_customer_user_agent" when calling AccountsApi.accountsGetAccounts, must be smaller than or equal to 100.');
        }
        if ($x_customer_user_agent !== null && strlen($x_customer_user_agent) < 1) {
            throw new \InvalidArgumentException('invalid length for "$x_customer_user_agent" when calling AccountsApi.accountsGetAccounts, must be bigger than or equal to 1.');
        }
        if ($x_customer_user_agent !== null && !preg_match("/[\\w\\W\\s]*/", $x_customer_user_agent)) {
            throw new \InvalidArgumentException("invalid value for \"x_customer_user_agent\" when calling AccountsApi.accountsGetAccounts, must conform to the pattern /[\\w\\W\\s]*/.");
        }
        
        if ($page !== null && $page > 2147483647) {
            throw new \InvalidArgumentException('invalid value for "$page" when calling AccountsApi.accountsGetAccounts, must be smaller than or equal to 2147483647.');
        }
        if ($page !== null && $page < 1) {
            throw new \InvalidArgumentException('invalid value for "$page" when calling AccountsApi.accountsGetAccounts, must be bigger than or equal to 1.');
        }
        
        if ($page_size !== null && $page_size > 1000) {
            throw new \InvalidArgumentException('invalid value for "$page_size" when calling AccountsApi.accountsGetAccounts, must be smaller than or equal to 1000.');
        }
        if ($page_size !== null && $page_size < 1) {
            throw new \InvalidArgumentException('invalid value for "$page_size" when calling AccountsApi.accountsGetAccounts, must be bigger than or equal to 1.');
        }
        

        if ($pagination_key !== null && strlen($pagination_key) > 2048) {
            throw new \InvalidArgumentException('invalid length for "$pagination_key" when calling AccountsApi.accountsGetAccounts, must be smaller than or equal to 2048.');
        }
        if ($pagination_key !== null && !preg_match("/[\\w\\W\\s]*/", $pagination_key)) {
            throw new \InvalidArgumentException("invalid value for \"pagination_key\" when calling AccountsApi.accountsGetAccounts, must conform to the pattern /[\\w\\W\\s]*/.");
        }
        

        $resourcePath = '/accounts';
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
        // query params
        $queryParams = array_merge($queryParams, ObjectSerializer::toQueryValue(
            $account_type,
            'accountType', // param base name
            'EnumAccountType', // openApiType
            'form', // style
            true, // explode
            false // required
        ) ?? []);
        // query params
        $queryParams = array_merge($queryParams, ObjectSerializer::toQueryValue(
            $pagination_key,
            'pagination-key', // param base name
            'string', // openApiType
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
     * Operation accountsGetAccountsAccountId
     *
     * Obtém os dados de identificação da conta identificada por accountId.
     *
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser “espelhado” pela transmissora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a transmissora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A receptora deve acatar o valor recebido da transmissora. (required)
     * @param  string $account_id Identificador da conta de depósito à vista, de poupança ou de pagamento pré-paga. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['accountsGetAccountsAccountId'] to see the possible values for this operation
     *
     * @throws \OpenAPI\Client\ApiException on non-2xx response or if the response body is not in the expected format
     * @throws \InvalidArgumentException
     * @return \OpenAPI\Client\Model\ResponseAccountIdentification|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError
     */
    public function accountsGetAccountsAccountId($authorization, $x_fapi_interaction_id, $account_id, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, string $contentType = self::contentTypes['accountsGetAccountsAccountId'][0])
    {
        list($response) = $this->accountsGetAccountsAccountIdWithHttpInfo($authorization, $x_fapi_interaction_id, $account_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $contentType);
        return $response;
    }

    /**
     * Operation accountsGetAccountsAccountIdWithHttpInfo
     *
     * Obtém os dados de identificação da conta identificada por accountId.
     *
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser “espelhado” pela transmissora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a transmissora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A receptora deve acatar o valor recebido da transmissora. (required)
     * @param  string $account_id Identificador da conta de depósito à vista, de poupança ou de pagamento pré-paga. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['accountsGetAccountsAccountId'] to see the possible values for this operation
     *
     * @throws \OpenAPI\Client\ApiException on non-2xx response or if the response body is not in the expected format
     * @throws \InvalidArgumentException
     * @return array of \OpenAPI\Client\Model\ResponseAccountIdentification|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError, HTTP status code, HTTP response headers (array of strings)
     */
    public function accountsGetAccountsAccountIdWithHttpInfo($authorization, $x_fapi_interaction_id, $account_id, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, string $contentType = self::contentTypes['accountsGetAccountsAccountId'][0])
    {
        $request = $this->accountsGetAccountsAccountIdRequest($authorization, $x_fapi_interaction_id, $account_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $contentType);

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
                        '\OpenAPI\Client\Model\ResponseAccountIdentification',
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
                case 422:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 423:
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
                        '\OpenAPI\Client\Model\ResponseError',
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
                '\OpenAPI\Client\Model\ResponseAccountIdentification',
                $request,
                $response,
            );
        } catch (ApiException $e) {
            switch ($e->getCode()) {
                case 200:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseAccountIdentification',
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
                case 422:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 423:
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
                        '\OpenAPI\Client\Model\ResponseError',
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
     * Operation accountsGetAccountsAccountIdAsync
     *
     * Obtém os dados de identificação da conta identificada por accountId.
     *
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser “espelhado” pela transmissora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a transmissora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A receptora deve acatar o valor recebido da transmissora. (required)
     * @param  string $account_id Identificador da conta de depósito à vista, de poupança ou de pagamento pré-paga. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['accountsGetAccountsAccountId'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function accountsGetAccountsAccountIdAsync($authorization, $x_fapi_interaction_id, $account_id, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, string $contentType = self::contentTypes['accountsGetAccountsAccountId'][0])
    {
        return $this->accountsGetAccountsAccountIdAsyncWithHttpInfo($authorization, $x_fapi_interaction_id, $account_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $contentType)
            ->then(
                function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation accountsGetAccountsAccountIdAsyncWithHttpInfo
     *
     * Obtém os dados de identificação da conta identificada por accountId.
     *
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser “espelhado” pela transmissora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a transmissora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A receptora deve acatar o valor recebido da transmissora. (required)
     * @param  string $account_id Identificador da conta de depósito à vista, de poupança ou de pagamento pré-paga. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['accountsGetAccountsAccountId'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function accountsGetAccountsAccountIdAsyncWithHttpInfo($authorization, $x_fapi_interaction_id, $account_id, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, string $contentType = self::contentTypes['accountsGetAccountsAccountId'][0])
    {
        $returnType = '\OpenAPI\Client\Model\ResponseAccountIdentification';
        $request = $this->accountsGetAccountsAccountIdRequest($authorization, $x_fapi_interaction_id, $account_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $contentType);

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
     * Create request for operation 'accountsGetAccountsAccountId'
     *
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser “espelhado” pela transmissora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a transmissora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A receptora deve acatar o valor recebido da transmissora. (required)
     * @param  string $account_id Identificador da conta de depósito à vista, de poupança ou de pagamento pré-paga. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['accountsGetAccountsAccountId'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Psr7\Request
     */
    public function accountsGetAccountsAccountIdRequest($authorization, $x_fapi_interaction_id, $account_id, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, string $contentType = self::contentTypes['accountsGetAccountsAccountId'][0])
    {

        // verify the required parameter 'authorization' is set
        if ($authorization === null || (is_array($authorization) && count($authorization) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $authorization when calling accountsGetAccountsAccountId'
            );
        }
        if (strlen($authorization) > 2048) {
            throw new \InvalidArgumentException('invalid length for "$authorization" when calling AccountsApi.accountsGetAccountsAccountId, must be smaller than or equal to 2048.');
        }
        if (!preg_match("/[\\w\\W\\s]*/", $authorization)) {
            throw new \InvalidArgumentException("invalid value for \"authorization\" when calling AccountsApi.accountsGetAccountsAccountId, must conform to the pattern /[\\w\\W\\s]*/.");
        }
        
        // verify the required parameter 'x_fapi_interaction_id' is set
        if ($x_fapi_interaction_id === null || (is_array($x_fapi_interaction_id) && count($x_fapi_interaction_id) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $x_fapi_interaction_id when calling accountsGetAccountsAccountId'
            );
        }
        if (strlen($x_fapi_interaction_id) > 36) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_interaction_id" when calling AccountsApi.accountsGetAccountsAccountId, must be smaller than or equal to 36.');
        }
        if (strlen($x_fapi_interaction_id) < 1) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_interaction_id" when calling AccountsApi.accountsGetAccountsAccountId, must be bigger than or equal to 1.');
        }
        if (!preg_match("/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/", $x_fapi_interaction_id)) {
            throw new \InvalidArgumentException("invalid value for \"x_fapi_interaction_id\" when calling AccountsApi.accountsGetAccountsAccountId, must conform to the pattern /^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/.");
        }
        
        // verify the required parameter 'account_id' is set
        if ($account_id === null || (is_array($account_id) && count($account_id) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $account_id when calling accountsGetAccountsAccountId'
            );
        }
        if (strlen($account_id) > 100) {
            throw new \InvalidArgumentException('invalid length for "$account_id" when calling AccountsApi.accountsGetAccountsAccountId, must be smaller than or equal to 100.');
        }
        if (!preg_match("/^[a-zA-Z0-9][a-zA-Z0-9\\-]{0,99}$/", $account_id)) {
            throw new \InvalidArgumentException("invalid value for \"account_id\" when calling AccountsApi.accountsGetAccountsAccountId, must conform to the pattern /^[a-zA-Z0-9][a-zA-Z0-9\\-]{0,99}$/.");
        }
        
        if ($x_fapi_auth_date !== null && strlen($x_fapi_auth_date) > 29) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_auth_date" when calling AccountsApi.accountsGetAccountsAccountId, must be smaller than or equal to 29.');
        }
        if ($x_fapi_auth_date !== null && strlen($x_fapi_auth_date) < 29) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_auth_date" when calling AccountsApi.accountsGetAccountsAccountId, must be bigger than or equal to 29.');
        }
        if ($x_fapi_auth_date !== null && !preg_match("/^(Mon|Tue|Wed|Thu|Fri|Sat|Sun), \\d{2} (Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec) \\d{4} \\d{2}:\\d{2}:\\d{2} (GMT|UTC)$/", $x_fapi_auth_date)) {
            throw new \InvalidArgumentException("invalid value for \"x_fapi_auth_date\" when calling AccountsApi.accountsGetAccountsAccountId, must conform to the pattern /^(Mon|Tue|Wed|Thu|Fri|Sat|Sun), \\d{2} (Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec) \\d{4} \\d{2}:\\d{2}:\\d{2} (GMT|UTC)$/.");
        }
        
        if ($x_fapi_customer_ip_address !== null && strlen($x_fapi_customer_ip_address) > 100) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_customer_ip_address" when calling AccountsApi.accountsGetAccountsAccountId, must be smaller than or equal to 100.');
        }
        if ($x_fapi_customer_ip_address !== null && strlen($x_fapi_customer_ip_address) < 1) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_customer_ip_address" when calling AccountsApi.accountsGetAccountsAccountId, must be bigger than or equal to 1.');
        }
        if ($x_fapi_customer_ip_address !== null && !preg_match("/[\\w\\W\\s]*/", $x_fapi_customer_ip_address)) {
            throw new \InvalidArgumentException("invalid value for \"x_fapi_customer_ip_address\" when calling AccountsApi.accountsGetAccountsAccountId, must conform to the pattern /[\\w\\W\\s]*/.");
        }
        
        if ($x_customer_user_agent !== null && strlen($x_customer_user_agent) > 100) {
            throw new \InvalidArgumentException('invalid length for "$x_customer_user_agent" when calling AccountsApi.accountsGetAccountsAccountId, must be smaller than or equal to 100.');
        }
        if ($x_customer_user_agent !== null && strlen($x_customer_user_agent) < 1) {
            throw new \InvalidArgumentException('invalid length for "$x_customer_user_agent" when calling AccountsApi.accountsGetAccountsAccountId, must be bigger than or equal to 1.');
        }
        if ($x_customer_user_agent !== null && !preg_match("/[\\w\\W\\s]*/", $x_customer_user_agent)) {
            throw new \InvalidArgumentException("invalid value for \"x_customer_user_agent\" when calling AccountsApi.accountsGetAccountsAccountId, must conform to the pattern /[\\w\\W\\s]*/.");
        }
        

        $resourcePath = '/accounts/{accountId}';
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
        if ($account_id !== null) {
            $resourcePath = str_replace(
                '{' . 'accountId' . '}',
                ObjectSerializer::toPathValue($account_id),
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
     * Operation accountsGetAccountsAccountIdBalances
     *
     * Obtém os saldos da conta identificada por accountId.
     *
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser “espelhado” pela transmissora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a transmissora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A receptora deve acatar o valor recebido da transmissora. (required)
     * @param  string $account_id Identificador da conta de depósito à vista, de poupança ou de pagamento pré-paga. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['accountsGetAccountsAccountIdBalances'] to see the possible values for this operation
     *
     * @throws \OpenAPI\Client\ApiException on non-2xx response or if the response body is not in the expected format
     * @throws \InvalidArgumentException
     * @return \OpenAPI\Client\Model\ResponseAccountBalances|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError
     */
    public function accountsGetAccountsAccountIdBalances($authorization, $x_fapi_interaction_id, $account_id, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, string $contentType = self::contentTypes['accountsGetAccountsAccountIdBalances'][0])
    {
        list($response) = $this->accountsGetAccountsAccountIdBalancesWithHttpInfo($authorization, $x_fapi_interaction_id, $account_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $contentType);
        return $response;
    }

    /**
     * Operation accountsGetAccountsAccountIdBalancesWithHttpInfo
     *
     * Obtém os saldos da conta identificada por accountId.
     *
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser “espelhado” pela transmissora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a transmissora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A receptora deve acatar o valor recebido da transmissora. (required)
     * @param  string $account_id Identificador da conta de depósito à vista, de poupança ou de pagamento pré-paga. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['accountsGetAccountsAccountIdBalances'] to see the possible values for this operation
     *
     * @throws \OpenAPI\Client\ApiException on non-2xx response or if the response body is not in the expected format
     * @throws \InvalidArgumentException
     * @return array of \OpenAPI\Client\Model\ResponseAccountBalances|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError, HTTP status code, HTTP response headers (array of strings)
     */
    public function accountsGetAccountsAccountIdBalancesWithHttpInfo($authorization, $x_fapi_interaction_id, $account_id, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, string $contentType = self::contentTypes['accountsGetAccountsAccountIdBalances'][0])
    {
        $request = $this->accountsGetAccountsAccountIdBalancesRequest($authorization, $x_fapi_interaction_id, $account_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $contentType);

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
                        '\OpenAPI\Client\Model\ResponseAccountBalances',
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
                case 422:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 423:
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
                        '\OpenAPI\Client\Model\ResponseError',
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
                '\OpenAPI\Client\Model\ResponseAccountBalances',
                $request,
                $response,
            );
        } catch (ApiException $e) {
            switch ($e->getCode()) {
                case 200:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseAccountBalances',
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
                case 422:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 423:
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
                        '\OpenAPI\Client\Model\ResponseError',
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
     * Operation accountsGetAccountsAccountIdBalancesAsync
     *
     * Obtém os saldos da conta identificada por accountId.
     *
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser “espelhado” pela transmissora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a transmissora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A receptora deve acatar o valor recebido da transmissora. (required)
     * @param  string $account_id Identificador da conta de depósito à vista, de poupança ou de pagamento pré-paga. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['accountsGetAccountsAccountIdBalances'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function accountsGetAccountsAccountIdBalancesAsync($authorization, $x_fapi_interaction_id, $account_id, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, string $contentType = self::contentTypes['accountsGetAccountsAccountIdBalances'][0])
    {
        return $this->accountsGetAccountsAccountIdBalancesAsyncWithHttpInfo($authorization, $x_fapi_interaction_id, $account_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $contentType)
            ->then(
                function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation accountsGetAccountsAccountIdBalancesAsyncWithHttpInfo
     *
     * Obtém os saldos da conta identificada por accountId.
     *
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser “espelhado” pela transmissora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a transmissora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A receptora deve acatar o valor recebido da transmissora. (required)
     * @param  string $account_id Identificador da conta de depósito à vista, de poupança ou de pagamento pré-paga. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['accountsGetAccountsAccountIdBalances'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function accountsGetAccountsAccountIdBalancesAsyncWithHttpInfo($authorization, $x_fapi_interaction_id, $account_id, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, string $contentType = self::contentTypes['accountsGetAccountsAccountIdBalances'][0])
    {
        $returnType = '\OpenAPI\Client\Model\ResponseAccountBalances';
        $request = $this->accountsGetAccountsAccountIdBalancesRequest($authorization, $x_fapi_interaction_id, $account_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $contentType);

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
     * Create request for operation 'accountsGetAccountsAccountIdBalances'
     *
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser “espelhado” pela transmissora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a transmissora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A receptora deve acatar o valor recebido da transmissora. (required)
     * @param  string $account_id Identificador da conta de depósito à vista, de poupança ou de pagamento pré-paga. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['accountsGetAccountsAccountIdBalances'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Psr7\Request
     */
    public function accountsGetAccountsAccountIdBalancesRequest($authorization, $x_fapi_interaction_id, $account_id, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, string $contentType = self::contentTypes['accountsGetAccountsAccountIdBalances'][0])
    {

        // verify the required parameter 'authorization' is set
        if ($authorization === null || (is_array($authorization) && count($authorization) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $authorization when calling accountsGetAccountsAccountIdBalances'
            );
        }
        if (strlen($authorization) > 2048) {
            throw new \InvalidArgumentException('invalid length for "$authorization" when calling AccountsApi.accountsGetAccountsAccountIdBalances, must be smaller than or equal to 2048.');
        }
        if (!preg_match("/[\\w\\W\\s]*/", $authorization)) {
            throw new \InvalidArgumentException("invalid value for \"authorization\" when calling AccountsApi.accountsGetAccountsAccountIdBalances, must conform to the pattern /[\\w\\W\\s]*/.");
        }
        
        // verify the required parameter 'x_fapi_interaction_id' is set
        if ($x_fapi_interaction_id === null || (is_array($x_fapi_interaction_id) && count($x_fapi_interaction_id) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $x_fapi_interaction_id when calling accountsGetAccountsAccountIdBalances'
            );
        }
        if (strlen($x_fapi_interaction_id) > 36) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_interaction_id" when calling AccountsApi.accountsGetAccountsAccountIdBalances, must be smaller than or equal to 36.');
        }
        if (strlen($x_fapi_interaction_id) < 1) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_interaction_id" when calling AccountsApi.accountsGetAccountsAccountIdBalances, must be bigger than or equal to 1.');
        }
        if (!preg_match("/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/", $x_fapi_interaction_id)) {
            throw new \InvalidArgumentException("invalid value for \"x_fapi_interaction_id\" when calling AccountsApi.accountsGetAccountsAccountIdBalances, must conform to the pattern /^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/.");
        }
        
        // verify the required parameter 'account_id' is set
        if ($account_id === null || (is_array($account_id) && count($account_id) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $account_id when calling accountsGetAccountsAccountIdBalances'
            );
        }
        if (strlen($account_id) > 100) {
            throw new \InvalidArgumentException('invalid length for "$account_id" when calling AccountsApi.accountsGetAccountsAccountIdBalances, must be smaller than or equal to 100.');
        }
        if (!preg_match("/^[a-zA-Z0-9][a-zA-Z0-9\\-]{0,99}$/", $account_id)) {
            throw new \InvalidArgumentException("invalid value for \"account_id\" when calling AccountsApi.accountsGetAccountsAccountIdBalances, must conform to the pattern /^[a-zA-Z0-9][a-zA-Z0-9\\-]{0,99}$/.");
        }
        
        if ($x_fapi_auth_date !== null && strlen($x_fapi_auth_date) > 29) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_auth_date" when calling AccountsApi.accountsGetAccountsAccountIdBalances, must be smaller than or equal to 29.');
        }
        if ($x_fapi_auth_date !== null && strlen($x_fapi_auth_date) < 29) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_auth_date" when calling AccountsApi.accountsGetAccountsAccountIdBalances, must be bigger than or equal to 29.');
        }
        if ($x_fapi_auth_date !== null && !preg_match("/^(Mon|Tue|Wed|Thu|Fri|Sat|Sun), \\d{2} (Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec) \\d{4} \\d{2}:\\d{2}:\\d{2} (GMT|UTC)$/", $x_fapi_auth_date)) {
            throw new \InvalidArgumentException("invalid value for \"x_fapi_auth_date\" when calling AccountsApi.accountsGetAccountsAccountIdBalances, must conform to the pattern /^(Mon|Tue|Wed|Thu|Fri|Sat|Sun), \\d{2} (Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec) \\d{4} \\d{2}:\\d{2}:\\d{2} (GMT|UTC)$/.");
        }
        
        if ($x_fapi_customer_ip_address !== null && strlen($x_fapi_customer_ip_address) > 100) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_customer_ip_address" when calling AccountsApi.accountsGetAccountsAccountIdBalances, must be smaller than or equal to 100.');
        }
        if ($x_fapi_customer_ip_address !== null && strlen($x_fapi_customer_ip_address) < 1) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_customer_ip_address" when calling AccountsApi.accountsGetAccountsAccountIdBalances, must be bigger than or equal to 1.');
        }
        if ($x_fapi_customer_ip_address !== null && !preg_match("/[\\w\\W\\s]*/", $x_fapi_customer_ip_address)) {
            throw new \InvalidArgumentException("invalid value for \"x_fapi_customer_ip_address\" when calling AccountsApi.accountsGetAccountsAccountIdBalances, must conform to the pattern /[\\w\\W\\s]*/.");
        }
        
        if ($x_customer_user_agent !== null && strlen($x_customer_user_agent) > 100) {
            throw new \InvalidArgumentException('invalid length for "$x_customer_user_agent" when calling AccountsApi.accountsGetAccountsAccountIdBalances, must be smaller than or equal to 100.');
        }
        if ($x_customer_user_agent !== null && strlen($x_customer_user_agent) < 1) {
            throw new \InvalidArgumentException('invalid length for "$x_customer_user_agent" when calling AccountsApi.accountsGetAccountsAccountIdBalances, must be bigger than or equal to 1.');
        }
        if ($x_customer_user_agent !== null && !preg_match("/[\\w\\W\\s]*/", $x_customer_user_agent)) {
            throw new \InvalidArgumentException("invalid value for \"x_customer_user_agent\" when calling AccountsApi.accountsGetAccountsAccountIdBalances, must conform to the pattern /[\\w\\W\\s]*/.");
        }
        

        $resourcePath = '/accounts/{accountId}/balances';
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
        if ($account_id !== null) {
            $resourcePath = str_replace(
                '{' . 'accountId' . '}',
                ObjectSerializer::toPathValue($account_id),
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
     * Operation accountsGetAccountsAccountIdOverdraftLimits
     *
     * Obtém os limites da conta identificada por accountId.
     *
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser “espelhado” pela transmissora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a transmissora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A receptora deve acatar o valor recebido da transmissora. (required)
     * @param  string $account_id Identificador da conta de depósito à vista, de poupança ou de pagamento pré-paga. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['accountsGetAccountsAccountIdOverdraftLimits'] to see the possible values for this operation
     *
     * @throws \OpenAPI\Client\ApiException on non-2xx response or if the response body is not in the expected format
     * @throws \InvalidArgumentException
     * @return \OpenAPI\Client\Model\ResponseAccountOverdraftLimits|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError
     */
    public function accountsGetAccountsAccountIdOverdraftLimits($authorization, $x_fapi_interaction_id, $account_id, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, string $contentType = self::contentTypes['accountsGetAccountsAccountIdOverdraftLimits'][0])
    {
        list($response) = $this->accountsGetAccountsAccountIdOverdraftLimitsWithHttpInfo($authorization, $x_fapi_interaction_id, $account_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $contentType);
        return $response;
    }

    /**
     * Operation accountsGetAccountsAccountIdOverdraftLimitsWithHttpInfo
     *
     * Obtém os limites da conta identificada por accountId.
     *
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser “espelhado” pela transmissora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a transmissora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A receptora deve acatar o valor recebido da transmissora. (required)
     * @param  string $account_id Identificador da conta de depósito à vista, de poupança ou de pagamento pré-paga. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['accountsGetAccountsAccountIdOverdraftLimits'] to see the possible values for this operation
     *
     * @throws \OpenAPI\Client\ApiException on non-2xx response or if the response body is not in the expected format
     * @throws \InvalidArgumentException
     * @return array of \OpenAPI\Client\Model\ResponseAccountOverdraftLimits|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError, HTTP status code, HTTP response headers (array of strings)
     */
    public function accountsGetAccountsAccountIdOverdraftLimitsWithHttpInfo($authorization, $x_fapi_interaction_id, $account_id, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, string $contentType = self::contentTypes['accountsGetAccountsAccountIdOverdraftLimits'][0])
    {
        $request = $this->accountsGetAccountsAccountIdOverdraftLimitsRequest($authorization, $x_fapi_interaction_id, $account_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $contentType);

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
                        '\OpenAPI\Client\Model\ResponseAccountOverdraftLimits',
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
                case 422:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 423:
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
                        '\OpenAPI\Client\Model\ResponseError',
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
                '\OpenAPI\Client\Model\ResponseAccountOverdraftLimits',
                $request,
                $response,
            );
        } catch (ApiException $e) {
            switch ($e->getCode()) {
                case 200:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseAccountOverdraftLimits',
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
                case 422:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 423:
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
                        '\OpenAPI\Client\Model\ResponseError',
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
     * Operation accountsGetAccountsAccountIdOverdraftLimitsAsync
     *
     * Obtém os limites da conta identificada por accountId.
     *
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser “espelhado” pela transmissora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a transmissora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A receptora deve acatar o valor recebido da transmissora. (required)
     * @param  string $account_id Identificador da conta de depósito à vista, de poupança ou de pagamento pré-paga. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['accountsGetAccountsAccountIdOverdraftLimits'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function accountsGetAccountsAccountIdOverdraftLimitsAsync($authorization, $x_fapi_interaction_id, $account_id, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, string $contentType = self::contentTypes['accountsGetAccountsAccountIdOverdraftLimits'][0])
    {
        return $this->accountsGetAccountsAccountIdOverdraftLimitsAsyncWithHttpInfo($authorization, $x_fapi_interaction_id, $account_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $contentType)
            ->then(
                function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation accountsGetAccountsAccountIdOverdraftLimitsAsyncWithHttpInfo
     *
     * Obtém os limites da conta identificada por accountId.
     *
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser “espelhado” pela transmissora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a transmissora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A receptora deve acatar o valor recebido da transmissora. (required)
     * @param  string $account_id Identificador da conta de depósito à vista, de poupança ou de pagamento pré-paga. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['accountsGetAccountsAccountIdOverdraftLimits'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function accountsGetAccountsAccountIdOverdraftLimitsAsyncWithHttpInfo($authorization, $x_fapi_interaction_id, $account_id, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, string $contentType = self::contentTypes['accountsGetAccountsAccountIdOverdraftLimits'][0])
    {
        $returnType = '\OpenAPI\Client\Model\ResponseAccountOverdraftLimits';
        $request = $this->accountsGetAccountsAccountIdOverdraftLimitsRequest($authorization, $x_fapi_interaction_id, $account_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $contentType);

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
     * Create request for operation 'accountsGetAccountsAccountIdOverdraftLimits'
     *
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser “espelhado” pela transmissora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a transmissora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A receptora deve acatar o valor recebido da transmissora. (required)
     * @param  string $account_id Identificador da conta de depósito à vista, de poupança ou de pagamento pré-paga. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['accountsGetAccountsAccountIdOverdraftLimits'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Psr7\Request
     */
    public function accountsGetAccountsAccountIdOverdraftLimitsRequest($authorization, $x_fapi_interaction_id, $account_id, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, string $contentType = self::contentTypes['accountsGetAccountsAccountIdOverdraftLimits'][0])
    {

        // verify the required parameter 'authorization' is set
        if ($authorization === null || (is_array($authorization) && count($authorization) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $authorization when calling accountsGetAccountsAccountIdOverdraftLimits'
            );
        }
        if (strlen($authorization) > 2048) {
            throw new \InvalidArgumentException('invalid length for "$authorization" when calling AccountsApi.accountsGetAccountsAccountIdOverdraftLimits, must be smaller than or equal to 2048.');
        }
        if (!preg_match("/[\\w\\W\\s]*/", $authorization)) {
            throw new \InvalidArgumentException("invalid value for \"authorization\" when calling AccountsApi.accountsGetAccountsAccountIdOverdraftLimits, must conform to the pattern /[\\w\\W\\s]*/.");
        }
        
        // verify the required parameter 'x_fapi_interaction_id' is set
        if ($x_fapi_interaction_id === null || (is_array($x_fapi_interaction_id) && count($x_fapi_interaction_id) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $x_fapi_interaction_id when calling accountsGetAccountsAccountIdOverdraftLimits'
            );
        }
        if (strlen($x_fapi_interaction_id) > 36) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_interaction_id" when calling AccountsApi.accountsGetAccountsAccountIdOverdraftLimits, must be smaller than or equal to 36.');
        }
        if (strlen($x_fapi_interaction_id) < 1) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_interaction_id" when calling AccountsApi.accountsGetAccountsAccountIdOverdraftLimits, must be bigger than or equal to 1.');
        }
        if (!preg_match("/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/", $x_fapi_interaction_id)) {
            throw new \InvalidArgumentException("invalid value for \"x_fapi_interaction_id\" when calling AccountsApi.accountsGetAccountsAccountIdOverdraftLimits, must conform to the pattern /^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/.");
        }
        
        // verify the required parameter 'account_id' is set
        if ($account_id === null || (is_array($account_id) && count($account_id) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $account_id when calling accountsGetAccountsAccountIdOverdraftLimits'
            );
        }
        if (strlen($account_id) > 100) {
            throw new \InvalidArgumentException('invalid length for "$account_id" when calling AccountsApi.accountsGetAccountsAccountIdOverdraftLimits, must be smaller than or equal to 100.');
        }
        if (!preg_match("/^[a-zA-Z0-9][a-zA-Z0-9\\-]{0,99}$/", $account_id)) {
            throw new \InvalidArgumentException("invalid value for \"account_id\" when calling AccountsApi.accountsGetAccountsAccountIdOverdraftLimits, must conform to the pattern /^[a-zA-Z0-9][a-zA-Z0-9\\-]{0,99}$/.");
        }
        
        if ($x_fapi_auth_date !== null && strlen($x_fapi_auth_date) > 29) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_auth_date" when calling AccountsApi.accountsGetAccountsAccountIdOverdraftLimits, must be smaller than or equal to 29.');
        }
        if ($x_fapi_auth_date !== null && strlen($x_fapi_auth_date) < 29) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_auth_date" when calling AccountsApi.accountsGetAccountsAccountIdOverdraftLimits, must be bigger than or equal to 29.');
        }
        if ($x_fapi_auth_date !== null && !preg_match("/^(Mon|Tue|Wed|Thu|Fri|Sat|Sun), \\d{2} (Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec) \\d{4} \\d{2}:\\d{2}:\\d{2} (GMT|UTC)$/", $x_fapi_auth_date)) {
            throw new \InvalidArgumentException("invalid value for \"x_fapi_auth_date\" when calling AccountsApi.accountsGetAccountsAccountIdOverdraftLimits, must conform to the pattern /^(Mon|Tue|Wed|Thu|Fri|Sat|Sun), \\d{2} (Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec) \\d{4} \\d{2}:\\d{2}:\\d{2} (GMT|UTC)$/.");
        }
        
        if ($x_fapi_customer_ip_address !== null && strlen($x_fapi_customer_ip_address) > 100) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_customer_ip_address" when calling AccountsApi.accountsGetAccountsAccountIdOverdraftLimits, must be smaller than or equal to 100.');
        }
        if ($x_fapi_customer_ip_address !== null && strlen($x_fapi_customer_ip_address) < 1) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_customer_ip_address" when calling AccountsApi.accountsGetAccountsAccountIdOverdraftLimits, must be bigger than or equal to 1.');
        }
        if ($x_fapi_customer_ip_address !== null && !preg_match("/[\\w\\W\\s]*/", $x_fapi_customer_ip_address)) {
            throw new \InvalidArgumentException("invalid value for \"x_fapi_customer_ip_address\" when calling AccountsApi.accountsGetAccountsAccountIdOverdraftLimits, must conform to the pattern /[\\w\\W\\s]*/.");
        }
        
        if ($x_customer_user_agent !== null && strlen($x_customer_user_agent) > 100) {
            throw new \InvalidArgumentException('invalid length for "$x_customer_user_agent" when calling AccountsApi.accountsGetAccountsAccountIdOverdraftLimits, must be smaller than or equal to 100.');
        }
        if ($x_customer_user_agent !== null && strlen($x_customer_user_agent) < 1) {
            throw new \InvalidArgumentException('invalid length for "$x_customer_user_agent" when calling AccountsApi.accountsGetAccountsAccountIdOverdraftLimits, must be bigger than or equal to 1.');
        }
        if ($x_customer_user_agent !== null && !preg_match("/[\\w\\W\\s]*/", $x_customer_user_agent)) {
            throw new \InvalidArgumentException("invalid value for \"x_customer_user_agent\" when calling AccountsApi.accountsGetAccountsAccountIdOverdraftLimits, must conform to the pattern /[\\w\\W\\s]*/.");
        }
        

        $resourcePath = '/accounts/{accountId}/overdraft-limits';
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
        if ($account_id !== null) {
            $resourcePath = str_replace(
                '{' . 'accountId' . '}',
                ObjectSerializer::toPathValue($account_id),
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
     * Operation accountsGetAccountsAccountIdTransactions
     *
     * Obtém a lista de transações da conta identificada por accountId.
     *
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser “espelhado” pela transmissora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a transmissora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A receptora deve acatar o valor recebido da transmissora. (required)
     * @param  string $account_id Identificador da conta de depósito à vista, de poupança ou de pagamento pré-paga. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  int|null $page Número da página que está sendo requisitada (o valor da primeira página é 1). (optional, default to 1)
     * @param  int|null $page_size Quantidade total de registros por páginas. (optional, default to 25)
     * @param  \DateTime|null $from_booking_date Data inicial de filtragem. [Restrição] Deve obrigatoriamente ser enviado caso o campo toBookingDate seja informado. Caso não seja informado, deve ser assumido o dia atual. (optional)
     * @param  \DateTime|null $to_booking_date Data final de filtragem. [Restrição] Deve obrigatoriamente ser enviado caso o campo fromBookingDate seja informado. Caso não seja informado, deve ser assumido o dia atual. (optional)
     * @param  \OpenAPI\Client\Model\EnumCreditDebitIndicator|null $credit_debit_indicator Indicador do tipo de lançamento (optional)
     * @param  string|null $pagination_key Identificador de rechamada, utilizado para evitar a contagem de chamadas ao endpoint durante a paginação. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['accountsGetAccountsAccountIdTransactions'] to see the possible values for this operation
     *
     * @throws \OpenAPI\Client\ApiException on non-2xx response or if the response body is not in the expected format
     * @throws \InvalidArgumentException
     * @return \OpenAPI\Client\Model\ResponseAccountTransactions|\OpenAPI\Client\Model\ResponseErrorMetaSingle|\OpenAPI\Client\Model\ResponseErrorMetaSingle|\OpenAPI\Client\Model\ResponseErrorMetaSingle|\OpenAPI\Client\Model\ResponseErrorMetaSingle|\OpenAPI\Client\Model\ResponseErrorMetaSingle|\OpenAPI\Client\Model\ResponseErrorMetaSingle|\OpenAPI\Client\Model\ResponseErrorMetaSingle|\OpenAPI\Client\Model\ResponseErrorMetaSingle|\OpenAPI\Client\Model\ResponseErrorMetaSingle|\OpenAPI\Client\Model\ResponseErrorMetaSingle|\OpenAPI\Client\Model\ResponseErrorMetaSingle|\OpenAPI\Client\Model\ResponseErrorMetaSingle|\OpenAPI\Client\Model\ResponseErrorMetaSingle
     */
    public function accountsGetAccountsAccountIdTransactions($authorization, $x_fapi_interaction_id, $account_id, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, $page = 1, $page_size = 25, $from_booking_date = null, $to_booking_date = null, $credit_debit_indicator = null, $pagination_key = null, string $contentType = self::contentTypes['accountsGetAccountsAccountIdTransactions'][0])
    {
        list($response) = $this->accountsGetAccountsAccountIdTransactionsWithHttpInfo($authorization, $x_fapi_interaction_id, $account_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $page, $page_size, $from_booking_date, $to_booking_date, $credit_debit_indicator, $pagination_key, $contentType);
        return $response;
    }

    /**
     * Operation accountsGetAccountsAccountIdTransactionsWithHttpInfo
     *
     * Obtém a lista de transações da conta identificada por accountId.
     *
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser “espelhado” pela transmissora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a transmissora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A receptora deve acatar o valor recebido da transmissora. (required)
     * @param  string $account_id Identificador da conta de depósito à vista, de poupança ou de pagamento pré-paga. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  int|null $page Número da página que está sendo requisitada (o valor da primeira página é 1). (optional, default to 1)
     * @param  int|null $page_size Quantidade total de registros por páginas. (optional, default to 25)
     * @param  \DateTime|null $from_booking_date Data inicial de filtragem. [Restrição] Deve obrigatoriamente ser enviado caso o campo toBookingDate seja informado. Caso não seja informado, deve ser assumido o dia atual. (optional)
     * @param  \DateTime|null $to_booking_date Data final de filtragem. [Restrição] Deve obrigatoriamente ser enviado caso o campo fromBookingDate seja informado. Caso não seja informado, deve ser assumido o dia atual. (optional)
     * @param  \OpenAPI\Client\Model\EnumCreditDebitIndicator|null $credit_debit_indicator Indicador do tipo de lançamento (optional)
     * @param  string|null $pagination_key Identificador de rechamada, utilizado para evitar a contagem de chamadas ao endpoint durante a paginação. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['accountsGetAccountsAccountIdTransactions'] to see the possible values for this operation
     *
     * @throws \OpenAPI\Client\ApiException on non-2xx response or if the response body is not in the expected format
     * @throws \InvalidArgumentException
     * @return array of \OpenAPI\Client\Model\ResponseAccountTransactions|\OpenAPI\Client\Model\ResponseErrorMetaSingle|\OpenAPI\Client\Model\ResponseErrorMetaSingle|\OpenAPI\Client\Model\ResponseErrorMetaSingle|\OpenAPI\Client\Model\ResponseErrorMetaSingle|\OpenAPI\Client\Model\ResponseErrorMetaSingle|\OpenAPI\Client\Model\ResponseErrorMetaSingle|\OpenAPI\Client\Model\ResponseErrorMetaSingle|\OpenAPI\Client\Model\ResponseErrorMetaSingle|\OpenAPI\Client\Model\ResponseErrorMetaSingle|\OpenAPI\Client\Model\ResponseErrorMetaSingle|\OpenAPI\Client\Model\ResponseErrorMetaSingle|\OpenAPI\Client\Model\ResponseErrorMetaSingle|\OpenAPI\Client\Model\ResponseErrorMetaSingle, HTTP status code, HTTP response headers (array of strings)
     */
    public function accountsGetAccountsAccountIdTransactionsWithHttpInfo($authorization, $x_fapi_interaction_id, $account_id, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, $page = 1, $page_size = 25, $from_booking_date = null, $to_booking_date = null, $credit_debit_indicator = null, $pagination_key = null, string $contentType = self::contentTypes['accountsGetAccountsAccountIdTransactions'][0])
    {
        $request = $this->accountsGetAccountsAccountIdTransactionsRequest($authorization, $x_fapi_interaction_id, $account_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $page, $page_size, $from_booking_date, $to_booking_date, $credit_debit_indicator, $pagination_key, $contentType);

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
                        '\OpenAPI\Client\Model\ResponseAccountTransactions',
                        $request,
                        $response,
                    );
                case 400:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseErrorMetaSingle',
                        $request,
                        $response,
                    );
                case 401:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseErrorMetaSingle',
                        $request,
                        $response,
                    );
                case 403:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseErrorMetaSingle',
                        $request,
                        $response,
                    );
                case 404:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseErrorMetaSingle',
                        $request,
                        $response,
                    );
                case 405:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseErrorMetaSingle',
                        $request,
                        $response,
                    );
                case 406:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseErrorMetaSingle',
                        $request,
                        $response,
                    );
                case 422:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseErrorMetaSingle',
                        $request,
                        $response,
                    );
                case 423:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseErrorMetaSingle',
                        $request,
                        $response,
                    );
                case 429:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseErrorMetaSingle',
                        $request,
                        $response,
                    );
                case 500:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseErrorMetaSingle',
                        $request,
                        $response,
                    );
                case 504:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseErrorMetaSingle',
                        $request,
                        $response,
                    );
                case 529:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseErrorMetaSingle',
                        $request,
                        $response,
                    );
                default:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseErrorMetaSingle',
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
                '\OpenAPI\Client\Model\ResponseAccountTransactions',
                $request,
                $response,
            );
        } catch (ApiException $e) {
            switch ($e->getCode()) {
                case 200:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseAccountTransactions',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 400:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseErrorMetaSingle',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 401:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseErrorMetaSingle',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 403:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseErrorMetaSingle',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 404:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseErrorMetaSingle',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 405:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseErrorMetaSingle',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 406:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseErrorMetaSingle',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 422:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseErrorMetaSingle',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 423:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseErrorMetaSingle',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 429:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseErrorMetaSingle',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 500:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseErrorMetaSingle',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 504:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseErrorMetaSingle',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 529:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseErrorMetaSingle',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                default:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseErrorMetaSingle',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
            }
        

            throw $e;
        }
    }

    /**
     * Operation accountsGetAccountsAccountIdTransactionsAsync
     *
     * Obtém a lista de transações da conta identificada por accountId.
     *
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser “espelhado” pela transmissora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a transmissora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A receptora deve acatar o valor recebido da transmissora. (required)
     * @param  string $account_id Identificador da conta de depósito à vista, de poupança ou de pagamento pré-paga. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  int|null $page Número da página que está sendo requisitada (o valor da primeira página é 1). (optional, default to 1)
     * @param  int|null $page_size Quantidade total de registros por páginas. (optional, default to 25)
     * @param  \DateTime|null $from_booking_date Data inicial de filtragem. [Restrição] Deve obrigatoriamente ser enviado caso o campo toBookingDate seja informado. Caso não seja informado, deve ser assumido o dia atual. (optional)
     * @param  \DateTime|null $to_booking_date Data final de filtragem. [Restrição] Deve obrigatoriamente ser enviado caso o campo fromBookingDate seja informado. Caso não seja informado, deve ser assumido o dia atual. (optional)
     * @param  \OpenAPI\Client\Model\EnumCreditDebitIndicator|null $credit_debit_indicator Indicador do tipo de lançamento (optional)
     * @param  string|null $pagination_key Identificador de rechamada, utilizado para evitar a contagem de chamadas ao endpoint durante a paginação. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['accountsGetAccountsAccountIdTransactions'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function accountsGetAccountsAccountIdTransactionsAsync($authorization, $x_fapi_interaction_id, $account_id, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, $page = 1, $page_size = 25, $from_booking_date = null, $to_booking_date = null, $credit_debit_indicator = null, $pagination_key = null, string $contentType = self::contentTypes['accountsGetAccountsAccountIdTransactions'][0])
    {
        return $this->accountsGetAccountsAccountIdTransactionsAsyncWithHttpInfo($authorization, $x_fapi_interaction_id, $account_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $page, $page_size, $from_booking_date, $to_booking_date, $credit_debit_indicator, $pagination_key, $contentType)
            ->then(
                function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation accountsGetAccountsAccountIdTransactionsAsyncWithHttpInfo
     *
     * Obtém a lista de transações da conta identificada por accountId.
     *
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser “espelhado” pela transmissora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a transmissora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A receptora deve acatar o valor recebido da transmissora. (required)
     * @param  string $account_id Identificador da conta de depósito à vista, de poupança ou de pagamento pré-paga. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  int|null $page Número da página que está sendo requisitada (o valor da primeira página é 1). (optional, default to 1)
     * @param  int|null $page_size Quantidade total de registros por páginas. (optional, default to 25)
     * @param  \DateTime|null $from_booking_date Data inicial de filtragem. [Restrição] Deve obrigatoriamente ser enviado caso o campo toBookingDate seja informado. Caso não seja informado, deve ser assumido o dia atual. (optional)
     * @param  \DateTime|null $to_booking_date Data final de filtragem. [Restrição] Deve obrigatoriamente ser enviado caso o campo fromBookingDate seja informado. Caso não seja informado, deve ser assumido o dia atual. (optional)
     * @param  \OpenAPI\Client\Model\EnumCreditDebitIndicator|null $credit_debit_indicator Indicador do tipo de lançamento (optional)
     * @param  string|null $pagination_key Identificador de rechamada, utilizado para evitar a contagem de chamadas ao endpoint durante a paginação. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['accountsGetAccountsAccountIdTransactions'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function accountsGetAccountsAccountIdTransactionsAsyncWithHttpInfo($authorization, $x_fapi_interaction_id, $account_id, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, $page = 1, $page_size = 25, $from_booking_date = null, $to_booking_date = null, $credit_debit_indicator = null, $pagination_key = null, string $contentType = self::contentTypes['accountsGetAccountsAccountIdTransactions'][0])
    {
        $returnType = '\OpenAPI\Client\Model\ResponseAccountTransactions';
        $request = $this->accountsGetAccountsAccountIdTransactionsRequest($authorization, $x_fapi_interaction_id, $account_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $page, $page_size, $from_booking_date, $to_booking_date, $credit_debit_indicator, $pagination_key, $contentType);

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
     * Create request for operation 'accountsGetAccountsAccountIdTransactions'
     *
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser “espelhado” pela transmissora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a transmissora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A receptora deve acatar o valor recebido da transmissora. (required)
     * @param  string $account_id Identificador da conta de depósito à vista, de poupança ou de pagamento pré-paga. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  int|null $page Número da página que está sendo requisitada (o valor da primeira página é 1). (optional, default to 1)
     * @param  int|null $page_size Quantidade total de registros por páginas. (optional, default to 25)
     * @param  \DateTime|null $from_booking_date Data inicial de filtragem. [Restrição] Deve obrigatoriamente ser enviado caso o campo toBookingDate seja informado. Caso não seja informado, deve ser assumido o dia atual. (optional)
     * @param  \DateTime|null $to_booking_date Data final de filtragem. [Restrição] Deve obrigatoriamente ser enviado caso o campo fromBookingDate seja informado. Caso não seja informado, deve ser assumido o dia atual. (optional)
     * @param  \OpenAPI\Client\Model\EnumCreditDebitIndicator|null $credit_debit_indicator Indicador do tipo de lançamento (optional)
     * @param  string|null $pagination_key Identificador de rechamada, utilizado para evitar a contagem de chamadas ao endpoint durante a paginação. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['accountsGetAccountsAccountIdTransactions'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Psr7\Request
     */
    public function accountsGetAccountsAccountIdTransactionsRequest($authorization, $x_fapi_interaction_id, $account_id, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, $page = 1, $page_size = 25, $from_booking_date = null, $to_booking_date = null, $credit_debit_indicator = null, $pagination_key = null, string $contentType = self::contentTypes['accountsGetAccountsAccountIdTransactions'][0])
    {

        // verify the required parameter 'authorization' is set
        if ($authorization === null || (is_array($authorization) && count($authorization) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $authorization when calling accountsGetAccountsAccountIdTransactions'
            );
        }
        if (strlen($authorization) > 2048) {
            throw new \InvalidArgumentException('invalid length for "$authorization" when calling AccountsApi.accountsGetAccountsAccountIdTransactions, must be smaller than or equal to 2048.');
        }
        if (!preg_match("/[\\w\\W\\s]*/", $authorization)) {
            throw new \InvalidArgumentException("invalid value for \"authorization\" when calling AccountsApi.accountsGetAccountsAccountIdTransactions, must conform to the pattern /[\\w\\W\\s]*/.");
        }
        
        // verify the required parameter 'x_fapi_interaction_id' is set
        if ($x_fapi_interaction_id === null || (is_array($x_fapi_interaction_id) && count($x_fapi_interaction_id) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $x_fapi_interaction_id when calling accountsGetAccountsAccountIdTransactions'
            );
        }
        if (strlen($x_fapi_interaction_id) > 36) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_interaction_id" when calling AccountsApi.accountsGetAccountsAccountIdTransactions, must be smaller than or equal to 36.');
        }
        if (strlen($x_fapi_interaction_id) < 1) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_interaction_id" when calling AccountsApi.accountsGetAccountsAccountIdTransactions, must be bigger than or equal to 1.');
        }
        if (!preg_match("/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/", $x_fapi_interaction_id)) {
            throw new \InvalidArgumentException("invalid value for \"x_fapi_interaction_id\" when calling AccountsApi.accountsGetAccountsAccountIdTransactions, must conform to the pattern /^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/.");
        }
        
        // verify the required parameter 'account_id' is set
        if ($account_id === null || (is_array($account_id) && count($account_id) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $account_id when calling accountsGetAccountsAccountIdTransactions'
            );
        }
        if (strlen($account_id) > 100) {
            throw new \InvalidArgumentException('invalid length for "$account_id" when calling AccountsApi.accountsGetAccountsAccountIdTransactions, must be smaller than or equal to 100.');
        }
        if (!preg_match("/^[a-zA-Z0-9][a-zA-Z0-9\\-]{0,99}$/", $account_id)) {
            throw new \InvalidArgumentException("invalid value for \"account_id\" when calling AccountsApi.accountsGetAccountsAccountIdTransactions, must conform to the pattern /^[a-zA-Z0-9][a-zA-Z0-9\\-]{0,99}$/.");
        }
        
        if ($x_fapi_auth_date !== null && strlen($x_fapi_auth_date) > 29) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_auth_date" when calling AccountsApi.accountsGetAccountsAccountIdTransactions, must be smaller than or equal to 29.');
        }
        if ($x_fapi_auth_date !== null && strlen($x_fapi_auth_date) < 29) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_auth_date" when calling AccountsApi.accountsGetAccountsAccountIdTransactions, must be bigger than or equal to 29.');
        }
        if ($x_fapi_auth_date !== null && !preg_match("/^(Mon|Tue|Wed|Thu|Fri|Sat|Sun), \\d{2} (Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec) \\d{4} \\d{2}:\\d{2}:\\d{2} (GMT|UTC)$/", $x_fapi_auth_date)) {
            throw new \InvalidArgumentException("invalid value for \"x_fapi_auth_date\" when calling AccountsApi.accountsGetAccountsAccountIdTransactions, must conform to the pattern /^(Mon|Tue|Wed|Thu|Fri|Sat|Sun), \\d{2} (Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec) \\d{4} \\d{2}:\\d{2}:\\d{2} (GMT|UTC)$/.");
        }
        
        if ($x_fapi_customer_ip_address !== null && strlen($x_fapi_customer_ip_address) > 100) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_customer_ip_address" when calling AccountsApi.accountsGetAccountsAccountIdTransactions, must be smaller than or equal to 100.');
        }
        if ($x_fapi_customer_ip_address !== null && strlen($x_fapi_customer_ip_address) < 1) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_customer_ip_address" when calling AccountsApi.accountsGetAccountsAccountIdTransactions, must be bigger than or equal to 1.');
        }
        if ($x_fapi_customer_ip_address !== null && !preg_match("/[\\w\\W\\s]*/", $x_fapi_customer_ip_address)) {
            throw new \InvalidArgumentException("invalid value for \"x_fapi_customer_ip_address\" when calling AccountsApi.accountsGetAccountsAccountIdTransactions, must conform to the pattern /[\\w\\W\\s]*/.");
        }
        
        if ($x_customer_user_agent !== null && strlen($x_customer_user_agent) > 100) {
            throw new \InvalidArgumentException('invalid length for "$x_customer_user_agent" when calling AccountsApi.accountsGetAccountsAccountIdTransactions, must be smaller than or equal to 100.');
        }
        if ($x_customer_user_agent !== null && strlen($x_customer_user_agent) < 1) {
            throw new \InvalidArgumentException('invalid length for "$x_customer_user_agent" when calling AccountsApi.accountsGetAccountsAccountIdTransactions, must be bigger than or equal to 1.');
        }
        if ($x_customer_user_agent !== null && !preg_match("/[\\w\\W\\s]*/", $x_customer_user_agent)) {
            throw new \InvalidArgumentException("invalid value for \"x_customer_user_agent\" when calling AccountsApi.accountsGetAccountsAccountIdTransactions, must conform to the pattern /[\\w\\W\\s]*/.");
        }
        
        if ($page !== null && $page > 2147483647) {
            throw new \InvalidArgumentException('invalid value for "$page" when calling AccountsApi.accountsGetAccountsAccountIdTransactions, must be smaller than or equal to 2147483647.');
        }
        if ($page !== null && $page < 1) {
            throw new \InvalidArgumentException('invalid value for "$page" when calling AccountsApi.accountsGetAccountsAccountIdTransactions, must be bigger than or equal to 1.');
        }
        
        if ($page_size !== null && $page_size > 1000) {
            throw new \InvalidArgumentException('invalid value for "$page_size" when calling AccountsApi.accountsGetAccountsAccountIdTransactions, must be smaller than or equal to 1000.');
        }
        if ($page_size !== null && $page_size < 1) {
            throw new \InvalidArgumentException('invalid value for "$page_size" when calling AccountsApi.accountsGetAccountsAccountIdTransactions, must be bigger than or equal to 1.');
        }
        
        if ($from_booking_date !== null && strlen($from_booking_date) > 10) {
            throw new \InvalidArgumentException('invalid length for "$from_booking_date" when calling AccountsApi.accountsGetAccountsAccountIdTransactions, must be smaller than or equal to 10.');
        }
        
        if ($to_booking_date !== null && strlen($to_booking_date) > 10) {
            throw new \InvalidArgumentException('invalid length for "$to_booking_date" when calling AccountsApi.accountsGetAccountsAccountIdTransactions, must be smaller than or equal to 10.');
        }
        

        if ($pagination_key !== null && strlen($pagination_key) > 2048) {
            throw new \InvalidArgumentException('invalid length for "$pagination_key" when calling AccountsApi.accountsGetAccountsAccountIdTransactions, must be smaller than or equal to 2048.');
        }
        if ($pagination_key !== null && !preg_match("/[\\w\\W\\s]*/", $pagination_key)) {
            throw new \InvalidArgumentException("invalid value for \"pagination_key\" when calling AccountsApi.accountsGetAccountsAccountIdTransactions, must conform to the pattern /[\\w\\W\\s]*/.");
        }
        

        $resourcePath = '/accounts/{accountId}/transactions';
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
        // query params
        $queryParams = array_merge($queryParams, ObjectSerializer::toQueryValue(
            $from_booking_date,
            'fromBookingDate', // param base name
            'string', // openApiType
            'form', // style
            true, // explode
            false // required
        ) ?? []);
        // query params
        $queryParams = array_merge($queryParams, ObjectSerializer::toQueryValue(
            $to_booking_date,
            'toBookingDate', // param base name
            'string', // openApiType
            'form', // style
            true, // explode
            false // required
        ) ?? []);
        // query params
        $queryParams = array_merge($queryParams, ObjectSerializer::toQueryValue(
            $credit_debit_indicator,
            'creditDebitIndicator', // param base name
            'EnumCreditDebitIndicator', // openApiType
            'form', // style
            true, // explode
            false // required
        ) ?? []);
        // query params
        $queryParams = array_merge($queryParams, ObjectSerializer::toQueryValue(
            $pagination_key,
            'pagination-key', // param base name
            'string', // openApiType
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
        if ($account_id !== null) {
            $resourcePath = str_replace(
                '{' . 'accountId' . '}',
                ObjectSerializer::toPathValue($account_id),
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
     * Operation accountsGetAccountsAccountIdTransactionsCurrent
     *
     * Obtém a lista de transações recentes (últimos 7 dias) da conta identificada por accountId.
     *
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser “espelhado” pela transmissora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a transmissora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A receptora deve acatar o valor recebido da transmissora. (required)
     * @param  string $account_id Identificador da conta de depósito à vista, de poupança ou de pagamento pré-paga. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  int|null $page Número da página que está sendo requisitada (o valor da primeira página é 1). (optional, default to 1)
     * @param  int|null $page_size Quantidade total de registros por páginas. (optional, default to 25)
     * @param  \DateTime|null $from_booking_date Data inicial de filtragem. O período máximo utilizado no filtro é de 7 dias inclusive (D-6).    [Restrição] Deve obrigatoriamente ser enviado caso o campo toBookingDate seja informado.  Caso não seja informado, deve ser assumido o dia atual. (optional)
     * @param  \DateTime|null $to_booking_date Data final de filtragem. O período máximo utilizado no filtro é de 7 dias inclusive (D-6).    [Restrição] Deve obrigatoriamente ser enviado caso o campo fromBookingDate seja informado.  Caso não seja informado, deve ser assumido o dia atual. (optional)
     * @param  \OpenAPI\Client\Model\EnumCreditDebitIndicator|null $credit_debit_indicator Indicador do tipo de lançamento (optional)
     * @param  string|null $pagination_key Identificador de rechamada, utilizado para evitar a contagem de chamadas ao endpoint durante a paginação. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['accountsGetAccountsAccountIdTransactionsCurrent'] to see the possible values for this operation
     *
     * @throws \OpenAPI\Client\ApiException on non-2xx response or if the response body is not in the expected format
     * @throws \InvalidArgumentException
     * @return \OpenAPI\Client\Model\ResponseAccountTransactions|\OpenAPI\Client\Model\ResponseErrorMetaSingle|\OpenAPI\Client\Model\ResponseErrorMetaSingle|\OpenAPI\Client\Model\ResponseErrorMetaSingle|\OpenAPI\Client\Model\ResponseErrorMetaSingle|\OpenAPI\Client\Model\ResponseErrorMetaSingle|\OpenAPI\Client\Model\ResponseErrorMetaSingle|\OpenAPI\Client\Model\ResponseErrorMetaSingle|\OpenAPI\Client\Model\ResponseErrorMetaSingle|\OpenAPI\Client\Model\ResponseErrorMetaSingle|\OpenAPI\Client\Model\ResponseErrorMetaSingle|\OpenAPI\Client\Model\ResponseErrorMetaSingle|\OpenAPI\Client\Model\ResponseErrorMetaSingle|\OpenAPI\Client\Model\ResponseErrorMetaSingle
     */
    public function accountsGetAccountsAccountIdTransactionsCurrent($authorization, $x_fapi_interaction_id, $account_id, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, $page = 1, $page_size = 25, $from_booking_date = null, $to_booking_date = null, $credit_debit_indicator = null, $pagination_key = null, string $contentType = self::contentTypes['accountsGetAccountsAccountIdTransactionsCurrent'][0])
    {
        list($response) = $this->accountsGetAccountsAccountIdTransactionsCurrentWithHttpInfo($authorization, $x_fapi_interaction_id, $account_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $page, $page_size, $from_booking_date, $to_booking_date, $credit_debit_indicator, $pagination_key, $contentType);
        return $response;
    }

    /**
     * Operation accountsGetAccountsAccountIdTransactionsCurrentWithHttpInfo
     *
     * Obtém a lista de transações recentes (últimos 7 dias) da conta identificada por accountId.
     *
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser “espelhado” pela transmissora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a transmissora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A receptora deve acatar o valor recebido da transmissora. (required)
     * @param  string $account_id Identificador da conta de depósito à vista, de poupança ou de pagamento pré-paga. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  int|null $page Número da página que está sendo requisitada (o valor da primeira página é 1). (optional, default to 1)
     * @param  int|null $page_size Quantidade total de registros por páginas. (optional, default to 25)
     * @param  \DateTime|null $from_booking_date Data inicial de filtragem. O período máximo utilizado no filtro é de 7 dias inclusive (D-6).    [Restrição] Deve obrigatoriamente ser enviado caso o campo toBookingDate seja informado.  Caso não seja informado, deve ser assumido o dia atual. (optional)
     * @param  \DateTime|null $to_booking_date Data final de filtragem. O período máximo utilizado no filtro é de 7 dias inclusive (D-6).    [Restrição] Deve obrigatoriamente ser enviado caso o campo fromBookingDate seja informado.  Caso não seja informado, deve ser assumido o dia atual. (optional)
     * @param  \OpenAPI\Client\Model\EnumCreditDebitIndicator|null $credit_debit_indicator Indicador do tipo de lançamento (optional)
     * @param  string|null $pagination_key Identificador de rechamada, utilizado para evitar a contagem de chamadas ao endpoint durante a paginação. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['accountsGetAccountsAccountIdTransactionsCurrent'] to see the possible values for this operation
     *
     * @throws \OpenAPI\Client\ApiException on non-2xx response or if the response body is not in the expected format
     * @throws \InvalidArgumentException
     * @return array of \OpenAPI\Client\Model\ResponseAccountTransactions|\OpenAPI\Client\Model\ResponseErrorMetaSingle|\OpenAPI\Client\Model\ResponseErrorMetaSingle|\OpenAPI\Client\Model\ResponseErrorMetaSingle|\OpenAPI\Client\Model\ResponseErrorMetaSingle|\OpenAPI\Client\Model\ResponseErrorMetaSingle|\OpenAPI\Client\Model\ResponseErrorMetaSingle|\OpenAPI\Client\Model\ResponseErrorMetaSingle|\OpenAPI\Client\Model\ResponseErrorMetaSingle|\OpenAPI\Client\Model\ResponseErrorMetaSingle|\OpenAPI\Client\Model\ResponseErrorMetaSingle|\OpenAPI\Client\Model\ResponseErrorMetaSingle|\OpenAPI\Client\Model\ResponseErrorMetaSingle|\OpenAPI\Client\Model\ResponseErrorMetaSingle, HTTP status code, HTTP response headers (array of strings)
     */
    public function accountsGetAccountsAccountIdTransactionsCurrentWithHttpInfo($authorization, $x_fapi_interaction_id, $account_id, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, $page = 1, $page_size = 25, $from_booking_date = null, $to_booking_date = null, $credit_debit_indicator = null, $pagination_key = null, string $contentType = self::contentTypes['accountsGetAccountsAccountIdTransactionsCurrent'][0])
    {
        $request = $this->accountsGetAccountsAccountIdTransactionsCurrentRequest($authorization, $x_fapi_interaction_id, $account_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $page, $page_size, $from_booking_date, $to_booking_date, $credit_debit_indicator, $pagination_key, $contentType);

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
                        '\OpenAPI\Client\Model\ResponseAccountTransactions',
                        $request,
                        $response,
                    );
                case 400:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseErrorMetaSingle',
                        $request,
                        $response,
                    );
                case 401:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseErrorMetaSingle',
                        $request,
                        $response,
                    );
                case 403:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseErrorMetaSingle',
                        $request,
                        $response,
                    );
                case 404:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseErrorMetaSingle',
                        $request,
                        $response,
                    );
                case 405:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseErrorMetaSingle',
                        $request,
                        $response,
                    );
                case 406:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseErrorMetaSingle',
                        $request,
                        $response,
                    );
                case 422:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseErrorMetaSingle',
                        $request,
                        $response,
                    );
                case 423:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseErrorMetaSingle',
                        $request,
                        $response,
                    );
                case 429:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseErrorMetaSingle',
                        $request,
                        $response,
                    );
                case 500:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseErrorMetaSingle',
                        $request,
                        $response,
                    );
                case 504:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseErrorMetaSingle',
                        $request,
                        $response,
                    );
                case 529:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseErrorMetaSingle',
                        $request,
                        $response,
                    );
                default:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseErrorMetaSingle',
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
                '\OpenAPI\Client\Model\ResponseAccountTransactions',
                $request,
                $response,
            );
        } catch (ApiException $e) {
            switch ($e->getCode()) {
                case 200:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseAccountTransactions',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 400:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseErrorMetaSingle',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 401:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseErrorMetaSingle',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 403:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseErrorMetaSingle',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 404:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseErrorMetaSingle',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 405:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseErrorMetaSingle',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 406:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseErrorMetaSingle',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 422:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseErrorMetaSingle',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 423:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseErrorMetaSingle',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 429:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseErrorMetaSingle',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 500:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseErrorMetaSingle',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 504:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseErrorMetaSingle',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 529:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseErrorMetaSingle',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                default:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseErrorMetaSingle',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
            }
        

            throw $e;
        }
    }

    /**
     * Operation accountsGetAccountsAccountIdTransactionsCurrentAsync
     *
     * Obtém a lista de transações recentes (últimos 7 dias) da conta identificada por accountId.
     *
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser “espelhado” pela transmissora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a transmissora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A receptora deve acatar o valor recebido da transmissora. (required)
     * @param  string $account_id Identificador da conta de depósito à vista, de poupança ou de pagamento pré-paga. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  int|null $page Número da página que está sendo requisitada (o valor da primeira página é 1). (optional, default to 1)
     * @param  int|null $page_size Quantidade total de registros por páginas. (optional, default to 25)
     * @param  \DateTime|null $from_booking_date Data inicial de filtragem. O período máximo utilizado no filtro é de 7 dias inclusive (D-6).    [Restrição] Deve obrigatoriamente ser enviado caso o campo toBookingDate seja informado.  Caso não seja informado, deve ser assumido o dia atual. (optional)
     * @param  \DateTime|null $to_booking_date Data final de filtragem. O período máximo utilizado no filtro é de 7 dias inclusive (D-6).    [Restrição] Deve obrigatoriamente ser enviado caso o campo fromBookingDate seja informado.  Caso não seja informado, deve ser assumido o dia atual. (optional)
     * @param  \OpenAPI\Client\Model\EnumCreditDebitIndicator|null $credit_debit_indicator Indicador do tipo de lançamento (optional)
     * @param  string|null $pagination_key Identificador de rechamada, utilizado para evitar a contagem de chamadas ao endpoint durante a paginação. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['accountsGetAccountsAccountIdTransactionsCurrent'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function accountsGetAccountsAccountIdTransactionsCurrentAsync($authorization, $x_fapi_interaction_id, $account_id, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, $page = 1, $page_size = 25, $from_booking_date = null, $to_booking_date = null, $credit_debit_indicator = null, $pagination_key = null, string $contentType = self::contentTypes['accountsGetAccountsAccountIdTransactionsCurrent'][0])
    {
        return $this->accountsGetAccountsAccountIdTransactionsCurrentAsyncWithHttpInfo($authorization, $x_fapi_interaction_id, $account_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $page, $page_size, $from_booking_date, $to_booking_date, $credit_debit_indicator, $pagination_key, $contentType)
            ->then(
                function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation accountsGetAccountsAccountIdTransactionsCurrentAsyncWithHttpInfo
     *
     * Obtém a lista de transações recentes (últimos 7 dias) da conta identificada por accountId.
     *
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser “espelhado” pela transmissora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a transmissora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A receptora deve acatar o valor recebido da transmissora. (required)
     * @param  string $account_id Identificador da conta de depósito à vista, de poupança ou de pagamento pré-paga. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  int|null $page Número da página que está sendo requisitada (o valor da primeira página é 1). (optional, default to 1)
     * @param  int|null $page_size Quantidade total de registros por páginas. (optional, default to 25)
     * @param  \DateTime|null $from_booking_date Data inicial de filtragem. O período máximo utilizado no filtro é de 7 dias inclusive (D-6).    [Restrição] Deve obrigatoriamente ser enviado caso o campo toBookingDate seja informado.  Caso não seja informado, deve ser assumido o dia atual. (optional)
     * @param  \DateTime|null $to_booking_date Data final de filtragem. O período máximo utilizado no filtro é de 7 dias inclusive (D-6).    [Restrição] Deve obrigatoriamente ser enviado caso o campo fromBookingDate seja informado.  Caso não seja informado, deve ser assumido o dia atual. (optional)
     * @param  \OpenAPI\Client\Model\EnumCreditDebitIndicator|null $credit_debit_indicator Indicador do tipo de lançamento (optional)
     * @param  string|null $pagination_key Identificador de rechamada, utilizado para evitar a contagem de chamadas ao endpoint durante a paginação. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['accountsGetAccountsAccountIdTransactionsCurrent'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function accountsGetAccountsAccountIdTransactionsCurrentAsyncWithHttpInfo($authorization, $x_fapi_interaction_id, $account_id, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, $page = 1, $page_size = 25, $from_booking_date = null, $to_booking_date = null, $credit_debit_indicator = null, $pagination_key = null, string $contentType = self::contentTypes['accountsGetAccountsAccountIdTransactionsCurrent'][0])
    {
        $returnType = '\OpenAPI\Client\Model\ResponseAccountTransactions';
        $request = $this->accountsGetAccountsAccountIdTransactionsCurrentRequest($authorization, $x_fapi_interaction_id, $account_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $page, $page_size, $from_booking_date, $to_booking_date, $credit_debit_indicator, $pagination_key, $contentType);

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
     * Create request for operation 'accountsGetAccountsAccountIdTransactionsCurrent'
     *
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser “espelhado” pela transmissora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a transmissora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A receptora deve acatar o valor recebido da transmissora. (required)
     * @param  string $account_id Identificador da conta de depósito à vista, de poupança ou de pagamento pré-paga. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  int|null $page Número da página que está sendo requisitada (o valor da primeira página é 1). (optional, default to 1)
     * @param  int|null $page_size Quantidade total de registros por páginas. (optional, default to 25)
     * @param  \DateTime|null $from_booking_date Data inicial de filtragem. O período máximo utilizado no filtro é de 7 dias inclusive (D-6).    [Restrição] Deve obrigatoriamente ser enviado caso o campo toBookingDate seja informado.  Caso não seja informado, deve ser assumido o dia atual. (optional)
     * @param  \DateTime|null $to_booking_date Data final de filtragem. O período máximo utilizado no filtro é de 7 dias inclusive (D-6).    [Restrição] Deve obrigatoriamente ser enviado caso o campo fromBookingDate seja informado.  Caso não seja informado, deve ser assumido o dia atual. (optional)
     * @param  \OpenAPI\Client\Model\EnumCreditDebitIndicator|null $credit_debit_indicator Indicador do tipo de lançamento (optional)
     * @param  string|null $pagination_key Identificador de rechamada, utilizado para evitar a contagem de chamadas ao endpoint durante a paginação. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['accountsGetAccountsAccountIdTransactionsCurrent'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Psr7\Request
     */
    public function accountsGetAccountsAccountIdTransactionsCurrentRequest($authorization, $x_fapi_interaction_id, $account_id, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, $page = 1, $page_size = 25, $from_booking_date = null, $to_booking_date = null, $credit_debit_indicator = null, $pagination_key = null, string $contentType = self::contentTypes['accountsGetAccountsAccountIdTransactionsCurrent'][0])
    {

        // verify the required parameter 'authorization' is set
        if ($authorization === null || (is_array($authorization) && count($authorization) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $authorization when calling accountsGetAccountsAccountIdTransactionsCurrent'
            );
        }
        if (strlen($authorization) > 2048) {
            throw new \InvalidArgumentException('invalid length for "$authorization" when calling AccountsApi.accountsGetAccountsAccountIdTransactionsCurrent, must be smaller than or equal to 2048.');
        }
        if (!preg_match("/[\\w\\W\\s]*/", $authorization)) {
            throw new \InvalidArgumentException("invalid value for \"authorization\" when calling AccountsApi.accountsGetAccountsAccountIdTransactionsCurrent, must conform to the pattern /[\\w\\W\\s]*/.");
        }
        
        // verify the required parameter 'x_fapi_interaction_id' is set
        if ($x_fapi_interaction_id === null || (is_array($x_fapi_interaction_id) && count($x_fapi_interaction_id) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $x_fapi_interaction_id when calling accountsGetAccountsAccountIdTransactionsCurrent'
            );
        }
        if (strlen($x_fapi_interaction_id) > 36) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_interaction_id" when calling AccountsApi.accountsGetAccountsAccountIdTransactionsCurrent, must be smaller than or equal to 36.');
        }
        if (strlen($x_fapi_interaction_id) < 1) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_interaction_id" when calling AccountsApi.accountsGetAccountsAccountIdTransactionsCurrent, must be bigger than or equal to 1.');
        }
        if (!preg_match("/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/", $x_fapi_interaction_id)) {
            throw new \InvalidArgumentException("invalid value for \"x_fapi_interaction_id\" when calling AccountsApi.accountsGetAccountsAccountIdTransactionsCurrent, must conform to the pattern /^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/.");
        }
        
        // verify the required parameter 'account_id' is set
        if ($account_id === null || (is_array($account_id) && count($account_id) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $account_id when calling accountsGetAccountsAccountIdTransactionsCurrent'
            );
        }
        if (strlen($account_id) > 100) {
            throw new \InvalidArgumentException('invalid length for "$account_id" when calling AccountsApi.accountsGetAccountsAccountIdTransactionsCurrent, must be smaller than or equal to 100.');
        }
        if (!preg_match("/^[a-zA-Z0-9][a-zA-Z0-9\\-]{0,99}$/", $account_id)) {
            throw new \InvalidArgumentException("invalid value for \"account_id\" when calling AccountsApi.accountsGetAccountsAccountIdTransactionsCurrent, must conform to the pattern /^[a-zA-Z0-9][a-zA-Z0-9\\-]{0,99}$/.");
        }
        
        if ($x_fapi_auth_date !== null && strlen($x_fapi_auth_date) > 29) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_auth_date" when calling AccountsApi.accountsGetAccountsAccountIdTransactionsCurrent, must be smaller than or equal to 29.');
        }
        if ($x_fapi_auth_date !== null && strlen($x_fapi_auth_date) < 29) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_auth_date" when calling AccountsApi.accountsGetAccountsAccountIdTransactionsCurrent, must be bigger than or equal to 29.');
        }
        if ($x_fapi_auth_date !== null && !preg_match("/^(Mon|Tue|Wed|Thu|Fri|Sat|Sun), \\d{2} (Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec) \\d{4} \\d{2}:\\d{2}:\\d{2} (GMT|UTC)$/", $x_fapi_auth_date)) {
            throw new \InvalidArgumentException("invalid value for \"x_fapi_auth_date\" when calling AccountsApi.accountsGetAccountsAccountIdTransactionsCurrent, must conform to the pattern /^(Mon|Tue|Wed|Thu|Fri|Sat|Sun), \\d{2} (Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec) \\d{4} \\d{2}:\\d{2}:\\d{2} (GMT|UTC)$/.");
        }
        
        if ($x_fapi_customer_ip_address !== null && strlen($x_fapi_customer_ip_address) > 100) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_customer_ip_address" when calling AccountsApi.accountsGetAccountsAccountIdTransactionsCurrent, must be smaller than or equal to 100.');
        }
        if ($x_fapi_customer_ip_address !== null && strlen($x_fapi_customer_ip_address) < 1) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_customer_ip_address" when calling AccountsApi.accountsGetAccountsAccountIdTransactionsCurrent, must be bigger than or equal to 1.');
        }
        if ($x_fapi_customer_ip_address !== null && !preg_match("/[\\w\\W\\s]*/", $x_fapi_customer_ip_address)) {
            throw new \InvalidArgumentException("invalid value for \"x_fapi_customer_ip_address\" when calling AccountsApi.accountsGetAccountsAccountIdTransactionsCurrent, must conform to the pattern /[\\w\\W\\s]*/.");
        }
        
        if ($x_customer_user_agent !== null && strlen($x_customer_user_agent) > 100) {
            throw new \InvalidArgumentException('invalid length for "$x_customer_user_agent" when calling AccountsApi.accountsGetAccountsAccountIdTransactionsCurrent, must be smaller than or equal to 100.');
        }
        if ($x_customer_user_agent !== null && strlen($x_customer_user_agent) < 1) {
            throw new \InvalidArgumentException('invalid length for "$x_customer_user_agent" when calling AccountsApi.accountsGetAccountsAccountIdTransactionsCurrent, must be bigger than or equal to 1.');
        }
        if ($x_customer_user_agent !== null && !preg_match("/[\\w\\W\\s]*/", $x_customer_user_agent)) {
            throw new \InvalidArgumentException("invalid value for \"x_customer_user_agent\" when calling AccountsApi.accountsGetAccountsAccountIdTransactionsCurrent, must conform to the pattern /[\\w\\W\\s]*/.");
        }
        
        if ($page !== null && $page > 2147483647) {
            throw new \InvalidArgumentException('invalid value for "$page" when calling AccountsApi.accountsGetAccountsAccountIdTransactionsCurrent, must be smaller than or equal to 2147483647.');
        }
        if ($page !== null && $page < 1) {
            throw new \InvalidArgumentException('invalid value for "$page" when calling AccountsApi.accountsGetAccountsAccountIdTransactionsCurrent, must be bigger than or equal to 1.');
        }
        
        if ($page_size !== null && $page_size > 1000) {
            throw new \InvalidArgumentException('invalid value for "$page_size" when calling AccountsApi.accountsGetAccountsAccountIdTransactionsCurrent, must be smaller than or equal to 1000.');
        }
        if ($page_size !== null && $page_size < 1) {
            throw new \InvalidArgumentException('invalid value for "$page_size" when calling AccountsApi.accountsGetAccountsAccountIdTransactionsCurrent, must be bigger than or equal to 1.');
        }
        
        if ($from_booking_date !== null && strlen($from_booking_date) > 10) {
            throw new \InvalidArgumentException('invalid length for "$from_booking_date" when calling AccountsApi.accountsGetAccountsAccountIdTransactionsCurrent, must be smaller than or equal to 10.');
        }
        
        if ($to_booking_date !== null && strlen($to_booking_date) > 10) {
            throw new \InvalidArgumentException('invalid length for "$to_booking_date" when calling AccountsApi.accountsGetAccountsAccountIdTransactionsCurrent, must be smaller than or equal to 10.');
        }
        

        if ($pagination_key !== null && strlen($pagination_key) > 2048) {
            throw new \InvalidArgumentException('invalid length for "$pagination_key" when calling AccountsApi.accountsGetAccountsAccountIdTransactionsCurrent, must be smaller than or equal to 2048.');
        }
        if ($pagination_key !== null && !preg_match("/[\\w\\W\\s]*/", $pagination_key)) {
            throw new \InvalidArgumentException("invalid value for \"pagination_key\" when calling AccountsApi.accountsGetAccountsAccountIdTransactionsCurrent, must conform to the pattern /[\\w\\W\\s]*/.");
        }
        

        $resourcePath = '/accounts/{accountId}/transactions-current';
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
        // query params
        $queryParams = array_merge($queryParams, ObjectSerializer::toQueryValue(
            $from_booking_date,
            'fromBookingDate', // param base name
            'string', // openApiType
            'form', // style
            true, // explode
            false // required
        ) ?? []);
        // query params
        $queryParams = array_merge($queryParams, ObjectSerializer::toQueryValue(
            $to_booking_date,
            'toBookingDate', // param base name
            'string', // openApiType
            'form', // style
            true, // explode
            false // required
        ) ?? []);
        // query params
        $queryParams = array_merge($queryParams, ObjectSerializer::toQueryValue(
            $credit_debit_indicator,
            'creditDebitIndicator', // param base name
            'EnumCreditDebitIndicator', // openApiType
            'form', // style
            true, // explode
            false // required
        ) ?? []);
        // query params
        $queryParams = array_merge($queryParams, ObjectSerializer::toQueryValue(
            $pagination_key,
            'pagination-key', // param base name
            'string', // openApiType
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
        if ($account_id !== null) {
            $resourcePath = str_replace(
                '{' . 'accountId' . '}',
                ObjectSerializer::toPathValue($account_id),
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
