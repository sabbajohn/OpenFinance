# # Organisation

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**organisation_id** | **string** | Unique ID associated with the organisation | [optional]
**status** | **string** | Status of the directory registration of an organisation | [optional] [default to 'Active']
**organisation_name** | **string** | Name of the organisation. | [optional]
**created_on** | **string** | JSONDatetime of organisation creation. | [optional]
**legal_entity_name** | **string** | Legal Entity name for the org. Usually the same as org name | [optional]
**country_of_registration** | **string** | Country of registration for the org | [optional]
**company_register** | **string** | Legal company register for the country, i.e. Companies House | [optional]
**registration_number** | **string** | Company registration number from company register i.e. Companies House registration number | [optional]
**registration_id** | **string** | Registered ID for the organisation i.e. Legal Entity identifier number | [optional]
**registered_name** | **string** |  | [optional]
**address_line1** | **string** | Address line 1 | [optional]
**address_line2** | **string** | Address line 2 | [optional]
**city** | **string** | City | [optional]
**postcode** | **string** | Postcode | [optional]
**country** | **string** | Country | [optional]
**parent_organisation_reference** | **string** | Parent Organisation Reference | [optional]
**requires_signing** | **bool** | true - one of the attached tncs has to be signed. false - no tnc present | [optional]
**tn_c_updated** | **bool** | true - attached tnc has been update. false - no tnc present | [optional]
**tn_cs_to_be_signed** | [**\OpenAPI\Client\Model\TermsAndConditionsItem[]**](TermsAndConditionsItem.md) |  | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
