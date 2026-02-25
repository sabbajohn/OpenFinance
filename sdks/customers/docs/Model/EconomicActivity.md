# # EconomicActivity

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**code** | **string** | Traz o código do ramo da atividade principal da empresa consultada, segundo padrão CNAE (Classificação Nacional de Atividades Econômicas)  [Observação] O campo sempre deve ser enviado com 7 caracteres, seguindo a classificação “CNAE-Subclasse 2.3”. Em casos em que o valor inicie com zeros, ele deve conter todos os caracteres, incluindo os zeros. |
**is_main** | **bool** | Indica se é o ramo principal de atividade da empresa quando true e se é o ramo secundário quando false. [Restrição] Somente uma ocorrência relativa ao código da atividade econômica principal deve trazer o valor true. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
