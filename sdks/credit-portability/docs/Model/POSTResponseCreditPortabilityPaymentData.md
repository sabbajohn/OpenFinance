# # POSTResponseCreditPortabilityPaymentData

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**portability_id** | **string** | Código identificador do pedido de portabilidade realizado. |
**payment_date_time** | **string** | Data e hora em que o pagamento à instituição credora foi realizado pela instituição proponente.  Uma string com data e hora conforme especificação [RFC-3339](https://datatracker.ietf.org/doc/html/rfc3339), sempre com a utilização de timezone UTC-0 (UTC time format) |
**payment_amount** | [**\OpenAPI\Client\Model\ResponsePortabilitiesByPortabilityIdDataLoanSettlementInstructionSettlementAmount**](ResponsePortabilitiesByPortabilityIdDataLoanSettlementInstructionSettlementAmount.md) |  |
**transaction_id** | **string** | Identificador da transação utilizada para proponente liquidar a portabilidade de crédito com a credora.  No contexto da STR0052, utilizar o valor do campo de retorno NumCtrlSTR (Numero de Controle da STR) |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
