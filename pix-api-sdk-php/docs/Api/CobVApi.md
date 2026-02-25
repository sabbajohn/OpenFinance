# OpenAPI\Client\CobVApi

Reúne endpoints destinados a lidar com gerenciamento de cobranças com vencimento.

All URIs are relative to https://pix.example.com/api, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**cobvGet()**](CobVApi.md#cobvGet) | **GET** /cobv | Consultar lista de cobranças com vencimento. |
| [**cobvTxidGet()**](CobVApi.md#cobvTxidGet) | **GET** /cobv/{txid} | Consultar cobrança com vencimento. |
| [**cobvTxidPatch()**](CobVApi.md#cobvTxidPatch) | **PATCH** /cobv/{txid} | Revisar cobrança com vencimento. |
| [**cobvTxidPut()**](CobVApi.md#cobvTxidPut) | **PUT** /cobv/{txid} | Criar cobrança com vencimento. |


## `cobvGet()`

```php
cobvGet($inicio, $fim, $cpf, $cnpj, $location_presente, $status, $lote_cob_vid, $paginacao_pagina_atual, $paginacao_itens_por_pagina): \OpenAPI\Client\Model\CobsVConsultadas
```

Consultar lista de cobranças com vencimento.

Endpoint para consultar cobranças com vencimento através de parâmetros como início, fim, cpf, cnpj e status.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\CobVApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$inicio = new \DateTime('2013-10-20T19:20:30+01:00'); // \DateTime
$fim = new \DateTime('2013-10-20T19:20:30+01:00'); // \DateTime
$cpf = 'cpf_example'; // string
$cnpj = 'cnpj_example'; // string
$location_presente = True; // bool
$status = 'status_example'; // string
$lote_cob_vid = 56; // int
$paginacao_pagina_atual = 0; // int
$paginacao_itens_por_pagina = 100; // int

try {
    $result = $apiInstance->cobvGet($inicio, $fim, $cpf, $cnpj, $location_presente, $status, $lote_cob_vid, $paginacao_pagina_atual, $paginacao_itens_por_pagina);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling CobVApi->cobvGet: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **inicio** | **\DateTime**|  | |
| **fim** | **\DateTime**|  | |
| **cpf** | **string**|  | [optional] |
| **cnpj** | **string**|  | [optional] |
| **location_presente** | **bool**|  | [optional] |
| **status** | **string**|  | [optional] |
| **lote_cob_vid** | **int**|  | [optional] |
| **paginacao_pagina_atual** | **int**|  | [optional] [default to 0] |
| **paginacao_itens_por_pagina** | **int**|  | [optional] [default to 100] |

### Return type

[**\OpenAPI\Client\Model\CobsVConsultadas**](../Model/CobsVConsultadas.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `cobvTxidGet()`

```php
cobvTxidGet($txid, $revisao): \OpenAPI\Client\Model\CobVCompleta
```

Consultar cobrança com vencimento.

Endpoint para consultar uma cobrança com vencimento através de um determinado txid.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\CobVApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$txid = 'txid_example'; // string
$revisao = 56; // int

try {
    $result = $apiInstance->cobvTxidGet($txid, $revisao);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling CobVApi->cobvTxidGet: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **txid** | **string**|  | |
| **revisao** | **int**|  | [optional] |

### Return type

[**\OpenAPI\Client\Model\CobVCompleta**](../Model/CobVCompleta.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `cobvTxidPatch()`

```php
cobvTxidPatch($txid, $cob_v_revisada): \OpenAPI\Client\Model\CobVGerada
```

Revisar cobrança com vencimento.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\CobVApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$txid = 'txid_example'; // string
$cob_v_revisada = new \OpenAPI\Client\Model\CobVRevisada(); // \OpenAPI\Client\Model\CobVRevisada | Dados para geração da cobrança.

try {
    $result = $apiInstance->cobvTxidPatch($txid, $cob_v_revisada);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling CobVApi->cobvTxidPatch: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **txid** | **string**|  | |
| **cob_v_revisada** | [**\OpenAPI\Client\Model\CobVRevisada**](../Model/CobVRevisada.md)| Dados para geração da cobrança. | |

### Return type

[**\OpenAPI\Client\Model\CobVGerada**](../Model/CobVGerada.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `cobvTxidPut()`

```php
cobvTxidPut($txid, $cob_v_solicitada): \OpenAPI\Client\Model\CobVGerada
```

Criar cobrança com vencimento.

Endpoint para criar uma cobrança com vencimento.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\CobVApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$txid = 'txid_example'; // string
$cob_v_solicitada = new \OpenAPI\Client\Model\CobVSolicitada(); // \OpenAPI\Client\Model\CobVSolicitada | Dados para geração da cobrança com vencimento.

try {
    $result = $apiInstance->cobvTxidPut($txid, $cob_v_solicitada);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling CobVApi->cobvTxidPut: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **txid** | **string**|  | |
| **cob_v_solicitada** | [**\OpenAPI\Client\Model\CobVSolicitada**](../Model/CobVSolicitada.md)| Dados para geração da cobrança com vencimento. | |

### Return type

[**\OpenAPI\Client\Model\CobVGerada**](../Model/CobVGerada.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
