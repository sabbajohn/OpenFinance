# # ResponseEnrollmentData

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**enrollment_id** | **string** | Identificador único do vínculo de conta criado para a iniciação de pagamento solicitada. Deverá ser um URN - Uniform Resource Name. Um URN, conforme definido na [RFC8141](https://tools.ietf.org/html/rfc8141) é um Uniform Resource Identifier - URI - que é atribuído sob o URI scheme \&quot;urn\&quot; e um namespace URN específico, com a intenção de que o URN seja um identificador de recurso persistente e independente da localização. Considerando a string urn:bancoex:C1DD33123 como exemplo para enrollmentId temos: - o namespace(urn) - o identificador associado ao namespace da instituição detentora de conta (bancoex) - o identificador específico dentro do namespace (C1DD33123). Informações mais detalhadas sobre a construção de namespaces devem ser consultadas na [RFC8141](https://tools.ietf.org/html/rfc8141). |
**creation_date_time** | **\DateTime** | O instante em que o vínculo de conta foi criado no ambiente da detentora. |
**status** | [**\OpenAPI\Client\Model\EnumEnrollmentStatus**](EnumEnrollmentStatus.md) |  |
**status_update_date_time** | **\DateTime** | O instante em que ocorreu a última alteração de status do vínculo de conta. |
**permissions** | [**\OpenAPI\Client\Model\EnumEnrollmentPermission[]**](EnumEnrollmentPermission.md) |  |
**expiration_date_time** | **\DateTime** | Data e hora de expiração da permissão. Reflete a data limite de validade do vínculo.  Uma string com data e hora conforme especificação RFC-3339, sempre com a utilização de timezone UTC (UTC time format).  [Restrição] De preenchimento obrigatório nos casos em que houver validade determinada.  Em casos de vínculo com prazo indeterminado, o campo não deve ser preenchido. | [optional]
**logged_user** | [**\OpenAPI\Client\Model\LoggedUser**](LoggedUser.md) |  |
**business_entity** | [**\OpenAPI\Client\Model\BusinessEntity**](BusinessEntity.md) |  | [optional]
**debtor_account** | [**\OpenAPI\Client\Model\ResponseEnrollmentDataDebtorAccount**](ResponseEnrollmentDataDebtorAccount.md) |  | [optional]
**cancellation** | [**\OpenAPI\Client\Model\ResponseEnrollmentDataCancellation**](ResponseEnrollmentDataCancellation.md) |  | [optional]
**transaction_limit** | **string** | Valor máximo, por transação, admitido para este vínculo de conta. Este limite não garante a autorização de iniciações de pagamento; servindo como referência para a iniciadora evitar a criação de consentimentos de valores tais que, garantidamente, não serão autorizados.  [Restrição] Campo de preenchimento obrigatório pelos participantes quando o campo &#x60;status&#x60; for preenchido com os valores &#x60;AUTHORISED&#x60; ou &#x60;AWAITING_ENROLLMENT&#x60;. | [optional]
**daily_limit** | **string** | Limite diário cumulativo para este vínculo de conta. Este limite não garante a autorização de iniciações de pagamento; servindo como referência para a iniciadora evitar a criação de consentimentos para valores tais que, garantidamente, não serão autorizados. Este campo só estará presente quando o usuário, durante a autorização do vínculo, definir um valor máximo diário diferente do seu limite Pix disponível para o dia. | [optional]
**enrollment_name** | **string** | [Restrição] Deve ser preenchido sempre que o usuário pagador inserir alguma informação no nome do vínculo/dispositivo tanto no iniciador como no detentor de conta | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
