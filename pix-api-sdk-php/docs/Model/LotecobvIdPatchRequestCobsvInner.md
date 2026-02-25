# # LotecobvIdPatchRequestCobsvInner

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**calendario** | [**\OpenAPI\Client\Model\CobDataDeVencimento**](CobDataDeVencimento.md) | Os campos aninhados sob o identificador calendário organizam informações a respeito de controle de tempo da cobrança. | [optional]
**devedor** | [**\OpenAPI\Client\Model\DadosDevedorDevedor**](DadosDevedorDevedor.md) |  | [optional]
**loc** | [**\OpenAPI\Client\Model\PayloadLocationCob**](PayloadLocationCob.md) |  | [optional]
**status** | **string** |  | [optional]
**valor** | [**\OpenAPI\Client\Model\CobVValor**](CobVValor.md) |  | [optional]
**chave** | **string** | # Formato do campo chave  * O campo chave determina a chave Pix registrada no DICT que será utilizada para a cobrança. Essa chave será lida pelo aplicativo do PSP do pagador para consulta ao DICT, que retornará a informação que identificará o recebedor da cobrança. * Os tipos de chave podem ser: telefone, e-mail, cpf/cnpj ou EVP. * O formato das chaves pode ser encontrado na seção \&quot;Formatação das chaves do DICT no BR Code\&quot; do [Manual de Padrões para iniciação do Pix](https://www.bcb.gov.br/estabilidadefinanceira/pix). | [optional]
**solicitacao_pagador** | **string** | O campo solicitacaoPagador, opcional, determina um texto a ser apresentado ao pagador para que ele possa digitar uma informação correlata, em formato livre, a ser enviada ao recebedor. Esse texto será preenchido, na pacs.008, pelo PSP do pagador, no campo RemittanceInformation &lt;RmtInf&gt;. O tamanho do campo &lt;RmtInf&gt; na pacs.008 está limitado a 140 caracteres. | [optional]
**info_adicionais** | [**\OpenAPI\Client\Model\InformaEsAdicionaisInner[]**](InformaEsAdicionaisInner.md) | Cada respectiva informação adicional contida na lista (nome e valor) deve ser apresentada ao pagador. | [optional]
**txid** | **string** | # Identificador da transação  O campo &#x60;txid&#x60; determina o identificador da transação. O objetivo desse campo é ser um elemento que possibilite ao PSP do recebedor apresentar ao usuário recebedor a funcionalidade de conciliação de pagamentos.  Na pacs.008, é referenciado como &#x60;TransactionIdentification &lt;txId&gt;&#x60; ou &#x60;idConciliacaoRecebedor&#x60;.  Em termos de fluxo de funcionamento, o txid é lido pelo aplicativo do PSP do pagador e,  depois de confirmado o pagamento, é enviado para o SPI via pacs.008.  Uma pacs.008 também é enviada ao PSP do recebedor, contendo, além de todas as informações usuais  do pagamento, o txid. Ao perceber um recebimento dotado de txid, o PSP do recebedor está apto a se comunicar com o usuário recebedor,  informando que um pagamento específico foi liquidado.  O txid é criado exclusivamente pelo usuário recebedor e está sob sua responsabilidade. O txid, no contexto de representação de uma cobrança, é único por CPF/CNPJ do usuário recebedor. Cabe ao  PSP recebedor validar essa regra na API Pix. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
