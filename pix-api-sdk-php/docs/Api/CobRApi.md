# OpenAPI\Client\CobRApi

Reúne endpoints destinados a lidar com gerenciamento de cobranças associadas a uma recorrência.

All URIs are relative to https://pix.example.com/api, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**cobrGet()**](CobRApi.md#cobrGet) | **GET** /cobr | Consultar lista de cobranças recorrentes. |
| [**cobrPost()**](CobRApi.md#cobrPost) | **POST** /cobr | Criar cobrança recorrente. |
| [**cobrTxidGet()**](CobRApi.md#cobrTxidGet) | **GET** /cobr/{txid} | Consultar cobrança recorrente. |
| [**cobrTxidPatch()**](CobRApi.md#cobrTxidPatch) | **PATCH** /cobr/{txid} | Revisar cobrança recorrente. |
| [**cobrTxidPut()**](CobRApi.md#cobrTxidPut) | **PUT** /cobr/{txid} | Criar cobrança recorrente. |
| [**cobrTxidRetentativaDataPost()**](CobRApi.md#cobrTxidRetentativaDataPost) | **POST** /cobr/{txid}/retentativa/{data} | Solicitar retentativa de cobrança. |


## `cobrGet()`

```php
cobrGet($inicio, $fim, $id_rec, $cpf, $cnpj, $status, $convenio, $paginacao_pagina_atual, $paginacao_itens_por_pagina): \OpenAPI\Client\Model\CobsRConsultadas
```

Consultar lista de cobranças recorrentes.

Endpoint para consultar cobranças recorrentes através de parâmetros como início, fim, idRec, cpf, cnpj, status e convênio.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\CobRApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$inicio = new \DateTime('2013-10-20T19:20:30+01:00'); // \DateTime
$fim = new \DateTime('2013-10-20T19:20:30+01:00'); // \DateTime
$id_rec = 'id_rec_example'; // string
$cpf = 'cpf_example'; // string
$cnpj = 'cnpj_example'; // string
$status = 'status_example'; // string
$convenio = 'convenio_example'; // string
$paginacao_pagina_atual = 0; // int
$paginacao_itens_por_pagina = 100; // int

try {
    $result = $apiInstance->cobrGet($inicio, $fim, $id_rec, $cpf, $cnpj, $status, $convenio, $paginacao_pagina_atual, $paginacao_itens_por_pagina);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling CobRApi->cobrGet: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **inicio** | **\DateTime**|  | |
| **fim** | **\DateTime**|  | |
| **id_rec** | **string**|  | [optional] |
| **cpf** | **string**|  | [optional] |
| **cnpj** | **string**|  | [optional] |
| **status** | **string**|  | [optional] |
| **convenio** | **string**|  | [optional] |
| **paginacao_pagina_atual** | **int**|  | [optional] [default to 0] |
| **paginacao_itens_por_pagina** | **int**|  | [optional] [default to 100] |

### Return type

[**\OpenAPI\Client\Model\CobsRConsultadas**](../Model/CobsRConsultadas.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `cobrPost()`

```php
cobrPost($cob_r_solicitada): \OpenAPI\Client\Model\CobRGerada
```

Criar cobrança recorrente.

Endpoint para criar uma cobrança recorrente, neste caso, o txid deve ser definido pelo PSP.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\CobRApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$cob_r_solicitada = new \OpenAPI\Client\Model\CobRSolicitada(); // \OpenAPI\Client\Model\CobRSolicitada | Dados para geração da cobrança recorrente.

try {
    $result = $apiInstance->cobrPost($cob_r_solicitada);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling CobRApi->cobrPost: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **cob_r_solicitada** | [**\OpenAPI\Client\Model\CobRSolicitada**](../Model/CobRSolicitada.md)| Dados para geração da cobrança recorrente. | |

### Return type

[**\OpenAPI\Client\Model\CobRGerada**](../Model/CobRGerada.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `cobrTxidGet()`

```php
cobrTxidGet($txid): \OpenAPI\Client\Model\CobRCompleta
```

Consultar cobrança recorrente.

Endpoint para consultar uma cobrança recorrente através de um determinado txid.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\CobRApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$txid = 'txid_example'; // string

try {
    $result = $apiInstance->cobrTxidGet($txid);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling CobRApi->cobrTxidGet: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **txid** | **string**|  | |

### Return type

[**\OpenAPI\Client\Model\CobRCompleta**](../Model/CobRCompleta.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `cobrTxidPatch()`

```php
cobrTxidPatch($txid, $cob_r_revisada): \OpenAPI\Client\Model\CobRGerada
```

Revisar cobrança recorrente.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\CobRApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$txid = 'txid_example'; // string
$cob_r_revisada = new \OpenAPI\Client\Model\CobRRevisada(); // \OpenAPI\Client\Model\CobRRevisada | Dados para geração da cobrança.

try {
    $result = $apiInstance->cobrTxidPatch($txid, $cob_r_revisada);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling CobRApi->cobrTxidPatch: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **txid** | **string**|  | |
| **cob_r_revisada** | [**\OpenAPI\Client\Model\CobRRevisada**](../Model/CobRRevisada.md)| Dados para geração da cobrança. | |

### Return type

[**\OpenAPI\Client\Model\CobRGerada**](../Model/CobRGerada.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `cobrTxidPut()`

```php
cobrTxidPut($txid, $cob_r_solicitada): \OpenAPI\Client\Model\CobRGerada
```

Criar cobrança recorrente.

Endpoint para criar uma cobrança recorrente.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\CobRApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$txid = 'txid_example'; // string
$cob_r_solicitada = new \OpenAPI\Client\Model\CobRSolicitada(); // \OpenAPI\Client\Model\CobRSolicitada | Dados para geração da cobrança recorrente.

try {
    $result = $apiInstance->cobrTxidPut($txid, $cob_r_solicitada);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling CobRApi->cobrTxidPut: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **txid** | **string**|  | |
| **cob_r_solicitada** | [**\OpenAPI\Client\Model\CobRSolicitada**](../Model/CobRSolicitada.md)| Dados para geração da cobrança recorrente. | |

### Return type

[**\OpenAPI\Client\Model\CobRGerada**](../Model/CobRGerada.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `cobrTxidRetentativaDataPost()`

```php
cobrTxidRetentativaDataPost($txid, $data): \OpenAPI\Client\Model\CobRCompleta
```

Solicitar retentativa de cobrança.

Endpoint para solicitar retentativa de uma cobrança recorrente.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\CobRApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$txid = 'txid_example'; // string
$data = Fri Mar 31 21:00:00 BRT 2023; // \DateTime | Data prevista para liquidação da ordem de pagamento correspondente. Trata-se de uma data, no formato `YYYY-MM-DD`, segundo ISO 8601.

try {
    $result = $apiInstance->cobrTxidRetentativaDataPost($txid, $data);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling CobRApi->cobrTxidRetentativaDataPost: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **txid** | **string**|  | |
| **data** | **\DateTime**| Data prevista para liquidação da ordem de pagamento correspondente. Trata-se de uma data, no formato &#x60;YYYY-MM-DD&#x60;, segundo ISO 8601. | |

### Return type

[**\OpenAPI\Client\Model\CobRCompleta**](../Model/CobRCompleta.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
