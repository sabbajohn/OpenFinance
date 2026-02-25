# # BankingAgentPostalAddress

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**address** | **string** | Deverá trazer toda a informação referente ao endereço da dependência informada: Tipo de logradouro + Nome do logradouro + Número do Logradouro (se não existir usar &#39; s/n&#39;) + complemento (se houver), como, p.ex.: &#39;R Diamatina, 59, bloco 35, fundos&#39; &#39;Praça da Boa Vontade s/n&#39; |
**additional_info** | **string** | Alguns logradouros ainda necessitam ser especificados por meio de complemento | [optional]
**district_name** | **string** | Bairro é uma comunidade ou região localizada em uma cidade ou município de acordo com as suas subdivisões geográficas. p.ex: &#39;Paraíso&#39; |
**town_name** | **string** | Localidade: O nome da localidade corresponde à designação da cidade ou município no qual o endereço está localizado. p.ex. &#39;São Paulo&#39; |
**ibge_code** | **string** | Código IBGE do município | [optional]
**country_sub_division** | **string** | Enumeração referente a cada sigla da unidade da federação que identifica o estado ou o distrito federal, no qual o endereço está localizado. p.ex. &#39;AC&#39;. São consideradas apenas as siglas para os estados brasileiros |
**post_code** | **string** | Código de Endereçamento Postal |
**country** | **string** | Nome do país | [optional]
**country_code** | **string** | Código do país | [optional]
**geographic_coordinates** | [**\OpenAPI\Client\Model\GeographicCoordinates**](GeographicCoordinates.md) |  | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
