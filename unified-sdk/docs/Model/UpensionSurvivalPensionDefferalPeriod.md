# # SurvivalPensionDefferalPeriod

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**interest_rate** | **string** | Taxa de juros mensal garantida que remunera o plano durante a fase de diferimento/acumulação. |
**update_index** | [**\OpenAPI\Client\Model\UpdateIndex**](UpdateIndex.md) |  | [optional]
**other_minimum_performance_garantees** | **string** | Para produtos do tipo PDR e VDR, indicação do índice de ampla divulgação utilizados como garantia mínima de desempenho. |
**other_min_guarantees_percentage** | **string** | Para produtos do tipo PDR e VDR, indicação do percentual do índice de ampla divulgação utilizados como garantia mínima de desempenho. Exemplo 1.000000 igual a 100 por cento. | [optional]
**reversal_financial_results** | **string** | Percentual de reversão de excedente financeiro na concessão. Em %. |
**minimum_premiums** | [**\OpenAPI\Client\Model\SurvivalPensionMinimumPremium[]**](SurvivalPensionMinimumPremium.md) | Valor mínimo do prêmio/contribuição aceita pela sociedade ao plano (identificar valor mensal e/ou aporte único) | [optional]
**premium_payment_methods** | **string[]** | Meio de pagamento escolhido pelo segurado | [optional]
**premium_payment_methods_additional_info** | **string** | Campo livre para preenchimento das informações adicionais referente a descrição do meio de pagamento.  [Restrição] Obrigatório quando \&quot;\&quot;premiumPaymentMethods\&quot;\&quot; for igual &#39;OUTROS&#39; | [optional]
**permission_extraordinary_contributions** | **bool** | Se ficam permitidos aportes extraordinários. A considerar os seguintes domínios: 1. true 2. false | [optional]
**permission_scheduled_financial_payments** | **bool** | Se ficam permitidos pagamentos financeiros programados. A considerar os seguintes domínios: 1. true 2. false | [optional]
**grace_period** | [**\OpenAPI\Client\Model\SurvivalPensionGracePeriod**](SurvivalPensionGracePeriod.md) |  | [optional]
**redemption_payment_term** | **int** | Prazo em dias para pagamento do resgate |
**portability_payment_term** | **int** | Prazo em dias para pagamento da portabilidade (entre empresas diferentes). |
**investment_funds** | [**\OpenAPI\Client\Model\SurvivalPensionInvestmentFund[]**](SurvivalPensionInvestmentFund.md) | Lista com as informações do(s) Fundo(s) de Investimento(s) disponíveis para o período de diferimento/acumulação ou de concessão | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
