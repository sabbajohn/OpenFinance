# # RiskProducts

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**name** | **string** | Nome comercial do produto, pelo qual é identificado nos canais de distribuição e atendimento da sociedade. |
**code** | **string** | Código único a ser definido pela sociedade. |
**modality** | [**\OpenAPI\Client\Model\EnumProductModality**](EnumProductModality.md) |  | [optional]
**coverages** | [**\OpenAPI\Client\Model\Coverage[]**](Coverage.md) | Conjunto de informações referente a cobertura |
**assistance_types** | [**\OpenAPI\Client\Model\EnumAssistanceType[]**](EnumAssistanceType.md) |  | [optional]
**assistance_types_additional_infos** | **string[]** | Lista a ser preenchida pelas participantes quando houver &#39;Outros&#39; no campo &#39;Tipo de Assistência&#39;. | [optional]
**plan_additional** | [**\OpenAPI\Client\Model\EnumPlanAdditional**](EnumPlanAdditional.md) |  | [optional]
**terms_and_conditions** | [**\OpenAPI\Client\Model\TermsAndConditions[]**](TermsAndConditions.md) | Conjunto de informações referente aos termos e condições conforme número do processo SUSEP |
**pmbac_remuneration** | [**\OpenAPI\Client\Model\RiskPensionEnumPmbacRemuneration**](RiskPensionEnumPmbacRemuneration.md) |  | [optional]
**premium_update_index** | [**\OpenAPI\Client\Model\RiskPensionEnumPremiumUpdateIndex**](RiskPensionEnumPremiumUpdateIndex.md) |  | [optional]
**age_adjustment** | [**\OpenAPI\Client\Model\AgeAdjustment**](AgeAdjustment.md) |  | [optional]
**financial_regime_contract_type** | [**\OpenAPI\Client\Model\RiskPensionEnumFinancialRegime**](RiskPensionEnumFinancialRegime.md) |  | [optional]
**reclaim** | [**\OpenAPI\Client\Model\RiskPensionReclaim**](RiskPensionReclaim.md) |  | [optional]
**other_guaranteed_values** | [**\OpenAPI\Client\Model\RiskPensionEnumOtherGuaranteedValues**](RiskPensionEnumOtherGuaranteedValues.md) |  | [optional]
**contribution_payment** | [**\OpenAPI\Client\Model\RiskPensionEnumContributionPayment**](RiskPensionEnumContributionPayment.md) |  |
**contribution_tax** | **string** | Distribuição de frequência relativa aos valores referentes às taxas cobradas | [optional]
**minimum_requirement** | [**\OpenAPI\Client\Model\RiskPensionMinimumRequirement**](RiskPensionMinimumRequirement.md) |  |
**target_audience** | **string** | A considerar os domínios abaixo:    1. Pessoa Natural   2. Pessoa Jurídica   3. Ambas (Pessoa Natural e Jurídica) | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
