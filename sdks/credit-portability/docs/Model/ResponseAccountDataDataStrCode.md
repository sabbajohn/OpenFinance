# # ResponseAccountDataDataStrCode

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**ispb** | **string** | Número do ISPB da Instituição credora a ser usada na STR para pagamento de portabilidade de crédito exclusiva para o OFB. |
**name** | **string** | Nome do proprietário da conta a ser usada na STR para pagamento de portabilidade de crédito exclusiva para o OFB.  [RESTRIÇÃO] campo de preenchimento obrigatório quando campo &#x60;hasFinancialAgent&#x60; for igual a true | [optional]
**company_cnpj** | **string** | CNPJ do proprietário da conta a ser usada na STR para pagamento de portabilidade de crédito exclusiva para o OFB.  [RESTRIÇÃO] campo de preenchimento obrigatório quando campo &#x60;hasFinancialAgent&#x60; for igual a true | [optional]
**branch_code** | **float** | Número da Agência creditada a ser usada na STR para pagamento de portabilidade de crédito exclusiva para o OFB. |
**has_financial_agent** | **bool** | Instituição trabalha com agente financeiro ao invés da conta reserva? |
**account_number** | **float** | Número da conta bancária da credora a ser usada na STR para pagamento de portabilidade de crédito exclusiva para o OFB.  [RESTRIÇÃO] campo de preenchimento obrigatório quando campo &#x60;hasFinancialAgent&#x60; for igual a true | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
