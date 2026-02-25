# # CreditCardAccountsBillsData

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**bill_id** | **string** | Informação que identifica a fatura |
**due_date** | **\DateTime** | Data de vencimento da Fatura, que aparece para pagamento pelo cliente |
**bill_total_amount** | [**\OpenAPI\Client\Model\CreditCardsBillTotalAmount**](CreditCardsBillTotalAmount.md) |  |
**bill_minimum_amount** | [**\OpenAPI\Client\Model\CreditCardAccountsBillMinimumAmount**](CreditCardAccountsBillMinimumAmount.md) |  |
**is_instalment** | **bool** | Indica se a fatura permite parcelamento (true) ou não (false). |
**finance_charges** | [**\OpenAPI\Client\Model\CreditCardAccountsBillsFinanceCharge[]**](CreditCardAccountsBillsFinanceCharge.md) | Lista dos encargos cobrados na fatura | [optional]
**payments** | [**\OpenAPI\Client\Model\CreditCardAccountsBillsPayment[]**](CreditCardAccountsBillsPayment.md) | Lista que traz os valores relativos aos pagamentos da Fatura da conta de pagamento pós-paga |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
