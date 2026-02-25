# # ResponsePortabilitiesByPortabilityIdDataRejectionReason

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**type** | **string** | Motivo de recusa do pedido de portabilidade, onde: CANCELADO_PELO_CLIENTE - Cliente desiste do pedido da portabilidade;    SALDO_DEVEDOR_ATUALIZADO_SUBSTANCIALMENTE_DIVERGENTE - Saldo devedor atualizado divergente (superior a 15%) do informado inicialmente;    POLITICA_DE_CREDITO - Proponente desiste da oferta ao cliente por políticas internas;    RETENCAO_DO_CLIENTE - Cliente aceitou contraproposta da instituição credora (dentro do prazo);    CONTRATO_JA_LIQUIDADO - Contrato liquidado pelo cliente;    DIVERGENCIA_DE_PAGAMENTO_EFETUADO - Proponente realizou a liquidação com valor divergente;    DECURSO_DO_PRAZO_PARA_PAGAMENTO - Proponente realizou a liquidação fora do prazo;    PORTABILIDADE_CANCELADA_POR_FALTA_DE_LIQUIDACAO - Proponente não realizou a liquidação da Portabilidade;    PORTABILIDADE_EM_ANDAMENTO - Posteriormente à efetivação do pedido de portabilidade, a IF credora identificou que o cliente já possui outro pedido de portabilidade em andamento para o mesmo contrato;    CLIENTE_COM_ACAO_JUDICIAL - Possui ação judicial;    MODALIDADE_DA_OPERACAO_INCOMPATIVEL - Modalidade divergente da indicada pela instituição proponente;   OUTROS - Motivo da rejeição não se encaixa nas opções disponíveis. |
**type_additional_info** | **string** | Informação sobre a disponibilidade ou não de um contrato para a portabilidade de crédito.  Ao utilizar essa opção, é fortemente recomendável enviar um ticket como sugestão da estrutura Open Finance para discussão e mapeamento em futuras versões.  [RESTRIÇÃO] Campo de preenchimento obrigatório quando campo type for igual a OUTROS. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
