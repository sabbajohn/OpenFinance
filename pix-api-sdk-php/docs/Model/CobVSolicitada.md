# # CobVSolicitada

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**devedor** | [**\OpenAPI\Client\Model\DadosDevedorDevedor**](DadosDevedorDevedor.md) |  |
**chave** | **string** | # Formato do campo chave  * O campo chave determina a chave Pix registrada no DICT que será utilizada para a cobrança. Essa chave será lida pelo aplicativo do PSP do pagador para consulta ao DICT, que retornará a informação que identificará o recebedor da cobrança. * Os tipos de chave podem ser: telefone, e-mail, cpf/cnpj ou EVP. * O formato das chaves pode ser encontrado na seção \&quot;Formatação das chaves do DICT no BR Code\&quot; do [Manual de Padrões para iniciação do Pix](https://www.bcb.gov.br/estabilidadefinanceira/pix). |
**solicitacao_pagador** | **string** | O campo solicitacaoPagador, opcional, determina um texto a ser apresentado ao pagador para que ele possa digitar uma informação correlata, em formato livre, a ser enviada ao recebedor. Esse texto será preenchido, na pacs.008, pelo PSP do pagador, no campo RemittanceInformation &lt;RmtInf&gt;. O tamanho do campo &lt;RmtInf&gt; na pacs.008 está limitado a 140 caracteres. | [optional]
**info_adicionais** | [**\OpenAPI\Client\Model\InformaEsAdicionaisInner[]**](InformaEsAdicionaisInner.md) | Cada respectiva informação adicional contida na lista (nome e valor) deve ser apresentada ao pagador. | [optional]
**calendario** | [**\OpenAPI\Client\Model\CobDataDeVencimento**](CobDataDeVencimento.md) | Os campos aninhados sob o identificador calendário organizam informações a respeito de controle de tempo da cobrança. |
**loc** | [**\OpenAPI\Client\Model\PayloadLocationCob**](PayloadLocationCob.md) |  | [optional]
**valor** | [**\OpenAPI\Client\Model\CobVValor**](CobVValor.md) |  |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
