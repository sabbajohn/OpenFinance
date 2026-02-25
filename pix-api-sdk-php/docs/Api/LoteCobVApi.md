# OpenAPI\Client\LoteCobVApi

Reúne endpoints destinados a lidar com gerenciamento de cobranças com vencimento em lote.

All URIs are relative to https://pix.example.com/api, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**lotecobvGet()**](LoteCobVApi.md#lotecobvGet) | **GET** /lotecobv | Consultar lotes de cobranças com vencimento. |
| [**lotecobvIdGet()**](LoteCobVApi.md#lotecobvIdGet) | **GET** /lotecobv/{id} | Consultar um lote específico de cobranças com vencimento. |
| [**lotecobvIdPatch()**](LoteCobVApi.md#lotecobvIdPatch) | **PATCH** /lotecobv/{id} | Utilizado para revisar cobranças específicas dentro de um lote de cobranças com vencimento. |
| [**lotecobvIdPut()**](LoteCobVApi.md#lotecobvIdPut) | **PUT** /lotecobv/{id} | Criar/Alterar lote de cobranças com vencimento. |


## `lotecobvGet()`

```php
lotecobvGet($inicio, $fim, $paginacao_pagina_atual, $paginacao_itens_por_pagina): \OpenAPI\Client\Model\LotesCobVConsultados
```

Consultar lotes de cobranças com vencimento.

Endpoint para consultar lista de lotes de cobranças com vencimento.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\LoteCobVApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$inicio = new \DateTime('2013-10-20T19:20:30+01:00'); // \DateTime
$fim = new \DateTime('2013-10-20T19:20:30+01:00'); // \DateTime
$paginacao_pagina_atual = 0; // int
$paginacao_itens_por_pagina = 100; // int

try {
    $result = $apiInstance->lotecobvGet($inicio, $fim, $paginacao_pagina_atual, $paginacao_itens_por_pagina);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling LoteCobVApi->lotecobvGet: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **inicio** | **\DateTime**|  | |
| **fim** | **\DateTime**|  | |
| **paginacao_pagina_atual** | **int**|  | [optional] [default to 0] |
| **paginacao_itens_por_pagina** | **int**|  | [optional] [default to 100] |

### Return type

[**\OpenAPI\Client\Model\LotesCobVConsultados**](../Model/LotesCobVConsultados.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `lotecobvIdGet()`

```php
lotecobvIdGet($id): \OpenAPI\Client\Model\LoteCobVConsultado
```

Consultar um lote específico de cobranças com vencimento.

Endpoint para consultar um lote de cobranças com vencimento.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\LoteCobVApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$id = 'id_example'; // string

try {
    $result = $apiInstance->lotecobvIdGet($id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling LoteCobVApi->lotecobvIdGet: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **id** | **string**|  | |

### Return type

[**\OpenAPI\Client\Model\LoteCobVConsultado**](../Model/LoteCobVConsultado.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `lotecobvIdPatch()`

```php
lotecobvIdPatch($id, $lotecobv_id_patch_request)
```

Utilizado para revisar cobranças específicas dentro de um lote de cobranças com vencimento.

Endpoint utilizado para revisar cobranças específicas dentro de um lote de cobranças com vencimento.   A diferença deste endpoint para o endpoint PUT correlato é que este endpoint admite um array `cobsv` com menos solicitações de criação ou alteração de cobranças do que o array atribuído na requisição originária do lote.  Não se pode, entretanto, utilizar esse endpoint para agregar ou remover solicitações de alteração ou criação de cobranças conforme constam na requisição originária do lote.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\LoteCobVApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$id = 'id_example'; // string
$lotecobv_id_patch_request = new \OpenAPI\Client\Model\LotecobvIdPatchRequest(); // \OpenAPI\Client\Model\LotecobvIdPatchRequest | Dados para geração de lote de cobranças com vencimento.

try {
    $apiInstance->lotecobvIdPatch($id, $lotecobv_id_patch_request);
} catch (Exception $e) {
    echo 'Exception when calling LoteCobVApi->lotecobvIdPatch: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **id** | **string**|  | |
| **lotecobv_id_patch_request** | [**\OpenAPI\Client\Model\LotecobvIdPatchRequest**](../Model/LotecobvIdPatchRequest.md)| Dados para geração de lote de cobranças com vencimento. | |

### Return type

void (empty response body)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `lotecobvIdPut()`

```php
lotecobvIdPut($id, $lotecobv_id_put_request)
```

Criar/Alterar lote de cobranças com vencimento.

Endpoint utilizado para criar ou alterar um lote de cobranças com vencimento.  Para o caso de uso de alteração de cobranças, o array a ser atribuído na requisicão deve ser composto pelas exatas requisições de criação de cobranças que constaram no array atribuído na requisição originária.  Não se pode utilizar este endpoint para _alterar_ um lote de cobranças com vencimento agregando ou removendo cobranças já existentes dentro do conjunto de cobranças criadas na requisição originária do lote.  Em outras palavras, se originalmente criou-se um lote, por exemplo, com as cobranças [`a`, `b` e `c`], não se pode _alterar_ esse conjunto de cobranças original que o lote representa para [`a`, `b`, `c`, `d`], ou para [`a`, `b`]. Por outro lado, pode-se alterar, _em lote_ as cobranças [`a`, `b`, `c`], conforme originalmente constam na requisição originária do lote.  Uma solicitação de __criação__ de cobrança com status \"EM_PROCESSAMENTO\" ou \"NEGADA\" está associada a uma cobrança não _existe_ de fato, portanto não será listada em `GET /cobv` ou `GET /cobv/{txid}`.  Uma cobrança, uma vez criada via `PUT /cobv/{txid}`, não pode ser associada a um lote posteriormente.  Uma cobrança, uma vez criada via `PUT /lotecobv/{id}`, não pode ser associada a um novo lote posteriormente.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\LoteCobVApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$id = 'id_example'; // string
$lotecobv_id_put_request = new \OpenAPI\Client\Model\LotecobvIdPutRequest(); // \OpenAPI\Client\Model\LotecobvIdPutRequest | Dados para geração de lote de cobranças com vencimento.

try {
    $apiInstance->lotecobvIdPut($id, $lotecobv_id_put_request);
} catch (Exception $e) {
    echo 'Exception when calling LoteCobVApi->lotecobvIdPut: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **id** | **string**|  | |
| **lotecobv_id_put_request** | [**\OpenAPI\Client\Model\LotecobvIdPutRequest**](../Model/LotecobvIdPutRequest.md)| Dados para geração de lote de cobranças com vencimento. | |

### Return type

void (empty response body)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
