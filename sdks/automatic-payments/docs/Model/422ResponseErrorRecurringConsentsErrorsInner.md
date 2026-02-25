# # 422ResponseErrorRecurringConsentsErrorsInner

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**code** | **string** |  |
**title** | **string** | Título específico do erro reportado, de acordo com o código enviado:   - CONSENTIMENTO_NAO_PERMITE_CANCELAMENTO: O status do consentimento não permite a realização do cancelamento (em status \&quot;CONSUMED\&quot; ou \&quot;REJECTED\&quot;). - CAMPO_NAO_PERMITIDO: O(s) campo(s) solicitado(s) para edição não podem ser editados. - PERMISSAO_INSUFICIENTE: Consentimento possui múltiplas alçadas aprovadoras e não permite a edição pelo usuário atual. - DETALHE_EDICAO_INVALIDO: A tentativa de edição do consentimento não respeitou as regras de negócio descritas nos campos. - FALTAM_SINAIS_OBRIGATORIOS_PLATAFORMA: Os sinais obrigatórios para a plataforma do usuário não foram enviados em sua totalidade. - PARAMETRO_INVALIDO: Os parâmetros informados não obedecem a formatação especificada. - PARAMETRO_NAO_INFORMADO: Algum ou todos os campos obrigatórios não foram informados. |
**detail** | **string** | Descrição específica do erro de acordo com o código reportado:   - CONSENTIMENTO_NAO_PERMITE_CANCELAMENTO: O status do consentimento não permite a realização do cancelamento (em status \&quot;CONSUMED\&quot; ou \&quot;REJECTED\&quot;) - CAMPO_NAO_PERMITIDO: O(s) campo(s) solicitado(s) para edição não podem ser editados. - PERMISSAO_INSUFICIENTE: Consentimento possui múltiplas alçadas aprovadoras e não permite a edição pelo usuário atual. - DETALHE_EDICAO_INVALIDO: A tentativa de edição do consentimento não respeitou as regras de negócio descritas nos campos. - FALTAM_SINAIS_OBRIGATORIOS_PLATAFORMA: Os sinais obrigatórios para a plataforma do usuário não foram enviados em sua totalidade. - PARAMETRO_INVALIDO: Os parâmetros informados não obedecem a formatação especificada. - PARAMETRO_NAO_INFORMADO: Algum ou todos os campos obrigatórios não foram informados. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
