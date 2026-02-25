# # ResponseConsentReadDataRejectionReason

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**code** | **string** | Define o código da razão pela qual o consentimento foi rejeitado.  - CONSENT_EXPIRED – consentimento que ultrapassou o tempo limite para autorização.    - CUSTOMER_MANUALLY_REJECTED – cliente efetuou a rejeição do consentimento manualmente através de interação nas instituições participantes.    - CUSTOMER_MANUALLY_REVOKED – cliente efetuou a revogação após a autorização do consentimento.    - CONSENT_MAX_DATE_REACHED – consentimento que ultrapassou o tempo limite de compartilhamento.    - CONSENT_TECHNICAL_ISSUE – consentimento que foi rejeitado devido a um problema técnico que impossibilita seu uso pela instituição receptora, por exemplo: falha associada a troca do AuthCode pelo AccessToken, durante o processo de Hybrid Flow.    - INTERNAL_SECURITY_REASON – consentimento que foi rejeitado devido as políticas de segurança aplicada pela instituição transmissora. |
**additional_information** | **string** | Contém informações adicionais a critério da transmissora. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
