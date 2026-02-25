# # CobValor

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**original** | **string** | Valor original da cobrança. | [optional]
**modalidade_alteracao** | **int** | Trata-se de um campo que determina se o valor final do documento pode ser alterado pelo pagador. Na ausência desse campo, assume-se que não se pode alterar o valor do documento de cobrança, ou seja, assume-se o valor 0. Se o campo estiver presente e com valor 1, então está determinado que o valor final da cobrança pode ter seu valor alterado pelo pagador. | [optional]
**retirada** | [**\OpenAPI\Client\Model\InformaEsDeRetirada**](InformaEsDeRetirada.md) |  | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
