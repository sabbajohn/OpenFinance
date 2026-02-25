# # UnarrangedAccountOverdraftService

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**name** | **string** | Nome da Tarifa cobrada sobre Serviço que incide sobre Adiantamento a depositante, para pessoa jurídica. | [default to 'CONCESSAO_ADIANTAMENTO_DEPOSITANTE']
**code** | **string** | Sigla de identificação do serviço relacionado à Modalidade de Adiantamento a depositante, para pessoa jurídica. | [default to 'ADIANT_DEPOSITANTE']
**charging_trigger_info** | **string** | Fato gerador de cobrança que incide sobre a Modalidade de Adiantamento a depositante informada, para pessoa jurídica. |
**prices** | [**\OpenAPI\Client\Model\Price[]**](Price.md) | lista das faixas dos  valores de tarfas cobradas |
**minimum** | [**\OpenAPI\Client\Model\MinimumPrice**](MinimumPrice.md) |  |
**maximum** | [**\OpenAPI\Client\Model\MaximumPrice**](MaximumPrice.md) |  |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
