# # TreasureTitlesProductTransaction

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**type** | [**\OpenAPI\Client\Model\TreasureTitlesType**](TreasureTitlesType.md) |  |
**transaction_type** | [**\OpenAPI\Client\Model\TreasureTitlesTransactionType**](TreasureTitlesTransactionType.md) |  |
**transaction_type_additional_info** | **string** | Informação adicional do tipo de movimentação, para preenchimento no caso de movimentações não de limitadas no domínio.  [Restrição] Campo de preenchimento obrigatório pelas participantes quando o campo &#39;transactionType&#39; for preenchido com o valor &#39;OUTROS&#39;. | [optional]
**transaction_date** | **\DateTime** | Data da movimentação. |
**transaction_unit_price** | [**\OpenAPI\Client\Model\TreasureTitlesTransactionUnitPrice**](TreasureTitlesTransactionUnitPrice.md) |  |
**transaction_quantity** | **float** | Quantidade de títulos envolvidos na movimentação. |
**transaction_gross_value** | [**\OpenAPI\Client\Model\TreasureTitlesTransactionGrossValue**](TreasureTitlesTransactionGrossValue.md) |  |
**income_tax** | [**\OpenAPI\Client\Model\TreasureTitlesProductTransactionIncomeTax**](TreasureTitlesProductTransactionIncomeTax.md) |  | [optional]
**financial_transaction_tax** | [**\OpenAPI\Client\Model\TreasureTitlesProductTransactionFinancialTransactionTax**](TreasureTitlesProductTransactionFinancialTransactionTax.md) |  | [optional]
**transaction_net_value** | [**\OpenAPI\Client\Model\TreasureTitlesTransactionNetValue**](TreasureTitlesTransactionNetValue.md) |  |
**remuneration_transaction_rate** | **float** | Taxa de remuneração da movimentação.    [Restrição] Campo de preenchimento obrigatório pelas participantes quando o campo &#39;type&#39; for preenchido com o valor &#39;ENTRADA&#39;. | [optional]
**transaction_id** | **string** | Código ou identificador único prestado pela instituição para individualizar o movimento. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
