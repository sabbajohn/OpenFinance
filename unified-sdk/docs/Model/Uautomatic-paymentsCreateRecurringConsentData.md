# # CreateRecurringConsentData

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**logged_user** | [**\OpenAPI\Client\Model\LoggedUser**](LoggedUser.md) |  |
**business_entity** | [**\OpenAPI\Client\Model\BusinessEntity**](BusinessEntity.md) |  | [optional]
**creditors** | [**\OpenAPI\Client\Model\CreditorsInner[]**](CreditorsInner.md) |  |
**expiration_date_time** | **\DateTime** | Data e hora em que o consentimento deve deixar de ser válido. Uma string com data e hora conforme especificação [RFC-3339](https://datatracker.ietf.org/doc/html/rfc3339), sempre com a utilização de timezone UTC (UTC time format).  [Restrição] Caso o consentimento seja para Pix Automático (\&quot;automatic\&quot; selecionado no oneOf \&quot;/data/recurringConfiguration/\&quot;) o horário de expiração do consentimento precisa ser às 23:59:59 (UTC). | [optional]
**additional_information** | **string** | Deve ser preenchido sempre que o usuário pagador inserir alguma informação adicional no consentimento | [optional]
**debtor_account** | [**\OpenAPI\Client\Model\CreateRecurringConsentDataDebtorAccount**](CreateRecurringConsentDataDebtorAccount.md) |  | [optional]
**recurring_configuration** | [**\OpenAPI\Client\Model\CreateRecurringConsentDataRecurringConfiguration**](CreateRecurringConsentDataRecurringConfiguration.md) |  |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
