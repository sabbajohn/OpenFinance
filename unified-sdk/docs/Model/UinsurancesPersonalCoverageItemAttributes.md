# # PersonalCoverageItemAttributes

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**indemnity_payment_methods** | **string[]** | Listagem da forma de pagamento da indenização para cada combinação de modalidade/cobertura do produto. | [optional]
**indemnity_payment_frequencies** | [**\OpenAPI\Client\Model\EnumPersonalIndemnityPaymentFrequencyType[]**](EnumPersonalIndemnityPaymentFrequencyType.md) | Listagem de tipos de frequência de pagamento de indenização para cada combinação de modalidade/cobertura do produto. | [optional]
**min_value** | [**\OpenAPI\Client\Model\InsurancePensionMinValue**](InsurancePensionMinValue.md) |  |
**max_value** | [**\OpenAPI\Client\Model\InsurancePensionMaxValue**](InsurancePensionMaxValue.md) |  |
**indemnifiable_periods** | **string[]** | Listagem de período indenizável para cada combinação de modalidade/cobertura do produto. |
**maximum_qty_indemnifiable_installments** | **int** | Caso o período indenizável seja relacionado a parcelas, listagem de número máximo de parcelas indenizáveis para cada combinação de modalidade/ cobertura do produto. |
**grace_period** | [**\OpenAPI\Client\Model\PersonalInsuranceGracePeriod**](PersonalInsuranceGracePeriod.md) |  |
**differentiated_grace_period** | **string** | Campo aberto para detalhamento de período de carência diferenciado, se houver. | [optional]
**deductible_days** | **int** | Listagem de franquia em dias para cada combinação de modalidade/cobertura do produto. |
**differentiated_deductible_days** | **int** | Detalhamento da franquia em dias diferentes para cada cobertura que exista alguma especificidade. Caso a seguradora não tenha essa diferenciação, não retornará nada no campo. | [optional]
**deductible** | [**\OpenAPI\Client\Model\PersonalCoverageItemAttributesDeductible**](PersonalCoverageItemAttributesDeductible.md) |  |
**differentiated_deductible** | [**\OpenAPI\Client\Model\PersonalCoverageItemAttributesDifferentiatedDeductible**](PersonalCoverageItemAttributesDifferentiatedDeductible.md) |  | [optional]
**excluded_risks** | [**\OpenAPI\Client\Model\EnumExcludedRisks[]**](EnumExcludedRisks.md) |  | [optional]
**excluded_risks_url** | **string** | Campo aberto (possibilidade de incluir URL) | [optional]
**allow_apart_purchase** | **bool** | Indicar se a cobertura pode ser contratada isoladamente ou não:   1. true   2. false | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
