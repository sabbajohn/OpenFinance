# # ResponseFundsProductListData

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**brand_name** | **string** | Nome da Marca reportada pelo participante no Open Finance. Recomenda-se utilizar, sempre que possível, o mesmo nome de marca atribuído no campo do diretório Customer Friendly Server Name (Authorisation Server). |
**company_cnpj** | **string** | Número completo do CNPJ da instituição responsável pelo Cadastro - o CNPJ corresponde ao número de inscrição no Cadastro de Pessoa Jurídica. Deve-se ter apenas os números do CNPJ, sem máscara. |
**anbima_category** | **string** | Conforme classificação ANBIMA, que segue a deliberação 77 da ANBIMA.  – Renda Fixa  – Ações  – Multimercado  – Cambial  https://www.anbima.com.br/data/files/5A/44/2C/B7/8411B510CD3B4DA568A80AC2/DeliberacaoN77-Diretriz-de-Classificacao-de-Fundos.pdf | [optional]
**anbima_class** | **string** | Campo necessário para aderência a Resolução CVM175. Aguardando definições de mercado. Deve se tratar de campo do tipo enum. | [optional]
**anbima_subclass** | **string** | Campo necessário para aderência a Resolução CVM175. Aguardando definições de mercado. Deve se tratar de campo do tipo enum. | [optional]
**investment_id** | **string** | Identifica de forma única o relacionamento do cliente com o produto, mantendo as regras de imutabilidade dentro da instituição transmissora. Nos casos em que o cliente, após completar 12 meses da última movimentação e com quantidade de ativos zerada (cliente não tem mais posse do produto sob custódia da transmissora), compre novamente o ativo que já investiu em períodos passados, manter o mesmo investmentId anteriormente utilizado. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
