# OpenAPI\Client\NoRedirectEnrollmentIdNotificationApi



All URIs are relative to https://api.banco.com.br/open-banking/webhook/v1, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**enrollmentIdNotification()**](NoRedirectEnrollmentIdNotificationApi.md#enrollmentIdNotification) | **POST** /enrollments/{versionApi}/enrollments/{enrollmentId} | Notificações de mudanças de estados do vínculo de conta da API - Pagamentos sem Redirecionamento. |


## `enrollmentIdNotification()`

```php
enrollmentIdNotification($enrollment_id, $version_api, $x_webhook_interaction_id, $request_body_webhook)
```

Notificações de mudanças de estados do vínculo de conta da API - Pagamentos sem Redirecionamento.

Notificações de mudanças de estados do vínculo de conta da API - Pagamentos sem Redirecionamento.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\NoRedirectEnrollmentIdNotificationApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$enrollment_id = 'enrollment_id_example'; // string | Identificador único do vínculo de conta criado para a iniciação de pagamento solicitada. Deverá ser um URN - Uniform Resource Name. Um URN, conforme definido na [RFC8141](https://datatracker.ietf.org/doc/html/rfc8141) é um Uniform Resource Identifier - URI - que é atribuído sob o URI scheme \"urn\" e um namespace URN específico, com a intenção de que o URN seja um identificador de recurso persistente e independente da localização.
$version_api = 'version_api_example'; // string | Identifica a versão da API que deverá ser utilizada para recebimento da notificação via webhook
$x_webhook_interaction_id = 'x_webhook_interaction_id_example'; // string | Identificador único para cada tentativa de notificação realizada. Caso haja retentativas de notificação para o mesmo evento, este identificador não poderá ser reaproveitado da notificação anterior. O identificador deverá seguir o padrão UUID [RFC4122](https://tools.ietf.org/html/rfc4122).
$request_body_webhook = new \OpenAPI\Client\Model\RequestBodyWebhook(); // \OpenAPI\Client\Model\RequestBodyWebhook | Payload enviado para notificar a alteração no estado do vínculo.

try {
    $apiInstance->enrollmentIdNotification($enrollment_id, $version_api, $x_webhook_interaction_id, $request_body_webhook);
} catch (Exception $e) {
    echo 'Exception when calling NoRedirectEnrollmentIdNotificationApi->enrollmentIdNotification: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **enrollment_id** | **string**| Identificador único do vínculo de conta criado para a iniciação de pagamento solicitada. Deverá ser um URN - Uniform Resource Name. Um URN, conforme definido na [RFC8141](https://datatracker.ietf.org/doc/html/rfc8141) é um Uniform Resource Identifier - URI - que é atribuído sob o URI scheme \&quot;urn\&quot; e um namespace URN específico, com a intenção de que o URN seja um identificador de recurso persistente e independente da localização. | |
| **version_api** | **string**| Identifica a versão da API que deverá ser utilizada para recebimento da notificação via webhook | |
| **x_webhook_interaction_id** | **string**| Identificador único para cada tentativa de notificação realizada. Caso haja retentativas de notificação para o mesmo evento, este identificador não poderá ser reaproveitado da notificação anterior. O identificador deverá seguir o padrão UUID [RFC4122](https://tools.ietf.org/html/rfc4122). | |
| **request_body_webhook** | [**\OpenAPI\Client\Model\RequestBodyWebhook**](../Model/RequestBodyWebhook.md)| Payload enviado para notificar a alteração no estado do vínculo. | |

### Return type

void (empty response body)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: Not defined

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
