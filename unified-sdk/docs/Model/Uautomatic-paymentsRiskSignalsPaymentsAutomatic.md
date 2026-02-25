# # RiskSignalsPaymentsAutomatic

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**last_login_date_time** | **\DateTime** | Caso o usuário pagador tenha acesso ao ambiente da iniciadora de pagamentos, utilizar data e hora da última interação do cliente com seu aplicativo/sistema.  Para casos onde a iniciadora de pagamentos presta serviços a um terceiro, deve-se enviar o horário que o pagador logou na aplicação do terceiro. |
**pix_key_registration_date_time** | **\DateTime** | Data e hora de cadastro da chave Pix do recebedor na iniciadora  [Restrição] Campo obrigatório a ser enviado, caso o valor do campo &#x60;/data/localInstrument&#x60; seja igual a &#x60;DICT&#x60; ou &#x60;INIC&#x60;. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
