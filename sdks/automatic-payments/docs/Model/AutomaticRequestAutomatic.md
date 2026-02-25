# # AutomaticRequestAutomatic

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**contract_id** | **string** | Identificador do contrato de transação |
**fixed_amount** | **string** | Valor fixo de cobrança, caso preenchido, representa um consentimento para pagamentos de valores fixos, ou não sujeitos a alteração durante a vigência do consentimento.   [Restrição] Excludente com o campo “/data/recurringConfiguration/automatic/maximumVariableAmount” | [optional]
**maximum_variable_amount** | **string** | Valor máximo permitido por cobrança, caso preenchido, representa um consentimento para pagamentos de valores variáveis.   [Restrição] Excludente com o campo “/data/recurringConfiguration/automatic/fixedAmount” | [optional]
**interval** | **string** | Define a periodicidade permitida para realização de transações - SEMANAL - MENSAL - ANUAL - SEMESTRAL - TRIMESTRAL |
**contract_debtor** | [**\OpenAPI\Client\Model\ContractDebtor**](ContractDebtor.md) |  |
**first_payment** | [**\OpenAPI\Client\Model\FirstPayment**](FirstPayment.md) |  | [optional]
**minimum_variable_amount** | **string** | Valor definido pelo usuário recebedor.  Se o usuário pagador atribuir um valor máximo para os pagamentos daquela autorização (campo “maximumVariableAmount”), ele não poderá ser inferior ao piso definido pelo usuário recebedor. Não pode ser preenchido nas autorizações de valor fixo, ou seja, com campo “/data/recurringConfiguration/automatic/fixedAmount”.  Não representa um valor mínimo de cobrança para o pagamento. | [optional]
**is_retry_accepted** | **bool** | Indica se é permitido pelo cliente recebedor fazer tentativas de pagamento (extradia), conforme as regras estabelecidas no arranjo Pix. |
**reference_start_date** | **\DateTime** | Representa a data prevista para a primeira ocorrência de um pagamento associado a recorrência.  Uma string com data e hora conforme especificação [RFC-3339](https://datatracker.ietf.org/doc/html/rfc3339), sempre com a utilização de timezone UTC(UTC time format). |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
