# # SurvivalProducts

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**name** | **string** | Nome comercial do produto, pelo qual é identificado nos canais de distribuição e atendimento da sociedade. |
**code** | **string** | Código único a ser definido pela sociedade. |
**segment** | **string** | Segmento do qual se trata o produto contratado 1. Seguro de Pessoas 2. Previdência | [optional]
**modality** | **string** | 1. Contribuição Variável; 2. Benefício Definido. | [optional]
**additional_info** | **string** | Campo aberto (possibilidade de incluir URL) | [optional]
**terms_and_conditions** | [**\OpenAPI\Client\Model\TermsAndConditions[]**](TermsAndConditions.md) | Conjunto de informações referente aos termos e condições conforme número do processo SUSEP | [optional]
**type** | [**\OpenAPI\Client\Model\SurvivalPensionType**](SurvivalPensionType.md) |  | [optional]
**defferal_period** | [**\OpenAPI\Client\Model\SurvivalPensionDefferalPeriod**](SurvivalPensionDefferalPeriod.md) |  |
**grant_period_benefit** | [**\OpenAPI\Client\Model\SurvivalPensionGrantPeriodBenefit**](SurvivalPensionGrantPeriodBenefit.md) |  |
**costs** | [**\OpenAPI\Client\Model\SurvivalPensionCosts**](SurvivalPensionCosts.md) |  |
**minimum_requirement** | [**\OpenAPI\Client\Model\SurvivalPensionMinimumRequirements**](SurvivalPensionMinimumRequirements.md) |  | [optional]
**target_audience** | [**\OpenAPI\Client\Model\SurvivalPensionEnumTargetAudience**](SurvivalPensionEnumTargetAudience.md) |  | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
