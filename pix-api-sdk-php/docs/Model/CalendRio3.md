# # CalendRio3

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**criacao** | **\DateTime** | Timestamp que indica o momento em que foi criada a cobrança. Respeita o formato definido na RFC 3339. |
**apresentacao** | **\DateTime** | Timestamp que indica o momento em que o payload JSON que representa a cobrança foi recuperado. Ou seja, idealmente, é o momento em que o usuário realizou a captura do QR Code para verificar os dados de pagamento. Respeita o formato definido na RFC 3339. |
**expiracao** | **int** | Tempo de vida da cobrança, especificado em segundos a partir da data de criação (Calendario.criacao) |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
