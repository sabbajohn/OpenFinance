# # OperationDetails

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**authorized_institution_cnpj_number** | **string** | CNPJ da instituição autorizada a operar no mercado de câmbio. |
**authorized_institution_name** | **string** | Nome da Instituição Financeira no Brasil. |
**intermediary_institution_cnpj_number** | **string** | CNPJ da instituição intermediadora autorizada a operar no mercado de câmbio.  Campo de envio obrigatório nos casos em que houver instituição intermediadora. | [optional]
**intermediary_institution_name** | **string** | Nome da corretora interveniente autorizada a operar no mercado de câmbio.  [Restrição] Campo de preenchimento obrigatório pelas participantes quando o campo &#39;intermediaryInstitutionCnpjNumber&#39; for informado. | [optional]
**operation_number** | **string** | Número do registro da operação no Bacen. Deve ser preenchido no compartilhamento, após registro no Sistema de Câmbio e número disponível na transmissora/detentora. | [optional]
**operation_type** | [**\OpenAPI\Client\Model\EnumExchangesOperationType**](EnumExchangesOperationType.md) |  |
**operation_date** | **\DateTime** | Data do fechamento do contrato de câmbio. |
**due_date** | **\DateTime** | Data em que a operação (compra ou venda) está prevista para ser liquidada. |
**local_currency_operation_tax** | [**\OpenAPI\Client\Model\OperationDetailsLocalCurrencyOperationTax**](OperationDetailsLocalCurrencyOperationTax.md) |  |
**local_currency_operation_value** | [**\OpenAPI\Client\Model\OperationDetailsLocalCurrencyOperationValue**](OperationDetailsLocalCurrencyOperationValue.md) |  |
**foreign_operation_value** | [**\OpenAPI\Client\Model\OperationDetailsForeignOperationValue**](OperationDetailsForeignOperationValue.md) |  |
**operation_outstanding_balance** | [**\OpenAPI\Client\Model\OperationDetailsOperationOutstandingBalance**](OperationDetailsOperationOutstandingBalance.md) |  | [optional]
**vet_amount** | [**\OpenAPI\Client\Model\OperationDetailsVetAmount**](OperationDetailsVetAmount.md) |  | [optional]
**local_currency_advance_percentage** | **string** | Percentual do valor de moeda estrangeira concedido ao cliente antecipadamente. p.ex. 0.014500.  O preenchimento deve respeitar as 6 casas decimais, mesmo que venham preenchidas com zeros(representação de porcentagem p.ex: 0.150000. Este valor representa 15%. O valor 1 representa 100%). Campos de envio obrigatório no caso de operações de câmbio com liquidação futura. | [optional]
**delivery_foreign_currency** | [**\OpenAPI\Client\Model\EnumExchangesDeliveryForeignCurrency**](EnumExchangesDeliveryForeignCurrency.md) |  |
**operation_category_code** | **string** | Código da natureza fato do fechamento da operação. Deve respeitar os códigos de natureza referenciados na resolução 277 ou na Circular 3690, conforme se aplicar ao contrato de câmbio. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
