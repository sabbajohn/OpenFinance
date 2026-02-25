# # ResponseBankFixedIncomesBalancesData

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**reference_date_time** | **\DateTime** | data e hora da última posição consolidada disponível a que se referem os dados transacionais do cliente disponíveis nos canais eletrônicos; Na representação data deve se considerar os minutos e segundos como zero (00:00:00Z). |
**quantity** | **float** | Quantidade de títulos detidos na data da posição do cliente |
**updated_unit_price** | [**\OpenAPI\Client\Model\ResponseBankFixedIncomesBalancesDataUpdatedUnitPrice**](ResponseBankFixedIncomesBalancesDataUpdatedUnitPrice.md) |  |
**gross_amount** | [**\OpenAPI\Client\Model\ResponseBankFixedIncomesBalancesDataGrossAmount**](ResponseBankFixedIncomesBalancesDataGrossAmount.md) |  |
**net_amount** | [**\OpenAPI\Client\Model\ResponseBankFixedIncomesBalancesDataNetAmount**](ResponseBankFixedIncomesBalancesDataNetAmount.md) |  |
**income_tax** | [**\OpenAPI\Client\Model\ResponseBankFixedIncomesBalancesDataIncomeTax**](ResponseBankFixedIncomesBalancesDataIncomeTax.md) |  |
**financial_transaction_tax** | [**\OpenAPI\Client\Model\ResponseBankFixedIncomesBalancesDataFinancialTransactionTax**](ResponseBankFixedIncomesBalancesDataFinancialTransactionTax.md) |  |
**blocked_balance** | [**\OpenAPI\Client\Model\ResponseBankFixedIncomesBalancesDataBlockedBalance**](ResponseBankFixedIncomesBalancesDataBlockedBalance.md) |  |
**purchase_unit_price** | [**\OpenAPI\Client\Model\ResponseBankFixedIncomesBalancesDataPurchaseUnitPrice**](ResponseBankFixedIncomesBalancesDataPurchaseUnitPrice.md) |  |
**pre_fixed_rate** | **float** | Taxa de remuneração acordada com o cliente na contratação. Em casos de produtos progressivos, considerar a taxa máxima contratada.  O preenchimento deve respeitar as 6 casas decimais, mesmo que venham preenchidas com zeros(representação de porcentagem p.ex: 0.150000.  Este valor representa 15%. O valor 1 representa 100%).  É esperado que o preenchimento deste campo pelas participantes seja enviado conforme foi acordado no momento da contratação. | [optional]
**post_fixed_indexer_percentage** | **float** | Percentual do indexador acordado com o cliente na contratação. Em casos de produtos progressivos, considerar a taxa máxima contratada.  O preenchimento deve respeitar as 6 casas decimais, mesmo que venham preenchidas com zeros(representação de porcentagem p.ex: 0.150000.  Este valor representa 15%. O valor 1 representa 100%).  É esperado que o preenchimento deste campo pelas participantes seja enviado conforme foi acordado no momento da contratação. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
