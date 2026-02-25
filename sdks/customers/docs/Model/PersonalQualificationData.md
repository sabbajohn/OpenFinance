# # PersonalQualificationData

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**update_date_time** | **\DateTime** | Data e hora da atualização dos campos \\&lt;_endpoint_\\&gt;, conforme especificação RFC-3339, formato UTC. Quando não existente uma data vinculada especificamente ao bloco, assumir a data e hora de atualização do cadastro como um todo. |
**company_cnpj** | **string** | Número completo do CNPJ da instituição responsável pelo Cadastro - o CNPJ corresponde ao número de inscrição no Cadastro de Pessoa Jurídica.  Deve-se ter apenas os números do CNPJ, sem máscara |
**occupation_code** | [**\OpenAPI\Client\Model\EnumOccupationMainCodeType**](EnumOccupationMainCodeType.md) |  | [optional]
**occupation_description** | **string** | Campo livre, de preenchimento obrigatório. Se selecionada a opção *occupationCode* \&quot;RECEITA_FEDERAL\&quot; ou \&quot;CBO\&quot;, informar o código desta lista padronizada.    Se selecionada *occupationCode* \&quot;OUTRO\&quot;, informar o descritivo da ocupação quando a IF não segue a lista padronizada da Receita Federal e nem da CBO. | [optional]
**informed_income** | [**\OpenAPI\Client\Model\InformedIncome**](InformedIncome.md) |  | [optional]
**informed_patrimony** | [**\OpenAPI\Client\Model\PersonalInformedPatrimony**](PersonalInformedPatrimony.md) |  | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
