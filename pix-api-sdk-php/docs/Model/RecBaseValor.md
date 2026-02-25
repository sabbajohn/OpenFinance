# # RecBaseValor

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**valor_rec** | **string** | Campo opcional, deve ser preenchido apenas quando o valor dos pagamentos for fixo ou não for sujeito a alteração durante a vigência da autorização. | [optional]
**valor_minimo_recebedor** | **string** | Campo opcional. Valor definido pelo usuário recebedor. Se o usuário pagador atribuir um valor máximo para os pagamentos daquela autorização, ele não poderá ser inferior ao piso definido pelo usuário recebedor. Não pode ser preenchido nas autorizações de valor fixo, ou seja, com campo valor preenchido. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
