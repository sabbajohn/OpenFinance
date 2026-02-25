# # ConsentEdition

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**risk_signals** | [**\OpenAPI\Client\Model\RiskSignalsConsentEdition**](RiskSignalsConsentEdition.md) |  | [optional]
**creditors** | [**\OpenAPI\Client\Model\ConsentEditionCreditorsInner[]**](ConsentEditionCreditorsInner.md) |  |
**expiration_date_time** | **\DateTime** | Data e hora em que o consentimento deve deixar de ser válido. Uma string com data e hora conforme especificação [RFC-3339](https://datatracker.ietf.org/doc/html/rfc3339), sempre com a utilização de timezone UTC(UTC time format).   [Restrição] Caso esse campo não seja enviado, a instituição detentora deve considerar a extensão do prazo para um período indeterminado; ou;   Caso enviado, esse campo deve ser, no mínimo, D, sendo D o dia da requisição e o horário deve ser às 23:59:59 (UTC). | [optional]
**recurring_configuration** | [**\OpenAPI\Client\Model\ConsentEditionRecurringConfiguration**](ConsentEditionRecurringConfiguration.md) |  | [optional]
**logged_user** | [**\OpenAPI\Client\Model\ConsentEditionLoggedUser**](ConsentEditionLoggedUser.md) |  | [optional]
**business_entity** | [**\OpenAPI\Client\Model\ConsentEditionBusinessEntity**](ConsentEditionBusinessEntity.md) |  | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
