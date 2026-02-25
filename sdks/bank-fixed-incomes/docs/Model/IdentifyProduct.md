# # IdentifyProduct

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**issuer_institution_cnpj_number** | **string** | CNPJ da instituição emissora. |
**isin_code** | **string** | Código ISIN da emissão, Código ISIN do produto, Código da emissora (campo opcional): código universal que identifica cada valor mobiliário ou instrumento financeiro, conforme Norma ISO 6166 | [optional]
**investment_type** | [**\OpenAPI\Client\Model\EnumInvestmentType**](EnumInvestmentType.md) |  |
**remuneration** | [**\OpenAPI\Client\Model\Remuneration**](Remuneration.md) |  |
**issue_unit_price** | [**\OpenAPI\Client\Model\IdentifyProductIssueUnitPrice**](IdentifyProductIssueUnitPrice.md) |  |
**due_date** | **\DateTime** | Data de vencimento do título. |
**issue_date** | **\DateTime** | Data de emissão do título. |
**clearing_code** | **string** | Código de registro do ativo na clearing. | [optional]
**purchase_date** | **\DateTime** | Data de aquisição do cliente. |
**grace_period_date** | **\DateTime** | Data até a qual o cliente não poderá resgatar antecipadamente seu investimento. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
