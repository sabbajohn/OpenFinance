# # TreasureTitlesRemuneration

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**indexer** | [**\OpenAPI\Client\Model\TreasureTitlesIndexer**](TreasureTitlesIndexer.md) |  |
**indexer_additional_info** | **string** | Informações adicionais do indexador.  [Restrição] Campo de preenchimento obrigatório pelas participantes quando o campo &#39;indexer&#39; for preenchido com o valor &#39;OUTROS&#39;. | [optional]
**pre_fixed_rate** | **float** | Valor da taxa da aquisição do contrato.  [Restrição] Campo de preenchimento obrigatório pelas participantes quando o campo &#39;indexer&#39; for preenchido com o valor ‘PRE FIXADO’. | [optional]
**post_fixed_indexer_percentage** | **float** | Percentual do indexador da aquisição do contrato.  [Restrição] Campo de preenchimento obrigatório pelas participantes quando o campo &#39;indexer&#39; for preenchido de forma diferente de ‘PRE FIXADO’. | [optional]
**rate_periodicity** | [**\OpenAPI\Client\Model\TreasureTitlesRatePeriodicity**](TreasureTitlesRatePeriodicity.md) |  |
**calculation** | [**\OpenAPI\Client\Model\TreasureTitlesCalculation**](TreasureTitlesCalculation.md) |  |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
