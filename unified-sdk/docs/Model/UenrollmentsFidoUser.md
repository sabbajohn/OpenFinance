# # FidoUser

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**id** | **string** | Identificador único do usuário sob registro em formato base64. A conversão deste valor para o formato original (BufferSource ou ArrayBuffer) não deve ultrapassar 64 bytes.  O identificador único deve ser opaco, ou seja, não deve carregar dados pessoais sobre o usuário, por exemplo (não exaustivo) um UUID RFC4122 cumpre com os requisitos desse campo |
**name** | **string** | Identificador do usuário human-readable. |
**display_name** | **string** | Identificador do usuário para fins de apresentação. Deve ser formado pelo nome social, se existente, ou nome e sobrenome do cadastro do cliente no detentor de contas. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
