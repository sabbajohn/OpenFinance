# OpenAPI\Client\PixApi

reúne endpoints destinados a lidar com  gerenciamento de Pix recebidos.

All URIs are relative to https://pix.example.com/api, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**pixE2eidDevolucaoIdGet()**](PixApi.md#pixE2eidDevolucaoIdGet) | **GET** /pix/{e2eid}/devolucao/{id} | Consultar devolução. |
| [**pixE2eidDevolucaoIdPut()**](PixApi.md#pixE2eidDevolucaoIdPut) | **PUT** /pix/{e2eid}/devolucao/{id} | Solicitar devolução. |
| [**pixE2eidGet()**](PixApi.md#pixE2eidGet) | **GET** /pix/{e2eid} | Consultar Pix. |
| [**pixGet()**](PixApi.md#pixGet) | **GET** /pix | Consultar Pix recebidos. |


## `pixE2eidDevolucaoIdGet()`

```php
pixE2eidDevolucaoIdGet($e2eid, $id): \OpenAPI\Client\Model\Devolucao
```

Consultar devolução.

Endpoint para consultar uma devolução através de um End To End ID do Pix e do ID da devolução

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\PixApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$e2eid = 'e2eid_example'; // string
$id = 'id_example'; // string

try {
    $result = $apiInstance->pixE2eidDevolucaoIdGet($e2eid, $id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PixApi->pixE2eidDevolucaoIdGet: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **e2eid** | **string**|  | |
| **id** | **string**|  | |

### Return type

[**\OpenAPI\Client\Model\Devolucao**](../Model/Devolucao.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `pixE2eidDevolucaoIdPut()`

```php
pixE2eidDevolucaoIdPut($e2eid, $id, $devolucao_solicitada): \OpenAPI\Client\Model\Devolucao
```

Solicitar devolução.

Endpoint para solicitar uma devolução através de um e2eid do Pix e do ID da devolução. O motivo que será atribuído à PACS.004 será \"MD06\" ou \"SL02\" de acordo com a aba RTReason da PACS.004 que consta no Catálogo de Mensagens do Pix a depender da `natureza` da devolução (Vide a descrição deste campo).

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\PixApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$e2eid = 'e2eid_example'; // string
$id = 'id_example'; // string
$devolucao_solicitada = new \OpenAPI\Client\Model\DevolucaoSolicitada(); // \OpenAPI\Client\Model\DevolucaoSolicitada | Dados para pedido de devolução.

try {
    $result = $apiInstance->pixE2eidDevolucaoIdPut($e2eid, $id, $devolucao_solicitada);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PixApi->pixE2eidDevolucaoIdPut: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **e2eid** | **string**|  | |
| **id** | **string**|  | |
| **devolucao_solicitada** | [**\OpenAPI\Client\Model\DevolucaoSolicitada**](../Model/DevolucaoSolicitada.md)| Dados para pedido de devolução. | |

### Return type

[**\OpenAPI\Client\Model\Devolucao**](../Model/Devolucao.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `pixE2eidGet()`

```php
pixE2eidGet($e2eid): \OpenAPI\Client\Model\Pix
```

Consultar Pix.

Endpoint para consultar um Pix através de um e2eid.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\PixApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$e2eid = 'e2eid_example'; // string

try {
    $result = $apiInstance->pixE2eidGet($e2eid);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PixApi->pixE2eidGet: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **e2eid** | **string**|  | |

### Return type

[**\OpenAPI\Client\Model\Pix**](../Model/Pix.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `pixGet()`

```php
pixGet($inicio, $fim, $txid, $tx_id_presente, $devolucao_presente, $cpf, $cnpj, $paginacao_pagina_atual, $paginacao_itens_por_pagina): \OpenAPI\Client\Model\PixConsultados
```

Consultar Pix recebidos.

Endpoint para consultar Pix recebidos

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\PixApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$inicio = new \DateTime('2013-10-20T19:20:30+01:00'); // \DateTime
$fim = new \DateTime('2013-10-20T19:20:30+01:00'); // \DateTime
$txid = 'txid_example'; // string
$tx_id_presente = True; // bool
$devolucao_presente = True; // bool
$cpf = 'cpf_example'; // string
$cnpj = 'cnpj_example'; // string
$paginacao_pagina_atual = 0; // int
$paginacao_itens_por_pagina = 100; // int

try {
    $result = $apiInstance->pixGet($inicio, $fim, $txid, $tx_id_presente, $devolucao_presente, $cpf, $cnpj, $paginacao_pagina_atual, $paginacao_itens_por_pagina);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PixApi->pixGet: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **inicio** | **\DateTime**|  | |
| **fim** | **\DateTime**|  | |
| **txid** | [**string**](../Model/.md)|  | [optional] |
| **tx_id_presente** | **bool**|  | [optional] |
| **devolucao_presente** | **bool**|  | [optional] |
| **cpf** | **string**|  | [optional] |
| **cnpj** | **string**|  | [optional] |
| **paginacao_pagina_atual** | **int**|  | [optional] [default to 0] |
| **paginacao_itens_por_pagina** | **int**|  | [optional] [default to 100] |

### Return type

[**\OpenAPI\Client\Model\PixConsultados**](../Model/PixConsultados.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
