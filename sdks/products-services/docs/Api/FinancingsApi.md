# OpenAPI\Client\FinancingsApi



All URIs are relative to http://api.banco.com.br/open-banking/products-services/v2, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**getBusinessFinancings()**](FinancingsApi.md#getBusinessFinancings) | **GET** /business-financings | Obtém a lista de Financiamentos de Pessoa Jurídica. |
| [**getPersonalFinancings()**](FinancingsApi.md#getPersonalFinancings) | **GET** /personal-financings | Obtém a lista de Financiamentos de Pessoa Natural. |


## `getBusinessFinancings()`

```php
getBusinessFinancings(): \OpenAPI\Client\Model\ResponseBusinessFinancings
```

Obtém a lista de Financiamentos de Pessoa Jurídica.

Obtém a lista de Financiamentos de Pessoa Jurídica.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\FinancingsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);

try {
    $result = $apiInstance->getBusinessFinancings();
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling FinancingsApi->getBusinessFinancings: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

This endpoint does not need any parameter.

### Return type

[**\OpenAPI\Client\Model\ResponseBusinessFinancings**](../Model/ResponseBusinessFinancings.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getPersonalFinancings()`

```php
getPersonalFinancings(): \OpenAPI\Client\Model\ResponsePersonalFinancings
```

Obtém a lista de Financiamentos de Pessoa Natural.

Obtém a lista de Financiamentos de Pessoa Natural

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\FinancingsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);

try {
    $result = $apiInstance->getPersonalFinancings();
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling FinancingsApi->getPersonalFinancings: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

This endpoint does not need any parameter.

### Return type

[**\OpenAPI\Client\Model\ResponsePersonalFinancings**](../Model/ResponsePersonalFinancings.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
