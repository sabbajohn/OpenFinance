# OpenAPI\Client\AccountsApi



All URIs are relative to http://api.banco.com.br/open-banking/products-services/v2, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**getBusinessAccounts()**](AccountsApi.md#getBusinessAccounts) | **GET** /business-accounts | Obtém dados das contas pessoa jurídica |
| [**getPersonalAccounts()**](AccountsApi.md#getPersonalAccounts) | **GET** /personal-accounts | Obtém dados das contas pessoa natural |


## `getBusinessAccounts()`

```php
getBusinessAccounts(): \OpenAPI\Client\Model\ResponseBusinessAccounts
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

try {
    $result = $apiInstance->getBusinessAccounts();
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountsApi->getBusinessAccounts: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

This endpoint does not need any parameter.

### Return type

[**\OpenAPI\Client\Model\ResponseBusinessAccounts**](../Model/ResponseBusinessAccounts.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getPersonalAccounts()`

```php
getPersonalAccounts(): \OpenAPI\Client\Model\ResponsePersonalAccounts
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

try {
    $result = $apiInstance->getPersonalAccounts();
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountsApi->getPersonalAccounts: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

This endpoint does not need any parameter.

### Return type

[**\OpenAPI\Client\Model\ResponsePersonalAccounts**](../Model/ResponsePersonalAccounts.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
