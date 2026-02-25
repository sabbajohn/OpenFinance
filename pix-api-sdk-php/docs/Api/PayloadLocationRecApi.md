# OpenAPI\Client\PayloadLocationRecApi

Reúne endpoints destinados a lidar com configuração e remoção de locations para uso dos payloads de recorrências

All URIs are relative to https://pix.example.com/api, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**locrecGet()**](PayloadLocationRecApi.md#locrecGet) | **GET** /locrec | Consultar locations cadastradas. |
| [**locrecIdGet()**](PayloadLocationRecApi.md#locrecIdGet) | **GET** /locrec/{id} | Recuperar location do payload. |
| [**locrecIdIdRecDelete()**](PayloadLocationRecApi.md#locrecIdIdRecDelete) | **DELETE** /locrec/{id}/idRec | Desvincular uma recorrência de uma location. |
| [**locrecPost()**](PayloadLocationRecApi.md#locrecPost) | **POST** /locrec | Criar location do payload. |


## `locrecGet()`

```php
locrecGet($inicio, $fim, $id_rec_presente, $convenio, $paginacao_pagina_atual, $paginacao_itens_por_pagina): \OpenAPI\Client\Model\PayloadLocationRecConsultadas
```

Consultar locations cadastradas.

Endpoint para consultar locations cadastradas

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\PayloadLocationRecApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$inicio = new \DateTime('2013-10-20T19:20:30+01:00'); // \DateTime
$fim = new \DateTime('2013-10-20T19:20:30+01:00'); // \DateTime
$id_rec_presente = True; // bool
$convenio = 'convenio_example'; // string
$paginacao_pagina_atual = 0; // int
$paginacao_itens_por_pagina = 100; // int

try {
    $result = $apiInstance->locrecGet($inicio, $fim, $id_rec_presente, $convenio, $paginacao_pagina_atual, $paginacao_itens_por_pagina);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PayloadLocationRecApi->locrecGet: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **inicio** | **\DateTime**|  | |
| **fim** | **\DateTime**|  | |
| **id_rec_presente** | **bool**|  | [optional] |
| **convenio** | **string**|  | [optional] |
| **paginacao_pagina_atual** | **int**|  | [optional] [default to 0] |
| **paginacao_itens_por_pagina** | **int**|  | [optional] [default to 100] |

### Return type

[**\OpenAPI\Client\Model\PayloadLocationRecConsultadas**](../Model/PayloadLocationRecConsultadas.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `locrecIdGet()`

```php
locrecIdGet($id): \OpenAPI\Client\Model\PayloadLocationRecCompleta
```

Recuperar location do payload.

Recupera a location do payload

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\PayloadLocationRecApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$id = 'id_example'; // string

try {
    $result = $apiInstance->locrecIdGet($id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PayloadLocationRecApi->locrecIdGet: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **id** | **string**|  | |

### Return type

[**\OpenAPI\Client\Model\PayloadLocationRecCompleta**](../Model/PayloadLocationRecCompleta.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `locrecIdIdRecDelete()`

```php
locrecIdIdRecDelete($id): \OpenAPI\Client\Model\PayloadLocationRecCompleta
```

Desvincular uma recorrência de uma location.

Endpoint utilizado para desvincular uma recorrência de uma location.  Se executado com sucesso, a entidade `loc` não apresentará mais uma recorrência, se apresentava anteriormente à chamada. Adicionalmente, a entidade associada ao recurso desvinculado também passará a não mais apresentar um _location_. Esta operação não altera o `status` do recurso em questão.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\PayloadLocationRecApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$id = 'id_example'; // string

try {
    $result = $apiInstance->locrecIdIdRecDelete($id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PayloadLocationRecApi->locrecIdIdRecDelete: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **id** | **string**|  | |

### Return type

[**\OpenAPI\Client\Model\PayloadLocationRecCompleta**](../Model/PayloadLocationRecCompleta.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `locrecPost()`

```php
locrecPost(): \OpenAPI\Client\Model\PayloadLocationRecGerada
```

Criar location do payload.

Criar location do payload

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\PayloadLocationRecApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);

try {
    $result = $apiInstance->locrecPost();
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PayloadLocationRecApi->locrecPost: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

This endpoint does not need any parameter.

### Return type

[**\OpenAPI\Client\Model\PayloadLocationRecGerada**](../Model/PayloadLocationRecGerada.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
