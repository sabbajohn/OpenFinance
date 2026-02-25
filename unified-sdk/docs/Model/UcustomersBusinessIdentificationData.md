# # BusinessIdentificationData

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**update_date_time** | **\DateTime** | Data e hora da atualização dos campos \\&lt;_endpoint_\\&gt;, conforme especificação RFC-3339, formato UTC. Quando não existente uma data vinculada especificamente ao bloco, assumir a data e hora de atualização do cadastro como um todo. |
**business_id** | **string** | Um identificador único e imutável usado para identificar o recurso cliente pessoa jurídica. Este identificador não tem significado para o cliente que deu o consentimento |
**brand_name** | **string** | Nome da Marca reportada pelo participante no Open Finance. Recomenda-se utilizar, sempre que possível, o mesmo nome de marca atribuído no campo do diretório Customer Friendly Server Name (Authorisation Server). |
**company_name** | **string** | Razão social da empresa consultada é o termo registrado sob o qual uma pessoa jurídica (PJ) se individualiza e exerce suas atividades. Também pode ser chamada por denominação social ou firma empresarial |
**trade_name** | **string** | Nome fantasia da pessoa jurídica, se houver. (É o nome popular da empresa, utilizado para divulgação da empresa e melhor fixação com o público). De preenchimento obrigatório se houver | [optional]
**incorporation_date** | **\DateTime** | Data de constituição da empresa, conforme especificação RFC-3339. |
**cnpj_number** | **string** | Número completo do CNPJ da Empresa consultada  - o CNPJ corresponde ao número de inscrição no Cadastro de Pessoa Jurídica. Deve-se ter apenas os números do CNPJ, sem máscara |
**companies_cnpj** | **string[]** | Número completo do CNPJ da instituição responsável pelo Cadastro - o CNPJ corresponde ao número de inscrição no Cadastro de Pessoa Jurídica.  Deve-se ter apenas os números do CNPJ, sem máscara |
**other_documents** | [**\OpenAPI\Client\Model\BusinessOtherDocument[]**](BusinessOtherDocument.md) | Relação dos demais documentos | [optional]
**parties** | [**\OpenAPI\Client\Model\PartiesParticipation[]**](PartiesParticipation.md) | Lista relativa às informações das partes envolvidas, como: sócio e/ou administrador. Objeto de envio obrigatório para todos os CNPJs que possuam sócios e/ou administradores no cadastro do QSA (Quadro de Sócios e Administradores) |
**contacts** | [**\OpenAPI\Client\Model\BusinessContacts**](BusinessContacts.md) |  |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
