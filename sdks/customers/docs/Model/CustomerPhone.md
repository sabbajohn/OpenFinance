# # CustomerPhone

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**is_main** | **bool** | Indica se o telefone informado é o principal |
**type** | [**\OpenAPI\Client\Model\EnumCustomerPhoneType**](EnumCustomerPhoneType.md) |  |
**additional_info** | **string** | Informação complementar relativa ao tipo de telefone selecionado. [Restrição] De preenchimento obrigatório quando selecionado o tipo &#39;OUTRO&#39;. | [optional]
**country_calling_code** | **string** | Número de DDI (Discagem Direta Internacional) para telefone de acesso ao Cliente - se houver  [Restrição] O preenchimento é obrigatório quando for diferente de 55. | [optional]
**area_code** | **string** | Número de DDD (Discagem Direta à Distância) do telefone do cliente - se houver |
**number** | **string** | Número de telefone do cliente |
**phone_extension** | **string** | Número do ramal. De preenchimento obrigatório se fizer parte da identificação do número do telefone informado | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
