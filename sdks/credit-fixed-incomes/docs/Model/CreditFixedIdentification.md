# # CreditFixedIdentification

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**issuer_institution_cnpj_number** | **string** | CNPJ da instituição emissora.  Caso a instituição tenha a informação, o envio será obrigatório. | [optional]
**isin_code** | **string** | Código ISIN é um código universal que identifica cada valor mobiliário ou instrumento financeiro, conforme Norma ISO 6166. Caso a transmissora possua a informação o envio deste campo é obrigatório.  [Restrição] Deve ser preenchido nos casos em que o &#39;clearingCode&#39; não seja preenchido. | [optional]
**investment_type** | [**\OpenAPI\Client\Model\EnumInvestimentType**](EnumInvestimentType.md) |  |
**debtor_cnpj_number** | **string** | CNPJ do devedor. Caso a transmissora possua a informação para os produtos CRI e CRA, o envio deste campo é obrigatório. | [optional]
**debtor_name** | **string** | Nome do devedor. Caso a transmissora possua a informação para os produtos CRI e CRA, o envio deste campo é obrigatório. | [optional]
**tax_exempt_product** | [**\OpenAPI\Client\Model\EnumTaxExemptProduct**](EnumTaxExemptProduct.md) |  |
**remuneration** | [**\OpenAPI\Client\Model\Remuneration**](Remuneration.md) |  |
**issue_unit_price** | [**\OpenAPI\Client\Model\IssueUnitPrice**](IssueUnitPrice.md) |  |
**issue_date** | **\DateTime** | Data de emissão do título |
**due_date** | **\DateTime** | Data de vencimento do título |
**voucher_payment_indicator** | [**\OpenAPI\Client\Model\VoucherPaymentIndicator**](VoucherPaymentIndicator.md) |  |
**voucher_payment_periodicity** | [**\OpenAPI\Client\Model\VoucherPaymentPeriodicity**](VoucherPaymentPeriodicity.md) |  | [optional]
**voucher_payment_periodicity_additional_info** | **string** | Informações adicionais da periodicidade de pagamento de cupom   [Restrição] Campo de preenchimento obrigatório pelas participantes quando houver &#39;Outros&#39; no campo &#39;voucherPaymentPeriodicity&#39;. | [optional]
**clearing_code** | **string** | Código de registro do ativo na Clearing. Caso a transmissora possua a informação o envio deste campo é obrigatório.  [Restrição] Deve ser preenchido nos casos em que o &#39;isinCode&#39; não seja preenchido. | [optional]
**purchase_date** | **\DateTime** | Data de aquisição do cliente |
**grace_period_date** | **\DateTime** | Data até a qual o cliente não poderá resgatar antecipadamente seu investimento.  Caso a instituição tenha a informação, o envio será obrigatório. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
