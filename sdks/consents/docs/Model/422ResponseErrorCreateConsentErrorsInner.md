# # 422ResponseErrorCreateConsentErrorsInner

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**code** | **string** | Códigos de erros previstos na durante o processo de extensão do consentimento:  - DEPENDE_MULTIPLA_ALCADA: Necessário aprovação de múltipla alçada.  - ESTADO_CONSENTIMENTO_INVALIDO: Estado inválido do consentimento.  - DATA_EXPIRACAO_INVALIDA: Nova data para expiração do consentimento é inválida.  - ERRO_NAO_MAPEADO: Utilizado quando não houver um code de erro definido. |
**title** | **string** | Título específico do erro reportado, de acordo com o código enviado: - DEPENDE_MULTIPLA_ALCADA: Necessário aprovação de múltipla alçada. - ESTADO_CONSENTIMENTO_INVALIDO: Estado inválido do consentimento. - DATA_EXPIRACAO_INVALIDA: Nova data para expiração do consentimento é inválida. - ERRO_NAO_MAPEADO: Utilizado quando não houver um code de erro definido. O texto deve deixar claro o motivo do erro ocorrido. |
**detail** | **string** | Título específico do erro reportado, de acordo com o código enviado: - DEPENDE_MULTIPLA_ALCADA: O consentimento informado não pode ser renovado sem redirecionamento porque depende de múltipla alçada para aprovação. - ESTADO_CONSENTIMENTO_INVALIDO: O consentimento informado não pode ser renovado sem redirecionamento porque está em um estado que não permite a renovação. - DATA_EXPIRACAO_INVALIDA: O consentimento informado não pode ser renovado pois a nova data de expiração não segue a convenção do ecossistema. - ERRO_NAO_MAPEADO: Utilizado quando não houver um code de erro definido. O texto deve deixar claro o motivo do erro ocorrido. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
