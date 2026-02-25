# OpenAPI\Client\MetricsApi



All URIs are relative to http://api.banco.com.br/open-banking/admin/v2, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**getMetrics()**](MetricsApi.md#getMetrics) | **GET** /metrics | Obtém as métricas de disponibilidade das APIs |


## `getMetrics()`

```php
getMetrics($page, $page_size, $period): \OpenAPI\Client\Model\ResponseMetricsList
```

Obtém as métricas de disponibilidade das APIs

Obtém as métricas de disponibilidade das APIs

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\MetricsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$page = 1; // int | Número da página que está sendo requisitada (o valor da primeira página é 1).
$page_size = 25; // int | Quantidade total de registros por páginas.
$period = 'period_example'; // string | Período a ser consultado   * `CURRENT` - Métricas do dia atual.   * `ALL` - Métricas de todo o período disponível (últimos 7 dias).

try {
    $result = $apiInstance->getMetrics($page, $page_size, $period);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling MetricsApi->getMetrics: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **page** | **int**| Número da página que está sendo requisitada (o valor da primeira página é 1). | [optional] [default to 1] |
| **page_size** | **int**| Quantidade total de registros por páginas. | [optional] [default to 25] |
| **period** | **string**| Período a ser consultado   * &#x60;CURRENT&#x60; - Métricas do dia atual.   * &#x60;ALL&#x60; - Métricas de todo o período disponível (últimos 7 dias). | [optional] |

### Return type

[**\OpenAPI\Client\Model\ResponseMetricsList**](../Model/ResponseMetricsList.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/json; charset=utf-8`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
