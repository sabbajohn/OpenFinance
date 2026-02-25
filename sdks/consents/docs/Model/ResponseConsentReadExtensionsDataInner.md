# # ResponseConsentReadExtensionsDataInner

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**expiration_date_time** | **\DateTime** | Data e hora de expiração da permissão. Reflete a data limite de validade do consentimento. Uma string com data e hora conforme especificação RFC-3339, sempre com a utilização de timezone UTC(UTC time format), utilizado apenas para consulta de alterações históricas de extensão do consentimento.  [Restrição] De preenchimento obrigatório nos casos em que houver validade determinada.  Em casos de consentimento com prazo indeterminada o campo não deve ser preenchido. | [optional]
**logged_user** | [**\OpenAPI\Client\Model\LoggedUserExtensions**](LoggedUserExtensions.md) |  |
**request_date_time** | **\DateTime** | Data e hora em que o recurso foi criado. Uma string com data e hora conforme especificação RFC-3339, sempre com a utilização de timezone UTC(UTC time format). |
**previous_expiration_date_time** | **\DateTime** | Data e hora de expiração anteriores a renovação. Reflete a data limite anterior de validade do consentimento. Uma string com data e hora conforme especificação RFC-3339, sempre com a utilização de timezone UTC (UTC time format).  [Restrição] De preenchimento obrigatório nos casos em que houver validade determinada. Em casos de consentimento com prazo indeterminado, ou renovações feitas com a v2.2.0 em que não exista persistência dessa informação, o campo não deve ser preenchido. | [optional]
**x_fapi_customer_ip_address** | **string** | O endereço IP do usuário logado com o receptor que solicitou a renovação sem redirecionamento.  [Restrição] De preenchimento obrigatório a partir da v3.0.0. Opcional para renovações feitas com a v2.2.0 quando não existir persistência dessa informação. | [optional]
**x_customer_user_agent** | **string** | Indica o user-agent que o usuário utilizou quando solicitou a renovação sem redirecionamento.  [Restrição] De preenchimento obrigatório a partir da v3.0.0. Opcional para renovações feitas com a v2.2.0 quando não existir persistência dessa informação. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
