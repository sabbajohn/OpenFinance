# # EnrollmentFidoRegistrationOptionsData

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**enrollment_id** | **string** | Identificador único do vínculo de conta criado para a iniciação de pagamento solicitada. Deverá ser um URN - Uniform Resource Name. Um URN, conforme definido na [RFC8141](https://tools.ietf.org/html/rfc8141) é um Uniform Resource Identifier - URI - que é atribuído sob o URI scheme \&quot;urn\&quot; e um namespace URN específico, com a intenção de que o URN seja um identificador de recurso persistente e independente da localização. Considerando a string urn:bancoex:C1DD33123 como exemplo para enrollmentId temos: - o namespace(urn) - o identificador associado ao namespace da instituição detentora de conta (bancoex) - o identificador específico dentro do namespace (C1DD33123). Informações mais detalhadas sobre a construção de namespaces devem ser consultadas na [RFC8141](https://tools.ietf.org/html/rfc8141). |
**rp** | [**\OpenAPI\Client\Model\FidoRelyingParty**](FidoRelyingParty.md) |  |
**user** | [**\OpenAPI\Client\Model\FidoUser**](FidoUser.md) |  |
**challenge** | **string** | Sequência de bytes aleatórios gerados pelo servidor FIDO2. Deve ser o valor em formato base64url sem padding. |
**pub_key_cred_params** | [**\OpenAPI\Client\Model\FidoPublicKeyCredentialCreationOptions[]**](FidoPublicKeyCredentialCreationOptions.md) |  |
**timeout** | **int** | Timeout, em milissegundos, para registro da credencial FIDO2. | [optional]
**exclude_credentials** | [**\OpenAPI\Client\Model\FidoPublicKeyCredentialDescriptor[]**](FidoPublicKeyCredentialDescriptor.md) |  | [optional]
**authenticator_selection** | [**\OpenAPI\Client\Model\FidoAuthenticatorSelectionCriteria**](FidoAuthenticatorSelectionCriteria.md) |  | [optional]
**attestation** | **string** | Indica o tipo de attestation que o autenticador pode utilizar. | [optional]
**attestation_formats** | **string[]** | Indica as preferências de formato sobre o campo attestation. | [optional]
**extensions** | **array<string,mixed>** | Campo de extensão com opções que variam por plataforma. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
