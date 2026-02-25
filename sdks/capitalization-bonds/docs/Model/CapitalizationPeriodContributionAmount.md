# # CapitalizationPeriodContributionAmount

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**periodicity** | **string** | Intervalo de tempo regular previsto entre os sorteios. Na integração entre os sistemas OFB e OPIN, a correspondência do item &#39;PERIODICO&#39; no OPIN está relacionada ao item &#39;OUTROS&#39; no OFB.  Detalhes adicionais sobre esse cenário podem ser descritos no campo &#39;periodicityAdditionalInfo&#39;. | [optional]
**periodicity_additional_info** | **string** | Campo livre para preenchimento das informações adicionais referente ao intervalo de tempo regular previsto entre os sorteios.  [Restrição] Obrigatório quando \&quot;periodicity\&quot; for igual &#39;OUTROS&#39;. | [optional]
**minimum** | **string** | Valor mínimo. Para a modalidade &#39;TRADICIONAL&#39; o valor mínimo correspondente ao pagamento efetuado pelo subscritor à sociedade de capitalização. |
**maximum** | **string** | Valor máximo. Para a modalidade &#39;TRADICIONAL&#39; o valor máximo correspondente ao pagamento efetuado pelo subscritor à sociedade de capitalização. |
**allowed_value** | **float** | Valor permitido. Para a modalidade &#39;TRADICIONAL&#39; o valor permitido correspondente ao pagamento efetuado pelo subscritor à sociedade de capitalização. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
