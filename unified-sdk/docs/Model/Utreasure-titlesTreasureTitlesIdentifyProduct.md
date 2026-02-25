# # TreasureTitlesIdentifyProduct

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**isin_code** | **string** | Código ISIN da emissão, Código ISIN do produto, Código da emissora : código universal que identifica cada valor mobiliário ou instrumento financeiro, conforme Norma ISO 6166. |
**product_name** | **string** | Nome do título em questão, conforme listado no site do Tesouro Direto [https://www.tesourodireto.com.br](https://www.tesourodireto.com.br) |
**remuneration** | [**\OpenAPI\Client\Model\TreasureTitlesRemuneration**](TreasureTitlesRemuneration.md) |  |
**due_date** | **\DateTime** | Data de vencimento do título. |
**purchase_date** | **\DateTime** | Data de aquisição do cliente. |
**voucher_payment_indicator** | [**\OpenAPI\Client\Model\TreasureTitlesVoucherPaymentIndicator**](TreasureTitlesVoucherPaymentIndicator.md) |  |
**voucher_payment_periodicity** | [**\OpenAPI\Client\Model\TreasureTitlesVoucherPaymentPeriodicity**](TreasureTitlesVoucherPaymentPeriodicity.md) |  | [optional]
**voucher_payment_periodicity_additional_info** | **string** | Informações adicionais da periodicidade de pagamento de cupom.  [Restrição] Campo de preenchimento obrigatório pelas participantes quando o campo &#39;voucherPaymentPeriodicity&#39; for preenchido com o valor &#39;OUTROS&#39;. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
