# # SweepingSweeping

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**total_allowed_amount** | **string** | Valor máximo a ser atingido pelo somatório de todas as transações que utilizam o consentimento autorizado pelo cliente. Caso o valor seja superado, a detentora de conta deve negar a transação solicitada pela iniciadora. | [optional]
**transaction_limit** | **string** | Valor máximo para cada transação de pagamento associada a esse consentimento. Caso valor do pagamento seja maior que esse limite, a detentora de contas deve rejeitar a transação de pagamento. | [optional]
**periodic_limits** | [**\OpenAPI\Client\Model\PeriodicLimits**](PeriodicLimits.md) |  | [optional]
**use_overdraft_limit** | **bool** | Indica se o usuário pagador autorizou a utilização de limite pré-aprovado (cheque especial) na sua conta para realização de pagamentos, caso o cliente possua o produto. | [default to true]
**start_date_time** | **\DateTime** | Description: Data e hora em que o consentimento deve passar a ser válido.  Uma string com data e hora conforme especificação [RFC-3339](https://datatracker.ietf.org/doc/html/rfc3339), sempre com a utilização de timezone UTC(UTC time format).  [Restrição] Caso esse campo não seja enviado pelo iniciador na requisição, o detentor deve preencher esse campo com o mesmo valor atribuído ao campo /data/creationDateTime. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
