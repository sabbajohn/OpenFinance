# # ResponsePortabilityEligibilityDataPortabilityIneligible

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**reason_type** | **string** | Informação sobre a disponibilidade ou não de um contrato para a portabilidade de crédito Informação sobre o motivo de inelegibilidade -&#x60;CONTRATO_LIQUIDADO&#x60;: Contrato liquidado pelo cliente. -&#x60;CLIENTE_COM_ACAO_JUDICIAL&#x60;: Cliente possui ação judicial -&#x60;MODALIDADE_OPERACAO_INCOMPATIVEL&#x60;: Caso o contrato tenha uma modalidade diferente do praticado no escopo de modalidades disponiveis para portabilidade de crédito -&#x60;OUTROS&#x60;: Caso exista algum motivo de recusa que não se encaixa nas opções disponiveis de &#x60;reasonType&#x60;, o campo &#x60;reasonTypeAdditionalInfo&#x60; deverá ser preenchido com o motivo da inelegibilidade. |
**reason_type_additional_info** | **string** | Informação sobre a disponibilidade ou não de um contrato para a portabilidade de crédito. Deve ser preenchido como uma proposta para inclusão nas definições, exemplo &#x60;MOTIVO_NAO_MAPEADO&#x60;: descrição de usar esse motivo específico. Ao utilizar essa opção, é obrigatório enviar um ticket para a estrutura open finance para mapeamento em futuras versões.  [RESTRIÇÃO] Campo de preenchimento obrigatório quando o campo &#x60;reasonType&#x60; for igual a &#x60;OUTROS&#x60;. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
