# # Indexer

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**rate** | **string** | Percentual que corresponde a mediana da taxa pré fixada cobrada do cliente pela contratação do crédito, no intervalo informado.  Ex: 0.087000 &#x3D; 8,7%. Nos casos de produtos puramente pós fixados, as faixas 1,2, 3 e 4 deverão receber o valor 0.000000 (zero).  Nesse caso, o customers/rate deverá ser representado com 1.000000, ou seja, 100%.  A apuração pode acontecer com até 6 casas decimais. O preenchimento deve respeitar as 6 casas decimais, mesmo que venham preenchidas com zeros (representação de porcentagem p.ex: 0.150000. Este valor representa 15%. O valor 1.000000 representa 100%). | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
