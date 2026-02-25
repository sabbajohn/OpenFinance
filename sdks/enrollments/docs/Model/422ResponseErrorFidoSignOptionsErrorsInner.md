# # 422ResponseErrorFidoSignOptionsErrorsInner

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**code** | **string** | Códigos de erros previstos: - RP_INVALIDA: O identificador da Relying Party informado não pode ser verificado. - STATUS_VINCULO_INVALIDO: O status do vínculo de conta é tal que não permite assinatura.  - STATUS_CONSENTIMENTO_INVALIDO: O status do consentimento não permite autorização. - PARAMETRO_NAO_INFORMADO: Parâmetro não informado. - PARAMETRO_INVALIDO: Parâmetro inválido. - ERRO_IDEMPOTENCIA: Erro idempotência. |
**title** | **string** | Título específico do erro reportado, de acordo com o código enviado: - RP_INVALIDA: Relying party inválida. - STATUS_VINCULO_INVALIDO: Status do vínculo de conta inválido. - STATUS_CONSENTIMENTO_INVALIDO: Status do consentimento de pagamento inválido. - PARAMETRO_NAO_INFORMADO: Parâmetro não informado. - PARAMETRO_INVALIDO: Parâmetro inválido. - ERRO_IDEMPOTENCIA: Erro idempotência. |
**detail** | **string** | Descrição específica do erro de acordo com o código reportado: - RP_INVALIDA: O identificador da Relying Party informado não pode ser verificado. - STATUS_VINCULO_INVALIDO: O status do vínculo de conta é tal que não permite assinatura.  - STATUS_CONSENTIMENTO_INVALIDO: O status do consentimento não permite autorização. - PARAMETRO_NAO_INFORMADO: Parâmetro [nome_campo] obrigatório não informado. - PARAMETRO_INVALIDO: Parâmetro [nome_campo] não obedece as regras de formatação esperadas. - ERRO_IDEMPOTENCIA: Conteúdo da mensagem (claim data) diverge do conteúdo associado a esta chave de idempotência (x-idempotency-key). |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
