# # SurvivalPensionGrantPeriodBenefit

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**income_modalities** | **string[]** | Modalidades de renda disponíveis para contratação. A considerar os seguintes domínios |
**biometric_table** | **string[]** | Obrigatório caso modalidade de renda seja diferente de (PAGAMENTO_UNICO, RENDA_PRAZO_CERTO). Tábua biométrica é o instrumento que mede a duração da vida humana (também conhecida como tábua de mortalidade) ou a probabilidade de entrada em invalidez e é um parâmetro utilizado para tarifar os planos de previdência complementar aberta. | [optional]
**interest_rate** | **string** | Taxa de juros garantida utilizada para conversão em renda. Em % |
**update_index** | [**\OpenAPI\Client\Model\UpdateIndex**](UpdateIndex.md) |  | [optional]
**reversal_financial_results** | **string** | Percentual de reversão de excedente financeiro na concessão. Em %. |
**investment_funds** | [**\OpenAPI\Client\Model\SurvivalPensionInvestmentFund[]**](SurvivalPensionInvestmentFund.md) | Lista com as informações do(s) Fundo(s) de Investimento(s) disponíveis para o período de diferimento/acumulação ou de concessão. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
