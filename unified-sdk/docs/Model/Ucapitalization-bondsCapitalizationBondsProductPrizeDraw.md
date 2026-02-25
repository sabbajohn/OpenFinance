# # CapitalizationBondsProductPrizeDraw

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**time_interval** | **string** | Período de tempo regular estabelecido entre as ocorrências dos sorteios. | [optional]
**time_interval_additional_info** | **string** | Campo livre para preenchimento das informações adicionais referente ao intervalo de tempo regular previsto entre os sorteios.  [Restrição] Obrigatório quando \&quot;timeInterval\&quot; for igual &#39;OUTROS&#39;. | [optional]
**quantity** | **int** | Número da quantidade de sorteios previstos ao longo da vigência. |
**prize_multiplier** | **float** | Valor dos sorteios representado por múltiplo do valor de contribuição. Por exemplo: 5 vezes valor da contribuição |
**early_settlement_raffle** | **bool** | Modelo de sorteio que acarreta, ao título contemplado, o seu resgate total obrigatório (Resolução Normativa 384/20). | [optional]
**mandatory_contemplation** | **bool** | Indicador da possibilidade de realização de sorteio com previsão de que o título sorteado seja obrigatoriamente um título comercializado, desde que atingidos os requisitos definidos nas condições gerais do plano. | [optional]
**rule_description** | **string** | Campo aberto para complementar a regra dos sorteios do produto, a ser feita para cada participante. | [optional]
**minimum_contemplation_probability** | **string** | Percentual da probabilidade mínima de contemplação nos sorteios. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
