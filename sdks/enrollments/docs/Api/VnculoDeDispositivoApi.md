# OpenAPI\Client\VnculoDeDispositivoApi



All URIs are relative to https://mtls-api.banco.com.br/open-banking/enrollments/v2, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**deleteEnrollment()**](VnculoDeDispositivoApi.md#deleteEnrollment) | **PATCH** /enrollments/{enrollmentId} | Revogar ou rejeitar vínculo de conta. |
| [**enrollmentCreateFidoRegistrationOptions()**](VnculoDeDispositivoApi.md#enrollmentCreateFidoRegistrationOptions) | **POST** /enrollments/{enrollmentId}/fido-registration-options | Obter parâmetros para criação de credenciais FIDO2. |
| [**enrollmentCreateFidoSigningOptions()**](VnculoDeDispositivoApi.md#enrollmentCreateFidoSigningOptions) | **POST** /enrollments/{enrollmentId}/fido-sign-options | Obter parâmetros para autenticação FIDO2. |
| [**enrollmentRegisterFidoCredential()**](VnculoDeDispositivoApi.md#enrollmentRegisterFidoCredential) | **POST** /enrollments/{enrollmentId}/fido-registration | Associação da credencial FIDO2 ao vínculo de conta. |
| [**getEnrollment()**](VnculoDeDispositivoApi.md#getEnrollment) | **GET** /enrollments/{enrollmentId} | Consultar vínculo de conta. |
| [**postEnrollments()**](VnculoDeDispositivoApi.md#postEnrollments) | **POST** /enrollments | Criar vínculo de conta. |
| [**riskSignals()**](VnculoDeDispositivoApi.md#riskSignals) | **POST** /enrollments/{enrollmentId}/risk-signals | Envio de sinais de risco para iniciação do vínculo de dispositivo |


## `deleteEnrollment()`

```php
deleteEnrollment($enrollment_id, $authorization, $x_fapi_interaction_id, $x_idempotency_key, $delete_enrollment_request, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent)
```

Revogar ou rejeitar vínculo de conta.

Método para revogar ou rejeitar um vínculo de conta. Após a execução com sucesso deste método irreversível, o vínculo de conta não poderá mais ser utilizado para autenticação nem autorização de iniciação de pagamentos. Os conceitos de revogação e rejeição estão associados à máquina de estados do vínculo de conta\\:  • Revogação: Cancelamento de um vínculo de conta no status \"AUTHORISED\";  • Rejeição: Cancelamento do vínculo de conta nos status \"AWATING_RISK_SIGNALS\", \"AWATING_ENROLLMENT\" e \"AWAITING_ACCOUNT_HOLDER_VALIDATION\"  Cabe ao cliente desta API distinguir entre os dois cenários para determinar o motivo adequado.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2ClientCredentials
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\VnculoDeDispositivoApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$enrollment_id = 'enrollment_id_example'; // string | Identificador único do vínculo de conta criado para a iniciação de pagamento solicitada. Deverá ser um URN - Uniform Resource Name. Um URN, conforme definido na [RFC8141](https://tools.ietf.org/html/rfc8141) é um Uniform Resource Identifier - URI - que é atribuído sob o URI scheme \"urn\" e um namespace URN específico, com a intenção de que o URN seja um identificador de recurso persistente e independente da localização. Considerando a string urn:bancoex:C1DD33123 como exemplo para enrollmentId temos: - o namespace(urn) - o identificador associado ao namespace da instituição detentora de conta (bancoex) - o identificador específico dentro do namespace (C1DD33123). Informações mais detalhadas sobre a construção de namespaces devem ser consultadas na [RFC8141](https://tools.ietf.org/html/rfc8141).
$authorization = 'authorization_example'; // string | Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado
$x_fapi_interaction_id = d78fc4e5-37ca-4da3-adf2-9b082bf92280; // string | Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser \"espelhado\" pela detentora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. A iniciadora deve acatar o valor recebido da detentora.
$x_idempotency_key = 'x_idempotency_key_example'; // string | Cabeçalho HTTP personalizado. Identificador de solicitação exclusivo para suportar a idempotência.
$delete_enrollment_request = new \OpenAPI\Client\Model\DeleteEnrollmentRequest(); // \OpenAPI\Client\Model\DeleteEnrollmentRequest | Dados para regovação do vínculo de conta.
$x_fapi_auth_date = 'x_fapi_auth_date_example'; // string | Data em que o usuário logou pela última vez com a iniciadora. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC
$x_fapi_customer_ip_address = 'x_fapi_customer_ip_address_example'; // string | O endereço IP do usuário se estiver atualmente logado com a iniciadora.
$x_customer_user_agent = 'x_customer_user_agent_example'; // string | Indica o user-agent que o usuário utiliza.

try {
    $apiInstance->deleteEnrollment($enrollment_id, $authorization, $x_fapi_interaction_id, $x_idempotency_key, $delete_enrollment_request, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent);
} catch (Exception $e) {
    echo 'Exception when calling VnculoDeDispositivoApi->deleteEnrollment: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **enrollment_id** | **string**| Identificador único do vínculo de conta criado para a iniciação de pagamento solicitada. Deverá ser um URN - Uniform Resource Name. Um URN, conforme definido na [RFC8141](https://tools.ietf.org/html/rfc8141) é um Uniform Resource Identifier - URI - que é atribuído sob o URI scheme \&quot;urn\&quot; e um namespace URN específico, com a intenção de que o URN seja um identificador de recurso persistente e independente da localização. Considerando a string urn:bancoex:C1DD33123 como exemplo para enrollmentId temos: - o namespace(urn) - o identificador associado ao namespace da instituição detentora de conta (bancoex) - o identificador específico dentro do namespace (C1DD33123). Informações mais detalhadas sobre a construção de namespaces devem ser consultadas na [RFC8141](https://tools.ietf.org/html/rfc8141). | |
| **authorization** | **string**| Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado | |
| **x_fapi_interaction_id** | **string**| Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser \&quot;espelhado\&quot; pela detentora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. A iniciadora deve acatar o valor recebido da detentora. | |
| **x_idempotency_key** | **string**| Cabeçalho HTTP personalizado. Identificador de solicitação exclusivo para suportar a idempotência. | |
| **delete_enrollment_request** | [**\OpenAPI\Client\Model\DeleteEnrollmentRequest**](../Model/DeleteEnrollmentRequest.md)| Dados para regovação do vínculo de conta. | |
| **x_fapi_auth_date** | **string**| Data em que o usuário logou pela última vez com a iniciadora. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC | [optional] |
| **x_fapi_customer_ip_address** | **string**| O endereço IP do usuário se estiver atualmente logado com a iniciadora. | [optional] |
| **x_customer_user_agent** | **string**| Indica o user-agent que o usuário utiliza. | [optional] |

### Return type

void (empty response body)

### Authorization

[OAuth2ClientCredentials](../../README.md#OAuth2ClientCredentials)

### HTTP request headers

- **Content-Type**: `application/jwt`
- **Accept**: `application/json; charset=utf-8`, `application/jwt`, `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `enrollmentCreateFidoRegistrationOptions()`

```php
enrollmentCreateFidoRegistrationOptions($enrollment_id, $authorization, $x_fapi_interaction_id, $x_idempotency_key, $enrollment_fido_options_input, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent): \OpenAPI\Client\Model\EnrollmentFidoRegistrationOptions
```

Obter parâmetros para criação de credenciais FIDO2.

Método para obter os parâmetros para criação de uma nova credencial FIDO2. Um novo challenge deve ser criado a cada chamada. Os parâmetros da resposta devem ser compatíveis com a definição [W3C](https://www.w3.org/TR/webauthn-2/#dictionary-makecredentialoptions)

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2AuthorizationCode
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\VnculoDeDispositivoApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$enrollment_id = 'enrollment_id_example'; // string | Identificador único do vínculo de conta criado para a iniciação de pagamento solicitada. Deverá ser um URN - Uniform Resource Name. Um URN, conforme definido na [RFC8141](https://tools.ietf.org/html/rfc8141) é um Uniform Resource Identifier - URI - que é atribuído sob o URI scheme \"urn\" e um namespace URN específico, com a intenção de que o URN seja um identificador de recurso persistente e independente da localização. Considerando a string urn:bancoex:C1DD33123 como exemplo para enrollmentId temos: - o namespace(urn) - o identificador associado ao namespace da instituição detentora de conta (bancoex) - o identificador específico dentro do namespace (C1DD33123). Informações mais detalhadas sobre a construção de namespaces devem ser consultadas na [RFC8141](https://tools.ietf.org/html/rfc8141).
$authorization = 'authorization_example'; // string | Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado
$x_fapi_interaction_id = d78fc4e5-37ca-4da3-adf2-9b082bf92280; // string | Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser \"espelhado\" pela detentora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. A iniciadora deve acatar o valor recebido da detentora.
$x_idempotency_key = 'x_idempotency_key_example'; // string | Cabeçalho HTTP personalizado. Identificador de solicitação exclusivo para suportar a idempotência.
$enrollment_fido_options_input = new \OpenAPI\Client\Model\EnrollmentFidoOptionsInput(); // \OpenAPI\Client\Model\EnrollmentFidoOptionsInput | Payload para criação de parâmetros de registro de nova credencial FIDO2.
$x_fapi_auth_date = 'x_fapi_auth_date_example'; // string | Data em que o usuário logou pela última vez com a iniciadora. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC
$x_fapi_customer_ip_address = 'x_fapi_customer_ip_address_example'; // string | O endereço IP do usuário se estiver atualmente logado com a iniciadora.
$x_customer_user_agent = 'x_customer_user_agent_example'; // string | Indica o user-agent que o usuário utiliza.

try {
    $result = $apiInstance->enrollmentCreateFidoRegistrationOptions($enrollment_id, $authorization, $x_fapi_interaction_id, $x_idempotency_key, $enrollment_fido_options_input, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling VnculoDeDispositivoApi->enrollmentCreateFidoRegistrationOptions: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **enrollment_id** | **string**| Identificador único do vínculo de conta criado para a iniciação de pagamento solicitada. Deverá ser um URN - Uniform Resource Name. Um URN, conforme definido na [RFC8141](https://tools.ietf.org/html/rfc8141) é um Uniform Resource Identifier - URI - que é atribuído sob o URI scheme \&quot;urn\&quot; e um namespace URN específico, com a intenção de que o URN seja um identificador de recurso persistente e independente da localização. Considerando a string urn:bancoex:C1DD33123 como exemplo para enrollmentId temos: - o namespace(urn) - o identificador associado ao namespace da instituição detentora de conta (bancoex) - o identificador específico dentro do namespace (C1DD33123). Informações mais detalhadas sobre a construção de namespaces devem ser consultadas na [RFC8141](https://tools.ietf.org/html/rfc8141). | |
| **authorization** | **string**| Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado | |
| **x_fapi_interaction_id** | **string**| Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser \&quot;espelhado\&quot; pela detentora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. A iniciadora deve acatar o valor recebido da detentora. | |
| **x_idempotency_key** | **string**| Cabeçalho HTTP personalizado. Identificador de solicitação exclusivo para suportar a idempotência. | |
| **enrollment_fido_options_input** | [**\OpenAPI\Client\Model\EnrollmentFidoOptionsInput**](../Model/EnrollmentFidoOptionsInput.md)| Payload para criação de parâmetros de registro de nova credencial FIDO2. | |
| **x_fapi_auth_date** | **string**| Data em que o usuário logou pela última vez com a iniciadora. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC | [optional] |
| **x_fapi_customer_ip_address** | **string**| O endereço IP do usuário se estiver atualmente logado com a iniciadora. | [optional] |
| **x_customer_user_agent** | **string**| Indica o user-agent que o usuário utiliza. | [optional] |

### Return type

[**\OpenAPI\Client\Model\EnrollmentFidoRegistrationOptions**](../Model/EnrollmentFidoRegistrationOptions.md)

### Authorization

[OAuth2AuthorizationCode](../../README.md#OAuth2AuthorizationCode)

### HTTP request headers

- **Content-Type**: `application/jwt`
- **Accept**: `application/jwt`, `application/json; charset=utf-8`, `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `enrollmentCreateFidoSigningOptions()`

```php
enrollmentCreateFidoSigningOptions($enrollment_id, $authorization, $x_fapi_interaction_id, $x_idempotency_key, $enrollment_create_fido_signing_options_request, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent): \OpenAPI\Client\Model\EnrollmentFidoSignOptions
```

Obter parâmetros para autenticação FIDO2.

Método para obter os parâmetros para autenticação a partir de uma credencial FIDO2 previamente registrada. Um novo challenge deve ser criado a cada chamada. Os parâmetros da resposta devem ser compatíveis com a [definição W3C](https://www.w3.org/TR/webauthn-2/#dictionary-assertion-options)

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2ClientCredentials
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\VnculoDeDispositivoApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$enrollment_id = 'enrollment_id_example'; // string | Identificador único do vínculo de conta criado para a iniciação de pagamento solicitada. Deverá ser um URN - Uniform Resource Name. Um URN, conforme definido na [RFC8141](https://tools.ietf.org/html/rfc8141) é um Uniform Resource Identifier - URI - que é atribuído sob o URI scheme \"urn\" e um namespace URN específico, com a intenção de que o URN seja um identificador de recurso persistente e independente da localização. Considerando a string urn:bancoex:C1DD33123 como exemplo para enrollmentId temos: - o namespace(urn) - o identificador associado ao namespace da instituição detentora de conta (bancoex) - o identificador específico dentro do namespace (C1DD33123). Informações mais detalhadas sobre a construção de namespaces devem ser consultadas na [RFC8141](https://tools.ietf.org/html/rfc8141).
$authorization = 'authorization_example'; // string | Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado
$x_fapi_interaction_id = d78fc4e5-37ca-4da3-adf2-9b082bf92280; // string | Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser \"espelhado\" pela detentora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. A iniciadora deve acatar o valor recebido da detentora.
$x_idempotency_key = 'x_idempotency_key_example'; // string | Cabeçalho HTTP personalizado. Identificador de solicitação exclusivo para suportar a idempotência.
$enrollment_create_fido_signing_options_request = new \OpenAPI\Client\Model\EnrollmentCreateFidoSigningOptionsRequest(); // \OpenAPI\Client\Model\EnrollmentCreateFidoSigningOptionsRequest | Payload para autenticação a partir de uma credencial FIDO2 previamente registrada.
$x_fapi_auth_date = 'x_fapi_auth_date_example'; // string | Data em que o usuário logou pela última vez com a iniciadora. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC
$x_fapi_customer_ip_address = 'x_fapi_customer_ip_address_example'; // string | O endereço IP do usuário se estiver atualmente logado com a iniciadora.
$x_customer_user_agent = 'x_customer_user_agent_example'; // string | Indica o user-agent que o usuário utiliza.

try {
    $result = $apiInstance->enrollmentCreateFidoSigningOptions($enrollment_id, $authorization, $x_fapi_interaction_id, $x_idempotency_key, $enrollment_create_fido_signing_options_request, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling VnculoDeDispositivoApi->enrollmentCreateFidoSigningOptions: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **enrollment_id** | **string**| Identificador único do vínculo de conta criado para a iniciação de pagamento solicitada. Deverá ser um URN - Uniform Resource Name. Um URN, conforme definido na [RFC8141](https://tools.ietf.org/html/rfc8141) é um Uniform Resource Identifier - URI - que é atribuído sob o URI scheme \&quot;urn\&quot; e um namespace URN específico, com a intenção de que o URN seja um identificador de recurso persistente e independente da localização. Considerando a string urn:bancoex:C1DD33123 como exemplo para enrollmentId temos: - o namespace(urn) - o identificador associado ao namespace da instituição detentora de conta (bancoex) - o identificador específico dentro do namespace (C1DD33123). Informações mais detalhadas sobre a construção de namespaces devem ser consultadas na [RFC8141](https://tools.ietf.org/html/rfc8141). | |
| **authorization** | **string**| Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado | |
| **x_fapi_interaction_id** | **string**| Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser \&quot;espelhado\&quot; pela detentora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. A iniciadora deve acatar o valor recebido da detentora. | |
| **x_idempotency_key** | **string**| Cabeçalho HTTP personalizado. Identificador de solicitação exclusivo para suportar a idempotência. | |
| **enrollment_create_fido_signing_options_request** | [**\OpenAPI\Client\Model\EnrollmentCreateFidoSigningOptionsRequest**](../Model/EnrollmentCreateFidoSigningOptionsRequest.md)| Payload para autenticação a partir de uma credencial FIDO2 previamente registrada. | |
| **x_fapi_auth_date** | **string**| Data em que o usuário logou pela última vez com a iniciadora. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC | [optional] |
| **x_fapi_customer_ip_address** | **string**| O endereço IP do usuário se estiver atualmente logado com a iniciadora. | [optional] |
| **x_customer_user_agent** | **string**| Indica o user-agent que o usuário utiliza. | [optional] |

### Return type

[**\OpenAPI\Client\Model\EnrollmentFidoSignOptions**](../Model/EnrollmentFidoSignOptions.md)

### Authorization

[OAuth2ClientCredentials](../../README.md#OAuth2ClientCredentials)

### HTTP request headers

- **Content-Type**: `application/jwt`
- **Accept**: `application/jwt`, `application/json; charset=utf-8`, `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `enrollmentRegisterFidoCredential()`

```php
enrollmentRegisterFidoCredential($enrollment_id, $authorization, $x_fapi_interaction_id, $x_idempotency_key, $enrollment_fido_registration, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent)
```

Associação da credencial FIDO2 ao vínculo de conta.

O vínculo de conta deve estar no status \"AWAITING_ENROLLMENT\".  Após o registro com sucesso, o vínculo de conta deve transitar ao status \"AUTHORISED\". A falha de verificação da credencial FIDO2 deve causar a rejeição do vínculo de conta por parte da detentora, uma vez que não é possível reusar um mesmo \"challenge\" para mais de um registro.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2AuthorizationCode
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\VnculoDeDispositivoApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$enrollment_id = 'enrollment_id_example'; // string | Identificador único do vínculo de conta criado para a iniciação de pagamento solicitada. Deverá ser um URN - Uniform Resource Name. Um URN, conforme definido na [RFC8141](https://tools.ietf.org/html/rfc8141) é um Uniform Resource Identifier - URI - que é atribuído sob o URI scheme \"urn\" e um namespace URN específico, com a intenção de que o URN seja um identificador de recurso persistente e independente da localização. Considerando a string urn:bancoex:C1DD33123 como exemplo para enrollmentId temos: - o namespace(urn) - o identificador associado ao namespace da instituição detentora de conta (bancoex) - o identificador específico dentro do namespace (C1DD33123). Informações mais detalhadas sobre a construção de namespaces devem ser consultadas na [RFC8141](https://tools.ietf.org/html/rfc8141).
$authorization = 'authorization_example'; // string | Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado
$x_fapi_interaction_id = d78fc4e5-37ca-4da3-adf2-9b082bf92280; // string | Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser \"espelhado\" pela detentora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. A iniciadora deve acatar o valor recebido da detentora.
$x_idempotency_key = 'x_idempotency_key_example'; // string | Cabeçalho HTTP personalizado. Identificador de solicitação exclusivo para suportar a idempotência.
$enrollment_fido_registration = new \OpenAPI\Client\Model\EnrollmentFidoRegistration(); // \OpenAPI\Client\Model\EnrollmentFidoRegistration | Payload para criação de parâmetros de registro de nova credencial FIDO2.
$x_fapi_auth_date = 'x_fapi_auth_date_example'; // string | Data em que o usuário logou pela última vez com a iniciadora. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC
$x_fapi_customer_ip_address = 'x_fapi_customer_ip_address_example'; // string | O endereço IP do usuário se estiver atualmente logado com a iniciadora.
$x_customer_user_agent = 'x_customer_user_agent_example'; // string | Indica o user-agent que o usuário utiliza.

try {
    $apiInstance->enrollmentRegisterFidoCredential($enrollment_id, $authorization, $x_fapi_interaction_id, $x_idempotency_key, $enrollment_fido_registration, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent);
} catch (Exception $e) {
    echo 'Exception when calling VnculoDeDispositivoApi->enrollmentRegisterFidoCredential: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **enrollment_id** | **string**| Identificador único do vínculo de conta criado para a iniciação de pagamento solicitada. Deverá ser um URN - Uniform Resource Name. Um URN, conforme definido na [RFC8141](https://tools.ietf.org/html/rfc8141) é um Uniform Resource Identifier - URI - que é atribuído sob o URI scheme \&quot;urn\&quot; e um namespace URN específico, com a intenção de que o URN seja um identificador de recurso persistente e independente da localização. Considerando a string urn:bancoex:C1DD33123 como exemplo para enrollmentId temos: - o namespace(urn) - o identificador associado ao namespace da instituição detentora de conta (bancoex) - o identificador específico dentro do namespace (C1DD33123). Informações mais detalhadas sobre a construção de namespaces devem ser consultadas na [RFC8141](https://tools.ietf.org/html/rfc8141). | |
| **authorization** | **string**| Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado | |
| **x_fapi_interaction_id** | **string**| Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser \&quot;espelhado\&quot; pela detentora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. A iniciadora deve acatar o valor recebido da detentora. | |
| **x_idempotency_key** | **string**| Cabeçalho HTTP personalizado. Identificador de solicitação exclusivo para suportar a idempotência. | |
| **enrollment_fido_registration** | [**\OpenAPI\Client\Model\EnrollmentFidoRegistration**](../Model/EnrollmentFidoRegistration.md)| Payload para criação de parâmetros de registro de nova credencial FIDO2. | |
| **x_fapi_auth_date** | **string**| Data em que o usuário logou pela última vez com a iniciadora. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC | [optional] |
| **x_fapi_customer_ip_address** | **string**| O endereço IP do usuário se estiver atualmente logado com a iniciadora. | [optional] |
| **x_customer_user_agent** | **string**| Indica o user-agent que o usuário utiliza. | [optional] |

### Return type

void (empty response body)

### Authorization

[OAuth2AuthorizationCode](../../README.md#OAuth2AuthorizationCode)

### HTTP request headers

- **Content-Type**: `application/jwt`
- **Accept**: `application/json; charset=utf-8`, `application/jwt`, `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getEnrollment()`

```php
getEnrollment($enrollment_id, $authorization, $x_fapi_interaction_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent): \OpenAPI\Client\Model\ResponseEnrollment
```

Consultar vínculo de conta.

Método para consultar um vínculo de conta para iniciação de pagamento sem redirecionamento.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2ClientCredentials
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\VnculoDeDispositivoApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$enrollment_id = 'enrollment_id_example'; // string | Identificador único do vínculo de conta criado para a iniciação de pagamento solicitada. Deverá ser um URN - Uniform Resource Name. Um URN, conforme definido na [RFC8141](https://tools.ietf.org/html/rfc8141) é um Uniform Resource Identifier - URI - que é atribuído sob o URI scheme \"urn\" e um namespace URN específico, com a intenção de que o URN seja um identificador de recurso persistente e independente da localização. Considerando a string urn:bancoex:C1DD33123 como exemplo para enrollmentId temos: - o namespace(urn) - o identificador associado ao namespace da instituição detentora de conta (bancoex) - o identificador específico dentro do namespace (C1DD33123). Informações mais detalhadas sobre a construção de namespaces devem ser consultadas na [RFC8141](https://tools.ietf.org/html/rfc8141).
$authorization = 'authorization_example'; // string | Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado
$x_fapi_interaction_id = d78fc4e5-37ca-4da3-adf2-9b082bf92280; // string | Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser \"espelhado\" pela detentora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. A iniciadora deve acatar o valor recebido da detentora.
$x_fapi_auth_date = 'x_fapi_auth_date_example'; // string | Data em que o usuário logou pela última vez com a iniciadora. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC
$x_fapi_customer_ip_address = 'x_fapi_customer_ip_address_example'; // string | O endereço IP do usuário se estiver atualmente logado com a iniciadora.
$x_customer_user_agent = 'x_customer_user_agent_example'; // string | Indica o user-agent que o usuário utiliza.

try {
    $result = $apiInstance->getEnrollment($enrollment_id, $authorization, $x_fapi_interaction_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling VnculoDeDispositivoApi->getEnrollment: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **enrollment_id** | **string**| Identificador único do vínculo de conta criado para a iniciação de pagamento solicitada. Deverá ser um URN - Uniform Resource Name. Um URN, conforme definido na [RFC8141](https://tools.ietf.org/html/rfc8141) é um Uniform Resource Identifier - URI - que é atribuído sob o URI scheme \&quot;urn\&quot; e um namespace URN específico, com a intenção de que o URN seja um identificador de recurso persistente e independente da localização. Considerando a string urn:bancoex:C1DD33123 como exemplo para enrollmentId temos: - o namespace(urn) - o identificador associado ao namespace da instituição detentora de conta (bancoex) - o identificador específico dentro do namespace (C1DD33123). Informações mais detalhadas sobre a construção de namespaces devem ser consultadas na [RFC8141](https://tools.ietf.org/html/rfc8141). | |
| **authorization** | **string**| Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado | |
| **x_fapi_interaction_id** | **string**| Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser \&quot;espelhado\&quot; pela detentora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. A iniciadora deve acatar o valor recebido da detentora. | |
| **x_fapi_auth_date** | **string**| Data em que o usuário logou pela última vez com a iniciadora. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC | [optional] |
| **x_fapi_customer_ip_address** | **string**| O endereço IP do usuário se estiver atualmente logado com a iniciadora. | [optional] |
| **x_customer_user_agent** | **string**| Indica o user-agent que o usuário utiliza. | [optional] |

### Return type

[**\OpenAPI\Client\Model\ResponseEnrollment**](../Model/ResponseEnrollment.md)

### Authorization

[OAuth2ClientCredentials](../../README.md#OAuth2ClientCredentials)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/jwt`, `application/json; charset=utf-8`, `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `postEnrollments()`

```php
postEnrollments($authorization, $x_fapi_interaction_id, $x_idempotency_key, $create_enrollment, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent): \OpenAPI\Client\Model\ResponseCreateEnrollment
```

Criar vínculo de conta.

Método para criar um novo vínculo de conta. Retorna um enrollment em status AWAITING_RISK_SIGNALS.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2ClientCredentials
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\VnculoDeDispositivoApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$authorization = 'authorization_example'; // string | Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado
$x_fapi_interaction_id = d78fc4e5-37ca-4da3-adf2-9b082bf92280; // string | Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser \"espelhado\" pela detentora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. A iniciadora deve acatar o valor recebido da detentora.
$x_idempotency_key = 'x_idempotency_key_example'; // string | Cabeçalho HTTP personalizado. Identificador de solicitação exclusivo para suportar a idempotência.
$create_enrollment = new \OpenAPI\Client\Model\CreateEnrollment(); // \OpenAPI\Client\Model\CreateEnrollment | Payload para criação de vínculo de conta.
$x_fapi_auth_date = 'x_fapi_auth_date_example'; // string | Data em que o usuário logou pela última vez com a iniciadora. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC
$x_fapi_customer_ip_address = 'x_fapi_customer_ip_address_example'; // string | O endereço IP do usuário se estiver atualmente logado com a iniciadora.
$x_customer_user_agent = 'x_customer_user_agent_example'; // string | Indica o user-agent que o usuário utiliza.

try {
    $result = $apiInstance->postEnrollments($authorization, $x_fapi_interaction_id, $x_idempotency_key, $create_enrollment, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling VnculoDeDispositivoApi->postEnrollments: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **authorization** | **string**| Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado | |
| **x_fapi_interaction_id** | **string**| Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser \&quot;espelhado\&quot; pela detentora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. A iniciadora deve acatar o valor recebido da detentora. | |
| **x_idempotency_key** | **string**| Cabeçalho HTTP personalizado. Identificador de solicitação exclusivo para suportar a idempotência. | |
| **create_enrollment** | [**\OpenAPI\Client\Model\CreateEnrollment**](../Model/CreateEnrollment.md)| Payload para criação de vínculo de conta. | |
| **x_fapi_auth_date** | **string**| Data em que o usuário logou pela última vez com a iniciadora. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC | [optional] |
| **x_fapi_customer_ip_address** | **string**| O endereço IP do usuário se estiver atualmente logado com a iniciadora. | [optional] |
| **x_customer_user_agent** | **string**| Indica o user-agent que o usuário utiliza. | [optional] |

### Return type

[**\OpenAPI\Client\Model\ResponseCreateEnrollment**](../Model/ResponseCreateEnrollment.md)

### Authorization

[OAuth2ClientCredentials](../../README.md#OAuth2ClientCredentials)

### HTTP request headers

- **Content-Type**: `application/jwt`
- **Accept**: `application/jwt`, `application/json; charset=utf-8`, `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `riskSignals()`

```php
riskSignals($enrollment_id, $authorization, $x_fapi_interaction_id, $x_idempotency_key, $risk_signals, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent)
```

Envio de sinais de risco para iniciação do vínculo de dispositivo

Envio de sinais de risco para iniciação do vínculo de dispositivo, o status do enrollment deve estar em `AWAITING_RISK_SIGNALS`. Após recebimento com sucesso dos sinais, o status do enrollment deve transitar para `AWAITING_ACCOUNT_HOLDER_VALIDATION`.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2ClientCredentials
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\VnculoDeDispositivoApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$enrollment_id = 'enrollment_id_example'; // string | Identificador único do vínculo de conta criado para a iniciação de pagamento solicitada. Deverá ser um URN - Uniform Resource Name. Um URN, conforme definido na [RFC8141](https://tools.ietf.org/html/rfc8141) é um Uniform Resource Identifier - URI - que é atribuído sob o URI scheme \"urn\" e um namespace URN específico, com a intenção de que o URN seja um identificador de recurso persistente e independente da localização. Considerando a string urn:bancoex:C1DD33123 como exemplo para enrollmentId temos: - o namespace(urn) - o identificador associado ao namespace da instituição detentora de conta (bancoex) - o identificador específico dentro do namespace (C1DD33123). Informações mais detalhadas sobre a construção de namespaces devem ser consultadas na [RFC8141](https://tools.ietf.org/html/rfc8141).
$authorization = 'authorization_example'; // string | Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado
$x_fapi_interaction_id = d78fc4e5-37ca-4da3-adf2-9b082bf92280; // string | Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser \"espelhado\" pela detentora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. A iniciadora deve acatar o valor recebido da detentora.
$x_idempotency_key = 'x_idempotency_key_example'; // string | Cabeçalho HTTP personalizado. Identificador de solicitação exclusivo para suportar a idempotência.
$risk_signals = new \OpenAPI\Client\Model\RiskSignals(); // \OpenAPI\Client\Model\RiskSignals | Payload para criação de vínculo de conta.
$x_fapi_auth_date = 'x_fapi_auth_date_example'; // string | Data em que o usuário logou pela última vez com a iniciadora. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC
$x_fapi_customer_ip_address = 'x_fapi_customer_ip_address_example'; // string | O endereço IP do usuário se estiver atualmente logado com a iniciadora.
$x_customer_user_agent = 'x_customer_user_agent_example'; // string | Indica o user-agent que o usuário utiliza.

try {
    $apiInstance->riskSignals($enrollment_id, $authorization, $x_fapi_interaction_id, $x_idempotency_key, $risk_signals, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent);
} catch (Exception $e) {
    echo 'Exception when calling VnculoDeDispositivoApi->riskSignals: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **enrollment_id** | **string**| Identificador único do vínculo de conta criado para a iniciação de pagamento solicitada. Deverá ser um URN - Uniform Resource Name. Um URN, conforme definido na [RFC8141](https://tools.ietf.org/html/rfc8141) é um Uniform Resource Identifier - URI - que é atribuído sob o URI scheme \&quot;urn\&quot; e um namespace URN específico, com a intenção de que o URN seja um identificador de recurso persistente e independente da localização. Considerando a string urn:bancoex:C1DD33123 como exemplo para enrollmentId temos: - o namespace(urn) - o identificador associado ao namespace da instituição detentora de conta (bancoex) - o identificador específico dentro do namespace (C1DD33123). Informações mais detalhadas sobre a construção de namespaces devem ser consultadas na [RFC8141](https://tools.ietf.org/html/rfc8141). | |
| **authorization** | **string**| Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado | |
| **x_fapi_interaction_id** | **string**| Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser \&quot;espelhado\&quot; pela detentora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. A iniciadora deve acatar o valor recebido da detentora. | |
| **x_idempotency_key** | **string**| Cabeçalho HTTP personalizado. Identificador de solicitação exclusivo para suportar a idempotência. | |
| **risk_signals** | [**\OpenAPI\Client\Model\RiskSignals**](../Model/RiskSignals.md)| Payload para criação de vínculo de conta. | |
| **x_fapi_auth_date** | **string**| Data em que o usuário logou pela última vez com a iniciadora. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC | [optional] |
| **x_fapi_customer_ip_address** | **string**| O endereço IP do usuário se estiver atualmente logado com a iniciadora. | [optional] |
| **x_customer_user_agent** | **string**| Indica o user-agent que o usuário utiliza. | [optional] |

### Return type

void (empty response body)

### Authorization

[OAuth2ClientCredentials](../../README.md#OAuth2ClientCredentials)

### HTTP request headers

- **Content-Type**: `application/jwt`
- **Accept**: `application/json; charset=utf-8`, `application/jwt`, `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
