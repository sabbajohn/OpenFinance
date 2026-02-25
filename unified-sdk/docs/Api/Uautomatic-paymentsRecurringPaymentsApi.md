# OpenAPI\Client\RecurringPaymentsApi



All URIs are relative to https://api.banco.com.br/open-banking/automatic-payments/v2, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**automaticPaymentsGetPixRecurringPayments()**](RecurringPaymentsApi.md#automaticPaymentsGetPixRecurringPayments) | **GET** /pix/recurring-payments | Busca informações de transações de pagamentos associadas a um consentimento. |
| [**automaticPaymentsGetPixRecurringPaymentsPaymentId()**](RecurringPaymentsApi.md#automaticPaymentsGetPixRecurringPaymentsPaymentId) | **GET** /pix/recurring-payments/{recurringPaymentId} | Busca informações de uma transação de pagamento. |
| [**automaticPaymentsPatchPixRecurringPaymentsPaymentId()**](RecurringPaymentsApi.md#automaticPaymentsPatchPixRecurringPaymentsPaymentId) | **PATCH** /pix/recurring-payments/{recurringPaymentId} | Cancelamento de solicitação de pagamento automático. |
| [**automaticPaymentsPostPixRecurringPayments()**](RecurringPaymentsApi.md#automaticPaymentsPostPixRecurringPayments) | **POST** /pix/recurring-payments | Cria uma transação de pagamento. |


## `automaticPaymentsGetPixRecurringPayments()`

```php
automaticPaymentsGetPixRecurringPayments($authorization, $x_fapi_interaction_id, $recurring_consent_id, $x_customer_user_agent, $x_fapi_auth_date, $x_fapi_customer_ip_address, $start_date, $end_date, $original_recurring_payment_id): \OpenAPI\Client\Model\ResponseRecurringPixPayment
```

Busca informações de transações de pagamentos associadas a um consentimento.

Método para buscar informações sobre um conjunto de pagamentos associados ao mesmo recurringConsentId.  Também é possível enviar uma data de início (startDate) e final (endDate), caso a busca seja por transações em uma determinada janela de tempo.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2ClientCredentials
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\RecurringPaymentsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$authorization = 'authorization_example'; // string | Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado
$x_fapi_interaction_id = d78fc4e5-37ca-4da3-adf2-9b082bf92280; // string | Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser “espelhado” pela detentora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. A iniciadora deve acatar o valor recebido da detentora..
$recurring_consent_id = 'recurring_consent_id_example'; // string | O `recurringConsentId` é o identificador único do consentimento de longa duração e deverá ser um URN - Uniform Resource Name.   Um URN, conforme definido na [RFC8141](https://tools.ietf.org/html/rfc8141) é um Uniform Resource  Identifier - URI - que é atribuído sob o URI scheme \"urn\" e um namespace URN específico, com a intenção de que o URN  seja um identificador de recurso persistente e independe da localização.   Considerando a string urn:bancoex:C1DD33123 como exemplo para `recurringConsentId` temos: - o namespace(urn) - o identificador associado ao namespace da instituição detentora (bancoex). - o identificador específico dentro do namespace (C1DD33123).   Informações mais detalhadas sobre a construção de namespaces devem ser consultadas na [RFC8141](https://tools.ietf.org/html/rfc8141).
$x_customer_user_agent = 'x_customer_user_agent_example'; // string | Indica o user-agent que o usuário utiliza.
$x_fapi_auth_date = 'x_fapi_auth_date_example'; // string | Data em que o usuário logou pela última vez com o iniciador. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC
$x_fapi_customer_ip_address = 'x_fapi_customer_ip_address_example'; // string | O endereço IP do usuário se estiver atualmente logado com o iniciador.
$start_date = 'start_date_example'; // string | Data inicial de corte da ocorrência do pagamento ligada ao consentimento de longa duração.
$end_date = 'end_date_example'; // string | Data final de corte para recuperação da ocorrência do pagamento ligada ao consentimento de longa duração.
$original_recurring_payment_id = TXpRMU9UQTROMWhZV2xSU1FUazJSMDl; // string | Campo que contém o código ou o identificador da tentativa original de pagamento que falhou.  Código ou identificador único criado pela instituição detentora da conta para representar a iniciação de pagamento.  Caso informado, devem ser retornados todos os pagamentos associados ao identificador informado, sendo eles o pagamento original (dono do identificador) e as novas tentativas que enviaram o identificador na sua requisição, indicando que representam nova tentativa.

try {
    $result = $apiInstance->automaticPaymentsGetPixRecurringPayments($authorization, $x_fapi_interaction_id, $recurring_consent_id, $x_customer_user_agent, $x_fapi_auth_date, $x_fapi_customer_ip_address, $start_date, $end_date, $original_recurring_payment_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling RecurringPaymentsApi->automaticPaymentsGetPixRecurringPayments: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **authorization** | **string**| Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado | |
| **x_fapi_interaction_id** | **string**| Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser “espelhado” pela detentora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. A iniciadora deve acatar o valor recebido da detentora.. | |
| **recurring_consent_id** | **string**| O &#x60;recurringConsentId&#x60; é o identificador único do consentimento de longa duração e deverá ser um URN - Uniform Resource Name.   Um URN, conforme definido na [RFC8141](https://tools.ietf.org/html/rfc8141) é um Uniform Resource  Identifier - URI - que é atribuído sob o URI scheme \&quot;urn\&quot; e um namespace URN específico, com a intenção de que o URN  seja um identificador de recurso persistente e independe da localização.   Considerando a string urn:bancoex:C1DD33123 como exemplo para &#x60;recurringConsentId&#x60; temos: - o namespace(urn) - o identificador associado ao namespace da instituição detentora (bancoex). - o identificador específico dentro do namespace (C1DD33123).   Informações mais detalhadas sobre a construção de namespaces devem ser consultadas na [RFC8141](https://tools.ietf.org/html/rfc8141). | |
| **x_customer_user_agent** | **string**| Indica o user-agent que o usuário utiliza. | [optional] |
| **x_fapi_auth_date** | **string**| Data em que o usuário logou pela última vez com o iniciador. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC | [optional] |
| **x_fapi_customer_ip_address** | **string**| O endereço IP do usuário se estiver atualmente logado com o iniciador. | [optional] |
| **start_date** | **string**| Data inicial de corte da ocorrência do pagamento ligada ao consentimento de longa duração. | [optional] |
| **end_date** | **string**| Data final de corte para recuperação da ocorrência do pagamento ligada ao consentimento de longa duração. | [optional] |
| **original_recurring_payment_id** | **string**| Campo que contém o código ou o identificador da tentativa original de pagamento que falhou.  Código ou identificador único criado pela instituição detentora da conta para representar a iniciação de pagamento.  Caso informado, devem ser retornados todos os pagamentos associados ao identificador informado, sendo eles o pagamento original (dono do identificador) e as novas tentativas que enviaram o identificador na sua requisição, indicando que representam nova tentativa. | [optional] |

### Return type

[**\OpenAPI\Client\Model\ResponseRecurringPixPayment**](../Model/ResponseRecurringPixPayment.md)

### Authorization

[OAuth2ClientCredentials](../../README.md#OAuth2ClientCredentials)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/jwt`, `application/json; charset=utf-8`, `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `automaticPaymentsGetPixRecurringPaymentsPaymentId()`

```php
automaticPaymentsGetPixRecurringPaymentsPaymentId($authorization, $x_fapi_interaction_id, $recurring_payment_id, $x_customer_user_agent, $x_fapi_auth_date, $x_fapi_customer_ip_address): \OpenAPI\Client\Model\ResponseRecurringPaymentsIdRead
```

Busca informações de uma transação de pagamento.

Método para buscar informações sobre um pagamento.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2ClientCredentials
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\RecurringPaymentsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$authorization = 'authorization_example'; // string | Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado
$x_fapi_interaction_id = d78fc4e5-37ca-4da3-adf2-9b082bf92280; // string | Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser “espelhado” pela detentora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. A iniciadora deve acatar o valor recebido da detentora..
$recurring_payment_id = 'recurring_payment_id_example'; // string | Identificador da operação de pagamento.
$x_customer_user_agent = 'x_customer_user_agent_example'; // string | Indica o user-agent que o usuário utiliza.
$x_fapi_auth_date = 'x_fapi_auth_date_example'; // string | Data em que o usuário logou pela última vez com o iniciador. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC
$x_fapi_customer_ip_address = 'x_fapi_customer_ip_address_example'; // string | O endereço IP do usuário se estiver atualmente logado com o iniciador.

try {
    $result = $apiInstance->automaticPaymentsGetPixRecurringPaymentsPaymentId($authorization, $x_fapi_interaction_id, $recurring_payment_id, $x_customer_user_agent, $x_fapi_auth_date, $x_fapi_customer_ip_address);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling RecurringPaymentsApi->automaticPaymentsGetPixRecurringPaymentsPaymentId: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **authorization** | **string**| Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado | |
| **x_fapi_interaction_id** | **string**| Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser “espelhado” pela detentora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. A iniciadora deve acatar o valor recebido da detentora.. | |
| **recurring_payment_id** | **string**| Identificador da operação de pagamento. | |
| **x_customer_user_agent** | **string**| Indica o user-agent que o usuário utiliza. | [optional] |
| **x_fapi_auth_date** | **string**| Data em que o usuário logou pela última vez com o iniciador. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC | [optional] |
| **x_fapi_customer_ip_address** | **string**| O endereço IP do usuário se estiver atualmente logado com o iniciador. | [optional] |

### Return type

[**\OpenAPI\Client\Model\ResponseRecurringPaymentsIdRead**](../Model/ResponseRecurringPaymentsIdRead.md)

### Authorization

[OAuth2ClientCredentials](../../README.md#OAuth2ClientCredentials)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/jwt`, `application/json; charset=utf-8`, `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `automaticPaymentsPatchPixRecurringPaymentsPaymentId()`

```php
automaticPaymentsPatchPixRecurringPaymentsPaymentId($authorization, $x_fapi_interaction_id, $recurring_payment_id, $x_idempotency_key, $patch_pix_payment, $x_customer_user_agent, $x_fapi_auth_date, $x_fapi_customer_ip_address): \OpenAPI\Client\Model\ResponseRecurringPaymentsIdPatch
```

Cancelamento de solicitação de pagamento automático.

Esse endpoint deve ser usado para cancelar as transações que estejam em uma das seguintes situações:  Agendada com sucesso (SCHD), retida para análise (PDNG). Caso a requisição seja bem sucedida, a transação vai para a situação CANC.   Caso o status do pagamento seja diferente de SCHD/PDNG ou alguma outra regra de negócio impeça o cancelamento, a requisição PATCH retorna  HTTP Status 422 com o code PAGAMENTO_NAO_PERMITE_CANCELAMENTO.   Caso receba um 422, a iniciadora deve fazer uma requisição GET no pagamento para verificar a situação atual dele, bem como detalhes associados.   [Restrição] Para o Pix automático (“recurringConfiguration” igual a “automatic”) tanto o recebedor quanto o pagador poderão realizar o cancelamento,  sendo permitido ao recebedor a solicitação de cancelamento até as 22:00 (Horário de Brasília) e ao pagador até as 23:59 (Horário de Brasília) do dia anterior à data de efetivação do pagamento,  exceto para os casos de novas tentativas em dias subsequentes, onde apenas o recebedor pode cancelar, também até as 22:00h.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2ClientCredentials
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\RecurringPaymentsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$authorization = 'authorization_example'; // string | Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado
$x_fapi_interaction_id = d78fc4e5-37ca-4da3-adf2-9b082bf92280; // string | Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser “espelhado” pela detentora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. A iniciadora deve acatar o valor recebido da detentora..
$recurring_payment_id = 'recurring_payment_id_example'; // string | Identificador da operação de pagamento.
$x_idempotency_key = 'x_idempotency_key_example'; // string | Cabeçalho HTTP personalizado. Identificador de solicitação exclusivo para suportar a idempotência.
$patch_pix_payment = new \OpenAPI\Client\Model\PatchPixPayment(); // \OpenAPI\Client\Model\PatchPixPayment | Atualização do Pagamento Recorrente.
$x_customer_user_agent = 'x_customer_user_agent_example'; // string | Indica o user-agent que o usuário utiliza.
$x_fapi_auth_date = 'x_fapi_auth_date_example'; // string | Data em que o usuário logou pela última vez com o iniciador. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC
$x_fapi_customer_ip_address = 'x_fapi_customer_ip_address_example'; // string | O endereço IP do usuário se estiver atualmente logado com o iniciador.

try {
    $result = $apiInstance->automaticPaymentsPatchPixRecurringPaymentsPaymentId($authorization, $x_fapi_interaction_id, $recurring_payment_id, $x_idempotency_key, $patch_pix_payment, $x_customer_user_agent, $x_fapi_auth_date, $x_fapi_customer_ip_address);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling RecurringPaymentsApi->automaticPaymentsPatchPixRecurringPaymentsPaymentId: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **authorization** | **string**| Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado | |
| **x_fapi_interaction_id** | **string**| Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser “espelhado” pela detentora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. A iniciadora deve acatar o valor recebido da detentora.. | |
| **recurring_payment_id** | **string**| Identificador da operação de pagamento. | |
| **x_idempotency_key** | **string**| Cabeçalho HTTP personalizado. Identificador de solicitação exclusivo para suportar a idempotência. | |
| **patch_pix_payment** | [**\OpenAPI\Client\Model\PatchPixPayment**](../Model/PatchPixPayment.md)| Atualização do Pagamento Recorrente. | |
| **x_customer_user_agent** | **string**| Indica o user-agent que o usuário utiliza. | [optional] |
| **x_fapi_auth_date** | **string**| Data em que o usuário logou pela última vez com o iniciador. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC | [optional] |
| **x_fapi_customer_ip_address** | **string**| O endereço IP do usuário se estiver atualmente logado com o iniciador. | [optional] |

### Return type

[**\OpenAPI\Client\Model\ResponseRecurringPaymentsIdPatch**](../Model/ResponseRecurringPaymentsIdPatch.md)

### Authorization

[OAuth2ClientCredentials](../../README.md#OAuth2ClientCredentials)

### HTTP request headers

- **Content-Type**: `application/jwt`
- **Accept**: `application/jwt`, `application/json; charset=utf-8`, `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `automaticPaymentsPostPixRecurringPayments()`

```php
automaticPaymentsPostPixRecurringPayments($authorization, $x_fapi_interaction_id, $x_idempotency_key, $create_recurring_pix_payment, $x_customer_user_agent, $x_fapi_auth_date, $x_fapi_customer_ip_address): \OpenAPI\Client\Model\ResponseRecurringPaymentsIdPost
```

Cria uma transação de pagamento.

Método para criação de uma transação de pagamento. Retorna um recurringPaymentId.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2AuthorizationCode
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

// Configure OAuth2 access token for authorization: NonRedirectAuthorizationCode
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\RecurringPaymentsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$authorization = 'authorization_example'; // string | Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado
$x_fapi_interaction_id = d78fc4e5-37ca-4da3-adf2-9b082bf92280; // string | Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser “espelhado” pela detentora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. A iniciadora deve acatar o valor recebido da detentora..
$x_idempotency_key = 'x_idempotency_key_example'; // string | Cabeçalho HTTP personalizado. Identificador de solicitação exclusivo para suportar a idempotência.
$create_recurring_pix_payment = new \OpenAPI\Client\Model\CreateRecurringPixPayment(); // \OpenAPI\Client\Model\CreateRecurringPixPayment | Payload para criação da iniciação do pagamento Pix.
$x_customer_user_agent = 'x_customer_user_agent_example'; // string | Indica o user-agent que o usuário utiliza.
$x_fapi_auth_date = 'x_fapi_auth_date_example'; // string | Data em que o usuário logou pela última vez com o iniciador. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC
$x_fapi_customer_ip_address = 'x_fapi_customer_ip_address_example'; // string | O endereço IP do usuário se estiver atualmente logado com o iniciador.

try {
    $result = $apiInstance->automaticPaymentsPostPixRecurringPayments($authorization, $x_fapi_interaction_id, $x_idempotency_key, $create_recurring_pix_payment, $x_customer_user_agent, $x_fapi_auth_date, $x_fapi_customer_ip_address);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling RecurringPaymentsApi->automaticPaymentsPostPixRecurringPayments: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **authorization** | **string**| Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado | |
| **x_fapi_interaction_id** | **string**| Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser “espelhado” pela detentora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. A iniciadora deve acatar o valor recebido da detentora.. | |
| **x_idempotency_key** | **string**| Cabeçalho HTTP personalizado. Identificador de solicitação exclusivo para suportar a idempotência. | |
| **create_recurring_pix_payment** | [**\OpenAPI\Client\Model\CreateRecurringPixPayment**](../Model/CreateRecurringPixPayment.md)| Payload para criação da iniciação do pagamento Pix. | |
| **x_customer_user_agent** | **string**| Indica o user-agent que o usuário utiliza. | [optional] |
| **x_fapi_auth_date** | **string**| Data em que o usuário logou pela última vez com o iniciador. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC | [optional] |
| **x_fapi_customer_ip_address** | **string**| O endereço IP do usuário se estiver atualmente logado com o iniciador. | [optional] |

### Return type

[**\OpenAPI\Client\Model\ResponseRecurringPaymentsIdPost**](../Model/ResponseRecurringPaymentsIdPost.md)

### Authorization

[OAuth2AuthorizationCode](../../README.md#OAuth2AuthorizationCode), [NonRedirectAuthorizationCode](../../README.md#NonRedirectAuthorizationCode)

### HTTP request headers

- **Content-Type**: `application/jwt`
- **Accept**: `application/jwt`, `application/json; charset=utf-8`, `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
