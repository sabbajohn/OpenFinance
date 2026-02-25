# # RiskPensionEnumContributionPayment

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**contribution_payment_method** | **string** | Forma de pagamento da contribuição.  - CARTAO_CREDITO  - CARTAO_DEBITO  - DEBITO_CONTA  - DEBITO_CONTA_POUPANCA  - BOLETO_BANCARIO  - PIX  - TED_DOC  - CONSIGNACAO_FOLHA_PAGAMENTO  - PONTOS_PROGRAMA_BENEFICIO  - OUTROS | [optional]
**contribution_payment_method_additional_info** | **string** | Campo livre para preenchimento das informações adicionais referente ao contributionPaymentMethod.  [Restrição] Obrigatório quando &#39;contributionPaymentMethod&#39; for igual &#39;OUTROS&#39;. | [optional]
**contribution_periodicity** | **string** | Periodicidade de pagamento da contribuição. - MENSAL - UNICA - ANUAL - TRIMESTRAL - SEMESTRAL - BIMESTRAL - OUTROS | [optional]
**contribution_periodicity_additional_info** | **string** | Campo livre para preenchimento das informações adicionais referente ao contributionPaymentMethod.  [Restrição] Obrigatório quando &#39;contributionPeriodicity&#39; for igual &#39;OUTROS&#39;. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
