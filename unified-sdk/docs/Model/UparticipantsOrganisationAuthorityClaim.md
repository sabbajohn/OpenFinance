# # OrganisationAuthorityClaim

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**organisation_id** | **string** | Unique ID associated with the organisation | [optional]
**organisation_authority_claim_id** | **string** | Unique ID associated with the authority claims | [optional]
**authority_id** | **string** | Unique ID associated with the Authorisation reference schema | [optional]
**status** | **string** | Is this software statement Active/Inactive | [optional] [default to 'Active']
**authorisation_domain** | **string** | Authorisation Domain for the authority | [optional]
**role** | **string** | Roles for the Authority i.e. ASPSP, AISP, PISP, CBPII | [optional]
**authorisations** | [**\OpenAPI\Client\Model\OrganisationAuthorityClaimAuthorisationsInner[]**](OrganisationAuthorityClaimAuthorisationsInner.md) |  | [optional]
**registration_id** | **string** | Registration ID for the organisation | [optional]
**unique_technical_idenifier** | **string[]** |  | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
