# # CreateConsentData

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**logged_user** | [**\OpenAPI\Client\Model\LoggedUser**](LoggedUser.md) |  |
**business_entity** | [**\OpenAPI\Client\Model\BusinessEntity**](BusinessEntity.md) |  | [optional]
**permissions** | **string[]** |  |
**expiration_date_time** | **\DateTime** | Data e hora de expiração da permissão. Reflete a data limite de validade do consentimento. Uma string com data e hora conforme especificação RFC-3339, sempre com a utilização de timezone UTC (UTC time format).  [Restrição] De preenchimento obrigatório nos casos em que houver validade determinada. Em casos de consentimento com prazo indeterminado o campo não deve ser enviado.  Quando preenchido, o valor do campo não pode ultrapassar 12 meses. | [optional]
**is_linked** | **bool** | Campo para identificação de consentimento iniciado em Jornada Otimizada. [RESTRIÇÃO] Campo de preenchimento obrigatório para todo consentimento iniciado a partir da jornada otimizada, independente do status do consentimento. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
