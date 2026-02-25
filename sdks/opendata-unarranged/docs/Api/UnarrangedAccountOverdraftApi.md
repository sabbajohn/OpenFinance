# OpenAPI\Client\UnarrangedAccountOverdraftApi



All URIs are relative to http://api.banco.com.br/open-banking/opendata-unarranged/v1, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**getBusinessUnarrangedAccountOverdraft()**](UnarrangedAccountOverdraftApi.md#getBusinessUnarrangedAccountOverdraft) | **GET** /business-unarranged-account-overdraft | Obtém a lista de adiantamento de depositante de Pessoa Jurídica. |
| [**getPersonalUnarrangedAccountOverdraft()**](UnarrangedAccountOverdraftApi.md#getPersonalUnarrangedAccountOverdraft) | **GET** /personal-unarranged-account-overdraft | Obtém a lista de adiantamento de depositante de Pessoa Natural. |


## `getBusinessUnarrangedAccountOverdraft()`

```php
getBusinessUnarrangedAccountOverdraft($page, $page_size): \OpenAPI\Client\Model\ResponseBusinessUnarrangedAccountOverdraft
```

Obtém a lista de adiantamento de depositante de Pessoa Jurídica.

Obtém a lista de adiantamento de depositante de Pessoa Jurídica.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\UnarrangedAccountOverdraftApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$page = 1; // int | Número da página que está sendo requisitada (o valor da primeira página é 1).
$page_size = 25; // int | Quantidade total de registros por páginas.

try {
    $result = $apiInstance->getBusinessUnarrangedAccountOverdraft($page, $page_size);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling UnarrangedAccountOverdraftApi->getBusinessUnarrangedAccountOverdraft: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **page** | **int**| Número da página que está sendo requisitada (o valor da primeira página é 1). | [optional] [default to 1] |
| **page_size** | **int**| Quantidade total de registros por páginas. | [optional] [default to 25] |

### Return type

[**\OpenAPI\Client\Model\ResponseBusinessUnarrangedAccountOverdraft**](../Model/ResponseBusinessUnarrangedAccountOverdraft.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/json; charset=utf-8`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getPersonalUnarrangedAccountOverdraft()`

```php
getPersonalUnarrangedAccountOverdraft($page, $page_size): \OpenAPI\Client\Model\ResponsePersonalUnarrangedAccountOverdraft
```

Obtém a lista de adiantamento de depositante de Pessoa Natural.

Obtém a lista de adiantamento de depositante de Pessoa Natural.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\UnarrangedAccountOverdraftApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$page = 1; // int | Número da página que está sendo requisitada (o valor da primeira página é 1).
$page_size = 25; // int | Quantidade total de registros por páginas.

try {
    $result = $apiInstance->getPersonalUnarrangedAccountOverdraft($page, $page_size);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling UnarrangedAccountOverdraftApi->getPersonalUnarrangedAccountOverdraft: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **page** | **int**| Número da página que está sendo requisitada (o valor da primeira página é 1). | [optional] [default to 1] |
| **page_size** | **int**| Quantidade total de registros por páginas. | [optional] [default to 25] |

### Return type

[**\OpenAPI\Client\Model\ResponsePersonalUnarrangedAccountOverdraft**](../Model/ResponsePersonalUnarrangedAccountOverdraft.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/json; charset=utf-8`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
