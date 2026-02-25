# OpenAPIClient-php

As APIs descritas neste documento são referentes a API de Seguros da fase OpenInsurance do Open Finance Brasil.


For more information, please visit [https://openbanking-brasil.github.io/areadesenvolvedor/](https://openbanking-brasil.github.io/areadesenvolvedor/).

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




$apiInstance = new OpenAPI\Client\Api\SegurosApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$page = 1; // int | Número da página que está sendo requisitada (o valor da primeira página é 1).
$page_size = 25; // int | Quantidade total de registros por páginas.

try {
    $result = $apiInstance->getPersonalInsurance($page, $page_size);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling SegurosApi->getPersonalInsurance: ', $e->getMessage(), PHP_EOL;
}

```

## API Endpoints

All URIs are relative to *https://api.banco.com.br/open-banking/opendata-insurance/v2*

Class | Method | HTTP request | Description
------------ | ------------- | ------------- | -------------
*SegurosApi* | [**getPersonalInsurance**](docs/Api/SegurosApi.md#getpersonalinsurance) | **GET** /personals | Conjunto de informações referentes a seguros pessoais de uma instituição

## Models

- [AgeAdjustment](docs/Model/AgeAdjustment.md)
- [BenefitRecalculation](docs/Model/BenefitRecalculation.md)
- [EnumContractTypePersonal](docs/Model/EnumContractTypePersonal.md)
- [EnumExcludedRisks](docs/Model/EnumExcludedRisks.md)
- [EnumGracePeriodUnit](docs/Model/EnumGracePeriodUnit.md)
- [EnumInsurancePersonalBenefitRecalculationUpdateIndex](docs/Model/EnumInsurancePersonalBenefitRecalculationUpdateIndex.md)
- [EnumInsurancePersonalCoverageTypePersonal](docs/Model/EnumInsurancePersonalCoverageTypePersonal.md)
- [EnumPersonalIndemnityPaymentFrequencyType](docs/Model/EnumPersonalIndemnityPaymentFrequencyType.md)
- [EnumPersonalInsuranceIndemnityPaymentIncome](docs/Model/EnumPersonalInsuranceIndemnityPaymentIncome.md)
- [EnumPersonalInsuranceIndemnityPaymentMethod](docs/Model/EnumPersonalInsuranceIndemnityPaymentMethod.md)
- [EnumPersonalInsuranceOtherGuaranteedValues](docs/Model/EnumPersonalInsuranceOtherGuaranteedValues.md)
- [EnumPersonalInsurancePremiumPaymentFrequency](docs/Model/EnumPersonalInsurancePremiumPaymentFrequency.md)
- [EnumPersonalUpdateIndex](docs/Model/EnumPersonalUpdateIndex.md)
- [EnumPremiumPaymentMethodTypePersonal](docs/Model/EnumPremiumPaymentMethodTypePersonal.md)
- [EnumProductModality](docs/Model/EnumProductModality.md)
- [GetPersonalInsurance529Response](docs/Model/GetPersonalInsurance529Response.md)
- [GetPersonalInsurance529ResponseErrorsInner](docs/Model/GetPersonalInsurance529ResponseErrorsInner.md)
- [GetPersonalInsurance529ResponseMeta](docs/Model/GetPersonalInsurance529ResponseMeta.md)
- [InsurancePensionEnumFinancialRegime](docs/Model/InsurancePensionEnumFinancialRegime.md)
- [InsurancePensionEnumPmbacRemuneration](docs/Model/InsurancePensionEnumPmbacRemuneration.md)
- [InsurancePensionMaxValue](docs/Model/InsurancePensionMaxValue.md)
- [InsurancePensionMinValue](docs/Model/InsurancePensionMinValue.md)
- [Links](docs/Model/Links.md)
- [Meta](docs/Model/Meta.md)
- [OKResponsePersonalInsuranceList](docs/Model/OKResponsePersonalInsuranceList.md)
- [OpenDataMeta](docs/Model/OpenDataMeta.md)
- [Participant](docs/Model/Participant.md)
- [PersonalCoverageItem](docs/Model/PersonalCoverageItem.md)
- [PersonalCoverageItemAttributes](docs/Model/PersonalCoverageItemAttributes.md)
- [PersonalCoverageItemAttributesDeductible](docs/Model/PersonalCoverageItemAttributesDeductible.md)
- [PersonalCoverageItemAttributesDifferentiatedDeductible](docs/Model/PersonalCoverageItemAttributesDifferentiatedDeductible.md)
- [PersonalInsuranceData](docs/Model/PersonalInsuranceData.md)
- [PersonalInsuranceGracePeriod](docs/Model/PersonalInsuranceGracePeriod.md)
- [PersonalInsuranceMinimumRequirement](docs/Model/PersonalInsuranceMinimumRequirement.md)
- [PersonalInsurancePortabilityGraceTime](docs/Model/PersonalInsurancePortabilityGraceTime.md)
- [PersonalInsurancePremiumPayment](docs/Model/PersonalInsurancePremiumPayment.md)
- [PersonalInsuranceReclaim](docs/Model/PersonalInsuranceReclaim.md)
- [PersonalInsuranceReclaimGracePeriod](docs/Model/PersonalInsuranceReclaimGracePeriod.md)
- [PersonalInsuranceReclaimTableItem](docs/Model/PersonalInsuranceReclaimTableItem.md)
- [PersonalInsuranceSociety](docs/Model/PersonalInsuranceSociety.md)
- [Product](docs/Model/Product.md)
- [ResponseError](docs/Model/ResponseError.md)
- [ResponseErrorErrorsInner](docs/Model/ResponseErrorErrorsInner.md)
- [TermsAndConditionsItem](docs/Model/TermsAndConditionsItem.md)

## Authorization
Endpoints do not require authorization.

## Tests

To run the tests, use:

```bash
composer install
vendor/bin/phpunit
```

## Author

gt-interfaces@openbankingbr.org

## About this package

This PHP package is automatically generated by the [OpenAPI Generator](https://openapi-generator.tech) project:

- API version: `2.0.0`
    - Generator version: `7.17.0`
- Build package: `org.openapitools.codegen.languages.PhpClientCodegen`
