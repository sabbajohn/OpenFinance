# # ConsentAuthorizationDataFidoAssertionResponse

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**client_data_json** | **string** | Agrega as informações do aplicativo que gerou a credencial. Deve ser enviado no formato base64url para a detentora de conta. |
**authenticator_data** | **string** | Representa a estrutura de dados do autenticador. Deve ser enviado no formato base64url para a detentora de conta. |
**signature** | **string** | Sequência de bytes contendo a assinatura. Deve ser enviado no formato base64url para a detentora de conta. |
**user_handle** | **string** | Nome de usuário que foi enviado durante a criação da credencial. Deve ser enviado no formato base64url para a detentora de conta.  Caso o autenticador FIDO2 não retorne este campo, a iniciadora deve enviar uma string vazia à detentora de conta. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
