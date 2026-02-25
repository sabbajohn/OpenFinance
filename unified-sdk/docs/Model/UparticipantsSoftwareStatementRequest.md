# # SoftwareStatementRequest

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**client_name** | **string** | Software Statement client name |
**description** | **string** | Software Statement description | [optional]
**on_behalf_of** | **string** | A reference to fourth party organisation resource on the RTS Directory if the registering Org is acting on behalf of another | [optional]
**policy_uri** | **string** | The Software Statement compliant policy URI |
**client_uri** | **string** | The Software Statement compliant client URI |
**logo_uri** | **string** | The Software Statement compliant logo URI |
**environment** | **string** | The additional check for software statement, this field can avoid environment checks. | [optional]
**mode** | **string** | The additional check to see if the environment reflected above is live or test. | [optional] [default to 'Live']
**redirect_uri** | **string[]** | The Software Statement redirect URIs |
**terms_of_service_uri** | **string** | The Software Statement terms of service compliant URI |
**version** | **float** | Software Statement version as provided by the organisation&#39;s PTC |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
