# OpenAPIClient-php

Informações sobre os participantes do Open Finance Brasil que estão registrados no Diretório.


## Installation & Usage

### Requirements

PHP 8.1 and later.

### Composer

To install the bindings via [Composer](https://getcomposer.org/), add the following to `composer.json`:

```json
{
  "repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/GIT_USER_ID/GIT_REPO_ID.git"
    }
  ],
  "require": {
    "GIT_USER_ID/GIT_REPO_ID": "*@dev"
  }
}
```

Then run `composer install`

### Manual Installation

Download the files and include `autoload.php`:

```php
<?php
require_once('/path/to/OpenAPIClient-php/vendor/autoload.php');
```

## Getting Started

Please follow the [installation procedure](#installation--usage) and then run the following:

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');




$apiInstance = new OpenAPI\Client\Api\OrganisationsExportApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);

try {
    $result = $apiInstance->participantsGet();
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling OrganisationsExportApi->participantsGet: ', $e->getMessage(), PHP_EOL;
}

```

## API Endpoints

All URIs are relative to *https://data.directory.openbankingbrasil.org.br*

Class | Method | HTTP request | Description
------------ | ------------- | ------------- | -------------
*OrganisationsExportApi* | [**participantsGet**](docs/Api/OrganisationsExportApi.md#participantsget) | **GET** /participants | Recupera informações técnicas sobre Participantes registrados no diretório, essas informações permitem identificar e consumir as APIs dos participantes

## Models

- [AccessTokenRequest](docs/Model/AccessTokenRequest.md)
- [AccessTokenResponse](docs/Model/AccessTokenResponse.md)
- [AmendCertificateRequest](docs/Model/AmendCertificateRequest.md)
- [ApiDiscoveryEndpoint](docs/Model/ApiDiscoveryEndpoint.md)
- [ApiDiscoveryEndpointRequest](docs/Model/ApiDiscoveryEndpointRequest.md)
- [ApiDiscoveryEndpoints](docs/Model/ApiDiscoveryEndpoints.md)
- [ApiResource](docs/Model/ApiResource.md)
- [ApiResourceRequest](docs/Model/ApiResourceRequest.md)
- [ApiResources](docs/Model/ApiResources.md)
- [AuthorisationDomain](docs/Model/AuthorisationDomain.md)
- [AuthorisationDomainRequest](docs/Model/AuthorisationDomainRequest.md)
- [AuthorisationDomainRole](docs/Model/AuthorisationDomainRole.md)
- [AuthorisationDomainRoleRequest](docs/Model/AuthorisationDomainRoleRequest.md)
- [AuthorisationDomainUser](docs/Model/AuthorisationDomainUser.md)
- [AuthorisationDomainUserCreateRequest](docs/Model/AuthorisationDomainUserCreateRequest.md)
- [AuthorisationDomainUserUpdateRequest](docs/Model/AuthorisationDomainUserUpdateRequest.md)
- [AuthorisationServer](docs/Model/AuthorisationServer.md)
- [AuthorisationServerRequest](docs/Model/AuthorisationServerRequest.md)
- [Authority](docs/Model/Authority.md)
- [AuthorityAuthorisationDomain](docs/Model/AuthorityAuthorisationDomain.md)
- [AuthorityAuthorisationDomainRequest](docs/Model/AuthorityAuthorisationDomainRequest.md)
- [AuthorityRequest](docs/Model/AuthorityRequest.md)
- [BadRequest](docs/Model/BadRequest.md)
- [CertificateOrKey](docs/Model/CertificateOrKey.md)
- [ClientCreationRequest](docs/Model/ClientCreationRequest.md)
- [ClientCreationResponse](docs/Model/ClientCreationResponse.md)
- [Contact](docs/Model/Contact.md)
- [ContactRequest](docs/Model/ContactRequest.md)
- [ContactRoleEnum](docs/Model/ContactRoleEnum.md)
- [DomainRoleDetail](docs/Model/DomainRoleDetail.md)
- [EssPollResponse](docs/Model/EssPollResponse.md)
- [EssSignRequest](docs/Model/EssSignRequest.md)
- [ExternalSigningServiceEnvelopeStatus](docs/Model/ExternalSigningServiceEnvelopeStatus.md)
- [ExternalSigningServiceSignerTemplateConfig](docs/Model/ExternalSigningServiceSignerTemplateConfig.md)
- [OrgAccessDetail](docs/Model/OrgAccessDetail.md)
- [OrgAdminUserCreateRequest](docs/Model/OrgAdminUserCreateRequest.md)
- [OrgTermsAndConditionsDetail](docs/Model/OrgTermsAndConditionsDetail.md)
- [OrgTermsAndConditionsPage](docs/Model/OrgTermsAndConditionsPage.md)
- [Organisation](docs/Model/Organisation.md)
- [OrganisationAdminUser](docs/Model/OrganisationAdminUser.md)
- [OrganisationAuthorityClaim](docs/Model/OrganisationAuthorityClaim.md)
- [OrganisationAuthorityClaimAuthorisation](docs/Model/OrganisationAuthorityClaimAuthorisation.md)
- [OrganisationAuthorityClaimAuthorisationRequest](docs/Model/OrganisationAuthorityClaimAuthorisationRequest.md)
- [OrganisationAuthorityClaimAuthorisationsInner](docs/Model/OrganisationAuthorityClaimAuthorisationsInner.md)
- [OrganisationAuthorityClaimRequest](docs/Model/OrganisationAuthorityClaimRequest.md)
- [OrganisationAuthorityDomainClaim](docs/Model/OrganisationAuthorityDomainClaim.md)
- [OrganisationAuthorityDomainClaimRequest](docs/Model/OrganisationAuthorityDomainClaimRequest.md)
- [OrganisationCertificateType](docs/Model/OrganisationCertificateType.md)
- [OrganisationEnrol](docs/Model/OrganisationEnrol.md)
- [OrganisationEnrolmentsInner](docs/Model/OrganisationEnrolmentsInner.md)
- [OrganisationExportOpenData](docs/Model/OrganisationExportOpenData.md)
- [OrganisationRequest](docs/Model/OrganisationRequest.md)
- [OrganisationSnapshot](docs/Model/OrganisationSnapshot.md)
- [OrganisationSnapshotPage](docs/Model/OrganisationSnapshotPage.md)
- [OrganisationSnapshotSoftwareStatementsValue](docs/Model/OrganisationSnapshotSoftwareStatementsValue.md)
- [OrganisationUpdateRequest](docs/Model/OrganisationUpdateRequest.md)
- [Pageable](docs/Model/Pageable.md)
- [PageableRequest](docs/Model/PageableRequest.md)
- [SoftwareAuthorityClaim](docs/Model/SoftwareAuthorityClaim.md)
- [SoftwareAuthorityClaimRequest](docs/Model/SoftwareAuthorityClaimRequest.md)
- [SoftwareAuthorityClaimUpdateRequest](docs/Model/SoftwareAuthorityClaimUpdateRequest.md)
- [SoftwareStatement](docs/Model/SoftwareStatement.md)
- [SoftwareStatementCertificateOrKeyType](docs/Model/SoftwareStatementCertificateOrKeyType.md)
- [SoftwareStatementRequest](docs/Model/SoftwareStatementRequest.md)
- [Sort](docs/Model/Sort.md)
- [SortOrderByInner](docs/Model/SortOrderByInner.md)
- [StatusEnum](docs/Model/StatusEnum.md)
- [SuperUser](docs/Model/SuperUser.md)
- [SuperUserCreationRequest](docs/Model/SuperUserCreationRequest.md)
- [TermsAndConditionsCreateRequest](docs/Model/TermsAndConditionsCreateRequest.md)
- [TermsAndConditionsDetail](docs/Model/TermsAndConditionsDetail.md)
- [TermsAndConditionsDetails](docs/Model/TermsAndConditionsDetails.md)
- [TermsAndConditionsItem](docs/Model/TermsAndConditionsItem.md)
- [TermsAndConditionsItemExternalSigningService](docs/Model/TermsAndConditionsItemExternalSigningService.md)
- [TermsAndConditionsPage](docs/Model/TermsAndConditionsPage.md)
- [TermsAndConditionsUpdateRequest](docs/Model/TermsAndConditionsUpdateRequest.md)
- [UserCreateRequest](docs/Model/UserCreateRequest.md)
- [UserDetail](docs/Model/UserDetail.md)
- [UserDetailBasicInformation](docs/Model/UserDetailBasicInformation.md)
- [UserOPInfo](docs/Model/UserOPInfo.md)
- [UserTermsAndConditionsPage](docs/Model/UserTermsAndConditionsPage.md)
- [UserUpdateRequest](docs/Model/UserUpdateRequest.md)
- [WellKnown](docs/Model/WellKnown.md)

## Authorization
Endpoints do not require authorization.

## Tests

To run the tests, use:

```bash
composer install
vendor/bin/phpunit
```

## Author



## About this package

This PHP package is automatically generated by the [OpenAPI Generator](https://openapi-generator.tech) project:

- API version: `1.0.0`
    - Generator version: `7.17.0`
- Build package: `org.openapitools.codegen.languages.PhpClientCodegen`
