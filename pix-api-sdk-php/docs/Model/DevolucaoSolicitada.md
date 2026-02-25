# # DevolucaoSolicitada

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**valor** | **string** | Valor solicitado para devolução. A soma dos valores de todas as devolucões não podem ultrapassar o valor total do Pix. |
**natureza** | [**\OpenAPI\Client\Model\DevolucaoSolicitadaNatureza**](DevolucaoSolicitadaNatureza.md) |  | [optional]
**descricao** | **string** | O campo &#x60;descricao&#x60;, opcional, determina um texto a ser apresentado ao pagador contendo informações sobre a devolução. Esse texto será preenchido, na pacs.004, pelo PSP do recebedor, no campo RemittanceInformation. O tamanho do campo na pacs.004 está limitado a 140 caracteres. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
