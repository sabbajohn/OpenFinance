# # BusinessPostalAddress

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**is_main** | **bool** | Indica se o endereço informado é o principal |
**address** | **string** | Corresponde ao endereço comercial do cliente. | [optional]
**additional_info** | **string** | Alguns logradouros ainda necessitam ser especificados por meio de complemento | [optional]
**district_name** | **string** | Bairro é uma comunidade ou região localizada em uma cidade ou município de acordo com as suas subdivisões geográficas. Preenchimento obrigatório, se houver. | [optional]
**town_name** | **string** | Localidade: O nome da localidade corresponde à designação da cidade ou município no qual o endereço está localizado. |
**ibge_town_code** | **string** | Código IBGE de Município. A Tabela de Códigos de Municípios do IBGE apresenta a lista dos municípios brasileiros associados a um código composto de 7 dígitos, sendo os dois primeiros referentes ao código da Unidade da Federação. | [optional]
**country_sub_division** | [**\OpenAPI\Client\Model\EnumCountrySubDivision**](EnumCountrySubDivision.md) |  | [optional]
**post_code** | **string** | Código de Endereçamento Postal: Composto por um conjunto numérico de oito dígitos, o objetivo principal do CEP é orientar e acelerar o encaminhamento, o tratamento e a entrega de objetos postados nos Correios, por meio da sua atribuição a localidades, logradouros, unidades dos Correios, serviços, órgãos públicos, empresas e edifícios. p.ex. &#39;01311000&#39; | [optional]
**country** | **string** | Nome do país |
**country_code** | **string** | Código do pais de acordo com o código alpha3 do ISO-3166 |
**geographic_coordinates** | [**\OpenAPI\Client\Model\GeographicCoordinates**](GeographicCoordinates.md) |  | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
