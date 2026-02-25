# OpenAPI\Client\RecurringConsentsApi



All URIs are relative to https://api.banco.com.br/open-banking/automatic-payments/v2, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**automaticPaymentsGetRecurringConsentsConsentId()**](RecurringConsentsApi.md#automaticPaymentsGetRecurringConsentsConsentId) | **GET** /recurring-consents/{recurringConsentId} | Busca informações de um consentimento. |
| [**automaticPaymentsPatchRecurringConsentsConsentId()**](RecurringConsentsApi.md#automaticPaymentsPatchRecurringConsentsConsentId) | **PATCH** /recurring-consents/{recurringConsentId} | Rejeita, revoga ou edita um consentimento. |
| [**automaticPaymentsPostRecurringConsents()**](RecurringConsentsApi.md#automaticPaymentsPostRecurringConsents) | **POST** /recurring-consents | Cria um consentimento para transações de pagamentos. |


## `automaticPaymentsGetRecurringConsentsConsentId()`

```php
automaticPaymentsGetRecurringConsentsConsentId($authorization, $x_fapi_interaction_id, $recurring_consent_id, $x_customer_user_agent, $x_fapi_auth_date, $x_fapi_customer_ip_address): \OpenAPI\Client\Model\ResponseRecurringConsent
```

Busca informações de um consentimento.

Método para buscar informações sobre um consentimento de longa duração.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2ClientCredentials
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\RecurringConsentsApi(
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

try {
    $result = $apiInstance->automaticPaymentsGetRecurringConsentsConsentId($authorization, $x_fapi_interaction_id, $recurring_consent_id, $x_customer_user_agent, $x_fapi_auth_date, $x_fapi_customer_ip_address);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling RecurringConsentsApi->automaticPaymentsGetRecurringConsentsConsentId: ', $e->getMessage(), PHP_EOL;
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

### Return type

[**\OpenAPI\Client\Model\ResponseRecurringConsent**](../Model/ResponseRecurringConsent.md)

### Authorization

[OAuth2ClientCredentials](../../README.md#OAuth2ClientCredentials)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/jwt`, `application/json; charset=utf-8`, `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `automaticPaymentsPatchRecurringConsentsConsentId()`

```php
automaticPaymentsPatchRecurringConsentsConsentId($authorization, $x_fapi_interaction_id, $recurring_consent_id, $x_idempotency_key, $patch_recurring_consent, $x_customer_user_agent, $x_fapi_auth_date, $x_fapi_customer_ip_address): \OpenAPI\Client\Model\ResponseRecurringConsentPatch
```

Rejeita, revoga ou edita um consentimento.

Método para rejeitar, revogar ou editar um consentimento de longa duração:   1 - Informações sobre a revogação: - Caso bem sucedido, o consentimento vai para o status “REVOKED”; - Apenas consentimentos com status “AUTHORISED” podem ser revogados; - Pagamentos automáticos associados ao consentimento e que estão agendados para ocorrer até as 23:59h do próximo dia (a partir do dia de  solicitação da revogação) deverão ser mantidos. Pagamentos agendados para ocorrer após esse período devem ser cancelados. - Demais orientações referentes a revogação podem ser encontrados no header da API, tópico “Validações”, item 3.  2 - Informações sobre a edição:  - Os campos que são passíveis de edição e suas regras podem ser encontrados através do [link](https://openfinancebrasil.atlassian.net/wiki/spaces/OF/pages/628195665); - A edição é possível apenas em casos de consentimento para Pix Automático (“automatic” escolhido no oneOf do objeto “recurringConfiguration”); - O envio do item \"/data/creditors/name\" atualizará o nome do recebedor em todos os elementos do array. - Caso consentimento seja de valor fixo (campo “/data/recurringConfiguration/automatic/fixedAmount” preenchido) não é permitida a edição do campo “/data/recurringConfiguration/automatic/maximumVariableAmount”. - Caso o recebedor tenha definido um piso para o limite a ser estipulado pelo pagador (campo “/data/recurringConfiguration/automatic/minimumVariableAmount” preenchido), o valor máximo de limite por transação definido pelo usuário pagador (campo “/data/recurringConfiguration/automatic/maximumVariableAmount”) não pode ser menor que o valor estipulado pelo recebedor. - Caso o seja editado o prazo de expiração do consentimento e já existam pagamentos agendados para dias posteriores a nova data definida, estes pagamentos devem ser cancelados.  3 - Informações sobre a rejeição:   - Caso haja necessidade de cancelamento de um consentimento ainda não autorizado, o iniciador poderá chamar o endpoint para mover o consentimento para REJECTED.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2ClientCredentials
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\RecurringConsentsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$authorization = 'authorization_example'; // string | Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado
$x_fapi_interaction_id = d78fc4e5-37ca-4da3-adf2-9b082bf92280; // string | Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser “espelhado” pela detentora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. A iniciadora deve acatar o valor recebido da detentora..
$recurring_consent_id = 'recurring_consent_id_example'; // string | O `recurringConsentId` é o identificador único do consentimento de longa duração e deverá ser um URN - Uniform Resource Name.   Um URN, conforme definido na [RFC8141](https://tools.ietf.org/html/rfc8141) é um Uniform Resource  Identifier - URI - que é atribuído sob o URI scheme \"urn\" e um namespace URN específico, com a intenção de que o URN  seja um identificador de recurso persistente e independe da localização.   Considerando a string urn:bancoex:C1DD33123 como exemplo para `recurringConsentId` temos: - o namespace(urn) - o identificador associado ao namespace da instituição detentora (bancoex). - o identificador específico dentro do namespace (C1DD33123).   Informações mais detalhadas sobre a construção de namespaces devem ser consultadas na [RFC8141](https://tools.ietf.org/html/rfc8141).
$x_idempotency_key = 'x_idempotency_key_example'; // string | Cabeçalho HTTP personalizado. Identificador de solicitação exclusivo para suportar a idempotência.
$patch_recurring_consent = new \OpenAPI\Client\Model\PatchRecurringConsent(); // \OpenAPI\Client\Model\PatchRecurringConsent | Payload para criação do consentimento para iniciação do pagamento.
$x_customer_user_agent = 'x_customer_user_agent_example'; // string | Indica o user-agent que o usuário utiliza.
$x_fapi_auth_date = 'x_fapi_auth_date_example'; // string | Data em que o usuário logou pela última vez com o iniciador. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC
$x_fapi_customer_ip_address = 'x_fapi_customer_ip_address_example'; // string | O endereço IP do usuário se estiver atualmente logado com o iniciador.

try {
    $result = $apiInstance->automaticPaymentsPatchRecurringConsentsConsentId($authorization, $x_fapi_interaction_id, $recurring_consent_id, $x_idempotency_key, $patch_recurring_consent, $x_customer_user_agent, $x_fapi_auth_date, $x_fapi_customer_ip_address);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling RecurringConsentsApi->automaticPaymentsPatchRecurringConsentsConsentId: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **authorization** | **string**| Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado | |
| **x_fapi_interaction_id** | **string**| Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser “espelhado” pela detentora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. A iniciadora deve acatar o valor recebido da detentora.. | |
| **recurring_consent_id** | **string**| O &#x60;recurringConsentId&#x60; é o identificador único do consentimento de longa duração e deverá ser um URN - Uniform Resource Name.   Um URN, conforme definido na [RFC8141](https://tools.ietf.org/html/rfc8141) é um Uniform Resource  Identifier - URI - que é atribuído sob o URI scheme \&quot;urn\&quot; e um namespace URN específico, com a intenção de que o URN  seja um identificador de recurso persistente e independe da localização.   Considerando a string urn:bancoex:C1DD33123 como exemplo para &#x60;recurringConsentId&#x60; temos: - o namespace(urn) - o identificador associado ao namespace da instituição detentora (bancoex). - o identificador específico dentro do namespace (C1DD33123).   Informações mais detalhadas sobre a construção de namespaces devem ser consultadas na [RFC8141](https://tools.ietf.org/html/rfc8141). | |
| **x_idempotency_key** | **string**| Cabeçalho HTTP personalizado. Identificador de solicitação exclusivo para suportar a idempotência. | |
| **patch_recurring_consent** | [**\OpenAPI\Client\Model\PatchRecurringConsent**](../Model/PatchRecurringConsent.md)| Payload para criação do consentimento para iniciação do pagamento. | |
| **x_customer_user_agent** | **string**| Indica o user-agent que o usuário utiliza. | [optional] |
| **x_fapi_auth_date** | **string**| Data em que o usuário logou pela última vez com o iniciador. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC | [optional] |
| **x_fapi_customer_ip_address** | **string**| O endereço IP do usuário se estiver atualmente logado com o iniciador. | [optional] |

### Return type

[**\OpenAPI\Client\Model\ResponseRecurringConsentPatch**](../Model/ResponseRecurringConsentPatch.md)

### Authorization

[OAuth2ClientCredentials](../../README.md#OAuth2ClientCredentials)

### HTTP request headers

- **Content-Type**: `application/jwt`
- **Accept**: `application/jwt`, `application/json; charset=utf-8`, `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `automaticPaymentsPostRecurringConsents()`

```php
automaticPaymentsPostRecurringConsents($authorization, $x_fapi_interaction_id, $x_idempotency_key, $create_recurring_consent, $x_customer_user_agent, $x_fapi_auth_date, $x_fapi_customer_ip_address): \OpenAPI\Client\Model\ResponsePostRecurringConsent
```

Cria um consentimento para transações de pagamentos.

Método para criação de consentimento de longa duração. Retorna um `recurringConsentId` no status AWAITING_AUTHORISATION.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2ClientCredentials
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\RecurringConsentsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$authorization = 'authorization_example'; // string | Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado
$x_fapi_interaction_id = d78fc4e5-37ca-4da3-adf2-9b082bf92280; // string | Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser “espelhado” pela detentora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. A iniciadora deve acatar o valor recebido da detentora..
$x_idempotency_key = 'x_idempotency_key_example'; // string | Cabeçalho HTTP personalizado. Identificador de solicitação exclusivo para suportar a idempotência.
$create_recurring_consent = new \OpenAPI\Client\Model\CreateRecurringConsent(); // \OpenAPI\Client\Model\CreateRecurringConsent | Payload para criação do consentimento para iniciação do pagamento.
$x_customer_user_agent = 'x_customer_user_agent_example'; // string | Indica o user-agent que o usuário utiliza.
$x_fapi_auth_date = 'x_fapi_auth_date_example'; // string | Data em que o usuário logou pela última vez com o iniciador. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC
$x_fapi_customer_ip_address = 'x_fapi_customer_ip_address_example'; // string | O endereço IP do usuário se estiver atualmente logado com o iniciador.

try {
    $result = $apiInstance->automaticPaymentsPostRecurringConsents($authorization, $x_fapi_interaction_id, $x_idempotency_key, $create_recurring_consent, $x_customer_user_agent, $x_fapi_auth_date, $x_fapi_customer_ip_address);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling RecurringConsentsApi->automaticPaymentsPostRecurringConsents: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **authorization** | **string**| Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado | |
| **x_fapi_interaction_id** | **string**| Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser “espelhado” pela detentora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. A iniciadora deve acatar o valor recebido da detentora.. | |
| **x_idempotency_key** | **string**| Cabeçalho HTTP personalizado. Identificador de solicitação exclusivo para suportar a idempotência. | |
| **create_recurring_consent** | [**\OpenAPI\Client\Model\CreateRecurringConsent**](../Model/CreateRecurringConsent.md)| Payload para criação do consentimento para iniciação do pagamento. | |
| **x_customer_user_agent** | **string**| Indica o user-agent que o usuário utiliza. | [optional] |
| **x_fapi_auth_date** | **string**| Data em que o usuário logou pela última vez com o iniciador. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC | [optional] |
| **x_fapi_customer_ip_address** | **string**| O endereço IP do usuário se estiver atualmente logado com o iniciador. | [optional] |

### Return type

[**\OpenAPI\Client\Model\ResponsePostRecurringConsent**](../Model/ResponsePostRecurringConsent.md)

### Authorization

[OAuth2ClientCredentials](../../README.md#OAuth2ClientCredentials)

### HTTP request headers

- **Content-Type**: `application/jwt`
- **Accept**: `application/jwt`, `application/json; charset=utf-8`, `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
