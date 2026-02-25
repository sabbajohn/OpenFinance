# # HistRicoDeTentativasDeCobranAInner

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**data_liquidacao** | **\DateTime** | Data da liquidação da cobrança. Trata-se de uma data, no formato &#x60;YYYY-MM-DD&#x60;, segundo ISO 8601. |
**tipo** | **string** | Tipo da tentativa da cobrança. |
**status** | **string** | Status da tentativa da cobrança. |
**end_to_end_id** | **string** | EndToEndIdentification que transita na PACS002, PACS004 e PACS008 |
**atualizacao** | [**\OpenAPI\Client\Model\HistRicoDeStatusDaTentativaInner[]**](HistRicoDeStatusDaTentativaInner.md) | Histórico das mudanças de status da tentativa de cobrança. |
**rejeicao** | [**\OpenAPI\Client\Model\InformaEsSobreARejeiODaTentativaDaCobranA**](InformaEsSobreARejeiODaTentativaDaCobranA.md) |  | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
