# # ResponseRecurringConsentData

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**recurring_consent_id** | **string** | Identificador único do consentimento de longa duração criado para a iniciação de pagamento solicitada. Deverá ser um URN - Uniform Resource Name. Um URN, conforme definido na [RFC8141](https://datatracker.ietf.org/doc/html/rfc8141) é um Uniform Resource Identifier - URI - que é atribuído sob o URI scheme \&quot;urn\&quot; e um namespace URN específico, com a intenção de que o URN seja um identificador de recurso persistente e independente da localização. Considerando a string urn:bancoex:C1DD33123 como exemplo para &#x60;recurringConsentId&#x60; temos:   - o namespace(urn)   - o identificador associado ao namespace da instituição transmissora (bancoex)   - o identificador específico dentro do namespace (C1DD33123). Informações mais detalhadas sobre a construção de namespaces devem ser consultadas na [RFC8141](https://datatracker.ietf.org/doc/html/rfc8141). |
**status_update_date_time** | **\DateTime** | Data e hora em que o consentimento teve o status atualizado. Uma string com data e hora conforme especificação [RFC-3339](https://datatracker.ietf.org/doc/html/rfc3339), sempre com a utilização de timezone UTC(UTC time format). |
**logged_user** | [**\OpenAPI\Client\Model\LoggedUser**](LoggedUser.md) |  |
**business_entity** | [**\OpenAPI\Client\Model\BusinessEntity**](BusinessEntity.md) |  | [optional]
**status** | [**\OpenAPI\Client\Model\EnumAuthorisationStatusType**](EnumAuthorisationStatusType.md) |  |
**creditors** | [**\OpenAPI\Client\Model\CreditorsInner[]**](CreditorsInner.md) |  |
**creation_date_time** | **\DateTime** | Data e hora em que o consentimento foi criado. Uma string com data e hora conforme especificação [RFC-3339](https://datatracker.ietf.org/doc/html/rfc3339), sempre com a utilização de timezone UTC(UTC time format). |
**expiration_date_time** | **\DateTime** | Data e hora em que o consentimento deve deixar de ser válido. Uma string com data e hora conforme especificação [RFC-3339](https://datatracker.ietf.org/doc/html/rfc3339), sempre com a utilização de timezone UTC (UTC time format).  [Restrição] Caso o consentimento seja para Pix Automático (\&quot;automatic\&quot; selecionado no oneOf \&quot;/data/recurringConfiguration/\&quot;) o horário de expiração do consentimento precisa ser às 23:59:59 (UTC). | [optional]
**additional_information** | **string** | Deve ser preenchido sempre que o usuário pagador inserir alguma informação adicional no consentimento | [optional]
**debtor_account** | [**\OpenAPI\Client\Model\ResponseRecurringConsentDataDebtorAccount**](ResponseRecurringConsentDataDebtorAccount.md) |  | [optional]
**rejection** | [**\OpenAPI\Client\Model\Rejection**](Rejection.md) |  | [optional]
**revocation** | [**\OpenAPI\Client\Model\ResponsePostRecurringConsentDataRevocation**](ResponsePostRecurringConsentDataRevocation.md) |  | [optional]
**recurring_configuration** | [**\OpenAPI\Client\Model\RecurringConfiguration**](RecurringConfiguration.md) |  |
**risk_signals** | [**\OpenAPI\Client\Model\RiskSignalsConsents**](RiskSignalsConsents.md) |  | [optional]
**authorised_at_date_time** | **\DateTime** | Data e hora em que o consentimento foi autorizado.  [Restrição] Campo de envio obrigatório quando consentimento transitar para AUTHORISED. | [optional]
**updated_at_date_time** | **\DateTime** | Data e hora em que o consentimento foi atualizado pelo usuário pagador.  O campo deve ser atualizado pelo detentor sempre que o consentimento for editado.  Caso a edição seja realizada a partir do iniciador, o detentor deve preencher com a data e hora (UTC) em que recebeu a solicitação de edição.  A edição só é permitida para o produto Pix automático. | [optional]
**approval_due_date** | **\DateTime** | Representa a data máxima para aprovação de um consentimento que encontra-se (ou passou) pelo estado PARTIALLY_ACCEPTED. A aprovação deve ocorrer até as 23:59h do dia informado, caso contrário, consentimento deve ser rejeitado.  [Restrição] Deve ser preenchido pela instituição detentora sempre que um consentimento estiver (ou passado) no estado PARTIALLY_ACCEPTED | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
