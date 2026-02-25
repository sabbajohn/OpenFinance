# # PersonalFinancialRelationData

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**update_date_time** | **\DateTime** | Data e hora da atualização dos campos \\&lt;_endpoint_\\&gt;, conforme especificação RFC-3339, formato UTC. Quando não existente uma data vinculada especificamente ao bloco, assumir a data e hora de atualização do cadastro como um todo. |
**start_date** | **\DateTime** | Data de início de relacionamento com a Instituição Financeira. Deve trazer o menor valor entre a informação reportada ao BACEN pelo DOC 3040 e CCS. |
**products_services_type** | [**\OpenAPI\Client\Model\EnumProductServiceType[]**](EnumProductServiceType.md) |  |
**products_services_type_additional_info** | **string** | Informações adicionais do tipo de serviço. [Restrição] Campo obrigatório quando productsServicesType for &#39;OUTROS&#39;. | [optional]
**procurators** | [**\OpenAPI\Client\Model\PersonalProcurator[]**](PersonalProcurator.md) | Lista dos representantes.  [Restrição] De preenchimento obrigatório se houver representante. |
**accounts** | [**\OpenAPI\Client\Model\PersonalAccount[]**](PersonalAccount.md) | Lista de contas depósito à vista, poupança e pagamento pré-pagas mantidas pelo cliente na instituição transmissora. |
**portabilities_received** | [**\OpenAPI\Client\Model\PortabilitiesReceived[]**](PortabilitiesReceived.md) | Lista de informações de empregador recebidos através de portabilidade de salário solicitada pelo cliente da transmissora à instituição detentora(s) de sua conta salário, ativos ou que já estiveram ativos,. Cada vínculo é associado a uma portabilidade de salário recebida pela transmissora.  Obs.: a portabilidade não é explicitamente encerrada, ou seja, a IF para a qual o salário foi portado não é avisado quando a conta salário se encerra ou o salário é portado para outra IF. Não é possível garantir que os dados informados sejam de uma portabilidade ativa, nem que o vínculo com o banco folha ainda exista. A transmissora terá tais informações apenas quando o pedido da portabilidade tiver sido solicitado em seus canais. | [optional]
**paychecks_bank_link** | [**\OpenAPI\Client\Model\PaychecksBankLink[]**](PaychecksBankLink.md) | Lista de informações de contas salário relacionadas com vínculos empregatícios, existentes ou que já existiram, firmados entre o cliente pessoa natural e um ou mais empregadores. Cada vínculo é associado a uma conta salário aberta mantida no banco-folha (instituição transmissora).  Obs: como empregadores antigos podem não ter solicitado o fechamento da conta salário, não é possível garantir que os dados informados sejam do empregador atual. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
