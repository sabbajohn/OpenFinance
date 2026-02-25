# # Events

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**event_sequence_number** | **string** | Número sequência do registro do evento de câmbio no Bacen. |
**event_type** | [**\OpenAPI\Client\Model\EnumExchangesEventType**](EnumExchangesEventType.md) |  |
**event_date** | **\DateTime** | Data do evento relacionado com a operação. |
**due_date** | **\DateTime** | Data em que a operação (compra ou venda), após evento, está prevista para ser liquidada. | [optional]
**local_currency_operation_tax** | [**\OpenAPI\Client\Model\EventsLocalCurrencyOperationTax**](EventsLocalCurrencyOperationTax.md) |  | [optional]
**local_currency_operation_value** | [**\OpenAPI\Client\Model\OperationDetailsLocalCurrencyOperationValue**](OperationDetailsLocalCurrencyOperationValue.md) |  | [optional]
**foreign_operation_value** | [**\OpenAPI\Client\Model\OperationDetailsForeignOperationValue**](OperationDetailsForeignOperationValue.md) |  | [optional]
**operation_outstanding_balance** | [**\OpenAPI\Client\Model\EventsOperationOutstandingBalance**](EventsOperationOutstandingBalance.md) |  | [optional]
**vet_amount** | [**\OpenAPI\Client\Model\OperationDetailsVetAmount**](OperationDetailsVetAmount.md) |  | [optional]
**local_currency_advance_percentage** | **string** | Percentual do valor de moeda estrangeira concedido ao cliente antecipadamente. p.ex. 0.014500.  O preenchimento deve respeitar as 6 casas decimais, mesmo que venham preenchidas com zeros(representação de porcentagem p.ex: 0.150000. Este valor representa 15%. O valor 1 representa 100%). Campos de envio obrigatório no caso de operações de câmbio com liquidação futura. | [optional]
**delivery_foreign_currency** | [**\OpenAPI\Client\Model\EnumExchangesDeliveryForeignCurrency**](EnumExchangesDeliveryForeignCurrency.md) |  | [optional]
**operation_category_code** | **string** | Código da natureza fato do fechamento da operação. Deve respeitar os códigos de natureza referenciados na resolução 277 ou na Circular 3690, conforme se aplicar ao contrato de câmbio. | [optional]
**foreign_partie** | [**\OpenAPI\Client\Model\EventsForeignPartie**](EventsForeignPartie.md) |  | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
