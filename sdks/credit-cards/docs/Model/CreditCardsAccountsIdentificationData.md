# # CreditCardsAccountsIdentificationData

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**name** | **string** | Denominação/Identificação do nome da conta de pagamento pós-paga (cartão). Conforme CIRCULAR Nº 3.680,BCB, 2013: &#39;conta de pagamento pós-paga: destinada à execução de transações de pagamento que independem do aporte prévio de recursos&#39;. |
**product_type** | [**\OpenAPI\Client\Model\EnumCreditCardAccountsProductType**](EnumCreditCardAccountsProductType.md) |  |
**product_additional_info** | **string** | Informações complementares se tipo de Cartão &#39;OUTROS&#39; | [optional]
**credit_card_network** | [**\OpenAPI\Client\Model\EnumCreditCardAccountNetwork**](EnumCreditCardAccountNetwork.md) |  |
**network_additional_info** | **string** | Texto livre para especificar categoria de bandeira marcada como &#39;OUTRAS&#39;. | [optional]
**payment_method** | [**\OpenAPI\Client\Model\CreditCardsAccountPaymentMethod[]**](CreditCardsAccountPaymentMethod.md) | Listagem dos cartões (ex.: virtual/adicional/titular) associados a conta cartão consentida, conforme disponíveis ao usuário nos canais proprietários. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
