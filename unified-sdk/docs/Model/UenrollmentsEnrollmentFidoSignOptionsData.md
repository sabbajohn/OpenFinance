# # EnrollmentFidoSignOptionsData

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**challenge** | **string** | Sequência de bytes aleatórios gerados pelo servidor FIDO2. Deve ser o valor em formato base64url sem padding. |
**timeout** | **int** | Expiração, em milissegundos, do challenge. | [optional]
**rp_id** | **string** | Identificador da Relying Party. | [optional]
**allow_credentials** | [**\OpenAPI\Client\Model\FidoPublicKeyCredentialDescriptor[]**](FidoPublicKeyCredentialDescriptor.md) |  | [optional]
**user_verification** | **string** |  | [optional]
**extensions** | **array<string,mixed>** | Campo de extensão com opções que variam por plataforma. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
