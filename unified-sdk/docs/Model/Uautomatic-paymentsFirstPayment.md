# # FirstPayment

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**type** | [**\OpenAPI\Client\Model\EnumPaymentType**](EnumPaymentType.md) |  |
**date** | **\DateTime** | Define a data alvo da liquidação do pagamento. O fuso horário de Brasília deve ser utilizado para criação e racionalização sobre os dados deste campo. |
**currency** | **string** | Código da moeda nacional segundo modelo ISO-4217, ou seja, &#39;BRL&#39;. Todos os valores monetários informados estão representados com a moeda vigente do Brasil. |
**amount** | **string** | Valor da transação com 2 casas decimais. |
**remittance_information** | **string** | Deve ser preenchido sempre que o usuário pagador inserir alguma informação adicional em um pagamento, a ser enviada ao recebedor. | [optional]
**creditor_account** | [**\OpenAPI\Client\Model\CreditorAccountConsent**](CreditorAccountConsent.md) |  |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
