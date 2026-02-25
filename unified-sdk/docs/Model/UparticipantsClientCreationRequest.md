# # ClientCreationRequest

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**id_token_signed_response_alg** | **string** | Signing algorithim that a client expects the server to return an id_token with. Must be PS256 | [default to 'PS256']
**token_endpoint_auth_method** | **string** | Token endpoint authentication method | [default to 'private_key_jwt']
**jwks_uri** | **string** | Link to the application active jwks |
**tls_client_auth_subject_dn** | **string** | The DN of the certificate that will be used to authenticate to this client | [optional]
**redirect_uris** | **string[]** | redirect_uris uri must be provided. For client_credentials this should be an empty array. |
**response_types** | **string[]** | response_types uri must be provided. For client_credentials this should be an empty array |
**grant_types** | **string[]** | grant_types uri must be provided. For client_credentials this should be array containing [\&quot;client_credentials\&quot;] |
**scope** | **string** | scopes to be tagged |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
