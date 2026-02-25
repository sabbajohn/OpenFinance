# # ResponseFundsProductIdentificationData

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**name** | **string** | Nome oficial do fundo de investimento conforme exibido para os clientes nos canais eletrônicos. |
**cnpj_number** | **string** | CNPJ do fundo de investimento. |
**isin_code** | **string** | Código universal que identifica cada valor mobiliário ou instrumento financeiro, conforme Norma ISO 6166.  DEFINIÇÃO: O ISIN (International Securities Identification Number) é um código que identifica um valor mobiliário, conforme a norma ISO 6166.  ESTRUTURA: O ISIN é um código alfanumérico que possui 12 caracteres com a seguinte estrutura:   - Um prefixo, composto de 2 caracteres alfa, que identifica o código do país (Norma ISO 3166); - O número básico, composto de 9 caracteres alfabéticos ou numéricos em sua extensão; - Um dígito numérico de controle. | [optional]
**anbima_category** | **string** | Conforme classificação ANBIMA, que segue a deliberação 77 da ANBIMA.  – Renda Fixa  – Ações  – Multimercado  – Cambial  https://www.anbima.com.br/data/files/5A/44/2C/B7/8411B510CD3B4DA568A80AC2/DeliberacaoN77-Diretriz-de-Classificacao-de-Fundos.pdf | [optional]
**anbima_class** | **string** | Campo necessário para aderência a Resolução CVM175. Aguardando definições de mercado. Deve se tratar de campo do tipo enum. | [optional]
**anbima_subclass** | **string** | Campo necessário para aderência a Resolução CVM175. Aguardando definições de mercado. Deve se tratar de campo do tipo enum. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
