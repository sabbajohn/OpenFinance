# OpenAPI\Client\PagamentosApi



All URIs are relative to https://api.banco.com.br/open-banking/payments/v4, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**paymentsGetConsentsConsentId()**](PagamentosApi.md#paymentsGetConsentsConsentId) | **GET** /consents/{consentId} | Consultar consentimento para iniciação de pagamento. |
| [**paymentsGetPixPaymentsPaymentId()**](PagamentosApi.md#paymentsGetPixPaymentsPaymentId) | **GET** /pix/payments/{paymentId} | Consultar iniciação de pagamento. |
| [**paymentsPatchPixPaymentsConsentId()**](PagamentosApi.md#paymentsPatchPixPaymentsConsentId) | **PATCH** /pix/payments/consents/{consentId} | Cancelar todos os pagamentos referentes ao mesmo Consentimento. |
| [**paymentsPatchPixPaymentsPaymentId()**](PagamentosApi.md#paymentsPatchPixPaymentsPaymentId) | **PATCH** /pix/payments/{paymentId} | Cancelar iniciação de pagamento. |
| [**paymentsPostConsents()**](PagamentosApi.md#paymentsPostConsents) | **POST** /consents | Criar consentimento para a iniciação de pagamento. |
| [**paymentsPostPixPayments()**](PagamentosApi.md#paymentsPostPixPayments) | **POST** /pix/payments | Criar iniciação de pagamento. |


## `paymentsGetConsentsConsentId()`

```php
paymentsGetConsentsConsentId($consent_id, $authorization, $x_fapi_interaction_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent): \OpenAPI\Client\Model\ResponsePaymentConsent
```

Consultar consentimento para iniciação de pagamento.

Método para consulta do consentimento para a iniciação de pagamento.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2ClientCredentials
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\PagamentosApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$consent_id = 'consent_id_example'; // string | O consentId é o identificador único do consentimento e deverá ser um URN - Uniform Resource Name.   Um URN, conforme definido na [RFC8141](https://tools.ietf.org/html/rfc8141) é um Uniform Resource  Identifier - URI - que é atribuído sob o URI scheme \"urn\" e um namespace URN específico, com a intenção de que o URN  seja um identificador de recurso persistente e independente da localização.   Considerando a string urn:bancoex:C1DD33123 como exemplo para consentId temos: - o namespace(urn) - o identificador associado ao namespace da instituição transnmissora (bancoex)  - o identificador específico dentro do namespace (C1DD33123).   Informações mais detalhadas sobre a construção de namespaces devem ser consultadas na [RFC8141](https://tools.ietf.org/html/rfc8141).
$authorization = 'authorization_example'; // string | Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado
$x_fapi_interaction_id = d78fc4e5-37ca-4da3-adf2-9b082bf92280; // string | Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser “espelhado” pela detentora (server) no cabeçalho de resposta. Caso não seja enviado pela iniciadora, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. Caso recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400 ou 422 (com o código PARAMETRO_INVALIDO). A iniciadora deve acatar o valor gerado pelo detentor e recebido na resposta.
$x_fapi_auth_date = 'x_fapi_auth_date_example'; // string | Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC
$x_fapi_customer_ip_address = 'x_fapi_customer_ip_address_example'; // string | O endereço IP do usuário se estiver atualmente logado com o receptor.
$x_customer_user_agent = 'x_customer_user_agent_example'; // string | Indica o user-agent que o usuário utiliza.

try {
    $result = $apiInstance->paymentsGetConsentsConsentId($consent_id, $authorization, $x_fapi_interaction_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PagamentosApi->paymentsGetConsentsConsentId: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **consent_id** | **string**| O consentId é o identificador único do consentimento e deverá ser um URN - Uniform Resource Name.   Um URN, conforme definido na [RFC8141](https://tools.ietf.org/html/rfc8141) é um Uniform Resource  Identifier - URI - que é atribuído sob o URI scheme \&quot;urn\&quot; e um namespace URN específico, com a intenção de que o URN  seja um identificador de recurso persistente e independente da localização.   Considerando a string urn:bancoex:C1DD33123 como exemplo para consentId temos: - o namespace(urn) - o identificador associado ao namespace da instituição transnmissora (bancoex)  - o identificador específico dentro do namespace (C1DD33123).   Informações mais detalhadas sobre a construção de namespaces devem ser consultadas na [RFC8141](https://tools.ietf.org/html/rfc8141). | |
| **authorization** | **string**| Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado | |
| **x_fapi_interaction_id** | **string**| Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser “espelhado” pela detentora (server) no cabeçalho de resposta. Caso não seja enviado pela iniciadora, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. Caso recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400 ou 422 (com o código PARAMETRO_INVALIDO). A iniciadora deve acatar o valor gerado pelo detentor e recebido na resposta. | |
| **x_fapi_auth_date** | **string**| Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC | [optional] |
| **x_fapi_customer_ip_address** | **string**| O endereço IP do usuário se estiver atualmente logado com o receptor. | [optional] |
| **x_customer_user_agent** | **string**| Indica o user-agent que o usuário utiliza. | [optional] |

### Return type

[**\OpenAPI\Client\Model\ResponsePaymentConsent**](../Model/ResponsePaymentConsent.md)

### Authorization

[OAuth2ClientCredentials](../../README.md#OAuth2ClientCredentials)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/jwt`, `application/json; charset=utf-8`, `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `paymentsGetPixPaymentsPaymentId()`

```php
paymentsGetPixPaymentsPaymentId($payment_id, $authorization, $x_fapi_interaction_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent): \OpenAPI\Client\Model\ResponsePixPayment
```

Consultar iniciação de pagamento.

Método para consultar uma iniciação de pagamento.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2ClientCredentials
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\PagamentosApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$payment_id = 'payment_id_example'; // string | Identificador da operação de pagamento.
$authorization = 'authorization_example'; // string | Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado
$x_fapi_interaction_id = d78fc4e5-37ca-4da3-adf2-9b082bf92280; // string | Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser “espelhado” pela detentora (server) no cabeçalho de resposta. Caso não seja enviado pela iniciadora, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. Caso recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400 ou 422 (com o código PARAMETRO_INVALIDO). A iniciadora deve acatar o valor gerado pelo detentor e recebido na resposta.
$x_fapi_auth_date = 'x_fapi_auth_date_example'; // string | Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC
$x_fapi_customer_ip_address = 'x_fapi_customer_ip_address_example'; // string | O endereço IP do usuário se estiver atualmente logado com o receptor.
$x_customer_user_agent = 'x_customer_user_agent_example'; // string | Indica o user-agent que o usuário utiliza.

try {
    $result = $apiInstance->paymentsGetPixPaymentsPaymentId($payment_id, $authorization, $x_fapi_interaction_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PagamentosApi->paymentsGetPixPaymentsPaymentId: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **payment_id** | **string**| Identificador da operação de pagamento. | |
| **authorization** | **string**| Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado | |
| **x_fapi_interaction_id** | **string**| Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser “espelhado” pela detentora (server) no cabeçalho de resposta. Caso não seja enviado pela iniciadora, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. Caso recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400 ou 422 (com o código PARAMETRO_INVALIDO). A iniciadora deve acatar o valor gerado pelo detentor e recebido na resposta. | |
| **x_fapi_auth_date** | **string**| Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC | [optional] |
| **x_fapi_customer_ip_address** | **string**| O endereço IP do usuário se estiver atualmente logado com o receptor. | [optional] |
| **x_customer_user_agent** | **string**| Indica o user-agent que o usuário utiliza. | [optional] |

### Return type

[**\OpenAPI\Client\Model\ResponsePixPayment**](../Model/ResponsePixPayment.md)

### Authorization

[OAuth2ClientCredentials](../../README.md#OAuth2ClientCredentials)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/jwt`, `application/json; charset=utf-8`, `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `paymentsPatchPixPaymentsConsentId()`

```php
paymentsPatchPixPaymentsConsentId($authorization, $x_fapi_interaction_id, $consent_id, $x_idempotency_key, $patch_pix_payment, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent): \OpenAPI\Client\Model\ResponsePatchPixConsent
```

Cancelar todos os pagamentos referentes ao mesmo Consentimento.

Esse endpoint deve ser usado para cancelar, a pedido do cliente pagador, as transações que estejam em uma das seguintes situações: Agendada com sucesso (SCHD) ou retida para análise (PDNG). O cancelamento de pagamentos agendados (SCHD) podem ser enviados até 23:59 (Horário de Brasília) do dia anterior à data de efetivação do pagamento. Todos os pagamentos vinculados a esse consentimento e previstos para ocorrer a partir do dia seguinte serão cancelados. Os pagamentos já liquidados não são passíveis de cancelamento. - Se não restar nenhum pagamento (ocorrência) que poderá ser cancelado, a requisição PATCH retorna HTTP Status 422 com o code PAGAMENTO_NAO_PERMITE_CANCELAMENTO. - Caso receba um 422, a iniciadora deve fazer uma requisição GET no pagamento para verificar a situação atual dele, bem como detalhes associados. - Caso o consentimento enviado pelo iniciador no path de requisição não exista na detentora de conta, a detentora deverá retornar código 404.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2ClientCredentials
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\PagamentosApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$authorization = 'authorization_example'; // string | Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado
$x_fapi_interaction_id = d78fc4e5-37ca-4da3-adf2-9b082bf92280; // string | Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser “espelhado” pela detentora (server) no cabeçalho de resposta. Caso não seja enviado pela iniciadora, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. Caso recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400 ou 422 (com o código PARAMETRO_INVALIDO). A iniciadora deve acatar o valor gerado pelo detentor e recebido na resposta.
$consent_id = 'consent_id_example'; // string | O consentId é o identificador único do consentimento e deverá ser um URN - Uniform Resource Name.   Um URN, conforme definido na [RFC8141](https://tools.ietf.org/html/rfc8141) é um Uniform Resource  Identifier - URI - que é atribuído sob o URI scheme \"urn\" e um namespace URN específico, com a intenção de que o URN  seja um identificador de recurso persistente e independente da localização.   Considerando a string urn:bancoex:C1DD33123 como exemplo para consentId temos: - o namespace(urn) - o identificador associado ao namespace da instituição transnmissora (bancoex)  - o identificador específico dentro do namespace (C1DD33123).   Informações mais detalhadas sobre a construção de namespaces devem ser consultadas na [RFC8141](https://tools.ietf.org/html/rfc8141).
$x_idempotency_key = 'x_idempotency_key_example'; // string | Cabeçalho HTTP personalizado. Identificador de solicitação exclusivo para suportar a idempotência.
$patch_pix_payment = new \OpenAPI\Client\Model\PatchPixPayment(); // \OpenAPI\Client\Model\PatchPixPayment | Payload para cancelamento do pagamento.
$x_fapi_auth_date = 'x_fapi_auth_date_example'; // string | Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC
$x_fapi_customer_ip_address = 'x_fapi_customer_ip_address_example'; // string | O endereço IP do usuário se estiver atualmente logado com o receptor.
$x_customer_user_agent = 'x_customer_user_agent_example'; // string | Indica o user-agent que o usuário utiliza.

try {
    $result = $apiInstance->paymentsPatchPixPaymentsConsentId($authorization, $x_fapi_interaction_id, $consent_id, $x_idempotency_key, $patch_pix_payment, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PagamentosApi->paymentsPatchPixPaymentsConsentId: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **authorization** | **string**| Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado | |
| **x_fapi_interaction_id** | **string**| Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser “espelhado” pela detentora (server) no cabeçalho de resposta. Caso não seja enviado pela iniciadora, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. Caso recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400 ou 422 (com o código PARAMETRO_INVALIDO). A iniciadora deve acatar o valor gerado pelo detentor e recebido na resposta. | |
| **consent_id** | **string**| O consentId é o identificador único do consentimento e deverá ser um URN - Uniform Resource Name.   Um URN, conforme definido na [RFC8141](https://tools.ietf.org/html/rfc8141) é um Uniform Resource  Identifier - URI - que é atribuído sob o URI scheme \&quot;urn\&quot; e um namespace URN específico, com a intenção de que o URN  seja um identificador de recurso persistente e independente da localização.   Considerando a string urn:bancoex:C1DD33123 como exemplo para consentId temos: - o namespace(urn) - o identificador associado ao namespace da instituição transnmissora (bancoex)  - o identificador específico dentro do namespace (C1DD33123).   Informações mais detalhadas sobre a construção de namespaces devem ser consultadas na [RFC8141](https://tools.ietf.org/html/rfc8141). | |
| **x_idempotency_key** | **string**| Cabeçalho HTTP personalizado. Identificador de solicitação exclusivo para suportar a idempotência. | |
| **patch_pix_payment** | [**\OpenAPI\Client\Model\PatchPixPayment**](../Model/PatchPixPayment.md)| Payload para cancelamento do pagamento. | |
| **x_fapi_auth_date** | **string**| Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC | [optional] |
| **x_fapi_customer_ip_address** | **string**| O endereço IP do usuário se estiver atualmente logado com o receptor. | [optional] |
| **x_customer_user_agent** | **string**| Indica o user-agent que o usuário utiliza. | [optional] |

### Return type

[**\OpenAPI\Client\Model\ResponsePatchPixConsent**](../Model/ResponsePatchPixConsent.md)

### Authorization

[OAuth2ClientCredentials](../../README.md#OAuth2ClientCredentials)

### HTTP request headers

- **Content-Type**: `application/jwt`
- **Accept**: `application/jwt`, `application/json; charset=utf-8`, `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `paymentsPatchPixPaymentsPaymentId()`

```php
paymentsPatchPixPaymentsPaymentId($payment_id, $authorization, $x_fapi_interaction_id, $patch_pix_payment, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent): \OpenAPI\Client\Model\ResponsePatchPixPayment
```

Cancelar iniciação de pagamento.

Esse endpoint deve ser usado para cancelar, a pedido do cliente pagador, as transações que estejam em uma das seguintes situações: Agendada com sucesso (SCHD) ou retida para análise (PDNG).  - Caso a requisição seja bem sucedida, a transação vai para a situação CANC.  - Caso o status do pagamento seja diferente de SCHD/PDNG ou alguma outra regra de negócio impeça o cancelamento, a requisição PATCH retorna HTTP Status 422 com o code PAGAMENTO_NAO_PERMITE_CANCELAMENTO.  - Caso receba um 422, a iniciadora deve fazer uma requisição GET no pagamento para verificar a situação atual dele, bem como detalhes associados.  O cancelamento de pagamento agendado (SCHD) pode ser enviado até 23:59 (Horário de Brasília) do dia anterior à data de efetivação do pagamento.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2ClientCredentials
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\PagamentosApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$payment_id = 'payment_id_example'; // string | Identificador da operação de pagamento.
$authorization = 'authorization_example'; // string | Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado
$x_fapi_interaction_id = d78fc4e5-37ca-4da3-adf2-9b082bf92280; // string | Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser “espelhado” pela detentora (server) no cabeçalho de resposta. Caso não seja enviado pela iniciadora, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. Caso recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400 ou 422 (com o código PARAMETRO_INVALIDO). A iniciadora deve acatar o valor gerado pelo detentor e recebido na resposta.
$patch_pix_payment = new \OpenAPI\Client\Model\PatchPixPayment(); // \OpenAPI\Client\Model\PatchPixPayment | Payload para cancelamento do pagamento.
$x_fapi_auth_date = 'x_fapi_auth_date_example'; // string | Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC
$x_fapi_customer_ip_address = 'x_fapi_customer_ip_address_example'; // string | O endereço IP do usuário se estiver atualmente logado com o receptor.
$x_customer_user_agent = 'x_customer_user_agent_example'; // string | Indica o user-agent que o usuário utiliza.

try {
    $result = $apiInstance->paymentsPatchPixPaymentsPaymentId($payment_id, $authorization, $x_fapi_interaction_id, $patch_pix_payment, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PagamentosApi->paymentsPatchPixPaymentsPaymentId: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **payment_id** | **string**| Identificador da operação de pagamento. | |
| **authorization** | **string**| Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado | |
| **x_fapi_interaction_id** | **string**| Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser “espelhado” pela detentora (server) no cabeçalho de resposta. Caso não seja enviado pela iniciadora, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. Caso recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400 ou 422 (com o código PARAMETRO_INVALIDO). A iniciadora deve acatar o valor gerado pelo detentor e recebido na resposta. | |
| **patch_pix_payment** | [**\OpenAPI\Client\Model\PatchPixPayment**](../Model/PatchPixPayment.md)| Payload para cancelamento do pagamento. | |
| **x_fapi_auth_date** | **string**| Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC | [optional] |
| **x_fapi_customer_ip_address** | **string**| O endereço IP do usuário se estiver atualmente logado com o receptor. | [optional] |
| **x_customer_user_agent** | **string**| Indica o user-agent que o usuário utiliza. | [optional] |

### Return type

[**\OpenAPI\Client\Model\ResponsePatchPixPayment**](../Model/ResponsePatchPixPayment.md)

### Authorization

[OAuth2ClientCredentials](../../README.md#OAuth2ClientCredentials)

### HTTP request headers

- **Content-Type**: `application/jwt`
- **Accept**: `application/jwt`, `application/json; charset=utf-8`, `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `paymentsPostConsents()`

```php
paymentsPostConsents($authorization, $x_fapi_interaction_id, $x_idempotency_key, $create_payment_consent, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent): \OpenAPI\Client\Model\ResponseCreatePaymentConsent
```

Criar consentimento para a iniciação de pagamento.

Método de criação do consentimento para a iniciação de pagamento.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2ClientCredentials
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\PagamentosApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$authorization = 'authorization_example'; // string | Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado
$x_fapi_interaction_id = d78fc4e5-37ca-4da3-adf2-9b082bf92280; // string | Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser “espelhado” pela detentora (server) no cabeçalho de resposta. Caso não seja enviado pela iniciadora, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. Caso recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400 ou 422 (com o código PARAMETRO_INVALIDO). A iniciadora deve acatar o valor gerado pelo detentor e recebido na resposta.
$x_idempotency_key = 'x_idempotency_key_example'; // string | Cabeçalho HTTP personalizado. Identificador de solicitação exclusivo para suportar a idempotência.
$create_payment_consent = new \OpenAPI\Client\Model\CreatePaymentConsent(); // \OpenAPI\Client\Model\CreatePaymentConsent | Payload para criação do consentimento para iniciação do pagamento.
$x_fapi_auth_date = 'x_fapi_auth_date_example'; // string | Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC
$x_fapi_customer_ip_address = 'x_fapi_customer_ip_address_example'; // string | O endereço IP do usuário se estiver atualmente logado com o receptor.
$x_customer_user_agent = 'x_customer_user_agent_example'; // string | Indica o user-agent que o usuário utiliza.

try {
    $result = $apiInstance->paymentsPostConsents($authorization, $x_fapi_interaction_id, $x_idempotency_key, $create_payment_consent, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PagamentosApi->paymentsPostConsents: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **authorization** | **string**| Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado | |
| **x_fapi_interaction_id** | **string**| Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser “espelhado” pela detentora (server) no cabeçalho de resposta. Caso não seja enviado pela iniciadora, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. Caso recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400 ou 422 (com o código PARAMETRO_INVALIDO). A iniciadora deve acatar o valor gerado pelo detentor e recebido na resposta. | |
| **x_idempotency_key** | **string**| Cabeçalho HTTP personalizado. Identificador de solicitação exclusivo para suportar a idempotência. | |
| **create_payment_consent** | [**\OpenAPI\Client\Model\CreatePaymentConsent**](../Model/CreatePaymentConsent.md)| Payload para criação do consentimento para iniciação do pagamento. | |
| **x_fapi_auth_date** | **string**| Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC | [optional] |
| **x_fapi_customer_ip_address** | **string**| O endereço IP do usuário se estiver atualmente logado com o receptor. | [optional] |
| **x_customer_user_agent** | **string**| Indica o user-agent que o usuário utiliza. | [optional] |

### Return type

[**\OpenAPI\Client\Model\ResponseCreatePaymentConsent**](../Model/ResponseCreatePaymentConsent.md)

### Authorization

[OAuth2ClientCredentials](../../README.md#OAuth2ClientCredentials)

### HTTP request headers

- **Content-Type**: `application/jwt`
- **Accept**: `application/jwt`, `application/json; charset=utf-8`, `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `paymentsPostPixPayments()`

```php
paymentsPostPixPayments($authorization, $x_fapi_interaction_id, $x_idempotency_key, $create_pix_payment, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent): \OpenAPI\Client\Model\ResponseCreatePixPayment
```

Criar iniciação de pagamento.

Método para criar uma iniciação de pagamento.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2AuthorizationCode
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

// Configure OAuth2 access token for authorization: NonRedirectAuthorizationCode
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\PagamentosApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$authorization = 'authorization_example'; // string | Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado
$x_fapi_interaction_id = d78fc4e5-37ca-4da3-adf2-9b082bf92280; // string | Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser “espelhado” pela detentora (server) no cabeçalho de resposta. Caso não seja enviado pela iniciadora, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. Caso recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400 ou 422 (com o código PARAMETRO_INVALIDO). A iniciadora deve acatar o valor gerado pelo detentor e recebido na resposta.
$x_idempotency_key = 'x_idempotency_key_example'; // string | Cabeçalho HTTP personalizado. Identificador de solicitação exclusivo para suportar a idempotência.
$create_pix_payment = new \OpenAPI\Client\Model\CreatePixPayment(); // \OpenAPI\Client\Model\CreatePixPayment | Payload para criação da iniciação do pagamento Pix.
$x_fapi_auth_date = 'x_fapi_auth_date_example'; // string | Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC
$x_fapi_customer_ip_address = 'x_fapi_customer_ip_address_example'; // string | O endereço IP do usuário se estiver atualmente logado com o receptor.
$x_customer_user_agent = 'x_customer_user_agent_example'; // string | Indica o user-agent que o usuário utiliza.

try {
    $result = $apiInstance->paymentsPostPixPayments($authorization, $x_fapi_interaction_id, $x_idempotency_key, $create_pix_payment, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PagamentosApi->paymentsPostPixPayments: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **authorization** | **string**| Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado | |
| **x_fapi_interaction_id** | **string**| Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser “espelhado” pela detentora (server) no cabeçalho de resposta. Caso não seja enviado pela iniciadora, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. Caso recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400 ou 422 (com o código PARAMETRO_INVALIDO). A iniciadora deve acatar o valor gerado pelo detentor e recebido na resposta. | |
| **x_idempotency_key** | **string**| Cabeçalho HTTP personalizado. Identificador de solicitação exclusivo para suportar a idempotência. | |
| **create_pix_payment** | [**\OpenAPI\Client\Model\CreatePixPayment**](../Model/CreatePixPayment.md)| Payload para criação da iniciação do pagamento Pix. | |
| **x_fapi_auth_date** | **string**| Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC | [optional] |
| **x_fapi_customer_ip_address** | **string**| O endereço IP do usuário se estiver atualmente logado com o receptor. | [optional] |
| **x_customer_user_agent** | **string**| Indica o user-agent que o usuário utiliza. | [optional] |

### Return type

[**\OpenAPI\Client\Model\ResponseCreatePixPayment**](../Model/ResponseCreatePixPayment.md)

### Authorization

[OAuth2AuthorizationCode](../../README.md#OAuth2AuthorizationCode), [NonRedirectAuthorizationCode](../../README.md#NonRedirectAuthorizationCode)

### HTTP request headers

- **Content-Type**: `application/jwt`
- **Accept**: `application/jwt`, `application/json; charset=utf-8`, `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
