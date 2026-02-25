# # UserDetail

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**super_user** | **bool** | Is the user a super user | [optional]
**system_user** | **bool** | Is the user a system user | [optional]
**basic_information** | [**\OpenAPI\Client\Model\UserDetailBasicInformation**](UserDetailBasicInformation.md) |  | [optional]
**org_access_details** | [**array<string,\OpenAPI\Client\Model\OrgAccessDetail>**](OrgAccessDetail.md) | Map Key - Org ID, Map Value - Org Access Detail(contaning info about org admin and domain role details) | [optional]
**directory_terms_and_conditions_details** | [**\OpenAPI\Client\Model\TermsAndConditionsDetails**](TermsAndConditionsDetails.md) |  | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
