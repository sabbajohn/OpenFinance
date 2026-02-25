# # RequestCreditPortabilityDataContractIdentification

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**contract_id** | **string** | Identifica de forma única o contrato da operação de crédito do cliente, mantendo as regras de imutabilidade dentro da instituição transmissora. |
**contract_number** | **string** | Número do contrato dado pela instituição contratante. |
**ipoc_code** | **string** | Número padronizado do contrato - IPOC (Identificação Padronizada da Operação de Crédito). Segundo DOC 3040, composta por:   CNPJ da instituição: 8 (oito) posições iniciais; Modalidade da operação: 4 (quatro) posições; Tipo do cliente: 1 (uma) posição( 1 &#x3D; pessoa natural - CPF, 2&#x3D; pessoa jurídica  – CNPJ, 3 &#x3D; pessoa física no exterior, 4 &#x3D; pessoa jurídica no exterior, 5 &#x3D; pessoa natural sem CPF e 6 &#x3D; pessoa jurídica sem CNPJ);  - Código do cliente: O número de posições varia conforme o tipo do cliente: Para clientes pessoa física com CPF (tipo de cliente &#x3D; 1), informar as 11 (onze) posições do CPF; Para clientes pessoa jurídica com CNPJ (tipo de cliente &#x3D; 2), informar as 8 (oito) posições iniciais do CNPJ; Para os demais clientes (tipos de cliente 3, 4, 5 e 6), informar 14 (catorze) posições com complemento de zeros à esquerda se a identificação tiver tamanho inferior;  - Código do contrato: 1 (uma) até 40 (quarenta) posições, sem complemento de caracteres. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
