# # PersonalInsurancePremiumPayment

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**payment_methods** | [**\OpenAPI\Client\Model\EnumPremiumPaymentMethodTypePersonal[]**](EnumPremiumPaymentMethodTypePersonal.md) |  | [optional]
**payment_method_adittional_info** | **string** | Campo livre para preenchimento das informações adicionais referente ao \&quot;paymentMethod\&quot;.   [Restrição] Obrigatório quando \&quot;paymentMethod\&quot; for igual &#39;OUTROS&#39;. | [optional]
**frequencies** | [**\OpenAPI\Client\Model\EnumPersonalInsurancePremiumPaymentFrequency[]**](EnumPersonalInsurancePremiumPaymentFrequency.md) |  | [optional]
**contribution_tax** | **string** | Distribuição de frequência relativa aos valores referentes às taxas cobradas, nos termos do Anexo III. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
