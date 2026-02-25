# # CreateRecurringPixPaymentDataDocument

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**identification** | **string** | Número do documento de identificação oficial do recebedor pessoa natural ou jurídica.  O valor informado deve ser igual a um dos valores enviados na etapa de criação do consentimento (dentro do array “/data/creditors”).  Quando não respeitada essa regra, deve ser retornado pelo detentor, de maneira síncrona, erro HTTP 422 - PAGAMENTO_DIVERGENTE_CONSENTIMENTO |
**rel** | **string** | Tipo do documento de identificação oficial do titular pessoa natural ou jurídica. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
