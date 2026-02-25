# OpenAPI\Client\DiscoveryApi



All URIs are relative to http://api.banco.com.br/open-banking/discovery/v2, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**getOutage()**](DiscoveryApi.md#getOutage) | **GET** /outages | a descrição referente ao código de status retornado pelas APIs |
| [**getStatus()**](DiscoveryApi.md#getStatus) | **GET** /status | Descrição do status da implementação das APIs do OFB |


## `getOutage()`

```php
getOutage($page, $page_size): \OpenAPI\Client\Model\ResponseDiscoveryOutageList
```

a descrição referente ao código de status retornado pelas APIs

a descrição referente ao código de status retornado pelas APIs

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\DiscoveryApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$page = 1; // int | Número da página que está sendo requisitada (o valor da primeira página é 1).
$page_size = 25; // int | Quantidade total de registros por páginas.

try {
    $result = $apiInstance->getOutage($page, $page_size);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling DiscoveryApi->getOutage: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **page** | **int**| Número da página que está sendo requisitada (o valor da primeira página é 1). | [optional] [default to 1] |
| **page_size** | **int**| Quantidade total de registros por páginas. | [optional] [default to 25] |

### Return type

[**\OpenAPI\Client\Model\ResponseDiscoveryOutageList**](../Model/ResponseDiscoveryOutageList.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/json; charset=utf-8`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getStatus()`

```php
getStatus($page, $page_size): \OpenAPI\Client\Model\ResponseDiscoveryStatusList
```

Descrição do status da implementação das APIs do OFB

Descrição do status da implementação das APIs do OFB

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\DiscoveryApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$page = 1; // int | Número da página que está sendo requisitada (o valor da primeira página é 1).
$page_size = 25; // int | Quantidade total de registros por páginas.

try {
    $result = $apiInstance->getStatus($page, $page_size);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling DiscoveryApi->getStatus: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **page** | **int**| Número da página que está sendo requisitada (o valor da primeira página é 1). | [optional] [default to 1] |
| **page_size** | **int**| Quantidade total de registros por páginas. | [optional] [default to 25] |

### Return type

[**\OpenAPI\Client\Model\ResponseDiscoveryStatusList**](../Model/ResponseDiscoveryStatusList.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/json; charset=utf-8`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
