# # BankFixedIncomesProductMovement

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**type** | [**\OpenAPI\Client\Model\EnumBankFixedIncomeMovementType**](EnumBankFixedIncomeMovementType.md) |  |
**transaction_type** | [**\OpenAPI\Client\Model\EnumBankFixedIncomeTransactionType**](EnumBankFixedIncomeTransactionType.md) |  |
**transaction_type_additional_info** | **string** | Informação adicional do tipo de movimentação, para preenchimento no caso de movimentações não delimitadas no domínio.  [Restrição] Campo de preenchimento obrigatório pelas participantes quando houver &#39;Outros&#39; no campo Motivo da movimentação. | [optional]
**transaction_date** | **\DateTime** | Data da movimentação. |
**transaction_unit_price** | [**\OpenAPI\Client\Model\BankFixedIncomesProductMovementTransactionUnitPrice**](BankFixedIncomesProductMovementTransactionUnitPrice.md) |  |
**transaction_quantity** | **float** | Quantidade de títulos envolvidos na movimentação. |
**transaction_gross_value** | [**\OpenAPI\Client\Model\BankFixedIncomesProductMovementTransactionGrossValue**](BankFixedIncomesProductMovementTransactionGrossValue.md) |  |
**income_tax** | [**\OpenAPI\Client\Model\BankFixedIncomesProductMovementIncomeTax**](BankFixedIncomesProductMovementIncomeTax.md) |  | [optional]
**financial_transaction_tax** | [**\OpenAPI\Client\Model\BankFixedIncomesProductMovementFinancialTransactionTax**](BankFixedIncomesProductMovementFinancialTransactionTax.md) |  | [optional]
**transaction_net_value** | [**\OpenAPI\Client\Model\BankFixedIncomesProductMovementTransactionNetValue**](BankFixedIncomesProductMovementTransactionNetValue.md) |  |
**remuneration_transaction_rate** | **float** | Taxa de remuneração da transação.  [Restrição] Campo de preenchimento obrigatório pelas participantes quando o campo &#39;type&#39; for preenchido com o valor &#39;ENTRADA&#39; e se tratar de produto prefixado ou híbrido. | [optional]
**indexer_percentage** | **float** | Percentual máximo do indexador acordado com o cliente na contratação.  [Restrição] Campo de preenchimento obrigatório pelas participantes quando o campo &#39;type&#39; for preenchido com o valor &#39;ENTRADA&#39; e se tratar de produto pós-fixado ou híbrido. | [optional]
**transaction_id** | **string** | Código ou identificador único prestado pela instituição que mantém a representação individual do movimento. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
