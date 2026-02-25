# # PhoneChannelIdentification

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**type** | **string** | Tipo de canal telefônico de atendimento:  * &#x60;CENTRAL_TELEFONICA&#x60;  * &#x60;SAC&#x60;  * &#x60;OUVIDORIA&#x60;  * &#x60;OUTROS&#x60; |
**additional_info** | **string** | Campo de texto livre para descrever informações complementateres sobre canais telefônicos. De preenchimento obrigatório quando o tipo de canal de atendimento telefônico selecionado for \&quot;OUTROS\&quot; | [optional]
**phones** | [**\OpenAPI\Client\Model\PhoneChannelPhone[]**](PhoneChannelPhone.md) | Lista de telefones do Canal de Atendimento | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
