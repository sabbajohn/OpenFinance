# # 422ResponseErrorRiskSignalsErrorsInner

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**code** | **string** | Códigos de erros previstos:  - STATUS_VINCULO_INVALIDO: O status do vínculo de conta é incompatível com a operação. - FALTAM_SINAIS_OBRIGATORIOS_DA_PLATAFORMA: Os sinais obrigatórios para a plataforma do usuário não foram enviados em sua totalidade. - PARAMETRO_NAO_INFORMADO: Parâmetro não informado. - PARAMETRO_INVALIDO: Parâmetro inválido. - ERRO_IDEMPOTENCIA: Erro idempotência. |
**title** | **string** | Título específico do erro reportado, de acordo com o código enviado:  - STATUS_VINCULO_INVALIDO: Status do vínculo de conta inválido. - FALTAM_SINAIS_OBRIGATORIOS_DA_PLATAFORMA: Os sinais obrigatórios para a plataforma do usuário não foram enviados em sua totalidade. - PARAMETRO_NAO_INFORMADO: Parâmetro não informado. - PARAMETRO_INVALIDO: Parâmetro inválido. - ERRO_IDEMPOTENCIA: Erro idempotência. |
**detail** | **string** | Descrição específica do erro de acordo com o código reportado:  - STATUS_VINCULO_INVALIDO: O status do vínculo de conta é incompatível com a operação. - FALTAM_SINAIS_OBRIGATORIOS_DA_PLATAFORMA: Os sinais obrigatórios para a plataforma do usuário não foram enviados em sua totalidade. - PARAMETRO_NAO_INFORMADO: Parâmetro [nome_campo] obrigatório não informado. - PARAMETRO_INVALIDO: Parâmetro [nome_campo] não obedece as regras de formatação esperadas. - ERRO_IDEMPOTENCIA: Conteúdo da mensagem (claim data) diverge do conteúdo associado a esta chave de idempotência (x-idempotency-key). |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
