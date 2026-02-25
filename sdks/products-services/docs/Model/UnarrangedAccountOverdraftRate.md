# # UnarrangedAccountOverdraftRate

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**referential_rate_indexer** | [**\OpenAPI\Client\Model\ReferentialRateIndexer**](ReferentialRateIndexer.md) |  |
**rate** | **string** | Percentual que incide sobre a composição das taxas de juros remuneratórios. (representa uma porcentagem Ex: 0.15 (O valor ao lado representa 15%. O valor &#39;1 &#39;representa 100%). A apuração pode acontecer com até 4 casas decimais. O preenchimento deve respeitar as 4 casas decimais, mesmo que venham preenchidas com zeros (representação de porcentagem p.ex: 0.1500. Este valor representa 15%. O valor 1 representa 100%) |
**applications** | [**\OpenAPI\Client\Model\ApplicationRate[]**](ApplicationRate.md) | Lista  das faixas de cobrança da taxa efetiva de remuneração. |
**minimum_rate** | **string** | Percentual mínimo cobrado (taxa efetiva) no mês de referência, para o crédito contratado A apuração pode acontecer com até 4 casas decimais. O preenchimento deve respeitar as 4 casas decimais, mesmo que venham preenchidas com zeros (representação de porcentagem p.ex: 0.1500. Este valor representa 15%. O valor 1 representa 100%) |
**maximum_rate** | **string** | Percentual máximo cobrado (taxa efetiva) no mês de referência, para o crédito contratado A apuração pode acontecer com até 4 casas decimais. O preenchimento deve respeitar as 4 casas decimais, mesmo que venham preenchidas com zeros (representação de porcentagem p.ex: 0.1500. Este valor representa 15%. O valor 1 representa 100%) |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
