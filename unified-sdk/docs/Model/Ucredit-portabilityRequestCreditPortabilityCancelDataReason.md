# # RequestCreditPortabilityCancelDataReason

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**type** | **string** | Motivo de recusa do pedido de portabilidade, onde:  &#x60;CANCELADO_PELO_CLIENTE&#x60; - Cliente desiste do pedido da portabilidade  &#x60;SALDO_DEVEDOR_ATUALIZADO_SUBSTANCIALMENTE_DIVERGENTE&#x60; - Saldo devedor atualizado divergente (superior a 15%) do informado inicialmente  &#x60;POLITICA_DE_CREDITO&#x60; - Proponente desiste da oferta ao cliente por políticas internas  &#x60;OUTROS&#x60; - Motivo da rejeição não se encaixa nas opções disponíveis |
**type_additional_info** | **string** | Informação adicional sobre rejeição de portabilidade de crédito.  Ao utilizar essa opção, é fortemente recomendável enviar um ticket para o GT de Portabilidade de Crédito como sugestão para estrutura Open Finance para discussão e mapeamento em futuras versões.  [RESTRIÇÃO] Campo de preenchimento obrigatório quando campo &#x60;type&#x60; for igual a &#x60;OUTROS&#x60;. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
