# OpenAPI\Client\WebhookRecApi

Reúne endpoints para gerenciamento de notificações de recorrências por parte do PSP recebedor ao usuário recebedor.

All URIs are relative to https://pix.example.com/api, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**webhookrecDelete()**](WebhookRecApi.md#webhookrecDelete) | **DELETE** /webhookrec | Cancelar o Webhook. |
| [**webhookrecGet()**](WebhookRecApi.md#webhookrecGet) | **GET** /webhookrec | Exibir informações acerca do Webhook. |
| [**webhookrecPut()**](WebhookRecApi.md#webhookrecPut) | **PUT** /webhookrec | Configurar Webhook. |


## `webhookrecDelete()`

```php
webhookrecDelete()
```

Cancelar o Webhook.

Endpoint para cancelamento do webhook. Não é a única forma pela qual um webhook pode ser removido.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\WebhookRecApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);

try {
    $apiInstance->webhookrecDelete();
} catch (Exception $e) {
    echo 'Exception when calling WebhookRecApi->webhookrecDelete: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

This endpoint does not need any parameter.

### Return type

void (empty response body)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `webhookrecGet()`

```php
webhookrecGet(): \OpenAPI\Client\Model\WebhookRecCompleto
```

Exibir informações acerca do Webhook.

Endpoint para recuperação de informações sobre o Webhook.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\WebhookRecApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);

try {
    $result = $apiInstance->webhookrecGet();
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling WebhookRecApi->webhookrecGet: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

This endpoint does not need any parameter.

### Return type

[**\OpenAPI\Client\Model\WebhookRecCompleto**](../Model/WebhookRecCompleto.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `webhookrecPut()`

```php
webhookrecPut($webhook_rec_solicitado)
```

Configurar Webhook.

Endpoint para configuração do serviço de notificações acerca de recorrências. Somente recorrências associadas ao usuário recebedor serão notificadas.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\WebhookRecApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$webhook_rec_solicitado = new \OpenAPI\Client\Model\WebhookRecSolicitado(); // \OpenAPI\Client\Model\WebhookRecSolicitado

try {
    $apiInstance->webhookrecPut($webhook_rec_solicitado);
} catch (Exception $e) {
    echo 'Exception when calling WebhookRecApi->webhookrecPut: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **webhook_rec_solicitado** | [**\OpenAPI\Client\Model\WebhookRecSolicitado**](../Model/WebhookRecSolicitado.md)|  | |

### Return type

void (empty response body)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
