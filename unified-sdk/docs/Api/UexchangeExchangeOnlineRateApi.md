# OpenAPI\Client\ExchangeOnlineRateApi



All URIs are relative to https://api.banco.com.br/open-banking/opendata-exchange/v1, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**exchangeGetOnlineRate()**](ExchangeOnlineRateApi.md#exchangeGetOnlineRate) | **GET** /online-rates | Conjunto de informações de Câmbio para taxa online. |


## `exchangeGetOnlineRate()`

```php
exchangeGetOnlineRate($page, $page_size): \OpenAPI\Client\Model\OKResponseExchangeOnlineRate
```

Conjunto de informações de Câmbio para taxa online.

As instituições autorizadas a operar em câmbio que disponibilizam em seus canais digitais a possibilidade de contratação ou a informação de taxa de câmbio devem retornar as condições no momento da consulta, sendo admitida uma defasagem máxima de atualização de 5 minutos em relação a seus canais digitais.  Já as demais instituições participantes do Open Finance autorizadas a operar em câmbio podem adotar as janelas de consulta da PTAX como frequência mínima de atualização das informações retornadas neste serviço.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\ExchangeOnlineRateApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$page = 1; // int | Número da página que está sendo requisitada (o valor da primeira página é 1).
$page_size = 25; // int | Quantidade total de registros por páginas.

try {
    $result = $apiInstance->exchangeGetOnlineRate($page, $page_size);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ExchangeOnlineRateApi->exchangeGetOnlineRate: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **page** | **int**| Número da página que está sendo requisitada (o valor da primeira página é 1). | [optional] [default to 1] |
| **page_size** | **int**| Quantidade total de registros por páginas. | [optional] [default to 25] |

### Return type

[**\OpenAPI\Client\Model\OKResponseExchangeOnlineRate**](../Model/OKResponseExchangeOnlineRate.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/json; charset=utf-8`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
