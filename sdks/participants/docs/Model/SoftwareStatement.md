# # SoftwareStatement

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**status** | **string** | Is this software statement Active/Inactive | [optional] [default to 'Active']
**client_id** | **string** | Software Statement client Id | [optional]
**client_name** | **string** | Software Statement client name | [optional]
**description** | **string** | Software Statement description | [optional]
**environment** | **string** | The additional check for software statement, this field can avoid | [optional]
**organisation_id** | **string** | Unique ID associated with the organisation | [optional]
**software_statement_id** | **string** | Unique Software Statement Id | [optional]
**mode** | **string** | Software Statement mode | [optional] [default to 'Live']
**rts_client_created** | **bool** | Client created flag | [optional]
**on_behalf_of** | **string** | A reference to fourth party organisation resource on the RTS Directory if the registering Org is acting on behalf of another | [optional]
**policy_uri** | **string** | The Software Statement policy compliant URI | [optional]
**client_uri** | **string** | The Software Statement client compliant URI | [optional]
**logo_uri** | **string** | The Software Statement logo compliant URI | [optional]
**redirect_uri** | **string[]** | The Software Statement redirect compliant URI | [optional]
**terms_of_service_uri** | **string** | The Software Statement terms of service compliant URI | [optional]
**version** | **float** | Software Statement version as provided by the organisation&#39;s PTC | [optional]
**locked** | **bool** | Flag shows if assertion has been generated on the software statement - will be set to true when assertion is generated | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
