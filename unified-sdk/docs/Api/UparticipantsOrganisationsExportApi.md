# OpenAPI\Client\OrganisationsExportApi



All URIs are relative to https://data.directory.openbankingbrasil.org.br, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**participantsGet()**](OrganisationsExportApi.md#participantsGet) | **GET** /participants | Recupera informações técnicas sobre Participantes registrados no diretório, essas informações permitem identificar e consumir as APIs dos participantes |


## `participantsGet()`

```php
participantsGet(): \OpenAPI\Client\Model\OrganisationExportOpenData[]
```

Recupera informações técnicas sobre Participantes registrados no diretório, essas informações permitem identificar e consumir as APIs dos participantes

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\OrganisationsExportApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);

try {
    $result = $apiInstance->participantsGet();
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling OrganisationsExportApi->participantsGet: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

This endpoint does not need any parameter.

### Return type

[**\OpenAPI\Client\Model\OrganisationExportOpenData[]**](../Model/OrganisationExportOpenData.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
