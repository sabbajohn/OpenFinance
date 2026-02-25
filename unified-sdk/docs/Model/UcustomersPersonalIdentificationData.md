# # PersonalIdentificationData

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**update_date_time** | **\DateTime** | Data e hora da atualização dos campos \\&lt;_endpoint_\\&gt;, conforme especificação RFC-3339, formato UTC. Quando não existente uma data vinculada especificamente ao bloco, assumir a data e hora de atualização do cadastro como um todo. |
**personal_id** | **string** | Um identificador único e imutável usado para identificar o recurso cliente pessoa natural. Este identificador não tem significado para o cliente que deu o consentimento |
**brand_name** | **string** | Nome da Marca reportada pelo participante no Open Finance. Recomenda-se utilizar, sempre que possível, o mesmo nome de marca atribuído no campo do diretório Customer Friendly Server Name (Authorisation Server). |
**civil_name** | **string** | Nome civil completo da pessoa natural (Direito fundamental da pessoa, o nome civil é aquele atribuído à pessoa natural desde o registro de seu nascimento, com o qual será identificada por toda a sua vida, bem como após a sua morte) |
**social_name** | **string** | Nome social da pessoa natural, se houver. (aquele pelo qual travestis e transexuais se reconhecem, bem como são identificados por sua comunidade e em seu meio social, conforme Decreto Local) | [optional]
**birth_date** | **\DateTime** | Data de nascimento, conforme especificação RFC-3339 |
**marital_status_code** | [**\OpenAPI\Client\Model\EnumMaritalStatusCode**](EnumMaritalStatusCode.md) |  | [optional]
**marital_status_additional_info** | **string** | Campo livre para complementar a informação relativa ao estado marital.  [Restrição] Preenchimento obrigatório quando selecionado o tipo &#39;OUTRO&#39;. | [optional]
**sex** | [**\OpenAPI\Client\Model\EnumSex**](EnumSex.md) |  | [optional]
**companies_cnpj** | **string[]** | Número completo do CNPJ da instituição responsável pelo Cadastro - o CNPJ corresponde ao número de inscrição no Cadastro de Pessoa Jurídica.  Deve-se ter apenas os números do CNPJ, sem máscara |
**documents** | [**\OpenAPI\Client\Model\PersonalDocument**](PersonalDocument.md) |  |
**other_documents** | [**\OpenAPI\Client\Model\PersonalOtherDocument[]**](PersonalOtherDocument.md) | Relação dos demais documentos | [optional]
**has_brazilian_nationality** | **bool** | Informa se o Cliente tem nacionalidade brasileira. |
**nationality** | [**\OpenAPI\Client\Model\Nationality[]**](Nationality.md) |  | [optional]
**filiation** | [**\OpenAPI\Client\Model\PersonalIdentificationDataFiliationInner[]**](PersonalIdentificationDataFiliationInner.md) |  | [optional]
**contacts** | [**\OpenAPI\Client\Model\PersonalContacts**](PersonalContacts.md) |  |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
