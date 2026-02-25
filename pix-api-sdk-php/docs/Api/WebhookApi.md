# OpenAPI\Client\WebhookApi

Reúne endpoints para gerenciamento de notificações por parte do PSP recebedor ao usuário recebedor.

All URIs are relative to https://pix.example.com/api, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**webhookChaveDelete()**](WebhookApi.md#webhookChaveDelete) | **DELETE** /webhook/{chave} | Cancelar o webhook Pix. |
| [**webhookChaveGet()**](WebhookApi.md#webhookChaveGet) | **GET** /webhook/{chave} | Exibir informações acerca do Webhook Pix. |
| [**webhookChavePut()**](WebhookApi.md#webhookChavePut) | **PUT** /webhook/{chave} | Configurar o Webhook Pix. |
| [**webhookGet()**](WebhookApi.md#webhookGet) | **GET** /webhook | Consultar webhooks cadastrados. |


## `webhookChaveDelete()`

```php
webhookChaveDelete($chave)
```

Cancelar o webhook Pix.

Endpoint para cancelamento do webhook. Não é a única forma pela qual um webhook pode ser removido.  O PSP recebedor está livre para remover unilateralmente um webhook que esteja associado a uma chave que não pertence mais a este usuário recebedor.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\WebhookApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$chave = 'chave_example'; // string

try {
    $apiInstance->webhookChaveDelete($chave);
} catch (Exception $e) {
    echo 'Exception when calling WebhookApi->webhookChaveDelete: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **chave** | **string**|  | |

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

## `webhookChaveGet()`

```php
webhookChaveGet($chave): \OpenAPI\Client\Model\WebhookCompleto
```

Exibir informações acerca do Webhook Pix.

Endpoint para recuperação de informações sobre o Webhook Pix.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\WebhookApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$chave = 'chave_example'; // string

try {
    $result = $apiInstance->webhookChaveGet($chave);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling WebhookApi->webhookChaveGet: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **chave** | **string**|  | |

### Return type

[**\OpenAPI\Client\Model\WebhookCompleto**](../Model/WebhookCompleto.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `webhookChavePut()`

```php
webhookChavePut($chave, $webhook_solicitado)
```

Configurar o Webhook Pix.

Endpoint para configuração do serviço de notificações acerca de Pix recebidos. Somente Pix associados a um txid serão notificados.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\WebhookApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$chave = 'chave_example'; // string
$webhook_solicitado = new \OpenAPI\Client\Model\WebhookSolicitado(); // \OpenAPI\Client\Model\WebhookSolicitado

try {
    $apiInstance->webhookChavePut($chave, $webhook_solicitado);
} catch (Exception $e) {
    echo 'Exception when calling WebhookApi->webhookChavePut: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **chave** | **string**|  | |
| **webhook_solicitado** | [**\OpenAPI\Client\Model\WebhookSolicitado**](../Model/WebhookSolicitado.md)|  | |

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

## `webhookGet()`

```php
webhookGet($inicio, $fim, $paginacao_pagina_atual, $paginacao_itens_por_pagina): \OpenAPI\Client\Model\WebhooksConsultados
```

Consultar webhooks cadastrados.

Endpoint para consultar Webhooks cadastrados

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\WebhookApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$inicio = new \DateTime('2013-10-20T19:20:30+01:00'); // \DateTime
$fim = new \DateTime('2013-10-20T19:20:30+01:00'); // \DateTime
$paginacao_pagina_atual = 0; // int
$paginacao_itens_por_pagina = 100; // int

try {
    $result = $apiInstance->webhookGet($inicio, $fim, $paginacao_pagina_atual, $paginacao_itens_por_pagina);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling WebhookApi->webhookGet: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **inicio** | **\DateTime**|  | [optional] |
| **fim** | **\DateTime**|  | [optional] |
| **paginacao_pagina_atual** | **int**|  | [optional] [default to 0] |
| **paginacao_itens_por_pagina** | **int**|  | [optional] [default to 100] |

### Return type

[**\OpenAPI\Client\Model\WebhooksConsultados**](../Model/WebhooksConsultados.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
