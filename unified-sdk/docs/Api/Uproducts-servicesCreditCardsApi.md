# OpenAPI\Client\CreditCardsApi



All URIs are relative to http://api.banco.com.br/open-banking/products-services/v2, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**getBusinessCreditCards()**](CreditCardsApi.md#getBusinessCreditCards) | **GET** /business-credit-cards | Obtém dados sobre cartões de crédito pessoa jurídica |
| [**getPersonalCreditCards()**](CreditCardsApi.md#getPersonalCreditCards) | **GET** /personal-credit-cards | Obtém dados sobre cartões de crédito pessoa natural |


## `getBusinessCreditCards()`

```php
getBusinessCreditCards(): \OpenAPI\Client\Model\BusinessCreditCardResponse
```

Obtém dados sobre cartões de crédito pessoa jurídica

Obtém dados sobre cartões de crédito pessoa jurídica

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\CreditCardsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);

try {
    $result = $apiInstance->getBusinessCreditCards();
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling CreditCardsApi->getBusinessCreditCards: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

This endpoint does not need any parameter.

### Return type

[**\OpenAPI\Client\Model\BusinessCreditCardResponse**](../Model/BusinessCreditCardResponse.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getPersonalCreditCards()`

```php
getPersonalCreditCards(): \OpenAPI\Client\Model\PersonalCreditCardResponse
```

Obtém dados sobre cartões de crédito pessoa natural

Obtém dados sobre cartões de crédito pessoa natural

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\CreditCardsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);

try {
    $result = $apiInstance->getPersonalCreditCards();
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling CreditCardsApi->getPersonalCreditCards: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

This endpoint does not need any parameter.

### Return type

[**\OpenAPI\Client\Model\PersonalCreditCardResponse**](../Model/PersonalCreditCardResponse.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
