# OpenAPIClient-php

Estas APIs visam o compartilhamento de dados sobre Investimentos e suas
características entre as Instituições Financeiras participantes do Open Finance Brasil


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




$apiInstance = new OpenAPI\Client\Api\BankFixedIncomesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$page = 1; // int | Número da página que está sendo requisitada (o valor da primeira página é 1).
$page_size = 25; // int | Quantidade total de registros por páginas.

try {
    $result = $apiInstance->investmentsGetFixedIncomeBank($page, $page_size);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling BankFixedIncomesApi->investmentsGetFixedIncomeBank: ', $e->getMessage(), PHP_EOL;
}

```

## API Endpoints

All URIs are relative to *https://api.banco.com.br/open-banking/opendata-investments/v1*

Class | Method | HTTP request | Description
------------ | ------------- | ------------- | -------------
*BankFixedIncomesApi* | [**investmentsGetFixedIncomeBank**](docs/Api/BankFixedIncomesApi.md#investmentsgetfixedincomebank) | **GET** /bank-fixed-incomes | Conjunto de informações de produtos de Renda Fixa Bancária (CDB, RDB, LCI e LCA)
*CreditFixedIncomesApi* | [**investmentsGetFixedIncomeCredit**](docs/Api/CreditFixedIncomesApi.md#investmentsgetfixedincomecredit) | **GET** /credit-fixed-incomes | Conjunto de informações de produtos de Renda Fixa Crédito (Debênture, CRI e CRA)
*FundsApi* | [**investmentsGetFunds**](docs/Api/FundsApi.md#investmentsgetfunds) | **GET** /funds | Conjunto de informações dos Fundos de Investimentos
*TreasureTitlesApi* | [**investmentsGetTreasure**](docs/Api/TreasureTitlesApi.md#investmentsgettreasure) | **GET** /treasure-titles | Conjunto de informações de Títulos do Tesouro Direto
*VariableIncomesApi* | [**investmentsGetVariableIncome**](docs/Api/VariableIncomesApi.md#investmentsgetvariableincome) | **GET** /variable-incomes | Conjunto de informações de produtos de Renda Variável (Ações e Fundos de Índices)

## Models

- [DistributionFrequencyPrice](docs/Model/DistributionFrequencyPrice.md)
- [EnumDistinctTargetAudience](docs/Model/EnumDistinctTargetAudience.md)
- [EnumExpirationGracePeriod](docs/Model/EnumExpirationGracePeriod.md)
- [EnumInterval](docs/Model/EnumInterval.md)
- [EnumInvestmentsFixedIncomeBankIndexer](docs/Model/EnumInvestmentsFixedIncomeBankIndexer.md)
- [EnumInvestmentsFixedIncomeBankProductType](docs/Model/EnumInvestmentsFixedIncomeBankProductType.md)
- [EnumInvestmentsFixedIncomeBankRedemptionTerm](docs/Model/EnumInvestmentsFixedIncomeBankRedemptionTerm.md)
- [EnumInvestmentsFixedIncomeCreditInvestmentType](docs/Model/EnumInvestmentsFixedIncomeCreditInvestmentType.md)
- [EnumInvestmentsFundFeesPerformanceFeeBenchmark](docs/Model/EnumInvestmentsFundFeesPerformanceFeeBenchmark.md)
- [EnumInvestmentsFundFeesPerformanceFeeMethod](docs/Model/EnumInvestmentsFundFeesPerformanceFeeMethod.md)
- [EnumInvestmentsFundGeneralConditionsFundQuotaType](docs/Model/EnumInvestmentsFundGeneralConditionsFundQuotaType.md)
- [EnumInvestmentsFundGeneralConditionsTermType](docs/Model/EnumInvestmentsFundGeneralConditionsTermType.md)
- [EnumInvestmentsFundProductAnbimaCategory](docs/Model/EnumInvestmentsFundProductAnbimaCategory.md)
- [EnumInvestmentsFundTaxation](docs/Model/EnumInvestmentsFundTaxation.md)
- [EnumInvestmentsTreasureInvestmentType](docs/Model/EnumInvestmentsTreasureInvestmentType.md)
- [EnumInvestmentsVariableIncomeInvestmentType](docs/Model/EnumInvestmentsVariableIncomeInvestmentType.md)
- [InvestmentFundMinimumAmount](docs/Model/InvestmentFundMinimumAmount.md)
- [InvestmentsFixedIncomeBank](docs/Model/InvestmentsFixedIncomeBank.md)
- [InvestmentsFixedIncomeBankIndex](docs/Model/InvestmentsFixedIncomeBankIndex.md)
- [InvestmentsFixedIncomeBankInvestmentConditions](docs/Model/InvestmentsFixedIncomeBankInvestmentConditions.md)
- [InvestmentsFixedIncomeCredit](docs/Model/InvestmentsFixedIncomeCredit.md)
- [InvestmentsFund](docs/Model/InvestmentsFund.md)
- [InvestmentsFundAdmin](docs/Model/InvestmentsFundAdmin.md)
- [InvestmentsFundFees](docs/Model/InvestmentsFundFees.md)
- [InvestmentsFundFeesPerformanceFee](docs/Model/InvestmentsFundFeesPerformanceFee.md)
- [InvestmentsFundFundManager](docs/Model/InvestmentsFundFundManager.md)
- [InvestmentsFundGeneralConditions](docs/Model/InvestmentsFundGeneralConditions.md)
- [InvestmentsFundGeneralConditionsApplication](docs/Model/InvestmentsFundGeneralConditionsApplication.md)
- [InvestmentsFundGeneralConditionsRedemption](docs/Model/InvestmentsFundGeneralConditionsRedemption.md)
- [InvestmentsGetFunds529Response](docs/Model/InvestmentsGetFunds529Response.md)
- [InvestmentsGetFunds529ResponseErrorsInner](docs/Model/InvestmentsGetFunds529ResponseErrorsInner.md)
- [InvestmentsGetFunds529ResponseMeta](docs/Model/InvestmentsGetFunds529ResponseMeta.md)
- [InvestmentsNoIdentificationFrequencyDistribution](docs/Model/InvestmentsNoIdentificationFrequencyDistribution.md)
- [InvestmentsParticipant](docs/Model/InvestmentsParticipant.md)
- [InvestmentsTreasure](docs/Model/InvestmentsTreasure.md)
- [InvestmentsVariableIncome](docs/Model/InvestmentsVariableIncome.md)
- [Links](docs/Model/Links.md)
- [NoIdentificationFrequencyDistribution](docs/Model/NoIdentificationFrequencyDistribution.md)
- [OKResponseInvestmentsFixedIncomeBank](docs/Model/OKResponseInvestmentsFixedIncomeBank.md)
- [OKResponseInvestmentsFixedIncomeCredit](docs/Model/OKResponseInvestmentsFixedIncomeCredit.md)
- [OKResponseInvestmentsFund](docs/Model/OKResponseInvestmentsFund.md)
- [OKResponseInvestmentsTreasure](docs/Model/OKResponseInvestmentsTreasure.md)
- [OKResponseInvestmentsVariableIncome](docs/Model/OKResponseInvestmentsVariableIncome.md)
- [OpenDataMeta](docs/Model/OpenDataMeta.md)
- [OpenDataMeta2Records](docs/Model/OpenDataMeta2Records.md)
- [OpenDataResponseError](docs/Model/OpenDataResponseError.md)
- [OpenDataResponseErrorErrorsInner](docs/Model/OpenDataResponseErrorErrorsInner.md)

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

- API version: `1.0.1`
    - Generator version: `7.17.0`
- Build package: `org.openapitools.codegen.languages.PhpClientCodegen`
