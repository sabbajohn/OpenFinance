# # CobGerada

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**pix_copia_e_cola** | **string** | Este campo retorna o valor do Pix Copia e Cola correspondente à cobrança. Trata-se da sequência de caracteres que representa o BR Code. | [optional]
**chave** | **string** | # Formato do campo chave  * O campo chave determina a chave Pix registrada no DICT que será utilizada para a cobrança. Essa chave será lida pelo aplicativo do PSP do pagador para consulta ao DICT, que retornará a informação que identificará o recebedor da cobrança. * Os tipos de chave podem ser: telefone, e-mail, cpf/cnpj ou EVP. * O formato das chaves pode ser encontrado na seção \&quot;Formatação das chaves do DICT no BR Code\&quot; do [Manual de Padrões para iniciação do Pix](https://www.bcb.gov.br/estabilidadefinanceira/pix). |
**solicitacao_pagador** | **string** | O campo solicitacaoPagador, opcional, determina um texto a ser apresentado ao pagador para que ele possa digitar uma informação correlata, em formato livre, a ser enviada ao recebedor. Esse texto será preenchido, na pacs.008, pelo PSP do pagador, no campo RemittanceInformation &lt;RmtInf&gt;. O tamanho do campo &lt;RmtInf&gt; na pacs.008 está limitado a 140 caracteres. | [optional]
**info_adicionais** | [**\OpenAPI\Client\Model\InformaEsAdicionaisInner[]**](InformaEsAdicionaisInner.md) | Cada respectiva informação adicional contida na lista (nome e valor) deve ser apresentada ao pagador. | [optional]
**calendario** | [**\OpenAPI\Client\Model\CalendRio**](CalendRio.md) |  |
**txid** | **string** | # Identificador da transação  O campo &#x60;txid&#x60; determina o identificador da transação. O objetivo desse campo é ser um elemento que possibilite ao PSP do recebedor apresentar ao usuário recebedor a funcionalidade de conciliação de pagamentos.  Na pacs.008, é referenciado como &#x60;TransactionIdentification &lt;txId&gt;&#x60; ou &#x60;idConciliacaoRecebedor&#x60;.  Em termos de fluxo de funcionamento, o txid é lido pelo aplicativo do PSP do pagador e,  depois de confirmado o pagamento, é enviado para o SPI via pacs.008.  Uma pacs.008 também é enviada ao PSP do recebedor, contendo, além de todas as informações usuais  do pagamento, o txid. Ao perceber um recebimento dotado de txid, o PSP do recebedor está apto a se comunicar com o usuário recebedor,  informando que um pagamento específico foi liquidado.  O txid é criado exclusivamente pelo usuário recebedor e está sob sua responsabilidade. O txid, no contexto de representação de uma cobrança, é único por CPF/CNPJ do usuário recebedor. Cabe ao  PSP recebedor validar essa regra na API Pix. |
**revisao** | **int** | # O campo &#x60;revisao&#x60;  Denota a revisão da cobrança.  Sempre começa em zero. Sempre varia em acréscimos de 1.  O incremento em uma cobrança deve ocorrer sempre que um objeto da cobrança em questão for alterado. O campo &#x60;loc&#x60; é uma exceção a esta regra.  Se em uma determinada alteração em uma cobrança, o único campo alterado for o campo &#x60;loc&#x60;, então esta operação não incrementa a revisão da cobrança.  O campo &#x60;loc&#x60; não ocasiona uma alteração na cobrança em si. Não é necessário armazenar histórico das alterações do campo &#x60;loc&#x60; para uma determinada cobrança. Para os outros campos da cobrança, registra-se histórico. | [readonly]
**devedor** | [**\OpenAPI\Client\Model\CobGeradaAllOfDevedor**](CobGeradaAllOfDevedor.md) |  | [optional]
**loc** | [**\OpenAPI\Client\Model\PayloadLocation**](PayloadLocation.md) |  | [optional]
**location** | **string** | Localização do Payload a ser informada na criação da cobrança. | [optional] [readonly]
**status** | [**\OpenAPI\Client\Model\CobrancaStatus**](CobrancaStatus.md) |  |
**valor** | [**\OpenAPI\Client\Model\CobValor**](CobValor.md) |  |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
