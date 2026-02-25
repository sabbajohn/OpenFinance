# # Products

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**name** | **string** | Nome comercial do produto, pelo qual é identificado nos canais de distribuição e atendimento da sociedade. |
**code** | **string** | Código único a ser definido pela sociedade. |
**modality** | [**\OpenAPI\Client\Model\EnumCapitalizationBondsProductModality**](EnumCapitalizationBondsProductModality.md) |  | [optional]
**cost_type** | [**\OpenAPI\Client\Model\EnumCapitalizationBondsProductCostType**](EnumCapitalizationBondsProductCostType.md) |  | [optional]
**terms_and_conditions** | [**\OpenAPI\Client\Model\TermsAndConditions**](TermsAndConditions.md) |  |
**quotas** | [**\OpenAPI\Client\Model\CapitalizationBondsProductQuota[]**](CapitalizationBondsProductQuota.md) | Informações relativas às taxas da cotas praticadas para cada parcela. |
**validity** | **int** | Período entre a data de início e a data final para constituição do capital a ser pago ao(s) titular(es) do direito de resgate. Prazo de vigência do título de capitalização em meses (Resolução CNSP 384/20). | [optional]
**serie_size** | **int** | Os títulos de capitalização com sorteio devem ser estruturados em séries, ou seja, em sequências ou em grupos de títulos submetidos às mesmas condições e características, à exceção do valor do pagamento. | [optional]
**capitalization_period** | [**\OpenAPI\Client\Model\CapitalizationBondsProductCapitalizationPeriod**](CapitalizationBondsProductCapitalizationPeriod.md) |  |
**late_payment** | [**\OpenAPI\Client\Model\LatePayment**](LatePayment.md) |  |
**contribution_payment** | [**\OpenAPI\Client\Model\ContributionPayment**](ContributionPayment.md) |  |
**final_redemption_rate** | **string** | Valor percentual (%) de resgate final permitido. |
**draws** | [**\OpenAPI\Client\Model\CapitalizationBondsProductPrizeDraw[]**](CapitalizationBondsProductPrizeDraw.md) | Informações relativas aos Sorteios |
**additional_details** | **string** | Campo livre para preenchimento das informações adicionais referente ao produto da sociedade seguradora participante do OPIN. Pode-se incluir URL. | [optional]
**minimum_requirement_details** | **string** | Campo livre para preenchimento das informações adicionais referente ao requerimento mínimo. Pode-se incluir URL. | [optional]
**target_audience** | **string** | Público alvo do produto da sociedade seguradora participante do OPIN. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
