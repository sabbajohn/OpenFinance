# # ResponsePaymentConsentData

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**consent_id** | **string** | Identificador único do consentimento criado para a iniciação de pagamento solicitada. Deverá ser um URN - Uniform Resource Name. Um URN, conforme definido na [RFC8141](https://tools.ietf.org/html/rfc8141) é um Uniform Resource Identifier - URI - que é atribuído sob o URI scheme \&quot;urn\&quot; e um namespace URN específico, com a intenção de que o URN seja um identificador de recurso persistente e independente da localização. Considerando a string urn:bancoex:C1DD33123 como exemplo para consentId temos: - o namespace(urn) - o identificador associado ao namespace da instituição transnmissora (bancoex) - o identificador específico dentro do namespace (C1DD33123). Informações mais detalhadas sobre a construção de namespaces devem ser consultadas na [RFC8141](https://tools.ietf.org/html/rfc8141). |
**creation_date_time** | **\DateTime** | Data e hora em que o consentimento foi criado. Uma string com data e hora conforme especificação RFC-3339, sempre com a utilização de timezone UTC(UTC time format). |
**expiration_date_time** | **\DateTime** | Data e hora em que o consentimento da iniciação de pagamento expira.  Para consentimentos em status AWAITING_AUTHORISATION, deve ser sempre “creationDateTime + 5 minutos”.  Após esse tempo, não sendo aprovado (seja a aprovação única ou primeiro aprovador), o consentimento deve ir para REJECTED.  Para consentimentos em status PARTIALLY_ACCEPTED, deve assumir o valor da política de aprovação de cada instituição.  Para consentimentos em status AUTHORISED, devem assumir o valor de “statusUpdateDateTime + 60 minutos”, sendo esse o tempo máximo permitido para o consumo do consentimento.  Caso não seja consumido, deve ser movido para o status REJECTED. |
**status_update_date_time** | **\DateTime** | Data e hora em que o recurso foi atualizado. Uma string com data e hora conforme especificação RFC-3339, sempre com a utilização de timezone UTC(UTC time format). |
**status** | [**\OpenAPI\Client\Model\EnumAuthorisationStatusType**](EnumAuthorisationStatusType.md) |  |
**logged_user** | [**\OpenAPI\Client\Model\LoggedUser**](LoggedUser.md) |  |
**business_entity** | [**\OpenAPI\Client\Model\BusinessEntity**](BusinessEntity.md) |  | [optional]
**creditor** | [**\OpenAPI\Client\Model\Identification**](Identification.md) |  |
**payment** | [**\OpenAPI\Client\Model\PaymentConsent**](PaymentConsent.md) |  |
**debtor_account** | [**\OpenAPI\Client\Model\ConsentsDebtorAccount**](ConsentsDebtorAccount.md) |  | [optional]
**rejection_reason** | [**\OpenAPI\Client\Model\ConsentRejectionReason**](ConsentRejectionReason.md) |  | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
