# # Product

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**name** | **string** | Nome comercial do produto, pelo qual é identificado nos canais de distribuição e atendimento da sociedade. |
**code** | **string** | Código único a ser definido pela sociedade. |
**category** | **string** | Indicar a categoria do Produto   - Tradicional   - Microsseguro | [optional]
**modality** | [**\OpenAPI\Client\Model\EnumProductModality**](EnumProductModality.md) |  | [optional]
**coverages** | [**\OpenAPI\Client\Model\PersonalCoverageItem[]**](PersonalCoverageItem.md) | Informações referente a cobertura do seguro. |
**assistance_types** | **string[]** |  | [optional]
**assistance_types_additional_infos** | **string[]** | Lista a ser preenchido pelas participantes quando houver &#39;Outros&#39; no campo &#39;Tipo de Assistência&#39; | [optional]
**additional_services** | **string[]** | Lista dos serviços adicionais associado ao produto. | [optional]
**terms_and_conditions** | [**\OpenAPI\Client\Model\TermsAndConditionsItem[]**](TermsAndConditionsItem.md) | Termos e condições do produto Seguros. |
**global_capital** | **bool** | A considerar os seguintes domínios:   1. true   2. false | [optional]
**terms** | **string[]** |  | [optional]
**pmbac_remuneration** | [**\OpenAPI\Client\Model\InsurancePensionEnumPmbacRemuneration**](InsurancePensionEnumPmbacRemuneration.md) |  | [optional]
**benefit_recalculation** | [**\OpenAPI\Client\Model\BenefitRecalculation**](BenefitRecalculation.md) |  | [optional]
**age_adjustment** | [**\OpenAPI\Client\Model\AgeAdjustment**](AgeAdjustment.md) |  | [optional]
**financial_regimes** | [**\OpenAPI\Client\Model\InsurancePensionEnumFinancialRegime[]**](InsurancePensionEnumFinancialRegime.md) |  | [optional]
**reclaim** | [**\OpenAPI\Client\Model\PersonalInsuranceReclaim**](PersonalInsuranceReclaim.md) |  | [optional]
**other_guaranteed_values** | [**\OpenAPI\Client\Model\EnumPersonalInsuranceOtherGuaranteedValues[]**](EnumPersonalInsuranceOtherGuaranteedValues.md) |  | [optional]
**allow_portability** | **bool** | 1. true 2. false | [optional]
**portability_grace_time** | [**\OpenAPI\Client\Model\PersonalInsurancePortabilityGraceTime**](PersonalInsurancePortabilityGraceTime.md) |  | [optional]
**indemnity_payment_methods** | [**\OpenAPI\Client\Model\EnumPersonalInsuranceIndemnityPaymentMethod[]**](EnumPersonalInsuranceIndemnityPaymentMethod.md) |  | [optional]
**indemnity_payment_incomes** | [**\OpenAPI\Client\Model\EnumPersonalInsuranceIndemnityPaymentIncome[]**](EnumPersonalInsuranceIndemnityPaymentIncome.md) |  | [optional]
**premium_payment** | [**\OpenAPI\Client\Model\PersonalInsurancePremiumPayment**](PersonalInsurancePremiumPayment.md) |  | [optional]
**minimum_requirement** | [**\OpenAPI\Client\Model\PersonalInsuranceMinimumRequirement**](PersonalInsuranceMinimumRequirement.md) |  | [optional]
**target_audience** | **string** | A considerar os domínios abaixo:    1. Pessoa Natural   2. Pessoa Jurídica   3. Ambas (Pessoa Natural e Jurídica) | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
