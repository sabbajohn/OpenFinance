# # OrganisationExportOpenData

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
**contacts** | [**\OpenAPI\Client\Model\Contact[]**](Contact.md) | The list of contacts | [optional]
**authorisation_servers** | [**\OpenAPI\Client\Model\AuthorisationServer[]**](AuthorisationServer.md) |  | [optional]
**org_domain_claims** | [**\OpenAPI\Client\Model\OrganisationAuthorityDomainClaim[]**](OrganisationAuthorityDomainClaim.md) |  | [optional]
**org_domain_role_claims** | [**\OpenAPI\Client\Model\OrganisationAuthorityClaim[]**](OrganisationAuthorityClaim.md) |  | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
