# OpenAPI\Client\SolicRecApi

Reúne endpoints destinados a lidar com gerenciamento de solicitações de recorrências.

All URIs are relative to https://pix.example.com/api, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**solicrecIdSolicRecGet()**](SolicRecApi.md#solicrecIdSolicRecGet) | **GET** /solicrec/{idSolicRec} | Consultar solicitação de confirmação de recorrência. |
| [**solicrecIdSolicRecPatch()**](SolicRecApi.md#solicrecIdSolicRecPatch) | **PATCH** /solicrec/{idSolicRec} | Revisar solicitação de confirmação de recorrência. |
| [**solicrecPost()**](SolicRecApi.md#solicrecPost) | **POST** /solicrec | Criar solicitação de confirmação de recorrência. |


## `solicrecIdSolicRecGet()`

```php
solicrecIdSolicRecGet($id_solic_rec): \OpenAPI\Client\Model\SolicRecCompleta
```

Consultar solicitação de confirmação de recorrência.

Consultar solicitação.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\SolicRecApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$id_solic_rec = 'id_solic_rec_example'; // string

try {
    $result = $apiInstance->solicrecIdSolicRecGet($id_solic_rec);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling SolicRecApi->solicrecIdSolicRecGet: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **id_solic_rec** | **string**|  | |

### Return type

[**\OpenAPI\Client\Model\SolicRecCompleta**](../Model/SolicRecCompleta.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `solicrecIdSolicRecPatch()`

```php
solicrecIdSolicRecPatch($id_solic_rec, $solic_rec_revisada): \OpenAPI\Client\Model\SolicRecCompleta
```

Revisar solicitação de confirmação de recorrência.

Revisar solicitação de confirmação de recorrência.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\SolicRecApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$id_solic_rec = 'id_solic_rec_example'; // string
$solic_rec_revisada = new \OpenAPI\Client\Model\SolicRecRevisada(); // \OpenAPI\Client\Model\SolicRecRevisada | Dados para revisão da solicitação da recorrência.

try {
    $result = $apiInstance->solicrecIdSolicRecPatch($id_solic_rec, $solic_rec_revisada);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling SolicRecApi->solicrecIdSolicRecPatch: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **id_solic_rec** | **string**|  | |
| **solic_rec_revisada** | [**\OpenAPI\Client\Model\SolicRecRevisada**](../Model/SolicRecRevisada.md)| Dados para revisão da solicitação da recorrência. | [optional] |

### Return type

[**\OpenAPI\Client\Model\SolicRecCompleta**](../Model/SolicRecCompleta.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `solicrecPost()`

```php
solicrecPost($solic_rec_solicitada): \OpenAPI\Client\Model\SolicRecCompleta
```

Criar solicitação de confirmação de recorrência.

Criar solicitação de confirmação de recorrência.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\SolicRecApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$solic_rec_solicitada = new \OpenAPI\Client\Model\SolicRecSolicitada(); // \OpenAPI\Client\Model\SolicRecSolicitada | Dados para geração da solicitação da recorrência.

try {
    $result = $apiInstance->solicrecPost($solic_rec_solicitada);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling SolicRecApi->solicrecPost: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **solic_rec_solicitada** | [**\OpenAPI\Client\Model\SolicRecSolicitada**](../Model/SolicRecSolicitada.md)| Dados para geração da solicitação da recorrência. | [optional] |

### Return type

[**\OpenAPI\Client\Model\SolicRecCompleta**](../Model/SolicRecCompleta.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
