# # ConsentAuthorizationData

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**enrollment_id** | **string** | Identificador único do vínculo de conta criado para a iniciação de pagamento solicitada. Deverá ser um URN - Uniform Resource Name. Um URN, conforme definido na [RFC8141](https://tools.ietf.org/html/rfc8141) é um Uniform Resource Identifier - URI - que é atribuído sob o URI scheme \&quot;urn\&quot; e um namespace URN específico, com a intenção de que o URN seja um identificador de recurso persistente e independente da localização. Considerando a string urn:bancoex:C1DD33123 como exemplo para enrollmentId temos: - o namespace(urn) - o identificador associado ao namespace da instituição detentora de conta (bancoex) - o identificador específico dentro do namespace (C1DD33123). Informações mais detalhadas sobre a construção de namespaces devem ser consultadas na [RFC8141](https://tools.ietf.org/html/rfc8141). |
**risk_signals** | [**\OpenAPI\Client\Model\ConsentAuthorizationDataRiskSignals**](ConsentAuthorizationDataRiskSignals.md) |  |
**fido_assertion** | [**\OpenAPI\Client\Model\ConsentAuthorizationDataFidoAssertion**](ConsentAuthorizationDataFidoAssertion.md) |  |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
