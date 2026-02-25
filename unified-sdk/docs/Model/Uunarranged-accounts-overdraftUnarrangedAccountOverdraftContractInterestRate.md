# # UnarrangedAccountOverdraftContractInterestRate

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**tax_type** | [**\OpenAPI\Client\Model\EnumContractTaxType**](EnumContractTaxType.md) |  |
**interest_rate_type** | [**\OpenAPI\Client\Model\EnumContractInterestRateType**](EnumContractInterestRateType.md) |  |
**tax_periodicity** | [**\OpenAPI\Client\Model\EnumContractTaxPeriodicity**](EnumContractTaxPeriodicity.md) |  |
**calculation** | **string** | Base de cálculo |
**referential_rate_indexer_type** | [**\OpenAPI\Client\Model\EnumContractReferentialRateIndexerType**](EnumContractReferentialRateIndexerType.md) |  |
**referential_rate_indexer_sub_type** | [**\OpenAPI\Client\Model\EnumContractReferentialRateIndexerSubType**](EnumContractReferentialRateIndexerSubType.md) |  | [optional]
**referential_rate_indexer_additional_info** | **string** | Campo livre para complementar a informação relativa ao Tipo de taxa referencial ou indexador. [Restrição] Obrigatório para complementar a informação relativa ao Tipo de taxa referencial ou indexador, quando selecionada o tipo ou subtipo OUTRO. | [optional]
**pre_fixed_rate** | **float** | Taxa pré-fixada aplicada sob o contrato da modalidade crédito. p.ex. 0.014500. O preenchimento deve respeitar as 6 casas decimais, mesmo que venham preenchidas com zeros(representação de porcentagem p.ex: 0.150000. Este valor representa 15%. O valor 1 representa 100%).  [Restrição] Este campo é de envio obrigatório caso não seja enviado o campo &#x60;postFixedRate&#x60;. | [optional]
**post_fixed_rate** | **float** | Taxa pós-fixada aplicada sob o contrato da modalidade crédito. p.ex. 0.0045. O preenchimento deve respeitar as 6 casas decimais, mesmo que venham preenchidas com zeros (representação de porcentagem p.ex: 0.1500. Este valor representa 15%. O valor 1 representa 100%).  [Restrição] Este campo é de envio obrigatório caso não seja enviado o campo &#x60;preFixedRate&#x60;. | [optional]
**additional_info** | **string** | Texto com informações adicionais sobre a composição das taxas de juros pactuadas.   [Restrição] Caso a instituição possua a informação para compartilhamento, esta deverá ser informada. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
