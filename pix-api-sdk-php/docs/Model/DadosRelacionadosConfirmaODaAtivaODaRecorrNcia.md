# # DadosRelacionadosConfirmaODaAtivaODaRecorrNcia

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**tipo_jornada** | **string** | Dado relacionado ao caminho percorrido pelo processo de adesão a recorrência pelo usuário pagador, os valores possíveis são:   - JORNADA_1: Usuário pagador aceitou a recorrência através de notificação externa ao ecossistema   - JORNADA_2: Usuário pagador aceitou a recorrência através de leitura de QR Code de recorrência   - JORNADA_3: Usuário pagador iniciou a recorrência através de leitura de QR Code composto e pagamento de cobrança imediata. O uso desta jornada torna obrigatório o preenchimento da informação dadosJornada.txid   - JORNADA_4: Usuário pagador escolheu aderir à recorrência através de leitura de QR Code composto relacionado à cobrança com vencimento ou estática relacionada a um contrato vigente   - AGUARDANDO_DEFINICAO: Valor inicial posterior a criação e anterior a ativação da recorrência. |
**dados_jornada** | [**\OpenAPI\Client\Model\CobranAImediataVinculadaJornada3**](CobranAImediataVinculadaJornada3.md) |  | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
