# # BusinessAccounts

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**type** | [**\OpenAPI\Client\Model\AccountType**](AccountType.md) |  |
**fees** | [**\OpenAPI\Client\Model\BusinessAccountsFees**](BusinessAccountsFees.md) |  |
**service_bundles** | [**\OpenAPI\Client\Model\ServiceBundle[]**](ServiceBundle.md) | Lista dos serviços que compõe o pacote de serviços |
**opening_closing_channels** | [**\OpenAPI\Client\Model\OpeningClosingChannels[]**](OpeningClosingChannels.md) | Lista dos canais para aberturas e encerramento |
**additional_info** | **string** | Texto livre para complementar informação relativa ao Canal disponível, quando no campo &#39;&#39;openingClosingChannels&#39;&#39; estiver preenchida a opção &#39;&#39;Outros&#39;&#39; Restrição: Campo de preenchimento obrigatório se &#39;&#39;openingCloseChannels&#39;&#39; estiver preenchida a opção &#39;&#39;OUTROS&#39;&#39; | [optional]
**transaction_methods** | [**\OpenAPI\Client\Model\TransactionMethods[]**](TransactionMethods.md) | Lista de formas de movimentação |
**terms_conditions** | [**\OpenAPI\Client\Model\AccountsTermsConditions**](AccountsTermsConditions.md) |  |
**income_rate** | [**\OpenAPI\Client\Model\AccountsIncomeRate**](AccountsIncomeRate.md) |  |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
