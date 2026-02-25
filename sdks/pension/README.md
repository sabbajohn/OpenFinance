# OpenAPIClient-php

API de Previdência do Open Finance Brasil – Fase 4.
API que retorna informações de Previdência.


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




$apiInstance = new OpenAPI\Client\Api\RiskCoveragesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$page = 1; // int | Número da página que está sendo requisitada (o valor da primeira página é 1).
$page_size = 25; // int | Quantidade total de registros por páginas.

try {
    $result = $apiInstance->getPensionRiskCoverages($page, $page_size);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling RiskCoveragesApi->getPensionRiskCoverages: ', $e->getMessage(), PHP_EOL;
}

```

## API Endpoints

All URIs are relative to *https://api.banco.com.br/open-banking/opendata-pension/v2*

Class | Method | HTTP request | Description
------------ | ------------- | ------------- | -------------
*RiskCoveragesApi* | [**getPensionRiskCoverages**](docs/Api/RiskCoveragesApi.md#getpensionriskcoverages) | **GET** /risk-coverages | Informações de Previdência com Cobertura de Riscos.
*SurvivalCoveragesApi* | [**getPensionSurvivalCoverages**](docs/Api/SurvivalCoveragesApi.md#getpensionsurvivalcoverages) | **GET** /survival-coverages | Informações de Previdência com Cobertura de Sobrevivência.

## Models

- [AgeAdjustment](docs/Model/AgeAdjustment.md)
- [Coverage](docs/Model/Coverage.md)
- [CoverageAttributes](docs/Model/CoverageAttributes.md)
- [CoverageAttributesGracePeriod](docs/Model/CoverageAttributesGracePeriod.md)
- [EnumAssistanceType](docs/Model/EnumAssistanceType.md)
- [EnumExcludedRisks](docs/Model/EnumExcludedRisks.md)
- [EnumGracePeriodUnit](docs/Model/EnumGracePeriodUnit.md)
- [EnumPlanAdditional](docs/Model/EnumPlanAdditional.md)
- [EnumProductModality](docs/Model/EnumProductModality.md)
- [EnumRiskPensionCoverageType](docs/Model/EnumRiskPensionCoverageType.md)
- [GetRiskPensionContractData](docs/Model/GetRiskPensionContractData.md)
- [GetSurvivalPensionContractData](docs/Model/GetSurvivalPensionContractData.md)
- [InsurancePensionMaxValue](docs/Model/InsurancePensionMaxValue.md)
- [InsurancePensionMinValue](docs/Model/InsurancePensionMinValue.md)
- [Links](docs/Model/Links.md)
- [MetaOnlyRequestDateTime](docs/Model/MetaOnlyRequestDateTime.md)
- [OKResponseRiskCoveragePension](docs/Model/OKResponseRiskCoveragePension.md)
- [OKResponseSurvivalCoveragePension](docs/Model/OKResponseSurvivalCoveragePension.md)
- [OpenDataMeta](docs/Model/OpenDataMeta.md)
- [OpenDataResponseError](docs/Model/OpenDataResponseError.md)
- [OpenDataResponseErrorErrorsInner](docs/Model/OpenDataResponseErrorErrorsInner.md)
- [PensionParticipant](docs/Model/PensionParticipant.md)
- [ResponseErrorMetaSingle](docs/Model/ResponseErrorMetaSingle.md)
- [ResponseErrorMetaSingleErrorsInner](docs/Model/ResponseErrorMetaSingleErrorsInner.md)
- [RiskPensionEnumContributionPayment](docs/Model/RiskPensionEnumContributionPayment.md)
- [RiskPensionEnumFinancialRegime](docs/Model/RiskPensionEnumFinancialRegime.md)
- [RiskPensionEnumGracePeriodUnit](docs/Model/RiskPensionEnumGracePeriodUnit.md)
- [RiskPensionEnumIndemnifiablePeriodType](docs/Model/RiskPensionEnumIndemnifiablePeriodType.md)
- [RiskPensionEnumIndemnityPaymentMethod](docs/Model/RiskPensionEnumIndemnityPaymentMethod.md)
- [RiskPensionEnumOtherGuaranteedValues](docs/Model/RiskPensionEnumOtherGuaranteedValues.md)
- [RiskPensionEnumPmbacRemuneration](docs/Model/RiskPensionEnumPmbacRemuneration.md)
- [RiskPensionEnumPremiumUpdateIndex](docs/Model/RiskPensionEnumPremiumUpdateIndex.md)
- [RiskPensionEnumProfitModality](docs/Model/RiskPensionEnumProfitModality.md)
- [RiskPensionGracePeriod](docs/Model/RiskPensionGracePeriod.md)
- [RiskPensionMinimumRequirement](docs/Model/RiskPensionMinimumRequirement.md)
- [RiskPensionReclaim](docs/Model/RiskPensionReclaim.md)
- [RiskPensionReclaimTableItem](docs/Model/RiskPensionReclaimTableItem.md)
- [RiskProducts](docs/Model/RiskProducts.md)
- [RiskSociety](docs/Model/RiskSociety.md)
- [SurvivalPensionCosts](docs/Model/SurvivalPensionCosts.md)
- [SurvivalPensionDefferalPeriod](docs/Model/SurvivalPensionDefferalPeriod.md)
- [SurvivalPensionEnumTargetAudience](docs/Model/SurvivalPensionEnumTargetAudience.md)
- [SurvivalPensionGracePeriod](docs/Model/SurvivalPensionGracePeriod.md)
- [SurvivalPensionGrantPeriodBenefit](docs/Model/SurvivalPensionGrantPeriodBenefit.md)
- [SurvivalPensionInvestmentFund](docs/Model/SurvivalPensionInvestmentFund.md)
- [SurvivalPensionLoadingAntecipated](docs/Model/SurvivalPensionLoadingAntecipated.md)
- [SurvivalPensionLoadingLate](docs/Model/SurvivalPensionLoadingLate.md)
- [SurvivalPensionMinimumPremium](docs/Model/SurvivalPensionMinimumPremium.md)
- [SurvivalPensionMinimumRequirements](docs/Model/SurvivalPensionMinimumRequirements.md)
- [SurvivalPensionType](docs/Model/SurvivalPensionType.md)
- [SurvivalProducts](docs/Model/SurvivalProducts.md)
- [SurvivalSociety](docs/Model/SurvivalSociety.md)
- [TermsAndConditions](docs/Model/TermsAndConditions.md)
- [UpdateIndex](docs/Model/UpdateIndex.md)

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
