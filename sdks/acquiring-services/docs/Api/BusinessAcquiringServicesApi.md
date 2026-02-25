# OpenAPI\Client\BusinessAcquiringServicesApi

Operações para obter as informações de Credenciamento para Pessoa Jurídica.

All URIs are relative to https://api.banco.com.br/open-banking/opendata-acquiring-services/v1, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**getBusinessAcquiringServices()**](BusinessAcquiringServicesApi.md#getBusinessAcquiringServices) | **GET** /businesses | Conjunto de informações de Credenciamento para Pessoa Jurídica. |


## `getBusinessAcquiringServices()`

```php
getBusinessAcquiringServices($page, $page_size): \OpenAPI\Client\Model\OKResponseAcquiringServices
```

Conjunto de informações de Credenciamento para Pessoa Jurídica.

Método para disponibilizar as taxas e tarifas por serviços.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\BusinessAcquiringServicesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$page = 1; // int | Número da página que está sendo requisitada (o valor da primeira página é 1).
$page_size = 25; // int | Quantidade total de registros por páginas.

try {
    $result = $apiInstance->getBusinessAcquiringServices($page, $page_size);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling BusinessAcquiringServicesApi->getBusinessAcquiringServices: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **page** | **int**| Número da página que está sendo requisitada (o valor da primeira página é 1). | [optional] [default to 1] |
| **page_size** | **int**| Quantidade total de registros por páginas. | [optional] [default to 25] |

### Return type

[**\OpenAPI\Client\Model\OKResponseAcquiringServices**](../Model/OKResponseAcquiringServices.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/json; charset=utf-8`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
