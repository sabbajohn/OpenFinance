# OpenAPI\Client\LoansApi



All URIs are relative to http://api.banco.com.br/open-banking/products-services/v2, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**getBusinessLoans()**](LoansApi.md#getBusinessLoans) | **GET** /business-loans | Obtém dados sobre empréstimos pessoa jurídica |
| [**getPersonalLoans()**](LoansApi.md#getPersonalLoans) | **GET** /personal-loans | Obtém dados sobre empréstimos pessoa natural |


## `getBusinessLoans()`

```php
getBusinessLoans(): \OpenAPI\Client\Model\ResponseBusinessLoans
```

Obtém dados sobre empréstimos pessoa jurídica

Obtém dados sobre empréstimos pessoa jurídica

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\LoansApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);

try {
    $result = $apiInstance->getBusinessLoans();
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling LoansApi->getBusinessLoans: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

This endpoint does not need any parameter.

### Return type

[**\OpenAPI\Client\Model\ResponseBusinessLoans**](../Model/ResponseBusinessLoans.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getPersonalLoans()`

```php
getPersonalLoans(): \OpenAPI\Client\Model\ResponsePersonalLoans
```

Obtém dados sobre empréstimos pessoa natural

Obtém dados sobre empréstimos pessoa natural

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\LoansApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);

try {
    $result = $apiInstance->getPersonalLoans();
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling LoansApi->getPersonalLoans: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

This endpoint does not need any parameter.

### Return type

[**\OpenAPI\Client\Model\ResponsePersonalLoans**](../Model/ResponsePersonalLoans.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
