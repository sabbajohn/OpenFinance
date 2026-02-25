# # FinancingsWarranties

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**currency** | **string** | Moeda referente ao valor da garantia, segundo modelo ISO-4217. p.ex. &#39;BRL&#39;. Todos os valores monetários informados estão representados com a moeda vigente do Brasil |
**warranty_type** | **string** | Denominação/Identificação do tipo da garantia que avaliza a Modalidade da Operação de Crédito contratada  (Doc 3040, Anexo 12) |
**warranty_sub_type** | **string** | Denominação/Identificação do sub tipo da garantia que avaliza a Modalidade da Operação de Crédito contratada (Doc 3040, Anexo 12). |
**warranty_amount** | **float** | Valor original da garantia. Expresso em valor monetário com no mínimo 2 casas e no máximo 4 casas decimais.  [Restrição] Para casos em que warrantyType for igual a \&quot;GARANTIA_FIDEJUSSORIA\&quot; o valor da garantia corresponde a uma porcentagem do total garantido.  Dessa forma, os casos de garantia fidejussória para os quais não é possível determinar um valor monetário para a garantia devem ser preenchidos com 0.00. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
