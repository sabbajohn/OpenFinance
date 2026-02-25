# # CapitalizationBondsProductCapitalizationPeriod

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**interest_rate** | **string** | Taxa que remunera a parte da mensalidade destinada a formar o Capital, ou seja, a Provisão Matemática de Resgate, também chamada de saldo de capitalização. Em porcentagem ao mês (% a.m.). |
**update_index** | [**\OpenAPI\Client\Model\CapitalizationBondsProductUpdateIndex**](CapitalizationBondsProductUpdateIndex.md) |  | [optional]
**update_index_additional_info** | **string** | Campo livre para preenchimento das informações adicionais referente ao índice utilizado na atualização dos pagamentos mensais.   [Restrição] Obrigatório quando \&quot;updateIndex\&quot; for igual &#39;OUTROS&#39;. | [optional]
**contribution_amount** | [**\OpenAPI\Client\Model\CapitalizationPeriodContributionAmount[]**](CapitalizationPeriodContributionAmount.md) |  |
**early_redemptions** | [**\OpenAPI\Client\Model\CapitalizationBondsProductCapitalizationPeriodEarlyRedemptionsInner[]**](CapitalizationBondsProductCapitalizationPeriodEarlyRedemptionsInner.md) | Informações relativas ao resgate antecipado. |
**redemption_percentage_end_term** | **string** | Percentual mínimo da soma das contribuições efetuadas que poderá ser resgatado ao final da vigência, tendo como condição os pagamentos das parcelas nos respectivos vencimentos. |
**grace_period_redemption** | **float** | Intervalo de tempo mínimo entre contratação e resgate do direito, em meses. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
