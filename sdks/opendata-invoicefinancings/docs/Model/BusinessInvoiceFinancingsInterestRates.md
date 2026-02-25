# # BusinessInvoiceFinancingsInterestRates

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**referential_rate_indexer** | **string** | Tipos de taxas referenciais ou indexadores, conforme Anexo 5: Taxa referencial ou Indexador (Indx), do Documento 3040 |
**rate** | **string** | Percentual que representa o indexador Pós selecionado. Ex: 100% da TR &#x3D; 1.000000 da TR, 90% da TR &#x3D; 0.9000000.  Em casos em que não haja indexador, deve ser selecionado Sem Indexador no campo /referentialRateIndexe e representado o rate de 0.000000 (zero).  Em caso em que a taxa é somente Pré fixada, o rate também deverá ser colocado como 0.000000 (zero). A apuração pode acontecer com até 6 casas decimais. O preenchimento deve respeitar as 6 casas decimais, mesmo que venham preenchidas com zeros (representação de porcentagem - Ex: 0.150000 &#x3D; 15%. O valor 1.000000 representa 100%). |
**applications** | [**\OpenAPI\Client\Model\ApplicationRate[]**](ApplicationRate.md) | Lista  das faixas de cobrança da taxa efetiva de remuneração |
**minimum_rate** | **string** | Percentual mínimo cobrado (taxa efetiva) no mês de referência, para os Direitos Creditórios Descontados contratado  A apuração pode acontecer com até 4 casas decimais. O preenchimento deve respeitar as 4 casas decimais, mesmo que venham preenchidas com zeros (representação de porcentagem p.ex: 0.15. Este valor representa 15%. O valor 1 representa 100%) |
**maximum_rate** | **string** | Percentual máximo cobrado (taxa efetiva) no mês de referência, para os Direitos Creditórios Descontados contratado  A apuração pode acontecer com até 4 casas decimais. O preenchimento deve respeitar as 4 casas decimais, mesmo que venham preenchidas com zeros (representação de porcentagem p.ex: 0.15. Este valor representa 15%. O valor 1 representa 100%) |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
