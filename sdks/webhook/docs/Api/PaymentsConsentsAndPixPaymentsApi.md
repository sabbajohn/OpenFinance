# OpenAPI\Client\PaymentsConsentsAndPixPaymentsApi



All URIs are relative to https://api.banco.com.br/open-banking/webhook/v1, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**consentNotification()**](PaymentsConsentsAndPixPaymentsApi.md#consentNotification) | **POST** /payments/{versionApi}/consents/{consentId} | Notificações de mudanças de estados de consentimentos da API de Iniciação de Pagamentos. |
| [**pixPaymentNotification()**](PaymentsConsentsAndPixPaymentsApi.md#pixPaymentNotification) | **POST** /payments/{versionApi}/pix/payments/{paymentId} | Notificações de mudanças de estados do pagamento: Arranjo Pix da API de Iniciação de Pagamentos. |


## `consentNotification()`

```php
consentNotification($consent_id, $version_api, $x_webhook_interaction_id, $request_body_webhook)
```

Notificações de mudanças de estados de consentimentos da API de Iniciação de Pagamentos.

Notificações de mudanças de estados de consentimentos da API de Iniciação de Pagamentos.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\PaymentsConsentsAndPixPaymentsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$consent_id = 'consent_id_example'; // string | O consentId é o identificador único do consentimento e deverá ser um URN - Uniform Resource Name.   Um URN, conforme definido na [RFC8141](https://tools.ietf.org/html/rfc8141) é um Uniform Resource  Identifier - URI - que é atribuído sob o URI scheme \"urn\" e um namespace URN específico, com a intenção de que o URN  seja um identificador de recurso persistente e independente da localização.   Considerando a string urn:bancoex:C1DD33123 como exemplo para consentId temos: - o namespace(urn) - o identificador associado ao namespace da instituição transnmissora (bancoex)  - o identificador específico dentro do namespace (C1DD33123).   Informações mais detalhadas sobre a construção de namespaces devem ser consultadas na [RFC8141](https://tools.ietf.org/html/rfc8141).
$version_api = 'version_api_example'; // string | Identifica a versão da API que deverá ser utilizada para recebimento da notificação via webhook
$x_webhook_interaction_id = 'x_webhook_interaction_id_example'; // string | Identificador único para cada tentativa de notificação realizada. O identificador deverá seguir o padrão UID [RFC4122](https://tools.ietf.org/html/rfc4122).
$request_body_webhook = new \OpenAPI\Client\Model\RequestBodyWebhook(); // \OpenAPI\Client\Model\RequestBodyWebhook | Payload enviado para notificar a alteração no estado do consentimento.

try {
    $apiInstance->consentNotification($consent_id, $version_api, $x_webhook_interaction_id, $request_body_webhook);
} catch (Exception $e) {
    echo 'Exception when calling PaymentsConsentsAndPixPaymentsApi->consentNotification: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **consent_id** | **string**| O consentId é o identificador único do consentimento e deverá ser um URN - Uniform Resource Name.   Um URN, conforme definido na [RFC8141](https://tools.ietf.org/html/rfc8141) é um Uniform Resource  Identifier - URI - que é atribuído sob o URI scheme \&quot;urn\&quot; e um namespace URN específico, com a intenção de que o URN  seja um identificador de recurso persistente e independente da localização.   Considerando a string urn:bancoex:C1DD33123 como exemplo para consentId temos: - o namespace(urn) - o identificador associado ao namespace da instituição transnmissora (bancoex)  - o identificador específico dentro do namespace (C1DD33123).   Informações mais detalhadas sobre a construção de namespaces devem ser consultadas na [RFC8141](https://tools.ietf.org/html/rfc8141). | |
| **version_api** | **string**| Identifica a versão da API que deverá ser utilizada para recebimento da notificação via webhook | |
| **x_webhook_interaction_id** | **string**| Identificador único para cada tentativa de notificação realizada. O identificador deverá seguir o padrão UID [RFC4122](https://tools.ietf.org/html/rfc4122). | |
| **request_body_webhook** | [**\OpenAPI\Client\Model\RequestBodyWebhook**](../Model/RequestBodyWebhook.md)| Payload enviado para notificar a alteração no estado do consentimento. | |

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

## `pixPaymentNotification()`

```php
pixPaymentNotification($payment_id, $version_api, $x_webhook_interaction_id, $request_body_webhook)
```

Notificações de mudanças de estados do pagamento: Arranjo Pix da API de Iniciação de Pagamentos.

Notificações de mudanças de estados do pagamento: Arranjo Pix da API de Iniciação de Pagamentos.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\PaymentsConsentsAndPixPaymentsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$payment_id = 'payment_id_example'; // string | Identificador da operação de pagamento.
$version_api = 'version_api_example'; // string | Identifica a versão da API que deverá ser utilizada para recebimento da notificação via webhook
$x_webhook_interaction_id = 'x_webhook_interaction_id_example'; // string | Identificador único para cada tentativa de notificação realizada. O identificador deverá seguir o padrão UID [RFC4122](https://tools.ietf.org/html/rfc4122).
$request_body_webhook = new \OpenAPI\Client\Model\RequestBodyWebhook(); // \OpenAPI\Client\Model\RequestBodyWebhook | Payload enviado para notificar a alteração no estado do pagamento.

try {
    $apiInstance->pixPaymentNotification($payment_id, $version_api, $x_webhook_interaction_id, $request_body_webhook);
} catch (Exception $e) {
    echo 'Exception when calling PaymentsConsentsAndPixPaymentsApi->pixPaymentNotification: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **payment_id** | **string**| Identificador da operação de pagamento. | |
| **version_api** | **string**| Identifica a versão da API que deverá ser utilizada para recebimento da notificação via webhook | |
| **x_webhook_interaction_id** | **string**| Identificador único para cada tentativa de notificação realizada. O identificador deverá seguir o padrão UID [RFC4122](https://tools.ietf.org/html/rfc4122). | |
| **request_body_webhook** | [**\OpenAPI\Client\Model\RequestBodyWebhook**](../Model/RequestBodyWebhook.md)| Payload enviado para notificar a alteração no estado do pagamento. | |

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
