# OpenAPI\Client\BankFixedIncomesApi



All URIs are relative to https://api.banco.com.br/open-banking/opendata-investments/v1, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**investmentsGetFixedIncomeBank()**](BankFixedIncomesApi.md#investmentsGetFixedIncomeBank) | **GET** /bank-fixed-incomes | Conjunto de informações de produtos de Renda Fixa Bancária (CDB, RDB, LCI e LCA) |


## `investmentsGetFixedIncomeBank()`

```php
investmentsGetFixedIncomeBank($page, $page_size): \OpenAPI\Client\Model\OKResponseInvestmentsFixedIncomeBank
```

Conjunto de informações de produtos de Renda Fixa Bancária (CDB, RDB, LCI e LCA)

Método para obter a lista de produtos de Renda Fixa Bancária (CDB, RDB, LCI e LCA)

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\BankFixedIncomesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$page = 1; // int | Número da página que está sendo requisitada (o valor da primeira página é 1).
$page_size = 25; // int | Quantidade total de registros por páginas.

try {
    $result = $apiInstance->investmentsGetFixedIncomeBank($page, $page_size);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling BankFixedIncomesApi->investmentsGetFixedIncomeBank: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **page** | **int**| Número da página que está sendo requisitada (o valor da primeira página é 1). | [optional] [default to 1] |
| **page_size** | **int**| Quantidade total de registros por páginas. | [optional] [default to 25] |

### Return type

[**\OpenAPI\Client\Model\OKResponseInvestmentsFixedIncomeBank**](../Model/OKResponseInvestmentsFixedIncomeBank.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/json; charset=utf-8`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
