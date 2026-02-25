# OpenAPI\Client\InvoiceFinancingsApi



All URIs are relative to http://api.banco.com.br/open-banking/products-services/v2, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**getBusinessInvoiceFinancings()**](InvoiceFinancingsApi.md#getBusinessInvoiceFinancings) | **GET** /business-invoice-financings | Obtém a lista de Adiantamento de Recebíveis de Pessoa Jurídica. |
| [**getPersonalInvoiceFinancings()**](InvoiceFinancingsApi.md#getPersonalInvoiceFinancings) | **GET** /personal-invoice-financings | Obtém a lista de Adiantamento de Recebíveis de Pessoa Natural. |


## `getBusinessInvoiceFinancings()`

```php
getBusinessInvoiceFinancings(): \OpenAPI\Client\Model\ResponseBusinessInvoiceFinancings
```

Obtém a lista de Adiantamento de Recebíveis de Pessoa Jurídica.

Obtém a lista de Adiantamento de Recebíveis de Pessoa Jurídica.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\InvoiceFinancingsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);

try {
    $result = $apiInstance->getBusinessInvoiceFinancings();
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling InvoiceFinancingsApi->getBusinessInvoiceFinancings: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

This endpoint does not need any parameter.

### Return type

[**\OpenAPI\Client\Model\ResponseBusinessInvoiceFinancings**](../Model/ResponseBusinessInvoiceFinancings.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getPersonalInvoiceFinancings()`

```php
getPersonalInvoiceFinancings(): \OpenAPI\Client\Model\ResponsePersonalInvoiceFinancings
```

Obtém a lista de Adiantamento de Recebíveis de Pessoa Natural.

Obtém a lista de Adiantamento de Recebíveis de Pessoa Natural.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\InvoiceFinancingsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);

try {
    $result = $apiInstance->getPersonalInvoiceFinancings();
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling InvoiceFinancingsApi->getPersonalInvoiceFinancings: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

This endpoint does not need any parameter.

### Return type

[**\OpenAPI\Client\Model\ResponsePersonalInvoiceFinancings**](../Model/ResponsePersonalInvoiceFinancings.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
