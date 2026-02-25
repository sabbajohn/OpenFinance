# # InvoiceFinancingsContractInterestRate

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**tax_type** | **string** | \&quot;Tipo de Taxa (vide  Enum) - NOMINAL (taxa nominal é uma taxa de juros em que a unidade referencial não coincide com a unidade de tempo da capitalização. Ela é sempre fornecida em termos anuais, e seus períodos de capitalização podem ser diários, mensais, trimestrais ou semestrais. p.ex. Uma taxa de 12% ao ano com capitalização mensal) - EFETIVA (É a taxa de juros em que a unidade referencial coincide com a unidade de tempo da capitalização. Como as unidades de medida de tempo da taxa de juros e dos períodos de capitalização são iguais, usa-se exemplos simples como 1% ao mês, 60% ao ano)\&quot; |
**interest_rate_type** | [**\OpenAPI\Client\Model\EnumContractInterestRateType**](EnumContractInterestRateType.md) |  |
**tax_periodicity** | [**\OpenAPI\Client\Model\EnumContractTaxPeriodicity**](EnumContractTaxPeriodicity.md) |  |
**calculation** | [**\OpenAPI\Client\Model\EnumContractCalculation**](EnumContractCalculation.md) |  |
**referential_rate_indexer_type** | [**\OpenAPI\Client\Model\EnumContractReferentialRateIndexerType**](EnumContractReferentialRateIndexerType.md) |  |
**referential_rate_indexer_sub_type** | [**\OpenAPI\Client\Model\EnumContractReferentialRateIndexerSubType**](EnumContractReferentialRateIndexerSubType.md) |  | [optional]
**referential_rate_indexer_additional_info** | **string** | Campo livre para complementar a informação relativa ao Tipo de taxa referencial ou indexador. [Restrição] Obrigatório para complementar a informação relativa ao Tipo de taxa referencial ou indexador, quando selecionada o tipo ou subtipo OUTRO. | [optional]
**pre_fixed_rate** | **float** | Taxa pré fixada aplicada sob o contrato da modalidade crédito.    p.ex. 0.014500. O preenchimento deve respeitar as 6 casas decimais, mesmo que venham preenchidas com zeros(representação de porcentagem p.ex: 0.150000.  Este valor representa 15%. O valor 1 representa 100%). | [optional]
**post_fixed_rate** | **float** | Taxa pós fixada aplicada sob o contrato da modalidade crédito.    p.ex. 0.014500. O preenchimento deve respeitar as 6 casas decimais, mesmo que venham preenchidas com zeros (representação de porcentagem p.ex: 0.150000.  Este valor representa 15%. O valor 1 representa 100%). | [optional]
**additional_info** | **string** | Texto com informações adicionais sobre a composição das taxas de juros pactuadas.   [Restrição] Caso a instituição possua a informação para compartilhamento, esta deverá ser informada. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
