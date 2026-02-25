# # ResponseConsentReadData

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**consent_id** | **string** | O consentId é o identificador único do consentimento e deverá ser um URN - Uniform Resource Name.   Um URN, conforme definido na [RFC8141](https://tools.ietf.org/html/rfc8141) é um Uniform Resource  Identifier - URI - que é atribuído sob o URI scheme \&quot;urn\&quot; e um namespace URN específico, com a intenção de que o URN  seja um identificador de recurso persistente e independente da localização.   Considerando a string urn:bancoex:C1DD33123 como exemplo para consentId temos: - o namespace(urn) - o identificador associado ao namespace da instituição transnmissora (bancoex)  - o identificador específico dentro do namespace (C1DD33123).   Informações mais detalhadas sobre a construção de namespaces devem ser consultadas na [RFC8141](https://tools.ietf.org/html/rfc8141). |
**creation_date_time** | **\DateTime** | Data e hora em que o recurso foi criado. Uma string com data e hora conforme especificação RFC-3339, sempre com a utilização de timezone UTC(UTC time format). |
**status** | **string** | Estado atual do consentimento cadastrado. |
**status_update_date_time** | **\DateTime** | Data e hora em que o recurso foi atualizado. Uma string com data e hora conforme especificação RFC-3339, sempre com a utilização de timezone UTC(UTC time format). |
**permissions** | **string[]** | Especifica os tipos de permissões de acesso às APIs no escopo do Open Finance Brasil - Dados cadastrais e transacionais, de acordo com os blocos de consentimento fornecidos pelo usuário e necessários ao acesso a cada endpoint das APIs. Esse array não deve ter duplicidade de itens. |
**expiration_date_time** | **\DateTime** | Data e hora de expiração da permissão. Reflete a data limite de validade do consentimento. Uma string com data e hora conforme especificação RFC-3339, sempre com a utilização de timezone UTC(UTC time format).    [Restrição] De preenchimento obrigatório nos casos em que houver validade determinada. Em casos de consentimento com prazo indeterminado o campo não deve ser preenchido. | [optional]
**rejection** | [**\OpenAPI\Client\Model\ResponseConsentReadDataRejection**](ResponseConsentReadDataRejection.md) |  | [optional]
**journey** | [**\OpenAPI\Client\Model\ResponseConsentReadDataJourney**](ResponseConsentReadDataJourney.md) |  | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
