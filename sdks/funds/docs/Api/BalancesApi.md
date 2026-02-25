# OpenAPI\Client\BalancesApi

Obtém a posição da operação de Fundos de Investimento identificada por investmentId.

All URIs are relative to https://api.banco.com.br/open-banking/funds/v1, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**fundsGetInvestmentsInvestmentIdBalances()**](BalancesApi.md#fundsGetInvestmentsInvestmentIdBalances) | **GET** /investments/{investmentId}/balances | Obtém a posição da operação de Fundos de Investimento identificada por investmentId. |


## `fundsGetInvestmentsInvestmentIdBalances()`

```php
fundsGetInvestmentsInvestmentIdBalances($investment_id, $authorization, $x_fapi_interaction_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent): \OpenAPI\Client\Model\ResponseFundsBalances
```

Obtém a posição da operação de Fundos de Investimento identificada por investmentId.

Obtém a posição da operação de Fundos de Investimento identificada por investmentId.  Nos casos em que não houver posição para o investimento, ou seja, quantidade de ativos e valores monetários zerados, mas o mesmo ainda estiver no prazo de exposição (até 12 meses após a última movimentação), deve se retornar status code 200 e para o payload de retorno considerar os valores abaixo. Campos não obrigatórios não devem ser retornados:   - Valores monetários: 0.00 - Quantidade de ativos: 0.00 - Data da última posição: mesmo conteúdo (data) do campo requestDateTime, com exceção da fração correspondente ao horário

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure OAuth2 access token for authorization: OAuth2AuthorizationCode
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\BalancesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$investment_id = 92792126019929200000000000000000000000000; // string | Identifica de forma única o relacionamento do cliente com o fundo, mantendo as regras de imutabilidade dentro da instituição transmissora.
$authorization = 'authorization_example'; // string | Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado
$x_fapi_interaction_id = 'x_fapi_interaction_id_example'; // string | Um UUID RFC4122 usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser \"espelhado\" pela transmissora (server) no cabeçalho de resposta.
$x_fapi_auth_date = 'x_fapi_auth_date_example'; // string | Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a RFC7231. Exemplo: Sun, 10 Sep 2017 19:43:31 UTC.
$x_fapi_customer_ip_address = 'x_fapi_customer_ip_address_example'; // string | O endereço IP do usuário se estiver atualmente logado com o receptor.
$x_customer_user_agent = 'x_customer_user_agent_example'; // string | Indica o user-agent que o usuário utiliza.

try {
    $result = $apiInstance->fundsGetInvestmentsInvestmentIdBalances($investment_id, $authorization, $x_fapi_interaction_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling BalancesApi->fundsGetInvestmentsInvestmentIdBalances: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **investment_id** | **string**| Identifica de forma única o relacionamento do cliente com o fundo, mantendo as regras de imutabilidade dentro da instituição transmissora. | |
| **authorization** | **string**| Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado | |
| **x_fapi_interaction_id** | **string**| Um UUID RFC4122 usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser \&quot;espelhado\&quot; pela transmissora (server) no cabeçalho de resposta. | |
| **x_fapi_auth_date** | **string**| Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a RFC7231. Exemplo: Sun, 10 Sep 2017 19:43:31 UTC. | [optional] |
| **x_fapi_customer_ip_address** | **string**| O endereço IP do usuário se estiver atualmente logado com o receptor. | [optional] |
| **x_customer_user_agent** | **string**| Indica o user-agent que o usuário utiliza. | [optional] |

### Return type

[**\OpenAPI\Client\Model\ResponseFundsBalances**](../Model/ResponseFundsBalances.md)

### Authorization

[OAuth2AuthorizationCode](../../README.md#OAuth2AuthorizationCode)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/json; charset=utf-8`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
