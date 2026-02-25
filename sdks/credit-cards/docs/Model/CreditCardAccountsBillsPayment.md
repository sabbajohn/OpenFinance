# # CreditCardAccountsBillsPayment

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**value_type** | [**\OpenAPI\Client\Model\EnumCreditCardAccountsBillingValueType**](EnumCreditCardAccountsBillingValueType.md) |  |
**payment_date** | **\DateTime** | Data efetiva de quando o Pagamento da fatura foi realizado |
**payment_mode** | [**\OpenAPI\Client\Model\EnumCreditCardAccountsPaymentMode**](EnumCreditCardAccountsPaymentMode.md) |  |
**amount** | **float** | Valor pagamento segundo o valueType.   Expresso em valor monetário com no mínimo 2 casas e no máximo 4 casas decimais.    O campo não pode assumir valor negativo por se tratar de um pagamento. |
**currency** | **string** | Moeda referente ao valor de pagamento da fatura, segundo modelo ISO-4217. p.ex. &#39;BRL&#39; Todos os valores informados estão representados com a moeda vigente do Brasil |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
