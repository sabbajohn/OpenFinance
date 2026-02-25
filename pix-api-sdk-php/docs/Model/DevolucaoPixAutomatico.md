# # DevolucaoPixAutomatico

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**id** | **string** | Id gerado pelo cliente para representar unicamente uma devolução. |
**rtr_id** | **string** | ReturnIdentification que transita na PACS004. |
**valor** | **string** | Valor a devolver. |
**natureza** | [**\OpenAPI\Client\Model\DevolucaoNaturezaPixAutomatico**](DevolucaoNaturezaPixAutomatico.md) |  | [optional]
**descricao** | **string** | O campo &#x60;descricao&#x60;, opcional, determina um texto a ser apresentado ao pagador contendo informações sobre a devolução. Esse texto será preenchido, na pacs.004, pelo PSP do recebedor, no campo RemittanceInformation. O tamanho do campo na pacs.004 está limitado a 140 caracteres. | [optional]
**horario** | [**\OpenAPI\Client\Model\DevolucaoHorario**](DevolucaoHorario.md) |  |
**status** | **string** | Status da devolução. |
**motivo** | **string** | # Status da Devolução  Campo opcional que pode ser utilizado pelo PSP recebedor para detalhar os motivos de a devolução ter atingido o status em questão. Pode ser utilizado, por exemplo, para detalhar o motivo de a devolução não ter sido realizada. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
