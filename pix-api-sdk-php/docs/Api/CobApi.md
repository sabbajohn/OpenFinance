# OpenAPI\Client\CobApi

Reúne endpoints destinados a lidar com gerenciamento de cobranças imediatas.

All URIs are relative to https://pix.example.com/api, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**cobGet()**](CobApi.md#cobGet) | **GET** /cob | Consultar lista de cobranças imediatas. |
| [**cobPost()**](CobApi.md#cobPost) | **POST** /cob | Criar cobrança imediata. |
| [**cobTxidGet()**](CobApi.md#cobTxidGet) | **GET** /cob/{txid} | Consultar cobrança imediata. |
| [**cobTxidPatch()**](CobApi.md#cobTxidPatch) | **PATCH** /cob/{txid} | Revisar cobrança imediata. |
| [**cobTxidPut()**](CobApi.md#cobTxidPut) | **PUT** /cob/{txid} | Criar cobrança imediata. |


## `cobGet()`

```php
cobGet($inicio, $fim, $cpf, $cnpj, $location_presente, $status, $paginacao_pagina_atual, $paginacao_itens_por_pagina): \OpenAPI\Client\Model\CobsConsultadas
```

Consultar lista de cobranças imediatas.

Endpoint para consultar cobranças imediatas através de parâmetros como início, fim, cpf, cnpj e status.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\CobApi(
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
$paginacao_pagina_atual = 0; // int
$paginacao_itens_por_pagina = 100; // int

try {
    $result = $apiInstance->cobGet($inicio, $fim, $cpf, $cnpj, $location_presente, $status, $paginacao_pagina_atual, $paginacao_itens_por_pagina);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling CobApi->cobGet: ', $e->getMessage(), PHP_EOL;
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
| **paginacao_pagina_atual** | **int**|  | [optional] [default to 0] |
| **paginacao_itens_por_pagina** | **int**|  | [optional] [default to 100] |

### Return type

[**\OpenAPI\Client\Model\CobsConsultadas**](../Model/CobsConsultadas.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `cobPost()`

```php
cobPost($cob_solicitada): \OpenAPI\Client\Model\CobGerada
```

Criar cobrança imediata.

Endpoint para criar uma cobrança imediata, neste caso, o txid deve ser definido pelo PSP.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\CobApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$cob_solicitada = new \OpenAPI\Client\Model\CobSolicitada(); // \OpenAPI\Client\Model\CobSolicitada | Dados para geração da cobrança imediata.

try {
    $result = $apiInstance->cobPost($cob_solicitada);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling CobApi->cobPost: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **cob_solicitada** | [**\OpenAPI\Client\Model\CobSolicitada**](../Model/CobSolicitada.md)| Dados para geração da cobrança imediata. | |

### Return type

[**\OpenAPI\Client\Model\CobGerada**](../Model/CobGerada.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `cobTxidGet()`

```php
cobTxidGet($txid, $revisao): \OpenAPI\Client\Model\CobCompleta
```

Consultar cobrança imediata.

Endpoint para consultar uma cobrança através de um determinado txid.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\CobApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$txid = 'txid_example'; // string
$revisao = 56; // int

try {
    $result = $apiInstance->cobTxidGet($txid, $revisao);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling CobApi->cobTxidGet: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **txid** | **string**|  | |
| **revisao** | **int**|  | [optional] |

### Return type

[**\OpenAPI\Client\Model\CobCompleta**](../Model/CobCompleta.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `cobTxidPatch()`

```php
cobTxidPatch($txid, $cob_revisada): \OpenAPI\Client\Model\CobGerada
```

Revisar cobrança imediata.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\CobApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$txid = 'txid_example'; // string
$cob_revisada = new \OpenAPI\Client\Model\CobRevisada(); // \OpenAPI\Client\Model\CobRevisada | Dados para geração da cobrança.

try {
    $result = $apiInstance->cobTxidPatch($txid, $cob_revisada);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling CobApi->cobTxidPatch: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **txid** | **string**|  | |
| **cob_revisada** | [**\OpenAPI\Client\Model\CobRevisada**](../Model/CobRevisada.md)| Dados para geração da cobrança. | |

### Return type

[**\OpenAPI\Client\Model\CobGerada**](../Model/CobGerada.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `cobTxidPut()`

```php
cobTxidPut($txid, $cob_solicitada): \OpenAPI\Client\Model\CobGerada
```

Criar cobrança imediata.

Endpoint para criar uma cobrança imediata.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\CobApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$txid = 'txid_example'; // string
$cob_solicitada = new \OpenAPI\Client\Model\CobSolicitada(); // \OpenAPI\Client\Model\CobSolicitada | Dados para geração da cobrança imediata.

try {
    $result = $apiInstance->cobTxidPut($txid, $cob_solicitada);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling CobApi->cobTxidPut: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **txid** | **string**|  | |
| **cob_solicitada** | [**\OpenAPI\Client\Model\CobSolicitada**](../Model/CobSolicitada.md)| Dados para geração da cobrança imediata. | |

### Return type

[**\OpenAPI\Client\Model\CobGerada**](../Model/CobGerada.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
