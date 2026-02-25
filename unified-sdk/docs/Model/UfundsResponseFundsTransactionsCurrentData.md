# # ResponseFundsTransactionsCurrentData

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**transaction_id** | **string** | Código ou identificador único prestado pela instituição que mantém a representação individual do movimento na posição do fundo. |
**type** | [**\OpenAPI\Client\Model\EnumFundsTransactionsCurrentType**](EnumFundsTransactionsCurrentType.md) |  |
**transaction_type** | [**\OpenAPI\Client\Model\EnumFundsTransactionsCurrentTransactionType**](EnumFundsTransactionsCurrentTransactionType.md) |  |
**transaction_type_additional_info** | **string** | Informação adicional do tipo do motivo, para preenchimento no caso de movimentações não delimitadas no domínio.  [Restrição] Campo de preenchimento obrigatório pelas participantes quando o campo &#39;transactionType&#39; for preenchido com o valor &#39;OUTROS&#39;. | [optional]
**transaction_conversion_date** | **\DateTime** | Data da conversão da transação de movimentação do fundo de investimento. |
**transaction_quota_price** | [**\OpenAPI\Client\Model\ResponseFundsTransactionsDataTransactionQuotaPrice**](ResponseFundsTransactionsDataTransactionQuotaPrice.md) |  |
**transaction_quota_quantity** | **float** | Número de cotas convertidas na data da movimentação. |
**transaction_value** | [**\OpenAPI\Client\Model\ResponseFundsTransactionsDataTransactionValue**](ResponseFundsTransactionsDataTransactionValue.md) |  |
**transaction_gross_value** | [**\OpenAPI\Client\Model\ResponseFundsTransactionsDataTransactionGrossValue**](ResponseFundsTransactionsDataTransactionGrossValue.md) |  |
**income_tax** | [**\OpenAPI\Client\Model\ResponseFundsTransactionsDataIncomeTax**](ResponseFundsTransactionsDataIncomeTax.md) |  | [optional]
**financial_transaction_tax** | [**\OpenAPI\Client\Model\ResponseFundsTransactionsDataFinancialTransactionTax**](ResponseFundsTransactionsDataFinancialTransactionTax.md) |  | [optional]
**transaction_exit_fee** | [**\OpenAPI\Client\Model\ResponseFundsTransactionsDataTransactionExitFee**](ResponseFundsTransactionsDataTransactionExitFee.md) |  | [optional]
**transaction_net_value** | [**\OpenAPI\Client\Model\ResponseFundsTransactionsDataTransactionNetValue**](ResponseFundsTransactionsDataTransactionNetValue.md) |  | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
