# # Remuneration

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**pre_fixed_rate** | **float** | Taxa de remuneração pré fixada de emissão do título.  p.ex. 0.014500.  O preenchimento deve respeitar as 6 casas decimais, mesmo que venham preenchidas com zeros(representação de porcentagem p.ex: 0.150000. Este valor representa 15%. O valor 1 representa 100%).    [Restrição] Campo de preenchimento obrigatório pelas participantes quando houver &#39;PRE_FIXADO&#39; no campo &#39;indexer&#39; ou quando se tratar de produto com remuneração híbrida. | [optional]
**post_fixed_indexer_percentage** | **float** | Percentual do indexador pós fixado de emissão do  título.  p.ex. 0.014500.  O preenchimento deve respeitar as 6 casas decimais, mesmo que venham preenchidas com zeros(representação de porcentagem p.ex: 0.150000. Este valor representa 15%. O valor 1 representa 100%).  [Restrição] Campo de preenchimento obrigatório pelas participantes quando o campo &#39;indexer&#39; for preenchido de forma diferente de &#39;PRE_FIXADO&#39; ou quando se tratar de produto com remuneração híbrida. | [optional]
**rate_type** | [**\OpenAPI\Client\Model\EnumRateType**](EnumRateType.md) |  |
**rate_periodicity** | [**\OpenAPI\Client\Model\EnumRatePeriodicity**](EnumRatePeriodicity.md) |  |
**calculation** | [**\OpenAPI\Client\Model\EnumCalculation**](EnumCalculation.md) |  |
**indexer** | [**\OpenAPI\Client\Model\EnumBankFixedIncomeIndexer**](EnumBankFixedIncomeIndexer.md) |  |
**indexer_additional_info** | **string** | Informações adicionais do indexador  [Restrição] Campo de preenchimento obrigatório pelas participantes quando houver &#39;Outros&#39; no campo &#39;indexer&#39;. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
