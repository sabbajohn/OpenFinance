# OpenAPI\Client\ChannelsApi

Operações para listagem de canais de atendimentos

All URIs are relative to http://api.banco.com.br/open-banking/channels/v1, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**getBankingAgents()**](ChannelsApi.md#getBankingAgents) | **GET** /banking-agents | Obtém a lista de correspondentes bancários da instituição financeira. |
| [**getBranches()**](ChannelsApi.md#getBranches) | **GET** /branches | Obtém a lista de dependências próprias da instituição financeira. |
| [**getElectronicChannels()**](ChannelsApi.md#getElectronicChannels) | **GET** /electronic-channels | Obtém a lista de canais eletrônicos de atendimento da instituição financeira. |
| [**getPhoneChannels()**](ChannelsApi.md#getPhoneChannels) | **GET** /phone-channels | Obtém a lista de canais telefônicos de atendimento da instituição financeira. |
| [**getSharedAutomatedTellerMachines()**](ChannelsApi.md#getSharedAutomatedTellerMachines) | **GET** /shared-automated-teller-machines | Obtém a lista de terminais compartilhados de autoatendimento. |


## `getBankingAgents()`

```php
getBankingAgents(): \OpenAPI\Client\Model\ResponseBankingAgentsList
```

Obtém a lista de correspondentes bancários da instituição financeira.

Método para obter a lista de correspondentes bancários da instituição financeira.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\ChannelsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);

try {
    $result = $apiInstance->getBankingAgents();
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ChannelsApi->getBankingAgents: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

This endpoint does not need any parameter.

### Return type

[**\OpenAPI\Client\Model\ResponseBankingAgentsList**](../Model/ResponseBankingAgentsList.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getBranches()`

```php
getBranches(): \OpenAPI\Client\Model\ResponseBranchesList
```

Obtém a lista de dependências próprias da instituição financeira.

Método para obter a lista de dependências próprias da instituição financeira.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\ChannelsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);

try {
    $result = $apiInstance->getBranches();
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ChannelsApi->getBranches: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

This endpoint does not need any parameter.

### Return type

[**\OpenAPI\Client\Model\ResponseBranchesList**](../Model/ResponseBranchesList.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getElectronicChannels()`

```php
getElectronicChannels(): \OpenAPI\Client\Model\ResponseElectronicChannelsList
```

Obtém a lista de canais eletrônicos de atendimento da instituição financeira.

Método para obter a lista de canais eletrônicos de atendimento da instituição financeira.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\ChannelsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);

try {
    $result = $apiInstance->getElectronicChannels();
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ChannelsApi->getElectronicChannels: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

This endpoint does not need any parameter.

### Return type

[**\OpenAPI\Client\Model\ResponseElectronicChannelsList**](../Model/ResponseElectronicChannelsList.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getPhoneChannels()`

```php
getPhoneChannels(): \OpenAPI\Client\Model\ResponsePhoneChannelsList
```

Obtém a lista de canais telefônicos de atendimento da instituição financeira.

Método para obter a lista de canais telefônicos de atendimento da instituição financeira.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\ChannelsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);

try {
    $result = $apiInstance->getPhoneChannels();
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ChannelsApi->getPhoneChannels: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

This endpoint does not need any parameter.

### Return type

[**\OpenAPI\Client\Model\ResponsePhoneChannelsList**](../Model/ResponsePhoneChannelsList.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getSharedAutomatedTellerMachines()`

```php
getSharedAutomatedTellerMachines(): \OpenAPI\Client\Model\ResponseSharedAutomatedTellerMachinesList
```

Obtém a lista de terminais compartilhados de autoatendimento.

Método para obter a lista de terminais compartilhados de autoatendimento da instituição financeira.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\ChannelsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);

try {
    $result = $apiInstance->getSharedAutomatedTellerMachines();
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ChannelsApi->getSharedAutomatedTellerMachines: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

This endpoint does not need any parameter.

### Return type

[**\OpenAPI\Client\Model\ResponseSharedAutomatedTellerMachinesList**](../Model/ResponseSharedAutomatedTellerMachinesList.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
