# # EnrollmentCreateFidoSigningOptionsRequestData

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**rp** | **string** | Identificador único da Relying Party, que corresponde ao valor do CN do certificado de transporte da iniciadora. |
**platform** | **string** | Indica a plataforma em que o usuário criará a nova credencial FIDO2.  Este campo permite que o servidor FIDO inclua extensões de acordo com a plataforma utilizada. |
**consent_id** | **string** | O consentId é o identificador único do consentimento e deverá ser um URN - Uniform Resource Name.   Um URN, conforme definido na [RFC8141](https://tools.ietf.org/html/rfc8141) é um Uniform Resource  Identifier - URI - que é atribuído sob o URI scheme \&quot;urn\&quot; e um namespace URN específico, com a intenção de que o URN  seja um identificador de recurso persistente e independente da localização.   Considerando a string urn:bancoex:C1DD33123 como exemplo para consentId temos: - o namespace(urn) - o identificador associado ao namespace da instituição detentora de conta (bancoex)  - o identificador específico dentro do namespace (C1DD33123).   Informações mais detalhadas sobre a construção de namespaces devem ser consultadas na [RFC8141](https://tools.ietf.org/html/rfc8141). |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
