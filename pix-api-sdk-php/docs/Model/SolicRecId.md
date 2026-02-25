# # SolicRecId

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**id_solic_rec** | **string** | # Identificador da Solicitação da Recorrência  Regra de formação: - SCxxxxxxxxyyyyMMddkkkkkkkkkkk (29 caracteres; “case sensitive”, isso é, diferencia letras maiúsculas e minúsculas), sendo:   - SC - fixo (2 caracteres);   - xxxxxxxx – ISPB do agente que envia a mensagem pain.009 de solicitação de confirmação da recorrência (8 caracteres alfanuméricos [A-Z|0-9]);   - yyyyMMdd – data (8 caracteres) de criação da mensagem pain.009 de solicitação de confirmação da recorrência;   - kkkkkkkkkkk – sequencial criado pelo agente que gerou a mensagem de solicitação de confirmação da recorrência (11 caracteres alfanuméricos [a-z|A-Z|0-9]). Deve ser único dentro de cada “yyyyMMdd”. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
