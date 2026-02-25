# # ParametrosConsultaPix

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**inicio** | **\DateTime** | Data inicial utilizada na consulta. Respeita RFC 3339. |
**fim** | **\DateTime** | Data de fim utilizada na consulta. Respeita RFC 3339. |
**txid** | **string** | # Identificador da transação  O campo &#x60;txid&#x60; determina o identificador da transação. O objetivo desse campo é ser um elemento que possibilite ao PSP do recebedor apresentar ao usuário recebedor a funcionalidade de conciliação de pagamentos.  Na pacs.008, é referenciado como &#x60;TransactionIdentification &lt;txId&gt;&#x60; ou &#x60;idConciliacaoRecebedor&#x60;.  Em termos de fluxo de funcionamento, o txid é lido pelo aplicativo do PSP do pagador e,  depois de confirmado o pagamento, é enviado para o SPI via pacs.008.  Uma pacs.008 também é enviada ao PSP do recebedor, contendo, além de todas as informações usuais  do pagamento, o txid. Ao perceber um recebimento dotado de txid, o PSP do recebedor está apto a se comunicar com o usuário recebedor,  informando que um pagamento específico foi liquidado.  O txid é criado exclusivamente pelo usuário recebedor e está sob sua responsabilidade. O txid, no contexto de representação de uma cobrança, é único por CPF/CNPJ do usuário recebedor. Cabe ao  PSP recebedor validar essa regra na API Pix. | [optional]
**tx_id_presente** | **bool** | Filtro pela existência de txid. | [optional]
**devolucao_presente** | **bool** | Filtro pela existência de devolução. | [optional]
**cpf** | **string** | CPF | [optional]
**cnpj** | **string** | CNPJ | [optional]
**paginacao** | [**\OpenAPI\Client\Model\Paginacao**](Paginacao.md) |  |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
