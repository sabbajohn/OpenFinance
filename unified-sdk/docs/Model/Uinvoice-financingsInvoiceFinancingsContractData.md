# # InvoiceFinancingsContractData

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**contract_id** | **string** | Identifica de forma única o contrato da operação de crédito do cliente, mantendo as regras de imutabilidade dentro da instituição transmissora. |
**brand_name** | **string** | Nome da Marca reportada pelo participante no Open Finance. Recomenda-se utilizar, sempre que possível, o mesmo nome de marca atribuído no campo do diretório Customer Friendly Server Name (Authorisation Server). |
**company_cnpj** | **string** | Registro completo do CNPJ da instituição responsável pelo Cadastro - o CNPJ corresponde a representação alfanumérica da inscrição no Cadastro de Pessoa Jurídica. Deve-se ter apenas os caracteres do CNPJ, sem máscara. |
**product_type** | [**\OpenAPI\Client\Model\EnumContractProductTypeInvoiceFinancings**](EnumContractProductTypeInvoiceFinancings.md) |  |
**product_sub_type** | [**\OpenAPI\Client\Model\EnumContractProductSubTypeInvoiceFinancings**](EnumContractProductSubTypeInvoiceFinancings.md) |  |
**ipoc_code** | **string** | Número padronizado do contrato - IPOC (Identificação Padronizada da Operação de Crédito). Segundo DOC 3040, composta por: - **CNPJ da instituição:** 8 (oito) posições iniciais; - **Modalidade da operação:** 4 (quatro) posições; - **Tipo do cliente:** 1 (uma) posição( 1 &#x3D; pessoa natural - CPF, 2&#x3D; pessoa jurídica – CNPJ, 3 &#x3D; pessoa física no exterior, 4 &#x3D; pessoa jurídica no exterior, 5 &#x3D; pessoa natural sem CPF e 6 &#x3D; pessoa jurídica sem CNPJ); - **Código do cliente:** O número de posições varia conforme o tipo do cliente:   1. Para clientes pessoa física com CPF (tipo de cliente &#x3D; 1), informar as 11 (onze) posições do CPF;   2. Para clientes pessoa jurídica com CNPJ (tipo de cliente &#x3D; 2), informar as 8 (oito) posições iniciais do CNPJ;   3. Para os demais clientes (tipos de cliente 3, 4, 5 e 6), informar 14 (catorze) posições com complemento de zeros à esquerda se a identificação tiver tamanho inferior; - **Código do contrato:** 1 (uma) até 40 (quarenta) posições, sem complemento de caracteres. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
