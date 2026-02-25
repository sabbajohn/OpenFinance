# # CreditFixedIncomesTransactions

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**type** | [**\OpenAPI\Client\Model\Type**](Type.md) |  |
**transaction_type** | [**\OpenAPI\Client\Model\TransactionType**](TransactionType.md) |  |
**transaction_type_additional_info** | **string** | Informação adicional do tipo de movimentação, para preenchimento no caso de movimentações não delimitadas no domínio   [Restrição] Campo de preenchimento obrigatório pelas participantes quando houver &#39;OUTROS&#39; no campo &#39;transactionType&#39;. | [optional]
**transaction_date** | **\DateTime** | Data da movimentação |
**transaction_unit_price** | [**\OpenAPI\Client\Model\ResponseCreditFixedIncomesTransactionsCurrentDataInnerTransactionUnitPrice**](ResponseCreditFixedIncomesTransactionsCurrentDataInnerTransactionUnitPrice.md) |  | [optional]
**transaction_quantity** | **string** | Quantidade de títulos envolvidos na movimentação  [Restrição] Campo de preenchimento obrigatório pelas participantes quando o campo &#39;transactionType&#39; for preenchido com os valores &#39;COMPRA&#39;, &#39;VENDA&#39;, &#39;VENCIMENTO&#39;, &#39;TRANSFERENCIA_TITULARIDADE&#39; ou &#39;TRANSFERENCIA_CUSTODIA&#39;. | [optional]
**transaction_gross_value** | [**\OpenAPI\Client\Model\TransactionGrossValue**](TransactionGrossValue.md) |  |
**income_tax** | [**\OpenAPI\Client\Model\ResponseCreditFixedIncomesTransactionsCurrentDataInnerIncomeTax**](ResponseCreditFixedIncomesTransactionsCurrentDataInnerIncomeTax.md) |  | [optional]
**financial_transaction_tax** | [**\OpenAPI\Client\Model\ResponseCreditFixedIncomesTransactionsCurrentDataInnerFinancialTransactionTax**](ResponseCreditFixedIncomesTransactionsCurrentDataInnerFinancialTransactionTax.md) |  | [optional]
**transaction_net_value** | [**\OpenAPI\Client\Model\TransactionNetValue**](TransactionNetValue.md) |  |
**remuneration_transaction_rate** | **string** | Taxa de remuneração acordada com o cliente na contratação.   [Restrição] Campo de preenchimento obrigatório pelas participantes quando o campo &#39;type&#39; for preenchido com o valor &#39;ENTRADA&#39; e se tratar de produto prefixado ou híbrido. | [optional]
**indexer_percentage** | **string** | Percentual máximo do indexador na transação acordado com o cliente na contratação.   [Restrição] Campo de preenchimento obrigatório pelas participantes quando o campo &#39;type&#39; for preenchido com o valor &#39;ENTRADA&#39; e se tratar de produto pós-fixado ou híbrido. | [optional]
**transaction_id** | **string** | Código ou identificador único prestado pela instituição para individualizar o movimento. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
