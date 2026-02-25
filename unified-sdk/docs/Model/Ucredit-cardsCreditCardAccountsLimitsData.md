# # CreditCardAccountsLimitsData

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**credit_line_limit_type** | [**\OpenAPI\Client\Model\EnumCreditCardAccountsLineLimitType**](EnumCreditCardAccountsLineLimitType.md) |  |
**consolidation_type** | [**\OpenAPI\Client\Model\EnumCreditCardAccountsConsolidationType**](EnumCreditCardAccountsConsolidationType.md) |  |
**identification_number** | **string** | Número de identificação do cartão: corresponde aos 4 últimos dígitos do cartão para PF, ou então, preencher com um identificador para PJ, com as caracteristicas definidas para os IDs no Open Finance. |
**line_name** | **string** |  | [optional]
**line_name_additional_info** | **string** | Campo de preenchimento obrigatório se selecionada a opção &#39;OUTRAS&#39; em lineName. | [optional]
**is_limit_flexible** | **bool** | True&#x3D; Indica que a conta cartão possui limite total flexível ou “sem limite”. False &#x3D; Indica que a conta cartão possui limite predeterminado exibido no canal para o cliente. |
**limit_amount** | [**\OpenAPI\Client\Model\CreditCardsLimitAmount**](CreditCardsLimitAmount.md) |  | [optional]
**used_amount** | [**\OpenAPI\Client\Model\CreditCardsUsedAmount**](CreditCardsUsedAmount.md) |  |
**available_amount** | [**\OpenAPI\Client\Model\CreditCardsAvailableAmount**](CreditCardsAvailableAmount.md) |  | [optional]
**customized_limit_amount** | [**\OpenAPI\Client\Model\CreditCardAccountsLimitsDataCustomizedLimitAmount**](CreditCardAccountsLimitsDataCustomizedLimitAmount.md) |  | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
