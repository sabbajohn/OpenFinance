# # BusinessFinancialRelationData

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**update_date_time** | **\DateTime** | Data e hora da atualização dos campos \\&lt;_endpoint_\\&gt;, conforme especificação RFC-3339, formato UTC. Quando não existente uma data vinculada especificamente ao bloco, assumir a data e hora de atualização do cadastro como um todo. |
**start_date** | **\DateTime** | Data de início de relacionamento com a Instituição Financeira. Deve trazer o menor valor entre a informação reportada ao BACEN pelo DOC 3040 e CCS. |
**products_services_type** | [**\OpenAPI\Client\Model\EnumProductServiceType[]**](EnumProductServiceType.md) |  |
**procurators** | [**\OpenAPI\Client\Model\BusinessProcurator[]**](BusinessProcurator.md) | Lista dos representantes. De preenchimento obrigatório se houver representante. |
**accounts** | [**\OpenAPI\Client\Model\BusinessAccount[]**](BusinessAccount.md) | Lista de contas depósito à vista, poupança e pagamento pré-pagas mantidas pelo cliente na instituição transmissora. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
