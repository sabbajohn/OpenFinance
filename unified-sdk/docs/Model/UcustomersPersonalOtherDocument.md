# # PersonalOtherDocument

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**type** | [**\OpenAPI\Client\Model\EnumPersonalOtherDocumentType**](EnumPersonalOtherDocumentType.md) |  |
**type_additional_info** | **string** | Campo livre de preenchimento obrigatório se selecionada a opção OUTROS tipos de documentos | [optional]
**number** | **string** | Identificação/Número do documento informado |
**check_digit** | **string** | Dígito verificador do documento informado. De preenchimento obrigatório se o documento informado tiver dígito verificador | [optional]
**additional_info** | **string** | Para documentos em que se aplique o uso do local de emissão o mesmo deve ser enviado mandatoriamente, com a informação de órgão e UF. Exemplo: RG, local de emissão: SSP/RS. [Restrição] Obrigatório quando o Local de Emissão do Documento for relevante. | [optional]
**expiration_date** | **\DateTime** | Data de validade do documento informado, conforme especificação RFC-3339. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
