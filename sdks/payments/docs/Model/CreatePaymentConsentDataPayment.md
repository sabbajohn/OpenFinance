# # CreatePaymentConsentDataPayment

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**type** | [**\OpenAPI\Client\Model\EnumPaymentType**](EnumPaymentType.md) |  |
**schedule** | [**\OpenAPI\Client\Model\Schedule**](Schedule.md) |  | [optional]
**date** | **\DateTime** | [Restrição] Mutuamente excludente com o objeto schedule.   Este campo é obrigatório no caso de pagamento único.   Neste caso, o objeto schedule não deve ser informado. | [optional]
**currency** | **string** | Código da moeda nacional segundo modelo ISO-4217, ou seja, &#39;BRL&#39;. Todos os valores monetários informados estão representados com a moeda vigente do Brasil. |
**amount** | **string** | Valor da transação com 2 casas decimais. O valor deve ser o mesmo enviado no consentimento.   Para QR Code estático com valor pré-determinado no QR Code ou para QR Code dinâmico com indicação de que o valor não pode ser alterado: O campo amount deve ser preenchido com o valor estabelecido no QR Code.  Caso seja preenchido com valor divergente do QR Code, deve ser retornado um erro HTTP Status 422. |
**ibge_town_code** | **string** | O campo ibgetowncode no arranjo PIX, tem o mesmo comportamento que o campo codMun descrito no item 1.6.6 do manual do PIX, conforme segue:  1. Caso a informação referente ao município não seja enviada; o PSP do recebedor assumirá que não existem feriados estaduais e municipais no período em questão; | [optional]
**details** | [**\OpenAPI\Client\Model\Details**](Details.md) |  |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
