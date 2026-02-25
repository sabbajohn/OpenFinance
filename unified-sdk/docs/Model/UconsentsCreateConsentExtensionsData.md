# # CreateConsentExtensionsData

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**expiration_date_time** | **\DateTime** | Data e hora de expiração da permissão. Reflete a data limite de validade do consentimento. Uma string com data e hora conforme especificação RFC-3339, sempre com a utilização de timezone UTC (UTC time format).  [Restrição] De preenchimento obrigatório nos casos em que houver validade determinada. Em casos de consentimento com prazo indeterminado o campo não deve ser enviado.  Quando preenchido, o valor do campo não pode ultrapassar 12 meses. | [optional]
**logged_user** | [**\OpenAPI\Client\Model\LoggedUserExtensions**](LoggedUserExtensions.md) |  |
**business_entity** | [**\OpenAPI\Client\Model\BusinessEntityExtensions**](BusinessEntityExtensions.md) |  | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
