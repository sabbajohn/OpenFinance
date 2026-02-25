# # EnrollmentFidoRegistrationData

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**id** | **string** | Identificador da credencial. Deve ser o valor em formato base64url do campo rawId da chave pública criada no processo de registro do dispositivo. |
**raw_id** | **string** | Identificador da credencial. Para envio ao detentor, o valor deste atributo deve ser idêntico ao valor do atributo id. |
**response** | [**\OpenAPI\Client\Model\EnrollmentFidoRegistrationDataResponse**](EnrollmentFidoRegistrationDataResponse.md) |  |
**authenticator_attachment** | **string** | Indica a forma de comunicação com o autenticador. | [optional]
**type** | **string** | Tipo da credencial | [optional]
**client_extension_results** | **array<string,mixed>** | Extensões da credencial, específicas por plataforma | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
