# # ResponseErrorCreateConsentErrorsInner

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**code** | **string** | Códigos de erros previstos na criação de consentimento para a iniciação de pagamentos:    - DATA_PAGAMENTO_INVALIDA - DETALHE_PAGAMENTO_INVALIDO - PARAMETRO_NAO_INFORMADO - PARAMETRO_INVALIDO - ERRO_IDEMPOTENCIA - NAO_INFORMADO - FUNCIONALIDADE_NAO_HABILITADA |
**title** | **string** | Título específico do erro reportado, de acordo com o código enviado:   - DATA_PAGAMENTO_INVALIDA: Data de pagamento inválida.   - DETALHE_PAGAMENTO_INVALIDO: Detalhe do pagamento inválido.   - PARAMETRO_NAO_INFORMADO: Parâmetro não informado.   - PARAMETRO_INVALIDO: Parâmetro inválido.   - ERRO_IDEMPOTENCIA: Erro idempotência.   - NAO_INFORMADO: Não informado.   - FUNCIONALIDADE_NAO_HABILITADA: A detentora de conta não oferece o serviço nessa modalidade. |
**detail** | **string** | Descrição específica do erro de acordo com o código reportado:   - DATA_PAGAMENTO_INVALIDA: Data de pagamento inválida para a forma de pagamento selecionada.   - DETALHE_PAGAMENTO_INVALIDO: Parâmetro [nome_campo] não obedece às regras de negócio.   - PARAMETRO_NAO_INFORMADO: Parâmetro [nome_campo] obrigatório não informado.   - PARAMETRO_INVALIDO: Parâmetro [nome_campo] não obedece as regras de formatação esperadas.   - ERRO_IDEMPOTENCIA: Conteúdo da mensagem (claim data) diverge do conteúdo associado a esta chave de idempotência (x-idempotency-key).   - NAO_INFORMADO: Não reportado/identificado pela instituição detentora de conta.   - FUNCIONALIDADE_NAO_HABILITADA: A detentora de conta não oferece o serviço nessa modalidade. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
