# # UnarrangedAccountOverdraftReleases

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**payment_id** | **string** | Código ou identificador único prestado pela instituição para representar o pagamento individual. |
**is_over_parcel_payment** | **bool** | Identifica se é um pagamento pactuado (false) ou avulso (true). |
**instalment_id** | **string** | Identificador de parcela, de responsabilidade de cada Instituição transmissora.  [Restrição] Informação de envio obrigatório quando isOverParcelPayment tiver o valor FALSE. | [optional]
**paid_date** | **\DateTime** | Data efetiva do pagamento referente ao contrato da modalidade de crédito consultada, conforme especificação [RFC-3339](https://datatracker.ietf.org/doc/html/rfc3339). p.ex. 2014-03-19 |
**currency** | **string** | Moeda referente ao valor monetário informado, segundo modelo ISO-4217. p.ex. &#39;BRL&#39;. Todos os valores monetários informados estão representados com a moeda vigente do Brasil. |
**paid_amount** | **string** | Valor do pagamento referente ao  contrato da modalidade de crédito consultada. Expresso em valor monetário com no mínimo 2 casas e no máximo 4 casas decimais. |
**over_parcel** | [**\OpenAPI\Client\Model\UnarrangedAccountOverdraftReleasesOverParcel**](UnarrangedAccountOverdraftReleasesOverParcel.md) |  | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
