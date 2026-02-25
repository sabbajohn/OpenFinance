# OpenAPI\Client\CreditCardApi



All URIs are relative to https://api.banco.com.br/open-banking/credit-cards-accounts/v2, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**creditCardsGetAccounts()**](CreditCardApi.md#creditCardsGetAccounts) | **GET** /accounts | Conjunto de informações das Contas de pagamento pós paga |
| [**creditCardsGetAccountsCreditCardAccountId()**](CreditCardApi.md#creditCardsGetAccountsCreditCardAccountId) | **GET** /accounts/{creditCardAccountId} | Obtém os dados de identificação da conta identificada por creditCardAccountId. |
| [**creditCardsGetAccountsCreditCardAccountIdBills()**](CreditCardApi.md#creditCardsGetAccountsCreditCardAccountIdBills) | **GET** /accounts/{creditCardAccountId}/bills | Obtém a lista de faturas da conta identificada por creditCardAccountId. |
| [**creditCardsGetAccountsCreditCardAccountIdBillsBillIdTransactions()**](CreditCardApi.md#creditCardsGetAccountsCreditCardAccountIdBillsBillIdTransactions) | **GET** /accounts/{creditCardAccountId}/bills/{billId}/transactions | Obtém a lista de transações da conta identificada por creditCardAccountId e billId. |
| [**creditCardsGetAccountsCreditCardAccountIdLimits()**](CreditCardApi.md#creditCardsGetAccountsCreditCardAccountIdLimits) | **GET** /accounts/{creditCardAccountId}/limits | Obtém os limites da conta identificada por creditCardAccountId. |
| [**creditCardsGetAccountsCreditCardAccountIdTransactions()**](CreditCardApi.md#creditCardsGetAccountsCreditCardAccountIdTransactions) | **GET** /accounts/{creditCardAccountId}/transactions | Obtém a lista de transações da conta identificada por creditCardAccountId. |
| [**creditCardsGetAccountsCreditCardAccountIdTransactionsCurrent()**](CreditCardApi.md#creditCardsGetAccountsCreditCardAccountIdTransactionsCurrent) | **GET** /accounts/{creditCardAccountId}/transactions-current | Obtém a lista de transações recentes (últimos 7 dias) da conta identificada por creditCardAccountId. |


## `creditCardsGetAccounts()`

```php
creditCardsGetAccounts($authorization, $x_fapi_interaction_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $page, $page_size, $pagination_key): \OpenAPI\Client\Model\ResponseCreditCardAccountsList
```

Conjunto de informações das Contas de pagamento pós paga

Método para obter a lista de contas de pagamento pós-paga mantidas pelo cliente na instituição transmissora e para as quais ele tenha fornecido consentimento

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2Security
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');



$apiInstance = new OpenAPI\Client\Api\CreditCardApi(
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
$pagination_key = 'pagination_key_example'; // string | Identificador de rechamada, utilizado para evitar a contagem de chamadas ao endpoint durante a paginação.

try {
    $result = $apiInstance->creditCardsGetAccounts($authorization, $x_fapi_interaction_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $page, $page_size, $pagination_key);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling CreditCardApi->creditCardsGetAccounts: ', $e->getMessage(), PHP_EOL;
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
| **pagination_key** | **string**| Identificador de rechamada, utilizado para evitar a contagem de chamadas ao endpoint durante a paginação. | [optional] |

### Return type

[**\OpenAPI\Client\Model\ResponseCreditCardAccountsList**](../Model/ResponseCreditCardAccountsList.md)

### Authorization

[OAuth2Security](../../README.md#OAuth2Security), [OpenId](../../README.md#OpenId)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/json; charset=utf-8`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `creditCardsGetAccountsCreditCardAccountId()`

```php
creditCardsGetAccountsCreditCardAccountId($authorization, $x_fapi_interaction_id, $credit_card_account_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent): \OpenAPI\Client\Model\ResponseCreditCardAccountsIdentification
```

Obtém os dados de identificação da conta identificada por creditCardAccountId.

Método para obter os dados de identificação da conta de pagamento pós-paga identificada por creditCardAccountId mantida pelo cliente na instituição transmissora.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2Security
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');



$apiInstance = new OpenAPI\Client\Api\CreditCardApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$authorization = 'authorization_example'; // string | Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado
$x_fapi_interaction_id = d78fc4e5-37ca-4da3-adf2-9b082bf92280; // string | Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser “espelhado” pela transmissora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a transmissora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A receptora deve acatar o valor recebido da transmissora.
$credit_card_account_id = 'credit_card_account_id_example'; // string | Identifica de forma única a conta pagamento pós-paga do cliente, mantendo as regras de imutabilidade detro da instituição transmissora
$x_fapi_auth_date = 'x_fapi_auth_date_example'; // string | Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC
$x_fapi_customer_ip_address = 'x_fapi_customer_ip_address_example'; // string | O endereço IP do usuário se estiver atualmente logado com o receptor.
$x_customer_user_agent = 'x_customer_user_agent_example'; // string | Indica o user-agent que o usuário utiliza.

try {
    $result = $apiInstance->creditCardsGetAccountsCreditCardAccountId($authorization, $x_fapi_interaction_id, $credit_card_account_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling CreditCardApi->creditCardsGetAccountsCreditCardAccountId: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **authorization** | **string**| Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado | |
| **x_fapi_interaction_id** | **string**| Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser “espelhado” pela transmissora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a transmissora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A receptora deve acatar o valor recebido da transmissora. | |
| **credit_card_account_id** | **string**| Identifica de forma única a conta pagamento pós-paga do cliente, mantendo as regras de imutabilidade detro da instituição transmissora | |
| **x_fapi_auth_date** | **string**| Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC | [optional] |
| **x_fapi_customer_ip_address** | **string**| O endereço IP do usuário se estiver atualmente logado com o receptor. | [optional] |
| **x_customer_user_agent** | **string**| Indica o user-agent que o usuário utiliza. | [optional] |

### Return type

[**\OpenAPI\Client\Model\ResponseCreditCardAccountsIdentification**](../Model/ResponseCreditCardAccountsIdentification.md)

### Authorization

[OAuth2Security](../../README.md#OAuth2Security), [OpenId](../../README.md#OpenId)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/json; charset=utf-8`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `creditCardsGetAccountsCreditCardAccountIdBills()`

```php
creditCardsGetAccountsCreditCardAccountIdBills($authorization, $x_fapi_interaction_id, $credit_card_account_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $page, $page_size, $pagination_key, $from_due_date, $to_due_date): \OpenAPI\Client\Model\ResponseCreditCardAccountsBills
```

Obtém a lista de faturas da conta identificada por creditCardAccountId.

Método para obter a lista de faturas da conta de pagamento pós-paga identificada por creditCardAccountId mantida pelo cliente na instituição transmissora.\\ Só deve ser informada uma fatura já fechada.\\ Qualquer pagamento deve ser contado para a última fatura fechada.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2Security
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');



$apiInstance = new OpenAPI\Client\Api\CreditCardApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$authorization = 'authorization_example'; // string | Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado
$x_fapi_interaction_id = d78fc4e5-37ca-4da3-adf2-9b082bf92280; // string | Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser “espelhado” pela transmissora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a transmissora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A receptora deve acatar o valor recebido da transmissora.
$credit_card_account_id = 'credit_card_account_id_example'; // string | Identifica de forma única a conta pagamento pós-paga do cliente, mantendo as regras de imutabilidade detro da instituição transmissora
$x_fapi_auth_date = 'x_fapi_auth_date_example'; // string | Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC
$x_fapi_customer_ip_address = 'x_fapi_customer_ip_address_example'; // string | O endereço IP do usuário se estiver atualmente logado com o receptor.
$x_customer_user_agent = 'x_customer_user_agent_example'; // string | Indica o user-agent que o usuário utiliza.
$page = 1; // int | Número da página que está sendo requisitada (o valor da primeira página é 1).
$page_size = 25; // int | Quantidade total de registros por páginas.
$pagination_key = 'pagination_key_example'; // string | Identificador de rechamada, utilizado para evitar a contagem de chamadas ao endpoint durante a paginação.
$from_due_date = Thu May 20 21:00:00 BRT 2021; // \DateTime | Data inicial de filtragem.
$to_due_date = Thu May 20 21:00:00 BRT 2021; // \DateTime | Data final de filtragem.

try {
    $result = $apiInstance->creditCardsGetAccountsCreditCardAccountIdBills($authorization, $x_fapi_interaction_id, $credit_card_account_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $page, $page_size, $pagination_key, $from_due_date, $to_due_date);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling CreditCardApi->creditCardsGetAccountsCreditCardAccountIdBills: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **authorization** | **string**| Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado | |
| **x_fapi_interaction_id** | **string**| Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser “espelhado” pela transmissora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a transmissora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A receptora deve acatar o valor recebido da transmissora. | |
| **credit_card_account_id** | **string**| Identifica de forma única a conta pagamento pós-paga do cliente, mantendo as regras de imutabilidade detro da instituição transmissora | |
| **x_fapi_auth_date** | **string**| Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC | [optional] |
| **x_fapi_customer_ip_address** | **string**| O endereço IP do usuário se estiver atualmente logado com o receptor. | [optional] |
| **x_customer_user_agent** | **string**| Indica o user-agent que o usuário utiliza. | [optional] |
| **page** | **int**| Número da página que está sendo requisitada (o valor da primeira página é 1). | [optional] [default to 1] |
| **page_size** | **int**| Quantidade total de registros por páginas. | [optional] [default to 25] |
| **pagination_key** | **string**| Identificador de rechamada, utilizado para evitar a contagem de chamadas ao endpoint durante a paginação. | [optional] |
| **from_due_date** | **\DateTime**| Data inicial de filtragem. | [optional] |
| **to_due_date** | **\DateTime**| Data final de filtragem. | [optional] |

### Return type

[**\OpenAPI\Client\Model\ResponseCreditCardAccountsBills**](../Model/ResponseCreditCardAccountsBills.md)

### Authorization

[OAuth2Security](../../README.md#OAuth2Security), [OpenId](../../README.md#OpenId)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/json; charset=utf-8`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `creditCardsGetAccountsCreditCardAccountIdBillsBillIdTransactions()`

```php
creditCardsGetAccountsCreditCardAccountIdBillsBillIdTransactions($authorization, $x_fapi_interaction_id, $credit_card_account_id, $bill_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $page, $page_size, $pagination_key, $from_transaction_date, $to_transaction_date, $transaction_type, $payee_mcc): \OpenAPI\Client\Model\CreditCardsGetAccountsCreditCardAccountIdBillsBillIdTransactions200Response
```

Obtém a lista de transações da conta identificada por creditCardAccountId e billId.

Método para obter a lista de transações da conta de pagamento pós-paga identificada por creditCardAccountId e billId mantida pelo cliente na instituição transmissora.  A lista a retornar se refere a transações após conciliado.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2Security
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');



$apiInstance = new OpenAPI\Client\Api\CreditCardApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$authorization = 'authorization_example'; // string | Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado
$x_fapi_interaction_id = d78fc4e5-37ca-4da3-adf2-9b082bf92280; // string | Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser “espelhado” pela transmissora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a transmissora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A receptora deve acatar o valor recebido da transmissora.
$credit_card_account_id = 'credit_card_account_id_example'; // string | Identifica de forma única a conta pagamento pós-paga do cliente, mantendo as regras de imutabilidade detro da instituição transmissora
$bill_id = 'bill_id_example'; // string | Identificador da fatura.
$x_fapi_auth_date = 'x_fapi_auth_date_example'; // string | Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC
$x_fapi_customer_ip_address = 'x_fapi_customer_ip_address_example'; // string | O endereço IP do usuário se estiver atualmente logado com o receptor.
$x_customer_user_agent = 'x_customer_user_agent_example'; // string | Indica o user-agent que o usuário utiliza.
$page = 1; // int | Número da página que está sendo requisitada (o valor da primeira página é 1).
$page_size = 25; // int | Quantidade total de registros por páginas.
$pagination_key = 'pagination_key_example'; // string | Identificador de rechamada, utilizado para evitar a contagem de chamadas ao endpoint durante a paginação.
$from_transaction_date = Thu May 20 21:00:00 BRT 2021; // \DateTime | Data inicial de filtragem.  [Restrição] Deve obrigatoriamente ser enviado caso o campo toTransactionDate seja informado. Caso não seja informado, deve ser assumido o dia atual.
$to_transaction_date = Thu May 20 21:00:00 BRT 2021; // \DateTime | Data final de filtragem.  [Restrição] Deve obrigatoriamente ser enviado caso o campo fromTransactionDate seja informado. Caso não seja informado, deve ser assumido o dia atual.
$transaction_type = new \OpenAPI\Client\Model\\OpenAPI\Client\Model\EnumCreditCardTransactionType(); // \OpenAPI\Client\Model\EnumCreditCardTransactionType | Traz os tipos de Transação
$payee_mcc = 8299; // float | MCC é o Merchant Category Code, ou o código da categoria do estabelecimento comercial. Os MCCs são agrupados segundo suas similaridades

try {
    $result = $apiInstance->creditCardsGetAccountsCreditCardAccountIdBillsBillIdTransactions($authorization, $x_fapi_interaction_id, $credit_card_account_id, $bill_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $page, $page_size, $pagination_key, $from_transaction_date, $to_transaction_date, $transaction_type, $payee_mcc);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling CreditCardApi->creditCardsGetAccountsCreditCardAccountIdBillsBillIdTransactions: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **authorization** | **string**| Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado | |
| **x_fapi_interaction_id** | **string**| Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser “espelhado” pela transmissora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a transmissora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A receptora deve acatar o valor recebido da transmissora. | |
| **credit_card_account_id** | **string**| Identifica de forma única a conta pagamento pós-paga do cliente, mantendo as regras de imutabilidade detro da instituição transmissora | |
| **bill_id** | **string**| Identificador da fatura. | |
| **x_fapi_auth_date** | **string**| Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC | [optional] |
| **x_fapi_customer_ip_address** | **string**| O endereço IP do usuário se estiver atualmente logado com o receptor. | [optional] |
| **x_customer_user_agent** | **string**| Indica o user-agent que o usuário utiliza. | [optional] |
| **page** | **int**| Número da página que está sendo requisitada (o valor da primeira página é 1). | [optional] [default to 1] |
| **page_size** | **int**| Quantidade total de registros por páginas. | [optional] [default to 25] |
| **pagination_key** | **string**| Identificador de rechamada, utilizado para evitar a contagem de chamadas ao endpoint durante a paginação. | [optional] |
| **from_transaction_date** | **\DateTime**| Data inicial de filtragem.  [Restrição] Deve obrigatoriamente ser enviado caso o campo toTransactionDate seja informado. Caso não seja informado, deve ser assumido o dia atual. | [optional] |
| **to_transaction_date** | **\DateTime**| Data final de filtragem.  [Restrição] Deve obrigatoriamente ser enviado caso o campo fromTransactionDate seja informado. Caso não seja informado, deve ser assumido o dia atual. | [optional] |
| **transaction_type** | [**\OpenAPI\Client\Model\EnumCreditCardTransactionType**](../Model/.md)| Traz os tipos de Transação | [optional] |
| **payee_mcc** | **float**| MCC é o Merchant Category Code, ou o código da categoria do estabelecimento comercial. Os MCCs são agrupados segundo suas similaridades | [optional] |

### Return type

[**\OpenAPI\Client\Model\CreditCardsGetAccountsCreditCardAccountIdBillsBillIdTransactions200Response**](../Model/CreditCardsGetAccountsCreditCardAccountIdBillsBillIdTransactions200Response.md)

### Authorization

[OAuth2Security](../../README.md#OAuth2Security), [OpenId](../../README.md#OpenId)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/json; charset=utf-8`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `creditCardsGetAccountsCreditCardAccountIdLimits()`

```php
creditCardsGetAccountsCreditCardAccountIdLimits($authorization, $x_fapi_interaction_id, $credit_card_account_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent): \OpenAPI\Client\Model\ResponseCreditCardAccountsLimits
```

Obtém os limites da conta identificada por creditCardAccountId.

Método para obter os limites da conta de pagamento pós-paga identificada por creditCardAccountId mantida pelo cliente na instituição transmissora.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2Security
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');



$apiInstance = new OpenAPI\Client\Api\CreditCardApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$authorization = 'authorization_example'; // string | Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado
$x_fapi_interaction_id = d78fc4e5-37ca-4da3-adf2-9b082bf92280; // string | Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser “espelhado” pela transmissora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a transmissora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A receptora deve acatar o valor recebido da transmissora.
$credit_card_account_id = 'credit_card_account_id_example'; // string | Identifica de forma única a conta pagamento pós-paga do cliente, mantendo as regras de imutabilidade detro da instituição transmissora
$x_fapi_auth_date = 'x_fapi_auth_date_example'; // string | Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC
$x_fapi_customer_ip_address = 'x_fapi_customer_ip_address_example'; // string | O endereço IP do usuário se estiver atualmente logado com o receptor.
$x_customer_user_agent = 'x_customer_user_agent_example'; // string | Indica o user-agent que o usuário utiliza.

try {
    $result = $apiInstance->creditCardsGetAccountsCreditCardAccountIdLimits($authorization, $x_fapi_interaction_id, $credit_card_account_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling CreditCardApi->creditCardsGetAccountsCreditCardAccountIdLimits: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **authorization** | **string**| Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado | |
| **x_fapi_interaction_id** | **string**| Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser “espelhado” pela transmissora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a transmissora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A receptora deve acatar o valor recebido da transmissora. | |
| **credit_card_account_id** | **string**| Identifica de forma única a conta pagamento pós-paga do cliente, mantendo as regras de imutabilidade detro da instituição transmissora | |
| **x_fapi_auth_date** | **string**| Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC | [optional] |
| **x_fapi_customer_ip_address** | **string**| O endereço IP do usuário se estiver atualmente logado com o receptor. | [optional] |
| **x_customer_user_agent** | **string**| Indica o user-agent que o usuário utiliza. | [optional] |

### Return type

[**\OpenAPI\Client\Model\ResponseCreditCardAccountsLimits**](../Model/ResponseCreditCardAccountsLimits.md)

### Authorization

[OAuth2Security](../../README.md#OAuth2Security), [OpenId](../../README.md#OpenId)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/json; charset=utf-8`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `creditCardsGetAccountsCreditCardAccountIdTransactions()`

```php
creditCardsGetAccountsCreditCardAccountIdTransactions($authorization, $x_fapi_interaction_id, $credit_card_account_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $page, $page_size, $pagination_key, $from_transaction_date, $to_transaction_date, $transaction_type, $payee_mcc): \OpenAPI\Client\Model\ResponseCreditCardAccountsTransactions
```

Obtém a lista de transações da conta identificada por creditCardAccountId.

Método para obter a lista de transações histórica (últimos 12 meses, ou recorte desse período) da conta de pagamento pós-paga identificada por creditCardAccountId mantida pelo cliente na instituição transmissora.  A lista a retornar se refere a transações após conciliado.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2Security
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');



$apiInstance = new OpenAPI\Client\Api\CreditCardApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$authorization = 'authorization_example'; // string | Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado
$x_fapi_interaction_id = d78fc4e5-37ca-4da3-adf2-9b082bf92280; // string | Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser “espelhado” pela transmissora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a transmissora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A receptora deve acatar o valor recebido da transmissora.
$credit_card_account_id = 'credit_card_account_id_example'; // string | Identifica de forma única a conta pagamento pós-paga do cliente, mantendo as regras de imutabilidade detro da instituição transmissora
$x_fapi_auth_date = 'x_fapi_auth_date_example'; // string | Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC
$x_fapi_customer_ip_address = 'x_fapi_customer_ip_address_example'; // string | O endereço IP do usuário se estiver atualmente logado com o receptor.
$x_customer_user_agent = 'x_customer_user_agent_example'; // string | Indica o user-agent que o usuário utiliza.
$page = 1; // int | Número da página que está sendo requisitada (o valor da primeira página é 1).
$page_size = 25; // int | Quantidade total de registros por páginas.
$pagination_key = 'pagination_key_example'; // string | Identificador de rechamada, utilizado para evitar a contagem de chamadas ao endpoint durante a paginação.
$from_transaction_date = Thu May 20 21:00:00 BRT 2021; // \DateTime | Data inicial de filtragem.  [Restrição] Deve obrigatoriamente ser enviado caso o campo toTransactionDate seja informado. Caso não seja informado, deve ser assumido o dia atual.
$to_transaction_date = Thu May 20 21:00:00 BRT 2021; // \DateTime | Data final de filtragem.  [Restrição] Deve obrigatoriamente ser enviado caso o campo fromTransactionDate seja informado. Caso não seja informado, deve ser assumido o dia atual.
$transaction_type = new \OpenAPI\Client\Model\\OpenAPI\Client\Model\EnumCreditCardTransactionType(); // \OpenAPI\Client\Model\EnumCreditCardTransactionType | Traz os tipos de Transação
$payee_mcc = 8299; // float | MCC é o Merchant Category Code, ou o código da categoria do estabelecimento comercial. Os MCCs são agrupados segundo suas similaridades

try {
    $result = $apiInstance->creditCardsGetAccountsCreditCardAccountIdTransactions($authorization, $x_fapi_interaction_id, $credit_card_account_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $page, $page_size, $pagination_key, $from_transaction_date, $to_transaction_date, $transaction_type, $payee_mcc);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling CreditCardApi->creditCardsGetAccountsCreditCardAccountIdTransactions: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **authorization** | **string**| Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado | |
| **x_fapi_interaction_id** | **string**| Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser “espelhado” pela transmissora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a transmissora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A receptora deve acatar o valor recebido da transmissora. | |
| **credit_card_account_id** | **string**| Identifica de forma única a conta pagamento pós-paga do cliente, mantendo as regras de imutabilidade detro da instituição transmissora | |
| **x_fapi_auth_date** | **string**| Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC | [optional] |
| **x_fapi_customer_ip_address** | **string**| O endereço IP do usuário se estiver atualmente logado com o receptor. | [optional] |
| **x_customer_user_agent** | **string**| Indica o user-agent que o usuário utiliza. | [optional] |
| **page** | **int**| Número da página que está sendo requisitada (o valor da primeira página é 1). | [optional] [default to 1] |
| **page_size** | **int**| Quantidade total de registros por páginas. | [optional] [default to 25] |
| **pagination_key** | **string**| Identificador de rechamada, utilizado para evitar a contagem de chamadas ao endpoint durante a paginação. | [optional] |
| **from_transaction_date** | **\DateTime**| Data inicial de filtragem.  [Restrição] Deve obrigatoriamente ser enviado caso o campo toTransactionDate seja informado. Caso não seja informado, deve ser assumido o dia atual. | [optional] |
| **to_transaction_date** | **\DateTime**| Data final de filtragem.  [Restrição] Deve obrigatoriamente ser enviado caso o campo fromTransactionDate seja informado. Caso não seja informado, deve ser assumido o dia atual. | [optional] |
| **transaction_type** | [**\OpenAPI\Client\Model\EnumCreditCardTransactionType**](../Model/.md)| Traz os tipos de Transação | [optional] |
| **payee_mcc** | **float**| MCC é o Merchant Category Code, ou o código da categoria do estabelecimento comercial. Os MCCs são agrupados segundo suas similaridades | [optional] |

### Return type

[**\OpenAPI\Client\Model\ResponseCreditCardAccountsTransactions**](../Model/ResponseCreditCardAccountsTransactions.md)

### Authorization

[OAuth2Security](../../README.md#OAuth2Security), [OpenId](../../README.md#OpenId)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/json; charset=utf-8`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `creditCardsGetAccountsCreditCardAccountIdTransactionsCurrent()`

```php
creditCardsGetAccountsCreditCardAccountIdTransactionsCurrent($authorization, $x_fapi_interaction_id, $credit_card_account_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $page, $page_size, $pagination_key, $from_transaction_date, $to_transaction_date, $transaction_type, $payee_mcc): \OpenAPI\Client\Model\ResponseCreditCardAccountsTransactions
```

Obtém a lista de transações recentes (últimos 7 dias) da conta identificada por creditCardAccountId.

Método para obter a lista de transações recentes (últimos 7 dias) da conta de pagamento pós-paga identificada por creditCardAccountId mantida pelo cliente na instituição transmissora.  A lista a retornar se refere a transações após conciliado.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2Security
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');



$apiInstance = new OpenAPI\Client\Api\CreditCardApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$authorization = 'authorization_example'; // string | Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado
$x_fapi_interaction_id = d78fc4e5-37ca-4da3-adf2-9b082bf92280; // string | Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser “espelhado” pela transmissora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a transmissora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A receptora deve acatar o valor recebido da transmissora.
$credit_card_account_id = 'credit_card_account_id_example'; // string | Identifica de forma única a conta pagamento pós-paga do cliente, mantendo as regras de imutabilidade detro da instituição transmissora
$x_fapi_auth_date = 'x_fapi_auth_date_example'; // string | Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC
$x_fapi_customer_ip_address = 'x_fapi_customer_ip_address_example'; // string | O endereço IP do usuário se estiver atualmente logado com o receptor.
$x_customer_user_agent = 'x_customer_user_agent_example'; // string | Indica o user-agent que o usuário utiliza.
$page = 1; // int | Número da página que está sendo requisitada (o valor da primeira página é 1).
$page_size = 25; // int | Quantidade total de registros por páginas.
$pagination_key = 'pagination_key_example'; // string | Identificador de rechamada, utilizado para evitar a contagem de chamadas ao endpoint durante a paginação.
$from_transaction_date = Thu May 20 21:00:00 BRT 2021; // \DateTime | Data inicial de filtragem. O período máximo utilizado no filtro é de 7 dias inclusive (D-6).  [Restrição] Deve obrigatoriamente ser enviado caso o campo toTransactionDate seja informado. Caso não seja informado, deve ser assumido o dia atual.
$to_transaction_date = Thu May 20 21:00:00 BRT 2021; // \DateTime | Data final de filtragem. O período máximo utilizado no filtro é de 7 dias inclusive (D-6).  [Restrição] Deve obrigatoriamente ser enviado caso o campo fromTransactionDate seja informado. Caso não seja informado, deve ser assumido o dia atual.
$transaction_type = new \OpenAPI\Client\Model\\OpenAPI\Client\Model\EnumCreditCardTransactionType(); // \OpenAPI\Client\Model\EnumCreditCardTransactionType | Traz os tipos de Transação
$payee_mcc = 8299; // float | MCC é o Merchant Category Code, ou o código da categoria do estabelecimento comercial. Os MCCs são agrupados segundo suas similaridades

try {
    $result = $apiInstance->creditCardsGetAccountsCreditCardAccountIdTransactionsCurrent($authorization, $x_fapi_interaction_id, $credit_card_account_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $page, $page_size, $pagination_key, $from_transaction_date, $to_transaction_date, $transaction_type, $payee_mcc);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling CreditCardApi->creditCardsGetAccountsCreditCardAccountIdTransactionsCurrent: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **authorization** | **string**| Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado | |
| **x_fapi_interaction_id** | **string**| Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser “espelhado” pela transmissora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a transmissora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A receptora deve acatar o valor recebido da transmissora. | |
| **credit_card_account_id** | **string**| Identifica de forma única a conta pagamento pós-paga do cliente, mantendo as regras de imutabilidade detro da instituição transmissora | |
| **x_fapi_auth_date** | **string**| Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC | [optional] |
| **x_fapi_customer_ip_address** | **string**| O endereço IP do usuário se estiver atualmente logado com o receptor. | [optional] |
| **x_customer_user_agent** | **string**| Indica o user-agent que o usuário utiliza. | [optional] |
| **page** | **int**| Número da página que está sendo requisitada (o valor da primeira página é 1). | [optional] [default to 1] |
| **page_size** | **int**| Quantidade total de registros por páginas. | [optional] [default to 25] |
| **pagination_key** | **string**| Identificador de rechamada, utilizado para evitar a contagem de chamadas ao endpoint durante a paginação. | [optional] |
| **from_transaction_date** | **\DateTime**| Data inicial de filtragem. O período máximo utilizado no filtro é de 7 dias inclusive (D-6).  [Restrição] Deve obrigatoriamente ser enviado caso o campo toTransactionDate seja informado. Caso não seja informado, deve ser assumido o dia atual. | [optional] |
| **to_transaction_date** | **\DateTime**| Data final de filtragem. O período máximo utilizado no filtro é de 7 dias inclusive (D-6).  [Restrição] Deve obrigatoriamente ser enviado caso o campo fromTransactionDate seja informado. Caso não seja informado, deve ser assumido o dia atual. | [optional] |
| **transaction_type** | [**\OpenAPI\Client\Model\EnumCreditCardTransactionType**](../Model/.md)| Traz os tipos de Transação | [optional] |
| **payee_mcc** | **float**| MCC é o Merchant Category Code, ou o código da categoria do estabelecimento comercial. Os MCCs são agrupados segundo suas similaridades | [optional] |

### Return type

[**\OpenAPI\Client\Model\ResponseCreditCardAccountsTransactions**](../Model/ResponseCreditCardAccountsTransactions.md)

### Authorization

[OAuth2Security](../../README.md#OAuth2Security), [OpenId](../../README.md#OpenId)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/json; charset=utf-8`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
