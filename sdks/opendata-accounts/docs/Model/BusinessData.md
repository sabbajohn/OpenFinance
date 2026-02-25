# # BusinessData

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**participant** | [**\OpenAPI\Client\Model\PersonalDataParticipant**](PersonalDataParticipant.md) |  |
**type** | [**\OpenAPI\Client\Model\AccountType**](AccountType.md) |  |
**fees** | [**\OpenAPI\Client\Model\BusinessDataFees**](BusinessDataFees.md) |  |
**service_bundles** | [**\OpenAPI\Client\Model\ServiceBundle[]**](ServiceBundle.md) | Lista dos pacotes de serviços de contas com serviços essenciais padronizados e regulados pela Resolução BC 3919, de 25/11/2010  [Restrição] - Obrigatório quando \&quot;type\&quot; for igual \&quot;CONTA_DEPOSITO_A_VISTA\&quot; (conta corrente) ou \&quot;CONTA_POUPANCA\&quot;, porque existem hoje pacotes passíveis de cobrança diferentes dos serviços essenciais (que não são cobrados);  - Opcional quando \&quot;type\&quot; for igual \&quot;CONTA_PAGAMENTO_PRE_PAGA\&quot; ficando condicionado caso a instituição tenha pacote de serviço atrelado a este tipo de conta. | [optional]
**opening_closing_channels** | [**\OpenAPI\Client\Model\OpeningClosingChannels[]**](OpeningClosingChannels.md) | Lista dos canais para aberturas e encerramento |
**opening_closing_channels_additional_info** | **string** | Campo livre para preenchimento das informações adicionais referente ao \&quot;openingClosingChannels\&quot;.   [Restrição] Obrigatório quando \&quot;openingClosingChannels\&quot; for igual &#39;OUTROS&#39;. | [optional]
**transaction_methods** | [**\OpenAPI\Client\Model\TransactionMethods[]**](TransactionMethods.md) | Lista de formas de movimentação |
**terms_conditions** | [**\OpenAPI\Client\Model\AccountsTermsConditions**](AccountsTermsConditions.md) |  |
**income_rate** | [**\OpenAPI\Client\Model\AccountsIncomeRate**](AccountsIncomeRate.md) |  | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
