# # ClientCreationResponse

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**application_type** | **string** | OIDC application type response | [optional] [default to 'web']
**tls_client_auth_subject_dn** | **string** | the subject dn used to authenticate this client | [optional]
**grant_types** | **string[]** | grant_types | [optional]
**id_token_signed_response_alg** | **string** |  | [optional]
**require_auth_time** | **bool** |  | [optional]
**subject_type** | **string** |  | [optional]
**response_types** | **string[]** | response_types | [optional]
**post_logout_redirect_uris** | **string[]** | post_logout_redirect_uris | [optional]
**token_endpoint_auth_method** | **string** |  | [optional]
**introspection_endpoint_auth_method** | **string** |  | [optional]
**revocation_endpoint_auth_method** | **string** |  | [optional]
**client_id_issued_at** | **float** |  | [optional]
**client_id** | **string** |  | [optional]
**jwks_uri** | **string** |  | [optional]
**registration_client_uri** | **string** | management uri location to manage client post creation | [optional]
**registration_access_token** | **string** | token used to manage client post creation | [optional]
**redirect_uris** | **string[]** | redirect_uris | [optional]
**request_uris** | **string[]** | request_uris | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
