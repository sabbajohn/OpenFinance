# # ParametrosConsultaCob

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**inicio** | **\DateTime** | Data inicial utilizada na consulta. Respeita RFC 3339. |
**fim** | **\DateTime** | Data de fim utilizada na consulta. Respeita RFC 3339. |
**cpf** | **string** | Filtro pelo CPF do devedor. Não pode ser utilizado ao mesmo tempo que o CNPJ. | [optional]
**cnpj** | **string** | Filtro pelo CNPJ do devedor. Não pode ser utilizado ao mesmo tempo que o CPF. | [optional]
**location_presente** | **bool** | Filtro pela existência de location vinculada. | [optional]
**status** | **string** | Filtro pelo status das cobranças. | [optional]
**paginacao** | [**\OpenAPI\Client\Model\Paginacao**](Paginacao.md) |  |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
