# # CobRBase

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**info_adicional** | **string** | Informações adicionais da fatura. | [optional]
**calendario** | [**\OpenAPI\Client\Model\InformaEsSobreCalendRioDaCobranA**](InformaEsSobreCalendRioDaCobranA.md) |  | [optional]
**valor** | [**\OpenAPI\Client\Model\ValorDaCobranARecorrente**](ValorDaCobranARecorrente.md) |  | [optional]
**ajuste_dia_util** | **bool** | Campo de ativação do ajuste da data prevista para liquidação para próximo dia útil caso o vencimento corrente seja um dia não útil. O PSP Recebedor deverá considerar os feriados locais com base no código município do usuário pagador. | [default to true]
**recebedor** | [**\OpenAPI\Client\Model\DadosBancariosRecebedor**](DadosBancariosRecebedor.md) | O objeto recebedor organiza as informações sobre o recebedor da cobrança. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
