# # ResponseCreditFixedIncomesBalancesData

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**reference_date_time** | **\DateTime** | data e hora da última posição consolidada disponível a que se referem os dados transacionais do cliente disponíveis nos canais eletrônicos; Na representação data deve se considerar os minutos e segundos como zero (00:00:00Z) |
**updated_unit_price** | [**\OpenAPI\Client\Model\UpdatedUnitPrice**](UpdatedUnitPrice.md) |  |
**quantity** | **float** | quantidade de títulos detidos na data da posição do cliente |
**gross_amount** | [**\OpenAPI\Client\Model\GrossAmount**](GrossAmount.md) |  |
**net_amount** | [**\OpenAPI\Client\Model\NetAmount**](NetAmount.md) |  |
**income_tax** | [**\OpenAPI\Client\Model\IncomeTax**](IncomeTax.md) |  |
**financial_transaction_tax** | [**\OpenAPI\Client\Model\FinancialTransactionTax**](FinancialTransactionTax.md) |  |
**blocked_balance** | [**\OpenAPI\Client\Model\BlockedBalance**](BlockedBalance.md) |  |
**purchase_unit_price** | [**\OpenAPI\Client\Model\PurchaseUnitPrice**](PurchaseUnitPrice.md) |  |
**pre_fixed_rate** | **string** | Taxa de remuneração acordada com o cliente na contratação. Em casos de produtos progressivos, considerar a taxa máxima contratada.  É esperado que o preenchimento deste campo pelas participantes seja enviado conforme foi acordado no momento da contratação. | [optional]
**post_fixed_indexer_percentage** | **string** | Percentual do indexador acordado com o cliente na contratação. Em casos de produtos progressivos, considerar a taxa máxima contratada.  É esperado que o preenchimento deste campo pelas participantes seja enviado conforme foi acordado no momento da contratação. | [optional]
**fine** | [**\OpenAPI\Client\Model\Fine**](Fine.md) |  | [optional]
**late_payment** | [**\OpenAPI\Client\Model\LatePayment**](LatePayment.md) |  | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
