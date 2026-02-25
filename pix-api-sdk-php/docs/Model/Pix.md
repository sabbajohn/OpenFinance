# # Pix

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**end_to_end_id** | **string** | EndToEndIdentification que transita na PACS002, PACS004 e PACS008 |
**txid** | **string** | # Identificador da transação  O campo &#x60;txid&#x60; determina o identificador da transação. O objetivo desse campo é ser um elemento que possibilite ao PSP do recebedor apresentar ao usuário recebedor a funcionalidade de conciliação de pagamentos.  Na pacs.008, é referenciado como &#x60;TransactionIdentification &lt;txId&gt;&#x60; ou &#x60;idConciliacaoRecebedor&#x60;.  Em termos de fluxo de funcionamento, o txid é lido pelo aplicativo do PSP do pagador e,  depois de confirmado o pagamento, é enviado para o SPI via pacs.008.  Uma pacs.008 também é enviada ao PSP do recebedor, contendo, além de todas as informações usuais  do pagamento, o txid. Ao perceber um recebimento dotado de txid, o PSP do recebedor está apto a se comunicar com o usuário recebedor,  informando que um pagamento específico foi liquidado.  O txid é criado exclusivamente pelo usuário recebedor e está sob sua responsabilidade. O txid, no contexto de representação de uma cobrança, é único por CPF/CNPJ do usuário recebedor. Cabe ao  PSP recebedor validar essa regra na API Pix. | [optional]
**valor** | **string** | Valor do Pix. |
**componentes_valor** | [**\OpenAPI\Client\Model\InformaEsSobreOValorDoPix**](InformaEsSobreOValorDoPix.md) |  | [optional]
**chave** | **string** | # Formato do campo chave  * Campo chave do recebedor conforme atribuído na respectiva PACS008. * Os tipos de chave podem ser: telefone, e-mail, cpf/cnpj ou EVP. * O formato das chaves pode ser encontrado na seção \&quot;Formatação das chaves do DICT no BR Code\&quot; do [Manual de Padrões para iniciação do Pix](https://www.bcb.gov.br/estabilidadefinanceira/pix). | [optional]
**horario** | **\DateTime** | Horário em que o Pix foi processado no PSP. |
**info_pagador** | **string** |  | [optional]
**devolucoes** | [**\OpenAPI\Client\Model\Devolucao[]**](Devolucao.md) |  | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
