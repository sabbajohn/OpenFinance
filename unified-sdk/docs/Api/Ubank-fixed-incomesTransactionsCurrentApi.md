# OpenAPI\Client\TransactionsCurrentApi



All URIs are relative to https://api.banco.com.br/open-banking/bank-fixed-incomes/v1, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**banktFixedIncomesGetInvestmentsInvestmentIdTransactionsCurrent()**](TransactionsCurrentApi.md#banktFixedIncomesGetInvestmentsInvestmentIdTransactionsCurrent) | **GET** /investments/{investmentId}/transactions-current | Obtém as movimentações recentes da operação de Renda Fixa Bancária identificada por investmentId. O período a ser considerado para apresentação de movimentações será de até 7 dias - 7 dias anteriores da consulta, incluindo o dia da consulta (D-6). |


## `banktFixedIncomesGetInvestmentsInvestmentIdTransactionsCurrent()`

```php
banktFixedIncomesGetInvestmentsInvestmentIdTransactionsCurrent($investment_id, $authorization, $x_fapi_interaction_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $page, $page_size, $pagination_key, $from_transaction_date, $to_transaction_date): \OpenAPI\Client\Model\ResponseBankFixedIncomesTransactions
```

Obtém as movimentações recentes da operação de Renda Fixa Bancária identificada por investmentId. O período a ser considerado para apresentação de movimentações será de até 7 dias - 7 dias anteriores da consulta, incluindo o dia da consulta (D-6).

Obtém as movimentações recentes da operação de Renda Fixa Bancária identificada por investmentId. O período a ser considerado para apresentação de movimentações será de até 7 dias - 7 dias anteriores da consulta, incluindo o dia da consulta (D-6).

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2AuthorizationCode
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\TransactionsCurrentApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$investment_id = 92792126019929200000000000000000000000000; // string | Identifica de forma única  o relacionamento do cliente com o produto, mantendo as regras de imutabilidade dentro da instituição transmissora.
$authorization = 'authorization_example'; // string | Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado
$x_fapi_interaction_id = 'x_fapi_interaction_id_example'; // string | Um UUID RFC4122 usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser \"espelhado\" pela transmissora (server) no cabeçalho de resposta.
$x_fapi_auth_date = 'x_fapi_auth_date_example'; // string | Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC
$x_fapi_customer_ip_address = 'x_fapi_customer_ip_address_example'; // string | O endereço IP do usuário se estiver atualmente logado com o receptor.
$x_customer_user_agent = 'x_customer_user_agent_example'; // string | Indica o user-agent que o usuário utiliza.
$page = 1; // int | Número da página que está sendo requisitada (o valor da primeira página é 1).
$page_size = 25; // int | Quantidade total de registros por páginas. A transmissora deve considerar entrada como 25, caso seja informado algum valor menor pela receptora. Enquanto houver mais que 25 registros a enviar, a transmissora deve considerar o mínimo por página como 25. Somente a última página retornada (ou primeira, no caso de página única) pode conter menos de 25 registros. Mais informações, acesse Especificações de APIs > Padrões > Paginação.
$pagination_key = 'pagination_key_example'; // string | Identificador de rechamada, utilizado para evitar a contagem de chamadas ao endpoint durante a paginação.
$from_transaction_date = Thu May 20 21:00:00 BRT 2021; // \DateTime | Data inicial de filtragem. O período máximo utilizado no filtro é de 7 dias inclusive (D-6).  [Restrição] Deve obrigatoriamente ser enviado caso o campo toTransactionDate seja informado. Caso não seja informado, deve ser assumido o dia atual.
$to_transaction_date = Thu May 20 21:00:00 BRT 2021; // \DateTime | Data final de filtragem. O período máximo utilizado no filtro é de 7 dias inclusive (D-6).  [Restrição] Deve obrigatoriamente ser enviado caso o campo fromTransactionDate seja informado. Caso não seja informado, deve ser assumido o dia atual.

try {
    $result = $apiInstance->banktFixedIncomesGetInvestmentsInvestmentIdTransactionsCurrent($investment_id, $authorization, $x_fapi_interaction_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $page, $page_size, $pagination_key, $from_transaction_date, $to_transaction_date);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling TransactionsCurrentApi->banktFixedIncomesGetInvestmentsInvestmentIdTransactionsCurrent: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **investment_id** | **string**| Identifica de forma única  o relacionamento do cliente com o produto, mantendo as regras de imutabilidade dentro da instituição transmissora. | |
| **authorization** | **string**| Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado | |
| **x_fapi_interaction_id** | **string**| Um UUID RFC4122 usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser \&quot;espelhado\&quot; pela transmissora (server) no cabeçalho de resposta. | |
| **x_fapi_auth_date** | **string**| Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC | [optional] |
| **x_fapi_customer_ip_address** | **string**| O endereço IP do usuário se estiver atualmente logado com o receptor. | [optional] |
| **x_customer_user_agent** | **string**| Indica o user-agent que o usuário utiliza. | [optional] |
| **page** | **int**| Número da página que está sendo requisitada (o valor da primeira página é 1). | [optional] [default to 1] |
| **page_size** | **int**| Quantidade total de registros por páginas. A transmissora deve considerar entrada como 25, caso seja informado algum valor menor pela receptora. Enquanto houver mais que 25 registros a enviar, a transmissora deve considerar o mínimo por página como 25. Somente a última página retornada (ou primeira, no caso de página única) pode conter menos de 25 registros. Mais informações, acesse Especificações de APIs &gt; Padrões &gt; Paginação. | [optional] [default to 25] |
| **pagination_key** | **string**| Identificador de rechamada, utilizado para evitar a contagem de chamadas ao endpoint durante a paginação. | [optional] |
| **from_transaction_date** | **\DateTime**| Data inicial de filtragem. O período máximo utilizado no filtro é de 7 dias inclusive (D-6).  [Restrição] Deve obrigatoriamente ser enviado caso o campo toTransactionDate seja informado. Caso não seja informado, deve ser assumido o dia atual. | [optional] |
| **to_transaction_date** | **\DateTime**| Data final de filtragem. O período máximo utilizado no filtro é de 7 dias inclusive (D-6).  [Restrição] Deve obrigatoriamente ser enviado caso o campo fromTransactionDate seja informado. Caso não seja informado, deve ser assumido o dia atual. | [optional] |

### Return type

[**\OpenAPI\Client\Model\ResponseBankFixedIncomesTransactions**](../Model/ResponseBankFixedIncomesTransactions.md)

### Authorization

[OAuth2AuthorizationCode](../../README.md#OAuth2AuthorizationCode)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/json; charset=utf-8`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
