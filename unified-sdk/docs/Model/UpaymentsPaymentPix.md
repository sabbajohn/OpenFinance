# # PaymentPix

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**amount** | **string** | Valor da transação com 2 casas decimais. O valor deve ser o mesmo enviado no consentimento.   Para QR Code estático com valor pré-determinado no QR Code ou para QR Code dinâmico com indicação de que o valor não pode ser alterado: O campo amount deve ser preenchido com o valor estabelecido no QR Code.  Caso seja preenchido com valor divergente do QR Code, deve ser retornado um erro HTTP Status 422. |
**currency** | **string** | Código da moeda nacional segundo modelo ISO-4217, ou seja, &#39;BRL&#39;.   Todos os valores monetários informados estão representados com a moeda vigente do Brasil. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
