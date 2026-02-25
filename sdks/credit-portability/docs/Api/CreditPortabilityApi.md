# OpenAPI\Client\CreditPortabilityApi



All URIs are relative to https://api.banco.com.br/open-banking/credit-portability/v1, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**creditPortabilityGetPortabilitiesByPortabilityId()**](CreditPortabilityApi.md#creditPortabilityGetPortabilitiesByPortabilityId) | **GET** /portabilities/{portabilityId} | Consulta portabilidade de crédito através da propriedade portabilityId. |
| [**creditPortabilityPatchPortabilitiesPortabilityIdCancel()**](CreditPortabilityApi.md#creditPortabilityPatchPortabilitiesPortabilityIdCancel) | **PATCH** /portabilities/{portabilityId}/cancel | Comunica a Instituição Credora a respeito do cancelamento da portabilidade de crédito. |
| [**creditPortabilityPostPortabilities()**](CreditPortabilityApi.md#creditPortabilityPostPortabilities) | **POST** /portabilities | Realiza pedido de portabilidade de crédito para um determinado contrato junto a instituição credora |


## `creditPortabilityGetPortabilitiesByPortabilityId()`

```php
creditPortabilityGetPortabilitiesByPortabilityId($portability_id, $authorization, $x_fapi_interaction_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent): \OpenAPI\Client\Model\ResponsePortabilitiesByPortabilityId
```

Consulta portabilidade de crédito através da propriedade portabilityId.

Endpoint responsável por consultar pedidos de portabilidade de crédito.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2ClientCredentials
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\CreditPortabilityApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$portability_id = 'portability_id_example'; // string | Identificador do pedido de portabilidade de crédito.
$authorization = 'authorization_example'; // string | Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado.
$x_fapi_interaction_id = d78fc4e5-37ca-4da3-adf2-9b082bf92280; // string | Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela IF Proponente (client) e o seu valor deve ser “espelhado” pela IF Credora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a IF Credora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A IF Proponente deve acatar o valor recebido da IF Credora.
$x_fapi_auth_date = 'x_fapi_auth_date_example'; // string | Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC
$x_fapi_customer_ip_address = 'x_fapi_customer_ip_address_example'; // string | O endereço IP do usuário se estiver atualmente logado com o receptor.
$x_customer_user_agent = 'x_customer_user_agent_example'; // string | Indica o user-agent que o usuário utiliza.

try {
    $result = $apiInstance->creditPortabilityGetPortabilitiesByPortabilityId($portability_id, $authorization, $x_fapi_interaction_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling CreditPortabilityApi->creditPortabilityGetPortabilitiesByPortabilityId: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **portability_id** | **string**| Identificador do pedido de portabilidade de crédito. | |
| **authorization** | **string**| Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado. | |
| **x_fapi_interaction_id** | **string**| Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela IF Proponente (client) e o seu valor deve ser “espelhado” pela IF Credora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a IF Credora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A IF Proponente deve acatar o valor recebido da IF Credora. | |
| **x_fapi_auth_date** | **string**| Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC | [optional] |
| **x_fapi_customer_ip_address** | **string**| O endereço IP do usuário se estiver atualmente logado com o receptor. | [optional] |
| **x_customer_user_agent** | **string**| Indica o user-agent que o usuário utiliza. | [optional] |

### Return type

[**\OpenAPI\Client\Model\ResponsePortabilitiesByPortabilityId**](../Model/ResponsePortabilitiesByPortabilityId.md)

### Authorization

[OAuth2ClientCredentials](../../README.md#OAuth2ClientCredentials)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/jwt`, `application/json; charset=utf-8`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `creditPortabilityPatchPortabilitiesPortabilityIdCancel()`

```php
creditPortabilityPatchPortabilitiesPortabilityIdCancel($portability_id, $authorization, $x_fapi_interaction_id, $request_credit_portability_cancel, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent): \OpenAPI\Client\Model\PatchResponseCreditPortabilityCancel
```

Comunica a Instituição Credora a respeito do cancelamento da portabilidade de crédito.

Comunica a Instituição Credora a respeito do cancelamento da portabilidade de crédito.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2ClientCredentials
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\CreditPortabilityApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$portability_id = 'portability_id_example'; // string | Identificador do pedido de portabilidade de crédito.
$authorization = 'authorization_example'; // string | Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado.
$x_fapi_interaction_id = d78fc4e5-37ca-4da3-adf2-9b082bf92280; // string | Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela IF Proponente (client) e o seu valor deve ser “espelhado” pela IF Credora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a IF Credora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A IF Proponente deve acatar o valor recebido da IF Credora.
$request_credit_portability_cancel = new \OpenAPI\Client\Model\RequestCreditPortabilityCancel(); // \OpenAPI\Client\Model\RequestCreditPortabilityCancel | Payload para comunicar a liquidação efetuada pela proponente a credora e iniciar a proxima etapa do fluxo de portabilidade de crédito.
$x_fapi_auth_date = 'x_fapi_auth_date_example'; // string | Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC
$x_fapi_customer_ip_address = 'x_fapi_customer_ip_address_example'; // string | O endereço IP do usuário se estiver atualmente logado com o receptor.
$x_customer_user_agent = 'x_customer_user_agent_example'; // string | Indica o user-agent que o usuário utiliza.

try {
    $result = $apiInstance->creditPortabilityPatchPortabilitiesPortabilityIdCancel($portability_id, $authorization, $x_fapi_interaction_id, $request_credit_portability_cancel, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling CreditPortabilityApi->creditPortabilityPatchPortabilitiesPortabilityIdCancel: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **portability_id** | **string**| Identificador do pedido de portabilidade de crédito. | |
| **authorization** | **string**| Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado. | |
| **x_fapi_interaction_id** | **string**| Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela IF Proponente (client) e o seu valor deve ser “espelhado” pela IF Credora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a IF Credora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A IF Proponente deve acatar o valor recebido da IF Credora. | |
| **request_credit_portability_cancel** | [**\OpenAPI\Client\Model\RequestCreditPortabilityCancel**](../Model/RequestCreditPortabilityCancel.md)| Payload para comunicar a liquidação efetuada pela proponente a credora e iniciar a proxima etapa do fluxo de portabilidade de crédito. | |
| **x_fapi_auth_date** | **string**| Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC | [optional] |
| **x_fapi_customer_ip_address** | **string**| O endereço IP do usuário se estiver atualmente logado com o receptor. | [optional] |
| **x_customer_user_agent** | **string**| Indica o user-agent que o usuário utiliza. | [optional] |

### Return type

[**\OpenAPI\Client\Model\PatchResponseCreditPortabilityCancel**](../Model/PatchResponseCreditPortabilityCancel.md)

### Authorization

[OAuth2ClientCredentials](../../README.md#OAuth2ClientCredentials)

### HTTP request headers

- **Content-Type**: `application/jwt`
- **Accept**: `application/jwt`, `application/json; charset=utf-8`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `creditPortabilityPostPortabilities()`

```php
creditPortabilityPostPortabilities($authorization, $x_fapi_interaction_id, $x_idempotency_key, $request_credit_portability, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent): \OpenAPI\Client\Model\POSTResponseCreditPortability
```

Realiza pedido de portabilidade de crédito para um determinado contrato junto a instituição credora

Solicitação de portabilidade de crédito via OFB.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2AuthorizationCodeLoans
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\CreditPortabilityApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$authorization = 'authorization_example'; // string | Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado.
$x_fapi_interaction_id = d78fc4e5-37ca-4da3-adf2-9b082bf92280; // string | Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela IF Proponente (client) e o seu valor deve ser “espelhado” pela IF Credora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a IF Credora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A IF Proponente deve acatar o valor recebido da IF Credora.
$x_idempotency_key = 'x_idempotency_key_example'; // string | Cabeçalho HTTP personalizado. Identificador de solicitação exclusivo para suportar a idempotência.
$request_credit_portability = new \OpenAPI\Client\Model\RequestCreditPortability(); // \OpenAPI\Client\Model\RequestCreditPortability | Payload para o pedido de portabilidade de crédito.
$x_fapi_auth_date = 'x_fapi_auth_date_example'; // string | Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC
$x_fapi_customer_ip_address = 'x_fapi_customer_ip_address_example'; // string | O endereço IP do usuário se estiver atualmente logado com o receptor.
$x_customer_user_agent = 'x_customer_user_agent_example'; // string | Indica o user-agent que o usuário utiliza.

try {
    $result = $apiInstance->creditPortabilityPostPortabilities($authorization, $x_fapi_interaction_id, $x_idempotency_key, $request_credit_portability, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling CreditPortabilityApi->creditPortabilityPostPortabilities: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **authorization** | **string**| Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado. | |
| **x_fapi_interaction_id** | **string**| Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela IF Proponente (client) e o seu valor deve ser “espelhado” pela IF Credora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a IF Credora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A IF Proponente deve acatar o valor recebido da IF Credora. | |
| **x_idempotency_key** | **string**| Cabeçalho HTTP personalizado. Identificador de solicitação exclusivo para suportar a idempotência. | |
| **request_credit_portability** | [**\OpenAPI\Client\Model\RequestCreditPortability**](../Model/RequestCreditPortability.md)| Payload para o pedido de portabilidade de crédito. | |
| **x_fapi_auth_date** | **string**| Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC | [optional] |
| **x_fapi_customer_ip_address** | **string**| O endereço IP do usuário se estiver atualmente logado com o receptor. | [optional] |
| **x_customer_user_agent** | **string**| Indica o user-agent que o usuário utiliza. | [optional] |

### Return type

[**\OpenAPI\Client\Model\POSTResponseCreditPortability**](../Model/POSTResponseCreditPortability.md)

### Authorization

[OAuth2AuthorizationCodeLoans](../../README.md#OAuth2AuthorizationCodeLoans)

### HTTP request headers

- **Content-Type**: `application/jwt`
- **Accept**: `application/jwt`, `application/json; charset=utf-8`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
