# # Remuneration

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**pre_fixed_rate** | **string** | Valor da taxa de emissão do contrato.          [Restrição] Campo de preenchimento obrigatório pelas participantes quando houver &#39;PRE_FIXADO&#39; no campo &#39;indexer&#39; ou quando se tratar de produto com remuneração híbrida. | [optional]
**post_fixed_indexer_percentage** | **string** | Percentual do indexador de emissão do contrato.     [Restrição] Campo de preenchimento obrigatório pelas participantes quando o campo &#39;indexer&#39; for preenchido de forma diferente de &#39;PRE_FIXADO&#39; ou quando se tratar de produto com remuneração híbrida. | [optional]
**rate_type** | [**\OpenAPI\Client\Model\EnumRateType**](EnumRateType.md) |  | [optional]
**rate_periodicity** | [**\OpenAPI\Client\Model\EnumRatePeriodicity**](EnumRatePeriodicity.md) |  | [optional]
**calculation** | [**\OpenAPI\Client\Model\EnumCalculation**](EnumCalculation.md) |  | [optional]
**indexer** | [**\OpenAPI\Client\Model\EnumIndexer**](EnumIndexer.md) |  |
**indexer_additional_info** | **string** | Informações adicionais do indexador   [Restrição] Campo de preenchimento obrigatório pelas participantes quando houver &#39;Outros&#39; no campo &#39;indexer&#39;. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
