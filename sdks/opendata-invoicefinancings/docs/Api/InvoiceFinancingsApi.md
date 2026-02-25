# OpenAPI\Client\InvoiceFinancingsApi



All URIs are relative to http://api.banco.com.br/open-banking/opendata-invoicefinancings/v1, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**getBusinessInvoiceFinancings()**](InvoiceFinancingsApi.md#getBusinessInvoiceFinancings) | **GET** /business-invoice-financings | Obtém a lista de Direitos Creditórios Descontados de Pessoa Jurídica. |
| [**getPersonalInvoiceFinancings()**](InvoiceFinancingsApi.md#getPersonalInvoiceFinancings) | **GET** /personal-invoice-financings | Obtém a lista de Direitos Creditórios Descontados de Pessoa Natural. |


## `getBusinessInvoiceFinancings()`

```php
getBusinessInvoiceFinancings($page, $page_size): \OpenAPI\Client\Model\ResponseBusinessInvoiceFinancings
```

Obtém a lista de Direitos Creditórios Descontados de Pessoa Jurídica.

Obtém a lista de Direitos Creditórios Descontados de Pessoa Jurídica.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\InvoiceFinancingsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$page = 1; // int | Número da página que está sendo requisitada (o valor da primeira página é 1).
$page_size = 25; // int | Quantidade total de registros por páginas.

try {
    $result = $apiInstance->getBusinessInvoiceFinancings($page, $page_size);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling InvoiceFinancingsApi->getBusinessInvoiceFinancings: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **page** | **int**| Número da página que está sendo requisitada (o valor da primeira página é 1). | [optional] [default to 1] |
| **page_size** | **int**| Quantidade total de registros por páginas. | [optional] [default to 25] |

### Return type

[**\OpenAPI\Client\Model\ResponseBusinessInvoiceFinancings**](../Model/ResponseBusinessInvoiceFinancings.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/json; charset=utf-8`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getPersonalInvoiceFinancings()`

```php
getPersonalInvoiceFinancings($page, $page_size): \OpenAPI\Client\Model\ResponsePersonalInvoiceFinancings
```

Obtém a lista de Direitos Creditórios Descontados de Pessoa Natural.

Obtém a lista de Direitos Creditórios Descontados de Pessoa Natural.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\InvoiceFinancingsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$page = 1; // int | Número da página que está sendo requisitada (o valor da primeira página é 1).
$page_size = 25; // int | Quantidade total de registros por páginas.

try {
    $result = $apiInstance->getPersonalInvoiceFinancings($page, $page_size);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling InvoiceFinancingsApi->getPersonalInvoiceFinancings: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **page** | **int**| Número da página que está sendo requisitada (o valor da primeira página é 1). | [optional] [default to 1] |
| **page_size** | **int**| Quantidade total de registros por páginas. | [optional] [default to 25] |

### Return type

[**\OpenAPI\Client\Model\ResponsePersonalInvoiceFinancings**](../Model/ResponsePersonalInvoiceFinancings.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/json; charset=utf-8`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
