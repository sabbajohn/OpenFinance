# # AccountIdentificationData

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**compe_code** | **string** | Código identificador atribuído pelo Banco Central do Brasil às instituições participantes do STR (Sistema de Transferência de reservas). O número-código substituiu o antigo código COMPE. Todos os participantes do STR, exceto as Infraestruturas do Mercado Financeiro (IMF) e a Secretaria do Tesouro Nacional, possuem um número-código independentemente de participarem da Centralizadora da Compensação de Cheques (Compe). |
**branch_code** | **string** | Código da Agência detentora da conta. (Agência é a dependência destinada ao atendimento aos clientes, ao público em geral e aos associados de cooperativas de crédito, no exercício de atividades da instituição, não podendo ser móvel ou transitória)  [Restrição] Obrigatoriamente deve ser preenchido quando o campo \&quot;type\&quot; for diferente de conta pré-paga. | [optional]
**number** | **string** | Número da conta |
**check_digit** | **string** | Dígito da conta |
**type** | [**\OpenAPI\Client\Model\EnumAccountType**](EnumAccountType.md) |  |
**subtype** | [**\OpenAPI\Client\Model\EnumAccountSubType**](EnumAccountSubType.md) |  |
**currency** | **string** | Moeda referente ao valor da transação, segundo modelo ISO-4217. p.ex. &#39;BRL&#39;  Todos os saldos informados estão representados com a moeda vigente do Brasil |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
