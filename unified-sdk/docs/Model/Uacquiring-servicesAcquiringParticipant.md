# # AcquiringParticipant

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**brand** | **string** | Nome da marca reportada pelo participante do Open Finance. O conceito a que se refere a &#39;marca&#39; é em essência uma promessa da empresa em fornecer uma série específica de atributos, benefícios e serviços uniformes aos clientes. |
**name** | **string** | Nome do participante do Open Finance. |
**cnpj_number** | **string** | O CNPJ corresponde ao número de inscrição no Cadastro de Pessoa Jurídica. Deve-se ter apenas os números do CNPJ, sem máscara. |
**url_complementary_list** | **string** | Espera-se que valor de retorno, após acesso ao link ‘urlComplementaryList’, deve ser array de objeto com a estrutura abaixo:  - ‘name’ com o valor contido no campo ‘LegalEntityName’ conforme cadastro no diretório;  - &#39;cnpjNumber&#39; com o valor contido no campo CNPJ (‘RegistrationNumber’) correspondente a esta instituição;  - Ambos do tipo string;  - Ambos obrigatórios. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
