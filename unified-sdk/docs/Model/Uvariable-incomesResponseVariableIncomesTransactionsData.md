# # ResponseVariableIncomesTransactionsData

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**type** | [**\OpenAPI\Client\Model\EnumVariableIncomesTransactionsType**](EnumVariableIncomesTransactionsType.md) |  |
**transaction_type** | [**\OpenAPI\Client\Model\EnumVariableIncomesTransactionsTransactionType**](EnumVariableIncomesTransactionsTransactionType.md) |  |
**transaction_type_additional_info** | **string** | Informação adicional do tipo de movimentação, para preenchimento no caso de movimentações não delimitadas no domínio.  [Restrição] Campo de preenchimento obrigatório pelas participantes quando o campo &#39;transactionType&#39; for preenchido com o valor &#39;OUTROS&#39;. | [optional]
**transaction_date** | **\DateTime** | Data da movimentação.  [Restrição] Data do pregão: compartilhar movimentos até a data da posição. |
**price_factor** | **float** | Fator que indica o número de ações utilizadas para a formação do preço. Valor informado deve ser maior que zero. | [optional]
**transaction_unit_price** | [**\OpenAPI\Client\Model\ResponseVariableIncomesTransactionsDataTransactionUnitPrice**](ResponseVariableIncomesTransactionsDataTransactionUnitPrice.md) |  | [optional]
**transaction_quantity** | **float** | Quantidade de ativos movimentados.  [Restrição] Campo de preenchimento obrigatório pelas participantes quando o campo &#39;transactionType&#39; for preenchido com os valores &#39;COMPRA&#39; ou &#39;VENDA&#39;. | [optional]
**transaction_value** | [**\OpenAPI\Client\Model\ResponseVariableIncomesTransactionsDataTransactionValue**](ResponseVariableIncomesTransactionsDataTransactionValue.md) |  |
**transaction_id** | **string** | Código ou identificador único prestado pela instituição que mantém a representação individual do movimento. |
**broker_note_id** | **string** | Identificador da nota de negociação.  [Restrição] Informação de envio obrigatório caso o motivo da movimentação seja compra ou venda. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
