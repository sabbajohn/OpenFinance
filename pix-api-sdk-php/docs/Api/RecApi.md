# OpenAPI\Client\RecApi

Reúne endpoints destinados a lidar com gerenciamento de recorrências.

All URIs are relative to https://pix.example.com/api, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**recGet()**](RecApi.md#recGet) | **GET** /rec | Consultar lista de recorrências. |
| [**recIdRecGet()**](RecApi.md#recIdRecGet) | **GET** /rec/{idRec} | Consultar recorrência. |
| [**recIdRecPatch()**](RecApi.md#recIdRecPatch) | **PATCH** /rec/{idRec} | Revisar recorrência. |
| [**recPost()**](RecApi.md#recPost) | **POST** /rec | Criar recorrência. |


## `recGet()`

```php
recGet($inicio, $fim, $cpf, $cnpj, $location_presente, $status, $convenio, $paginacao_pagina_atual, $paginacao_itens_por_pagina): \OpenAPI\Client\Model\RecsConsultadas
```

Consultar lista de recorrências.

Consultar lista de recorrências.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\RecApi(
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
$convenio = 'convenio_example'; // string
$paginacao_pagina_atual = 0; // int
$paginacao_itens_por_pagina = 100; // int

try {
    $result = $apiInstance->recGet($inicio, $fim, $cpf, $cnpj, $location_presente, $status, $convenio, $paginacao_pagina_atual, $paginacao_itens_por_pagina);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling RecApi->recGet: ', $e->getMessage(), PHP_EOL;
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
| **convenio** | **string**|  | [optional] |
| **paginacao_pagina_atual** | **int**|  | [optional] [default to 0] |
| **paginacao_itens_por_pagina** | **int**|  | [optional] [default to 100] |

### Return type

[**\OpenAPI\Client\Model\RecsConsultadas**](../Model/RecsConsultadas.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `recIdRecGet()`

```php
recIdRecGet($id_rec, $txid): \OpenAPI\Client\Model\RecCompleta
```

Consultar recorrência.

Consultar recorrência.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\RecApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$id_rec = 'id_rec_example'; // string
$txid = 'txid_example'; // string

try {
    $result = $apiInstance->recIdRecGet($id_rec, $txid);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling RecApi->recIdRecGet: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **id_rec** | **string**|  | |
| **txid** | **string**|  | [optional] |

### Return type

[**\OpenAPI\Client\Model\RecCompleta**](../Model/RecCompleta.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `recIdRecPatch()`

```php
recIdRecPatch($id_rec, $rec_revisada): \OpenAPI\Client\Model\RecGerada
```

Revisar recorrência.

Revisar recorrência.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\RecApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$id_rec = 'id_rec_example'; // string
$rec_revisada = new \OpenAPI\Client\Model\RecRevisada(); // \OpenAPI\Client\Model\RecRevisada | Dados para revisão da recorrência.

try {
    $result = $apiInstance->recIdRecPatch($id_rec, $rec_revisada);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling RecApi->recIdRecPatch: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **id_rec** | **string**|  | |
| **rec_revisada** | [**\OpenAPI\Client\Model\RecRevisada**](../Model/RecRevisada.md)| Dados para revisão da recorrência. | [optional] |

### Return type

[**\OpenAPI\Client\Model\RecGerada**](../Model/RecGerada.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `recPost()`

```php
recPost($rec_solicitada): \OpenAPI\Client\Model\RecGerada
```

Criar recorrência.

Criar recorrência

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\RecApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$rec_solicitada = new \OpenAPI\Client\Model\RecSolicitada(); // \OpenAPI\Client\Model\RecSolicitada | Dados para geração da recorrência.

try {
    $result = $apiInstance->recPost($rec_solicitada);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling RecApi->recPost: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **rec_solicitada** | [**\OpenAPI\Client\Model\RecSolicitada**](../Model/RecSolicitada.md)| Dados para geração da recorrência. | [optional] |

### Return type

[**\OpenAPI\Client\Model\RecGerada**](../Model/RecGerada.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
