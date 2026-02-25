# OpenAPI\Client\AccountsApi

Operações para listagem das informações da Conta do Cliente

All URIs are relative to https://api.banco.com.br/open-banking/accounts/v2, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**accountsGetAccounts()**](AccountsApi.md#accountsGetAccounts) | **GET** /accounts | Obtém a lista de contas consentidas pelo cliente. |
| [**accountsGetAccountsAccountId()**](AccountsApi.md#accountsGetAccountsAccountId) | **GET** /accounts/{accountId} | Obtém os dados de identificação da conta identificada por accountId. |
| [**accountsGetAccountsAccountIdBalances()**](AccountsApi.md#accountsGetAccountsAccountIdBalances) | **GET** /accounts/{accountId}/balances | Obtém os saldos da conta identificada por accountId. |
| [**accountsGetAccountsAccountIdOverdraftLimits()**](AccountsApi.md#accountsGetAccountsAccountIdOverdraftLimits) | **GET** /accounts/{accountId}/overdraft-limits | Obtém os limites da conta identificada por accountId. |
| [**accountsGetAccountsAccountIdTransactions()**](AccountsApi.md#accountsGetAccountsAccountIdTransactions) | **GET** /accounts/{accountId}/transactions | Obtém a lista de transações da conta identificada por accountId. |
| [**accountsGetAccountsAccountIdTransactionsCurrent()**](AccountsApi.md#accountsGetAccountsAccountIdTransactionsCurrent) | **GET** /accounts/{accountId}/transactions-current | Obtém a lista de transações recentes (últimos 7 dias) da conta identificada por accountId. |


## `accountsGetAccounts()`

```php
accountsGetAccounts($authorization, $x_fapi_interaction_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $page, $page_size, $account_type, $pagination_key): \OpenAPI\Client\Model\ResponseAccountList
```

Obtém a lista de contas consentidas pelo cliente.

Método para obter a lista de contas depósito à vista, poupança e pagamento pré-pagas mantidas pelo cliente na instituição transmissora e para as quais ele tenha fornecido consentimento.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2Security
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');



$apiInstance = new OpenAPI\Client\Api\AccountsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$authorization = 'authorization_example'; // string | Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado
$x_fapi_interaction_id = d78fc4e5-37ca-4da3-adf2-9b082bf92280; // string | Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser “espelhado” pela transmissora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a transmissora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A receptora deve acatar o valor recebido da transmissora.
$x_fapi_auth_date = 'x_fapi_auth_date_example'; // string | Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC
$x_fapi_customer_ip_address = 'x_fapi_customer_ip_address_example'; // string | O endereço IP do usuário se estiver atualmente logado com o receptor.
$x_customer_user_agent = 'x_customer_user_agent_example'; // string | Indica o user-agent que o usuário utiliza.
$page = 1; // int | Número da página que está sendo requisitada (o valor da primeira página é 1).
$page_size = 25; // int | Quantidade total de registros por páginas.
$account_type = new \OpenAPI\Client\Model\\OpenAPI\Client\Model\EnumAccountType(); // \OpenAPI\Client\Model\EnumAccountType | Tipos de contas. Modalidades tradicionais previstas pela Resolução 4.753, não contemplando contas vinculadas, conta de domiciliados no exterior, contas em moedas estrangeiras e conta correspondente moeda eletrônica. Vide Enum.
$pagination_key = 'pagination_key_example'; // string | Identificador de rechamada, utilizado para evitar a contagem de chamadas ao endpoint durante a paginação.

try {
    $result = $apiInstance->accountsGetAccounts($authorization, $x_fapi_interaction_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $page, $page_size, $account_type, $pagination_key);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountsApi->accountsGetAccounts: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **authorization** | **string**| Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado | |
| **x_fapi_interaction_id** | **string**| Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser “espelhado” pela transmissora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a transmissora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A receptora deve acatar o valor recebido da transmissora. | |
| **x_fapi_auth_date** | **string**| Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC | [optional] |
| **x_fapi_customer_ip_address** | **string**| O endereço IP do usuário se estiver atualmente logado com o receptor. | [optional] |
| **x_customer_user_agent** | **string**| Indica o user-agent que o usuário utiliza. | [optional] |
| **page** | **int**| Número da página que está sendo requisitada (o valor da primeira página é 1). | [optional] [default to 1] |
| **page_size** | **int**| Quantidade total de registros por páginas. | [optional] [default to 25] |
| **account_type** | [**\OpenAPI\Client\Model\EnumAccountType**](../Model/.md)| Tipos de contas. Modalidades tradicionais previstas pela Resolução 4.753, não contemplando contas vinculadas, conta de domiciliados no exterior, contas em moedas estrangeiras e conta correspondente moeda eletrônica. Vide Enum. | [optional] |
| **pagination_key** | **string**| Identificador de rechamada, utilizado para evitar a contagem de chamadas ao endpoint durante a paginação. | [optional] |

### Return type

[**\OpenAPI\Client\Model\ResponseAccountList**](../Model/ResponseAccountList.md)

### Authorization

[OAuth2Security](../../README.md#OAuth2Security), [OpenId](../../README.md#OpenId)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/json; charset=utf-8`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `accountsGetAccountsAccountId()`

```php
accountsGetAccountsAccountId($authorization, $x_fapi_interaction_id, $account_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent): \OpenAPI\Client\Model\ResponseAccountIdentification
```

Obtém os dados de identificação da conta identificada por accountId.

Método para obter os dados de identificação da conta de depósito à vista, poupança ou pagamento pré-paga identificada por accountId mantida pelo cliente na instituição transmissora.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2Security
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');



$apiInstance = new OpenAPI\Client\Api\AccountsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$authorization = 'authorization_example'; // string | Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado
$x_fapi_interaction_id = d78fc4e5-37ca-4da3-adf2-9b082bf92280; // string | Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser “espelhado” pela transmissora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a transmissora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A receptora deve acatar o valor recebido da transmissora.
$account_id = 'account_id_example'; // string | Identificador da conta de depósito à vista, de poupança ou de pagamento pré-paga.
$x_fapi_auth_date = 'x_fapi_auth_date_example'; // string | Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC
$x_fapi_customer_ip_address = 'x_fapi_customer_ip_address_example'; // string | O endereço IP do usuário se estiver atualmente logado com o receptor.
$x_customer_user_agent = 'x_customer_user_agent_example'; // string | Indica o user-agent que o usuário utiliza.

try {
    $result = $apiInstance->accountsGetAccountsAccountId($authorization, $x_fapi_interaction_id, $account_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountsApi->accountsGetAccountsAccountId: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **authorization** | **string**| Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado | |
| **x_fapi_interaction_id** | **string**| Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser “espelhado” pela transmissora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a transmissora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A receptora deve acatar o valor recebido da transmissora. | |
| **account_id** | **string**| Identificador da conta de depósito à vista, de poupança ou de pagamento pré-paga. | |
| **x_fapi_auth_date** | **string**| Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC | [optional] |
| **x_fapi_customer_ip_address** | **string**| O endereço IP do usuário se estiver atualmente logado com o receptor. | [optional] |
| **x_customer_user_agent** | **string**| Indica o user-agent que o usuário utiliza. | [optional] |

### Return type

[**\OpenAPI\Client\Model\ResponseAccountIdentification**](../Model/ResponseAccountIdentification.md)

### Authorization

[OAuth2Security](../../README.md#OAuth2Security), [OpenId](../../README.md#OpenId)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/json; charset=utf-8`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `accountsGetAccountsAccountIdBalances()`

```php
accountsGetAccountsAccountIdBalances($authorization, $x_fapi_interaction_id, $account_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent): \OpenAPI\Client\Model\ResponseAccountBalances
```

Obtém os saldos da conta identificada por accountId.

Método para obter os saldos da conta de depósito à vista, poupança ou pagamento pré-paga identificada por accountId mantida pelo cliente na instituição transmissora.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2Security
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');



$apiInstance = new OpenAPI\Client\Api\AccountsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$authorization = 'authorization_example'; // string | Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado
$x_fapi_interaction_id = d78fc4e5-37ca-4da3-adf2-9b082bf92280; // string | Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser “espelhado” pela transmissora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a transmissora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A receptora deve acatar o valor recebido da transmissora.
$account_id = 'account_id_example'; // string | Identificador da conta de depósito à vista, de poupança ou de pagamento pré-paga.
$x_fapi_auth_date = 'x_fapi_auth_date_example'; // string | Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC
$x_fapi_customer_ip_address = 'x_fapi_customer_ip_address_example'; // string | O endereço IP do usuário se estiver atualmente logado com o receptor.
$x_customer_user_agent = 'x_customer_user_agent_example'; // string | Indica o user-agent que o usuário utiliza.

try {
    $result = $apiInstance->accountsGetAccountsAccountIdBalances($authorization, $x_fapi_interaction_id, $account_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountsApi->accountsGetAccountsAccountIdBalances: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **authorization** | **string**| Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado | |
| **x_fapi_interaction_id** | **string**| Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser “espelhado” pela transmissora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a transmissora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A receptora deve acatar o valor recebido da transmissora. | |
| **account_id** | **string**| Identificador da conta de depósito à vista, de poupança ou de pagamento pré-paga. | |
| **x_fapi_auth_date** | **string**| Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC | [optional] |
| **x_fapi_customer_ip_address** | **string**| O endereço IP do usuário se estiver atualmente logado com o receptor. | [optional] |
| **x_customer_user_agent** | **string**| Indica o user-agent que o usuário utiliza. | [optional] |

### Return type

[**\OpenAPI\Client\Model\ResponseAccountBalances**](../Model/ResponseAccountBalances.md)

### Authorization

[OAuth2Security](../../README.md#OAuth2Security), [OpenId](../../README.md#OpenId)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/json; charset=utf-8`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `accountsGetAccountsAccountIdOverdraftLimits()`

```php
accountsGetAccountsAccountIdOverdraftLimits($authorization, $x_fapi_interaction_id, $account_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent): \OpenAPI\Client\Model\ResponseAccountOverdraftLimits
```

Obtém os limites da conta identificada por accountId.

Método para obter os limites da conta de depósito à vista, poupança ou pagamento pré-paga identificada por accountId mantida pelo cliente na instituição transmissora. Para as instituições financeiras transmissoras que possuam contas sem limites associados devem retornar HTTP Status 200 com o objeto “data” vazio, sem nenhum atributo interno.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2Security
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');



$apiInstance = new OpenAPI\Client\Api\AccountsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$authorization = 'authorization_example'; // string | Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado
$x_fapi_interaction_id = d78fc4e5-37ca-4da3-adf2-9b082bf92280; // string | Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser “espelhado” pela transmissora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a transmissora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A receptora deve acatar o valor recebido da transmissora.
$account_id = 'account_id_example'; // string | Identificador da conta de depósito à vista, de poupança ou de pagamento pré-paga.
$x_fapi_auth_date = 'x_fapi_auth_date_example'; // string | Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC
$x_fapi_customer_ip_address = 'x_fapi_customer_ip_address_example'; // string | O endereço IP do usuário se estiver atualmente logado com o receptor.
$x_customer_user_agent = 'x_customer_user_agent_example'; // string | Indica o user-agent que o usuário utiliza.

try {
    $result = $apiInstance->accountsGetAccountsAccountIdOverdraftLimits($authorization, $x_fapi_interaction_id, $account_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountsApi->accountsGetAccountsAccountIdOverdraftLimits: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **authorization** | **string**| Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado | |
| **x_fapi_interaction_id** | **string**| Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser “espelhado” pela transmissora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a transmissora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A receptora deve acatar o valor recebido da transmissora. | |
| **account_id** | **string**| Identificador da conta de depósito à vista, de poupança ou de pagamento pré-paga. | |
| **x_fapi_auth_date** | **string**| Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC | [optional] |
| **x_fapi_customer_ip_address** | **string**| O endereço IP do usuário se estiver atualmente logado com o receptor. | [optional] |
| **x_customer_user_agent** | **string**| Indica o user-agent que o usuário utiliza. | [optional] |

### Return type

[**\OpenAPI\Client\Model\ResponseAccountOverdraftLimits**](../Model/ResponseAccountOverdraftLimits.md)

### Authorization

[OAuth2Security](../../README.md#OAuth2Security), [OpenId](../../README.md#OpenId)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/json; charset=utf-8`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `accountsGetAccountsAccountIdTransactions()`

```php
accountsGetAccountsAccountIdTransactions($authorization, $x_fapi_interaction_id, $account_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $page, $page_size, $from_booking_date, $to_booking_date, $credit_debit_indicator, $pagination_key): \OpenAPI\Client\Model\ResponseAccountTransactions
```

Obtém a lista de transações da conta identificada por accountId.

Método para obter a lista de transações da conta de depósito à vista, poupança ou pagamento pré-paga identificada por accountId mantida pelo cliente na instituição transmissora. É permitida uma consulta máxima que se estenda em 12 meses no passado mais 12 meses no futuro.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2Security
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');



$apiInstance = new OpenAPI\Client\Api\AccountsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$authorization = 'authorization_example'; // string | Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado
$x_fapi_interaction_id = d78fc4e5-37ca-4da3-adf2-9b082bf92280; // string | Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser “espelhado” pela transmissora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a transmissora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A receptora deve acatar o valor recebido da transmissora.
$account_id = 'account_id_example'; // string | Identificador da conta de depósito à vista, de poupança ou de pagamento pré-paga.
$x_fapi_auth_date = 'x_fapi_auth_date_example'; // string | Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC
$x_fapi_customer_ip_address = 'x_fapi_customer_ip_address_example'; // string | O endereço IP do usuário se estiver atualmente logado com o receptor.
$x_customer_user_agent = 'x_customer_user_agent_example'; // string | Indica o user-agent que o usuário utiliza.
$page = 1; // int | Número da página que está sendo requisitada (o valor da primeira página é 1).
$page_size = 25; // int | Quantidade total de registros por páginas.
$from_booking_date = Thu May 20 21:00:00 BRT 2021; // \DateTime | Data inicial de filtragem. [Restrição] Deve obrigatoriamente ser enviado caso o campo toBookingDate seja informado. Caso não seja informado, deve ser assumido o dia atual.
$to_booking_date = Thu May 20 21:00:00 BRT 2021; // \DateTime | Data final de filtragem. [Restrição] Deve obrigatoriamente ser enviado caso o campo fromBookingDate seja informado. Caso não seja informado, deve ser assumido o dia atual.
$credit_debit_indicator = new \OpenAPI\Client\Model\\OpenAPI\Client\Model\EnumCreditDebitIndicator(); // \OpenAPI\Client\Model\EnumCreditDebitIndicator | Indicador do tipo de lançamento
$pagination_key = 'pagination_key_example'; // string | Identificador de rechamada, utilizado para evitar a contagem de chamadas ao endpoint durante a paginação.

try {
    $result = $apiInstance->accountsGetAccountsAccountIdTransactions($authorization, $x_fapi_interaction_id, $account_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $page, $page_size, $from_booking_date, $to_booking_date, $credit_debit_indicator, $pagination_key);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountsApi->accountsGetAccountsAccountIdTransactions: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **authorization** | **string**| Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado | |
| **x_fapi_interaction_id** | **string**| Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser “espelhado” pela transmissora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a transmissora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A receptora deve acatar o valor recebido da transmissora. | |
| **account_id** | **string**| Identificador da conta de depósito à vista, de poupança ou de pagamento pré-paga. | |
| **x_fapi_auth_date** | **string**| Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC | [optional] |
| **x_fapi_customer_ip_address** | **string**| O endereço IP do usuário se estiver atualmente logado com o receptor. | [optional] |
| **x_customer_user_agent** | **string**| Indica o user-agent que o usuário utiliza. | [optional] |
| **page** | **int**| Número da página que está sendo requisitada (o valor da primeira página é 1). | [optional] [default to 1] |
| **page_size** | **int**| Quantidade total de registros por páginas. | [optional] [default to 25] |
| **from_booking_date** | **\DateTime**| Data inicial de filtragem. [Restrição] Deve obrigatoriamente ser enviado caso o campo toBookingDate seja informado. Caso não seja informado, deve ser assumido o dia atual. | [optional] |
| **to_booking_date** | **\DateTime**| Data final de filtragem. [Restrição] Deve obrigatoriamente ser enviado caso o campo fromBookingDate seja informado. Caso não seja informado, deve ser assumido o dia atual. | [optional] |
| **credit_debit_indicator** | [**\OpenAPI\Client\Model\EnumCreditDebitIndicator**](../Model/.md)| Indicador do tipo de lançamento | [optional] |
| **pagination_key** | **string**| Identificador de rechamada, utilizado para evitar a contagem de chamadas ao endpoint durante a paginação. | [optional] |

### Return type

[**\OpenAPI\Client\Model\ResponseAccountTransactions**](../Model/ResponseAccountTransactions.md)

### Authorization

[OAuth2Security](../../README.md#OAuth2Security), [OpenId](../../README.md#OpenId)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/json; charset=utf-8`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `accountsGetAccountsAccountIdTransactionsCurrent()`

```php
accountsGetAccountsAccountIdTransactionsCurrent($authorization, $x_fapi_interaction_id, $account_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $page, $page_size, $from_booking_date, $to_booking_date, $credit_debit_indicator, $pagination_key): \OpenAPI\Client\Model\ResponseAccountTransactions
```

Obtém a lista de transações recentes (últimos 7 dias) da conta identificada por accountId.

Método para obter a lista de transações da conta de depósito à vista, poupança ou pagamento pré-paga identificada por accountId mantida pelo cliente na instituição transmissora. É permitida uma consulta máxima que se estenda em 7 dias no passado mais 12 meses no futuro.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2Security
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');



$apiInstance = new OpenAPI\Client\Api\AccountsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$authorization = 'authorization_example'; // string | Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado
$x_fapi_interaction_id = d78fc4e5-37ca-4da3-adf2-9b082bf92280; // string | Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser “espelhado” pela transmissora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a transmissora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A receptora deve acatar o valor recebido da transmissora.
$account_id = 'account_id_example'; // string | Identificador da conta de depósito à vista, de poupança ou de pagamento pré-paga.
$x_fapi_auth_date = 'x_fapi_auth_date_example'; // string | Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC
$x_fapi_customer_ip_address = 'x_fapi_customer_ip_address_example'; // string | O endereço IP do usuário se estiver atualmente logado com o receptor.
$x_customer_user_agent = 'x_customer_user_agent_example'; // string | Indica o user-agent que o usuário utiliza.
$page = 1; // int | Número da página que está sendo requisitada (o valor da primeira página é 1).
$page_size = 25; // int | Quantidade total de registros por páginas.
$from_booking_date = Thu May 20 21:00:00 BRT 2021; // \DateTime | Data inicial de filtragem. O período máximo utilizado no filtro é de 7 dias inclusive (D-6).    [Restrição] Deve obrigatoriamente ser enviado caso o campo toBookingDate seja informado.  Caso não seja informado, deve ser assumido o dia atual.
$to_booking_date = Thu May 20 21:00:00 BRT 2021; // \DateTime | Data final de filtragem. O período máximo utilizado no filtro é de 7 dias inclusive (D-6).    [Restrição] Deve obrigatoriamente ser enviado caso o campo fromBookingDate seja informado.  Caso não seja informado, deve ser assumido o dia atual.
$credit_debit_indicator = new \OpenAPI\Client\Model\\OpenAPI\Client\Model\EnumCreditDebitIndicator(); // \OpenAPI\Client\Model\EnumCreditDebitIndicator | Indicador do tipo de lançamento
$pagination_key = 'pagination_key_example'; // string | Identificador de rechamada, utilizado para evitar a contagem de chamadas ao endpoint durante a paginação.

try {
    $result = $apiInstance->accountsGetAccountsAccountIdTransactionsCurrent($authorization, $x_fapi_interaction_id, $account_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $page, $page_size, $from_booking_date, $to_booking_date, $credit_debit_indicator, $pagination_key);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountsApi->accountsGetAccountsAccountIdTransactionsCurrent: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **authorization** | **string**| Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado | |
| **x_fapi_interaction_id** | **string**| Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser “espelhado” pela transmissora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a transmissora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A receptora deve acatar o valor recebido da transmissora. | |
| **account_id** | **string**| Identificador da conta de depósito à vista, de poupança ou de pagamento pré-paga. | |
| **x_fapi_auth_date** | **string**| Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC | [optional] |
| **x_fapi_customer_ip_address** | **string**| O endereço IP do usuário se estiver atualmente logado com o receptor. | [optional] |
| **x_customer_user_agent** | **string**| Indica o user-agent que o usuário utiliza. | [optional] |
| **page** | **int**| Número da página que está sendo requisitada (o valor da primeira página é 1). | [optional] [default to 1] |
| **page_size** | **int**| Quantidade total de registros por páginas. | [optional] [default to 25] |
| **from_booking_date** | **\DateTime**| Data inicial de filtragem. O período máximo utilizado no filtro é de 7 dias inclusive (D-6).    [Restrição] Deve obrigatoriamente ser enviado caso o campo toBookingDate seja informado.  Caso não seja informado, deve ser assumido o dia atual. | [optional] |
| **to_booking_date** | **\DateTime**| Data final de filtragem. O período máximo utilizado no filtro é de 7 dias inclusive (D-6).    [Restrição] Deve obrigatoriamente ser enviado caso o campo fromBookingDate seja informado.  Caso não seja informado, deve ser assumido o dia atual. | [optional] |
| **credit_debit_indicator** | [**\OpenAPI\Client\Model\EnumCreditDebitIndicator**](../Model/.md)| Indicador do tipo de lançamento | [optional] |
| **pagination_key** | **string**| Identificador de rechamada, utilizado para evitar a contagem de chamadas ao endpoint durante a paginação. | [optional] |

### Return type

[**\OpenAPI\Client\Model\ResponseAccountTransactions**](../Model/ResponseAccountTransactions.md)

### Authorization

[OAuth2Security](../../README.md#OAuth2Security), [OpenId](../../README.md#OpenId)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/json; charset=utf-8`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
