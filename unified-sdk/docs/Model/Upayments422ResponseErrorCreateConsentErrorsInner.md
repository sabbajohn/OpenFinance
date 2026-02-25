# # 422ResponseErrorCreateConsentErrorsInner

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**code** | **string** | Códigos de erros previstos na criação de consentimento para a iniciação de pagamentos:   • FORMA_PAGAMENTO_INVALIDA: Forma de pagamento inválida.   • DATA_PAGAMENTO_INVALIDA: Data de pagamento inválida.   • DETALHE_PAGAMENTO_INVALIDO: Detalhe do pagamento inválido.   • PARAMETRO_NAO_INFORMADO: Parâmetro não informado.   • PARAMETRO_INVALIDO: Parâmetro inválido.   • ERRO_IDEMPOTENCIA: Erro idempotência.   • NAO_INFORMADO: Não informado. |
**title** | **string** | Título específico do erro reportado, de acordo com o código enviado:   • FORMA_PAGAMENTO_INVALIDA: Forma de pagamento inválida.   • DATA_PAGAMENTO_INVALIDA: Data de pagamento inválida.   • DETALHE_PAGAMENTO_INVALIDO: Detalhe do pagamento inválido.   • PARAMETRO_NAO_INFORMADO: Parâmetro não informado.   • PARAMETRO_INVALIDO: Parâmetro inválido.   • ERRO_IDEMPOTENCIA: Erro idempotência.   • NAO_INFORMADO: Não informado. |
**detail** | **string** | Descrição específica do erro de acordo com o código reportado:   • FORMA_PAGAMENTO_INVALIDA: Forma de pagamento [Modalidade] não suportada.   • DATA_PAGAMENTO_INVALIDA: Data de pagamento inválida para a forma de pagamento selecionada.   • DETALHE_PAGAMENTO_INVALIDO: Parâmetro [nome_campo] não obedece às regras de negócio.   • PARAMETRO_NAO_INFORMADO: Parâmetro [nome_campo] obrigatório não informado.   • PARAMETRO_INVALIDO: Parâmetro [nome_campo] não obedece as regras de formatação esperadas.   • ERRO_IDEMPOTENCIA: Conteúdo da mensagem (claim data) diverge do conteúdo associado a esta chave de idempotência (x-idempotency-key).   • NAO_INFORMADO: Não reportado/identificado pela instituição detentora de conta. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
