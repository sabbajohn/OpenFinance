# # BusinessFinancingData

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**participant** | [**\OpenAPI\Client\Model\Participant**](Participant.md) |  | [optional]
**type** | **string** | Modalidades de financiamentos ofertados para pessoas jurídicas, conforme Circular 4015-Bacen. Segundo cartilha do Bacen: Financiamento é um contrato entre o cliente e uma instituição financeira, mas com, destinação específica como para a aquisição de veículo ou de bem imóvel, que funcionam como garantia para o crédito concedido |
**fees** | [**\OpenAPI\Client\Model\Fee**](Fee.md) |  |
**interest_rates** | [**\OpenAPI\Client\Model\FinancingInterestRate[]**](FinancingInterestRate.md) | Lista que traz o conjunto de informações necessárias para demonstrar a distribuição de frequências das taxas de juros remuneratórios da Modalidade de crédito |
**required_warranties** | [**\OpenAPI\Client\Model\RequiredWarranty[]**](RequiredWarranty.md) |  |
**terms_conditions** | **string** | Campo aberto para informar as condições contratuais relativas à Modalidade de Financiamentos para pessoa jurídica informada. Pode ser informada a URL referente ao endereço onde constam as condições informadas. Endereço eletrônico de acesso ao canal. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
