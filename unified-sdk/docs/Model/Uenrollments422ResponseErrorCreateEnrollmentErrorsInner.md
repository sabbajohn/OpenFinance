# # 422ResponseErrorCreateEnrollmentErrorsInner

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**code** | **string** | Códigos de erros previstos na criação do vínculo de conta: - PERMISSOES_INVALIDAS: As permissões associadas ao vínculo de conta não contêm \&quot;PAYMENTS_INITIATE\&quot;. - CONTA_INVALIDA: A conta informada inexiste ou não é compatível com o fluxo de não-redirecionamento. - PARAMETRO_NAO_INFORMADO: Parâmetro não informado. - PARAMETRO_INVALIDO: Parâmetro inválido. - ERRO_IDEMPOTENCIA: Erro idempotência. |
**title** | **string** | Título específico do erro reportado, de acordo com o código enviado: - PERMISSOES_INVALIDAS: Permissões inválidas. - CONTA_INVALIDA: Conta inválida. - PARAMETRO_NAO_INFORMADO: Parâmetro não informado. - PARAMETRO_INVALIDO: Parâmetro inválido. - ERRO_IDEMPOTENCIA: Erro idempotência. |
**detail** | **string** | Descrição específica do erro de acordo com o código reportado: - PERMISSOES_INVALIDAS: As permissões associadas ao vínculo de conta não contêm \&quot;PAYMENTS_INITIATE\&quot; ou contêm valores não suportados para esta operação.   - CONTA_INVALIDA: A conta informada inexiste ou não é compatível com o fluxo de não-redirecionamento. - PARAMETRO_NAO_INFORMADO: Parâmetro [nome_campo] obrigatório não informado. - PARAMETRO_INVALIDO: Parâmetro [nome_campo] não obedece as regras de formatação esperadas. - ERRO_IDEMPOTENCIA: Conteúdo da mensagem (claim data) diverge do conteúdo associado a esta chave de idempotência (x-idempotency-key). |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
