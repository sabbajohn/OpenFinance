# # CreditCardAccountsData

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**credit_card_account_id** | **string** | Identifica de forma única a conta pagamento pós-paga do cliente, mantendo as regras de imutabilidade dentro da instituição transmissora. |
**brand_name** | **string** | Nome da Marca reportada pelo participante no Open Finance. Recomenda-se utilizar, sempre que possível, o mesmo nome de marca atribuído no campo do diretório Customer Friendly Server Name (Authorisation Server). |
**company_cnpj** | **string** | Número completo do CNPJ da instituição responsável pelo Cadastro - o CNPJ corresponde ao número de inscrição no Cadastro de Pessoa Jurídica. Deve-se ter apenas os números do CNPJ, sem máscara |
**name** | **string** | Denominação/Identificação do nome da conta de pagamento pós-paga (cartão). Conforme CIRCULAR Nº 3.680,BCB, 2013: &#39;conta de pagamento pós-paga: destinada à execução de transações de pagamento que independem do aporte prévio de recursos |
**product_type** | [**\OpenAPI\Client\Model\EnumCreditCardAccountsProductType**](EnumCreditCardAccountsProductType.md) |  |
**product_additional_info** | **string** | Informações complementares se tipo de Cartão &#39;OUTROS&#39; | [optional]
**credit_card_network** | [**\OpenAPI\Client\Model\EnumCreditCardAccountNetwork**](EnumCreditCardAccountNetwork.md) |  |
**network_additional_info** | **string** | Texto livre para especificar categoria de bandeira marcada como &#39;OUTRAS&#39; | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
