# OpenAPI\Client\WebhookCobRApi

Reúne endpoints para gerenciamento de notificações de cobranças recorrentes por parte do PSP recebedor ao usuário recebedor.

All URIs are relative to https://pix.example.com/api, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**webhookcobrDelete()**](WebhookCobRApi.md#webhookcobrDelete) | **DELETE** /webhookcobr | Cancelar o Webhook. |
| [**webhookcobrGet()**](WebhookCobRApi.md#webhookcobrGet) | **GET** /webhookcobr | Exibir informações acerca do Webhook. |
| [**webhookcobrPut()**](WebhookCobRApi.md#webhookcobrPut) | **PUT** /webhookcobr | Configurar Webhook. |


## `webhookcobrDelete()`

```php
webhookcobrDelete()
```

Cancelar o Webhook.

Endpoint para cancelamento do webhook. Não é a única forma pela qual um webhook pode ser removido.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\WebhookCobRApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);

try {
    $apiInstance->webhookcobrDelete();
} catch (Exception $e) {
    echo 'Exception when calling WebhookCobRApi->webhookcobrDelete: ', $e->getMessage(), PHP_EOL;
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

## `webhookcobrGet()`

```php
webhookcobrGet(): \OpenAPI\Client\Model\WebhookCobRCompleto
```

Exibir informações acerca do Webhook.

Endpoint para recuperação de informações sobre o Webhook.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\WebhookCobRApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);

try {
    $result = $apiInstance->webhookcobrGet();
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling WebhookCobRApi->webhookcobrGet: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

This endpoint does not need any parameter.

### Return type

[**\OpenAPI\Client\Model\WebhookCobRCompleto**](../Model/WebhookCobRCompleto.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `webhookcobrPut()`

```php
webhookcobrPut($webhook_cob_r_solicitado)
```

Configurar Webhook.

Endpoint para configuração do serviço de notificações acerca de cobranças recorrentes. Somente cobranças recorrentes associadas ao usuário recebedor serão notificadas.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\WebhookCobRApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$webhook_cob_r_solicitado = new \OpenAPI\Client\Model\WebhookCobRSolicitado(); // \OpenAPI\Client\Model\WebhookCobRSolicitado

try {
    $apiInstance->webhookcobrPut($webhook_cob_r_solicitado);
} catch (Exception $e) {
    echo 'Exception when calling WebhookCobRApi->webhookcobrPut: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **webhook_cob_r_solicitado** | [**\OpenAPI\Client\Model\WebhookCobRSolicitado**](../Model/WebhookCobRSolicitado.md)|  | |

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
