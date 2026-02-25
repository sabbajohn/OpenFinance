# OpenAPI\Client\PayloadLocationApi

Reúne endpoints destinados a lidar com configuração e remoção de locations para uso dos payloads

All URIs are relative to https://pix.example.com/api, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**locGet()**](PayloadLocationApi.md#locGet) | **GET** /loc | Consultar locations cadastradas. |
| [**locIdGet()**](PayloadLocationApi.md#locIdGet) | **GET** /loc/{id} | Recuperar location do payload. |
| [**locIdTxidDelete()**](PayloadLocationApi.md#locIdTxidDelete) | **DELETE** /loc/{id}/txid | Desvincular uma cobrança de uma location. |
| [**locPost()**](PayloadLocationApi.md#locPost) | **POST** /loc | Criar location do payload. |


## `locGet()`

```php
locGet($inicio, $fim, $tx_id_presente, $tipo_cob, $paginacao_pagina_atual, $paginacao_itens_por_pagina): \OpenAPI\Client\Model\PayloadLocationConsultadas
```

Consultar locations cadastradas.

Endpoint para consultar locations cadastradas

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\PayloadLocationApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$inicio = new \DateTime('2013-10-20T19:20:30+01:00'); // \DateTime
$fim = new \DateTime('2013-10-20T19:20:30+01:00'); // \DateTime
$tx_id_presente = True; // bool
$tipo_cob = 'tipo_cob_example'; // string
$paginacao_pagina_atual = 0; // int
$paginacao_itens_por_pagina = 100; // int

try {
    $result = $apiInstance->locGet($inicio, $fim, $tx_id_presente, $tipo_cob, $paginacao_pagina_atual, $paginacao_itens_por_pagina);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PayloadLocationApi->locGet: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **inicio** | **\DateTime**|  | |
| **fim** | **\DateTime**|  | |
| **tx_id_presente** | **bool**|  | [optional] |
| **tipo_cob** | **string**|  | [optional] |
| **paginacao_pagina_atual** | **int**|  | [optional] [default to 0] |
| **paginacao_itens_por_pagina** | **int**|  | [optional] [default to 100] |

### Return type

[**\OpenAPI\Client\Model\PayloadLocationConsultadas**](../Model/PayloadLocationConsultadas.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `locIdGet()`

```php
locIdGet($id): \OpenAPI\Client\Model\PayloadLocationCompleta
```

Recuperar location do payload.

Recupera a location do payload

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\PayloadLocationApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$id = 'id_example'; // string

try {
    $result = $apiInstance->locIdGet($id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PayloadLocationApi->locIdGet: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **id** | **string**|  | |

### Return type

[**\OpenAPI\Client\Model\PayloadLocationCompleta**](../Model/PayloadLocationCompleta.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `locIdTxidDelete()`

```php
locIdTxidDelete($id): \OpenAPI\Client\Model\PayloadLocation
```

Desvincular uma cobrança de uma location.

Endpoint utilizado para desvincular uma cobrança de uma location.  Se executado com sucesso, a entidade `loc` não apresentará mais um txid, se apresentava anteriormente à chamada. Adicionalmente, a entidade `cob` ou `cobv` associada ao txid desvinculado também passará a não mais apresentar um _location_. Esta operação não altera o `status` da `cob` ou `cobv` em questão.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\PayloadLocationApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$id = 'id_example'; // string

try {
    $result = $apiInstance->locIdTxidDelete($id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PayloadLocationApi->locIdTxidDelete: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **id** | **string**|  | |

### Return type

[**\OpenAPI\Client\Model\PayloadLocation**](../Model/PayloadLocation.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `locPost()`

```php
locPost($payload_location_solicitada): \OpenAPI\Client\Model\PayloadLocation
```

Criar location do payload.

Criar location do payload

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\PayloadLocationApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$payload_location_solicitada = new \OpenAPI\Client\Model\PayloadLocationSolicitada(); // \OpenAPI\Client\Model\PayloadLocationSolicitada | Dados para geração da location.

try {
    $result = $apiInstance->locPost($payload_location_solicitada);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PayloadLocationApi->locPost: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **payload_location_solicitada** | [**\OpenAPI\Client\Model\PayloadLocationSolicitada**](../Model/PayloadLocationSolicitada.md)| Dados para geração da location. | |

### Return type

[**\OpenAPI\Client\Model\PayloadLocation**](../Model/PayloadLocation.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
