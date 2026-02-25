# OpenAPI\Client\AccountsApi

Operações para listagem de contas.

All URIs are relative to http://api.banco.com.br/open-banking/opendata-accounts/v1, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**getBusinessAccounts()**](AccountsApi.md#getBusinessAccounts) | **GET** /business-accounts | Obtém dados das contas pessoa jurídica |
| [**getPersonalAccounts()**](AccountsApi.md#getPersonalAccounts) | **GET** /personal-accounts | Obtém dados das contas pessoa natural |


## `getBusinessAccounts()`

```php
getBusinessAccounts($page, $page_size): \OpenAPI\Client\Model\ResponseBusinessAccounts
```

Obtém dados das contas pessoa jurídica

Obtém dados das contas pessoa jurídica

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\AccountsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$page = 1; // int | Número da página que está sendo requisitada (o valor da primeira página é 1).
$page_size = 25; // int | Quantidade total de registros por páginas.

try {
    $result = $apiInstance->getBusinessAccounts($page, $page_size);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountsApi->getBusinessAccounts: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **page** | **int**| Número da página que está sendo requisitada (o valor da primeira página é 1). | [optional] [default to 1] |
| **page_size** | **int**| Quantidade total de registros por páginas. | [optional] [default to 25] |

### Return type

[**\OpenAPI\Client\Model\ResponseBusinessAccounts**](../Model/ResponseBusinessAccounts.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/json; charset=utf-8`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getPersonalAccounts()`

```php
getPersonalAccounts($page, $page_size): \OpenAPI\Client\Model\ResponsePersonalAccounts
```

Obtém dados das contas pessoa natural

Obtém dados das contas

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\AccountsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$page = 1; // int | Número da página que está sendo requisitada (o valor da primeira página é 1).
$page_size = 25; // int | Quantidade total de registros por páginas.

try {
    $result = $apiInstance->getPersonalAccounts($page, $page_size);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountsApi->getPersonalAccounts: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **page** | **int**| Número da página que está sendo requisitada (o valor da primeira página é 1). | [optional] [default to 1] |
| **page_size** | **int**| Quantidade total de registros por páginas. | [optional] [default to 25] |

### Return type

[**\OpenAPI\Client\Model\ResponsePersonalAccounts**](../Model/ResponsePersonalAccounts.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/json; charset=utf-8`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
