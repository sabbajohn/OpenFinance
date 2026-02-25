# # RecCompleta

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**vinculo** | [**\OpenAPI\Client\Model\DescriODoObjetoDaRecorrNcia**](DescriODoObjetoDaRecorrNcia.md) |  |
**calendario** | [**\OpenAPI\Client\Model\InformaEsSobreCalendRioDaRecorrNcia1**](InformaEsSobreCalendRioDaRecorrNcia1.md) |  |
**valor** | [**\OpenAPI\Client\Model\RecBaseValor**](RecBaseValor.md) |  | [optional]
**pagador** | [**\OpenAPI\Client\Model\DadosPagadorRecPagador**](DadosPagadorRecPagador.md) |  | [optional]
**status** | **string** |  |
**politica_retentativa** | **string** |  |
**atualizacao** | [**\OpenAPI\Client\Model\HistRicoDeStatusInner[]**](HistRicoDeStatusInner.md) | Histórico das mudanças de status da recorrência. |
**encerramento** | [**\OpenAPI\Client\Model\DetalhamentoDoEncerramentoDaRecorrNcia**](DetalhamentoDoEncerramentoDaRecorrNcia.md) |  | [optional]
**ativacao** | [**\OpenAPI\Client\Model\DadosRelacionadosConfirmaODaAtivaODaRecorrNcia**](DadosRelacionadosConfirmaODaAtivaODaRecorrNcia.md) |  | [optional]
**id_rec** | **string** | # Identificador da Recorrência  Regra de formação: - RAxxxxxxxxyyyyMMddkkkkkkkkkkk (29 caracteres; \&quot;case sensitive\&quot;, isso é, diferencia letras maiúsculas e minúsculas), sendo:   - \&quot;R\&quot;:  fixo (1 caractere). \&quot;R\&quot; para a recorrência criada dentro do Pix;   - \&quot;A\&quot;: identificação da possibilidade de novas tentativas, sendo possíveis os valores \&quot;R\&quot; ou \&quot;N\&quot; (1 caractere). \&quot;R\&quot; caso a recorrência permita novas tentativas de pagamento pós vencimento, ou \&quot;N\&quot; caso não permita novas tentativas.   - \&quot;xxxxxxxx\&quot;:  identificação do agente que presta serviço para o usuário recebedor que gerou o ID Recorrência, podendo ser: o ISPB do participante direto, o ISPB do participante indireto ou os 8 primeiros caracteres do CNPJ do prestador de serviço de iniciação (8 caracteres alfanuméricos [A-Z|0-9]);   - \&quot;yyyyMMdd\&quot;:  data (8 caracteres) de criação da recorrência;   - \&quot;kkkkkkkkkkk\&quot;: sequencial criado pelo agente que gerou o ID Recorrência (11 caracteres alfanuméricos [a-z|A-Z|0-9]). Deve ser único dentro de cada \&quot;yyyyMMdd\&quot;.  Dessa forma, o ID da recorrência deve ser formado de acordo com um dos tipos a seguir: - \&quot;RRxxxxxxxxyyyyMMddkkkkkkkkkkk\&quot;; para recorrência criada dentro do Pix e que permite novas tentativas de pagamento pós vencimento; ou - \&quot;RNxxxxxxxxyyyyMMddkkkkkkkkkkk\&quot;; para recorrência criada dentro do Pix e que não permite novas tentativas de pagamento pós vencimento.” |
**recebedor** | [**\OpenAPI\Client\Model\RecCompletaAllOfRecebedor**](RecCompletaAllOfRecebedor.md) |  |
**loc** | [**\OpenAPI\Client\Model\PayloadLocationRecCompleta**](PayloadLocationRecCompleta.md) |  | [optional]
**solicitacao** | [**\OpenAPI\Client\Model\RecCompletaAllOfSolicitacao[]**](RecCompletaAllOfSolicitacao.md) | Solicitações vinculadas | [optional]
**dados_qr** | [**\OpenAPI\Client\Model\InformaEsDoQRComposto**](InformaEsDoQRComposto.md) |  | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
