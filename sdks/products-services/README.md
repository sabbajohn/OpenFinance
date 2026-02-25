# OpenAPIClient-php

As API's descritas neste documento são referentes as API's da fase OpenData do Open Finance Brasil.


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




$apiInstance = new OpenAPI\Client\Api\AccountsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);

try {
    $result = $apiInstance->getBusinessAccounts();
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountsApi->getBusinessAccounts: ', $e->getMessage(), PHP_EOL;
}

```

## API Endpoints

All URIs are relative to *http://api.banco.com.br/open-banking/products-services/v2*

Class | Method | HTTP request | Description
------------ | ------------- | ------------- | -------------
*AccountsApi* | [**getBusinessAccounts**](docs/Api/AccountsApi.md#getbusinessaccounts) | **GET** /business-accounts | Obtém dados das contas pessoa jurídica
*AccountsApi* | [**getPersonalAccounts**](docs/Api/AccountsApi.md#getpersonalaccounts) | **GET** /personal-accounts | Obtém dados das contas pessoa natural
*CreditCardsApi* | [**getBusinessCreditCards**](docs/Api/CreditCardsApi.md#getbusinesscreditcards) | **GET** /business-credit-cards | Obtém dados sobre cartões de crédito pessoa jurídica
*CreditCardsApi* | [**getPersonalCreditCards**](docs/Api/CreditCardsApi.md#getpersonalcreditcards) | **GET** /personal-credit-cards | Obtém dados sobre cartões de crédito pessoa natural
*FinancingsApi* | [**getBusinessFinancings**](docs/Api/FinancingsApi.md#getbusinessfinancings) | **GET** /business-financings | Obtém a lista de Financiamentos de Pessoa Jurídica.
*FinancingsApi* | [**getPersonalFinancings**](docs/Api/FinancingsApi.md#getpersonalfinancings) | **GET** /personal-financings | Obtém a lista de Financiamentos de Pessoa Natural.
*InvoiceFinancingsApi* | [**getBusinessInvoiceFinancings**](docs/Api/InvoiceFinancingsApi.md#getbusinessinvoicefinancings) | **GET** /business-invoice-financings | Obtém a lista de Adiantamento de Recebíveis de Pessoa Jurídica.
*InvoiceFinancingsApi* | [**getPersonalInvoiceFinancings**](docs/Api/InvoiceFinancingsApi.md#getpersonalinvoicefinancings) | **GET** /personal-invoice-financings | Obtém a lista de Adiantamento de Recebíveis de Pessoa Natural.
*LoansApi* | [**getBusinessLoans**](docs/Api/LoansApi.md#getbusinessloans) | **GET** /business-loans | Obtém dados sobre empréstimos pessoa jurídica
*LoansApi* | [**getPersonalLoans**](docs/Api/LoansApi.md#getpersonalloans) | **GET** /personal-loans | Obtém dados sobre empréstimos pessoa natural
*UnarrangedAccountOverdraftApi* | [**getBusinessUnarrangedAccountOverdraft**](docs/Api/UnarrangedAccountOverdraftApi.md#getbusinessunarrangedaccountoverdraft) | **GET** /business-unarranged-account-overdraft | Obtém a lista de adiantamento de depositante de Pessoa Jurídica.
*UnarrangedAccountOverdraftApi* | [**getPersonalUnarrangedAccountOverdraft**](docs/Api/UnarrangedAccountOverdraftApi.md#getpersonalunarrangedaccountoverdraft) | **GET** /personal-unarranged-account-overdraft | Obtém a lista de adiantamento de depositante de Pessoa Natural.

## Models

- [AccountFee](docs/Model/AccountFee.md)
- [AccountOtherService](docs/Model/AccountOtherService.md)
- [AccountPriorityService](docs/Model/AccountPriorityService.md)
- [AccountPriorityServiceCode](docs/Model/AccountPriorityServiceCode.md)
- [AccountType](docs/Model/AccountType.md)
- [AccountsIncomeRate](docs/Model/AccountsIncomeRate.md)
- [AccountsTermsConditions](docs/Model/AccountsTermsConditions.md)
- [ApplicationIntervals](docs/Model/ApplicationIntervals.md)
- [ApplicationRate](docs/Model/ApplicationRate.md)
- [Brand](docs/Model/Brand.md)
- [BusinessAccounts](docs/Model/BusinessAccounts.md)
- [BusinessAccountsBrand](docs/Model/BusinessAccountsBrand.md)
- [BusinessAccountsCompany](docs/Model/BusinessAccountsCompany.md)
- [BusinessAccountsFees](docs/Model/BusinessAccountsFees.md)
- [BusinessAccountsService](docs/Model/BusinessAccountsService.md)
- [BusinessCreditCard](docs/Model/BusinessCreditCard.md)
- [BusinessCreditCardBrand](docs/Model/BusinessCreditCardBrand.md)
- [BusinessCreditCardCompany](docs/Model/BusinessCreditCardCompany.md)
- [BusinessCreditCardResponse](docs/Model/BusinessCreditCardResponse.md)
- [BusinessCreditCardResponseData](docs/Model/BusinessCreditCardResponseData.md)
- [BusinessFinancing](docs/Model/BusinessFinancing.md)
- [BusinessFinancingBrand](docs/Model/BusinessFinancingBrand.md)
- [BusinessFinancingCompany](docs/Model/BusinessFinancingCompany.md)
- [BusinessFinancingFee](docs/Model/BusinessFinancingFee.md)
- [BusinessInvoiceFinancings](docs/Model/BusinessInvoiceFinancings.md)
- [BusinessInvoiceFinancingsBrand](docs/Model/BusinessInvoiceFinancingsBrand.md)
- [BusinessInvoiceFinancingsCompanies](docs/Model/BusinessInvoiceFinancingsCompanies.md)
- [BusinessInvoiceFinancingsFees](docs/Model/BusinessInvoiceFinancingsFees.md)
- [BusinessInvoiceFinancingsInterestRates](docs/Model/BusinessInvoiceFinancingsInterestRates.md)
- [BusinessLoan](docs/Model/BusinessLoan.md)
- [BusinessLoanBrand](docs/Model/BusinessLoanBrand.md)
- [BusinessLoanCompany](docs/Model/BusinessLoanCompany.md)
- [BusinessUnarrangedAccountOverdraft](docs/Model/BusinessUnarrangedAccountOverdraft.md)
- [BusinessUnarrangedAccountOverdraftBrand](docs/Model/BusinessUnarrangedAccountOverdraftBrand.md)
- [BusinessUnarrangedAccountOverdraftCompany](docs/Model/BusinessUnarrangedAccountOverdraftCompany.md)
- [BusinessUnarrangedAccountOverdraftFee](docs/Model/BusinessUnarrangedAccountOverdraftFee.md)
- [CNPJ](docs/Model/CNPJ.md)
- [Company](docs/Model/Company.md)
- [CreditCardIdentification](docs/Model/CreditCardIdentification.md)
- [CreditCardIdentificationCreditCard](docs/Model/CreditCardIdentificationCreditCard.md)
- [CreditCardIdentificationProduct](docs/Model/CreditCardIdentificationProduct.md)
- [CreditCardInterest](docs/Model/CreditCardInterest.md)
- [CreditCardInterestRate](docs/Model/CreditCardInterestRate.md)
- [CreditCardRewardsProgram](docs/Model/CreditCardRewardsProgram.md)
- [CreditCardService](docs/Model/CreditCardService.md)
- [CreditCardTermsConditions](docs/Model/CreditCardTermsConditions.md)
- [Customer](docs/Model/Customer.md)
- [FeeReferentialRateIndexer](docs/Model/FeeReferentialRateIndexer.md)
- [FinancingInterestRate](docs/Model/FinancingInterestRate.md)
- [FinancingService](docs/Model/FinancingService.md)
- [Indexer](docs/Model/Indexer.md)
- [InterestRate](docs/Model/InterestRate.md)
- [InterestRateFee](docs/Model/InterestRateFee.md)
- [InvoiceFinancingsService](docs/Model/InvoiceFinancingsService.md)
- [Links](docs/Model/Links.md)
- [LoanFees](docs/Model/LoanFees.md)
- [LoanInterestRate](docs/Model/LoanInterestRate.md)
- [LoanService](docs/Model/LoanService.md)
- [MaximumPrice](docs/Model/MaximumPrice.md)
- [Meta](docs/Model/Meta.md)
- [MinimumBalance](docs/Model/MinimumBalance.md)
- [MinimumPrice](docs/Model/MinimumPrice.md)
- [MonthlyPrice](docs/Model/MonthlyPrice.md)
- [OpeningClosingChannels](docs/Model/OpeningClosingChannels.md)
- [PersonalAccount](docs/Model/PersonalAccount.md)
- [PersonalAccountBrand](docs/Model/PersonalAccountBrand.md)
- [PersonalAccountCompany](docs/Model/PersonalAccountCompany.md)
- [PersonalCreditCard](docs/Model/PersonalCreditCard.md)
- [PersonalCreditCardBrand](docs/Model/PersonalCreditCardBrand.md)
- [PersonalCreditCardCompany](docs/Model/PersonalCreditCardCompany.md)
- [PersonalCreditCardFees](docs/Model/PersonalCreditCardFees.md)
- [PersonalCreditCardResponse](docs/Model/PersonalCreditCardResponse.md)
- [PersonalCreditCardResponseData](docs/Model/PersonalCreditCardResponseData.md)
- [PersonalFinancing](docs/Model/PersonalFinancing.md)
- [PersonalFinancingBrand](docs/Model/PersonalFinancingBrand.md)
- [PersonalFinancingCompany](docs/Model/PersonalFinancingCompany.md)
- [PersonalFinancingFee](docs/Model/PersonalFinancingFee.md)
- [PersonalInvoiceFinancings](docs/Model/PersonalInvoiceFinancings.md)
- [PersonalInvoiceFinancingsBrand](docs/Model/PersonalInvoiceFinancingsBrand.md)
- [PersonalInvoiceFinancingsCompanies](docs/Model/PersonalInvoiceFinancingsCompanies.md)
- [PersonalInvoiceFinancingsFees](docs/Model/PersonalInvoiceFinancingsFees.md)
- [PersonalInvoiceFinancingsInterestRates](docs/Model/PersonalInvoiceFinancingsInterestRates.md)
- [PersonalLoan](docs/Model/PersonalLoan.md)
- [PersonalLoanBrand](docs/Model/PersonalLoanBrand.md)
- [PersonalLoanCompany](docs/Model/PersonalLoanCompany.md)
- [PersonalUnarrangedAccountOverdraft](docs/Model/PersonalUnarrangedAccountOverdraft.md)
- [PersonalUnarrangedAccountOverdraftBrand](docs/Model/PersonalUnarrangedAccountOverdraftBrand.md)
- [PersonalUnarrangedAccountOverdraftCompany](docs/Model/PersonalUnarrangedAccountOverdraftCompany.md)
- [PersonalUnarrangedAccountOverdraftFee](docs/Model/PersonalUnarrangedAccountOverdraftFee.md)
- [Price](docs/Model/Price.md)
- [PriceIntervals](docs/Model/PriceIntervals.md)
- [PriorityServiceName](docs/Model/PriorityServiceName.md)
- [ReferentialRateIndexer](docs/Model/ReferentialRateIndexer.md)
- [RequiredWarranty](docs/Model/RequiredWarranty.md)
- [ResponseBusinessAccounts](docs/Model/ResponseBusinessAccounts.md)
- [ResponseBusinessAccountsData](docs/Model/ResponseBusinessAccountsData.md)
- [ResponseBusinessFinancings](docs/Model/ResponseBusinessFinancings.md)
- [ResponseBusinessFinancingsData](docs/Model/ResponseBusinessFinancingsData.md)
- [ResponseBusinessInvoiceFinancings](docs/Model/ResponseBusinessInvoiceFinancings.md)
- [ResponseBusinessInvoiceFinancingsData](docs/Model/ResponseBusinessInvoiceFinancingsData.md)
- [ResponseBusinessLoans](docs/Model/ResponseBusinessLoans.md)
- [ResponseBusinessLoansData](docs/Model/ResponseBusinessLoansData.md)
- [ResponseBusinessUnarrangedAccountOverdraft](docs/Model/ResponseBusinessUnarrangedAccountOverdraft.md)
- [ResponseBusinessUnarrangedAccountOverdraftData](docs/Model/ResponseBusinessUnarrangedAccountOverdraftData.md)
- [ResponsePersonalAccounts](docs/Model/ResponsePersonalAccounts.md)
- [ResponsePersonalAccountsData](docs/Model/ResponsePersonalAccountsData.md)
- [ResponsePersonalFinancings](docs/Model/ResponsePersonalFinancings.md)
- [ResponsePersonalFinancingsData](docs/Model/ResponsePersonalFinancingsData.md)
- [ResponsePersonalInvoiceFinancings](docs/Model/ResponsePersonalInvoiceFinancings.md)
- [ResponsePersonalInvoiceFinancingsData](docs/Model/ResponsePersonalInvoiceFinancingsData.md)
- [ResponsePersonalLoans](docs/Model/ResponsePersonalLoans.md)
- [ResponsePersonalLoansData](docs/Model/ResponsePersonalLoansData.md)
- [ResponsePersonalUnarrangedAccountOverdraft](docs/Model/ResponsePersonalUnarrangedAccountOverdraft.md)
- [ResponsePersonalUnarrangedAccountOverdraftData](docs/Model/ResponsePersonalUnarrangedAccountOverdraftData.md)
- [ServiceBundle](docs/Model/ServiceBundle.md)
- [ServiceBundleServiceDetail](docs/Model/ServiceBundleServiceDetail.md)
- [TransactionMethods](docs/Model/TransactionMethods.md)
- [UnarrangedAccountOverdraftRate](docs/Model/UnarrangedAccountOverdraftRate.md)
- [UnarrangedAccountOverdraftService](docs/Model/UnarrangedAccountOverdraftService.md)

## Authorization

Authentication schemes defined for the API:
### APIKey1

- **Type**: API key
- **API key parameter name**: API Key
- **Location**: URL query string


### APIKey2

- **Type**: API key
- **API key parameter name**: API Key
- **Location**: URL query string


## Tests

To run the tests, use:

```bash
composer install
vendor/bin/phpunit
```

## Author

apiteam@swagger.io

## About this package

This PHP package is automatically generated by the [OpenAPI Generator](https://openapi-generator.tech) project:

- API version: `3.0.0`
    - Generator version: `7.17.0`
- Build package: `org.openapitools.codegen.languages.PhpClientCodegen`
