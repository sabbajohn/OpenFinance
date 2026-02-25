# OpenAPIClient-php

As APIs descritas neste documento são referentes as APIs da fase OpenData do Open Finance Brasil.


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




$apiInstance = new OpenAPI\Client\Api\ChannelsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);

try {
    $result = $apiInstance->getBankingAgents();
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ChannelsApi->getBankingAgents: ', $e->getMessage(), PHP_EOL;
}

```

## API Endpoints

All URIs are relative to *http://api.banco.com.br/open-banking/channels/v1*

Class | Method | HTTP request | Description
------------ | ------------- | ------------- | -------------
*ChannelsApi* | [**getBankingAgents**](docs/Api/ChannelsApi.md#getbankingagents) | **GET** /banking-agents | Obtém a lista de correspondentes bancários da instituição financeira.
*ChannelsApi* | [**getBranches**](docs/Api/ChannelsApi.md#getbranches) | **GET** /branches | Obtém a lista de dependências próprias da instituição financeira.
*ChannelsApi* | [**getElectronicChannels**](docs/Api/ChannelsApi.md#getelectronicchannels) | **GET** /electronic-channels | Obtém a lista de canais eletrônicos de atendimento da instituição financeira.
*ChannelsApi* | [**getPhoneChannels**](docs/Api/ChannelsApi.md#getphonechannels) | **GET** /phone-channels | Obtém a lista de canais telefônicos de atendimento da instituição financeira.
*ChannelsApi* | [**getSharedAutomatedTellerMachines**](docs/Api/ChannelsApi.md#getsharedautomatedtellermachines) | **GET** /shared-automated-teller-machines | Obtém a lista de terminais compartilhados de autoatendimento.

## Models

- [BankingAgent](docs/Model/BankingAgent.md)
- [BankingAgentAvailability](docs/Model/BankingAgentAvailability.md)
- [BankingAgentAvailabilityStandardsInner](docs/Model/BankingAgentAvailabilityStandardsInner.md)
- [BankingAgentIdentification](docs/Model/BankingAgentIdentification.md)
- [BankingAgentLocation](docs/Model/BankingAgentLocation.md)
- [BankingAgentPostalAddress](docs/Model/BankingAgentPostalAddress.md)
- [BankingAgentService](docs/Model/BankingAgentService.md)
- [BankingAgentsBrand](docs/Model/BankingAgentsBrand.md)
- [BankingAgentsCompanies](docs/Model/BankingAgentsCompanies.md)
- [BankingAgentsContractor](docs/Model/BankingAgentsContractor.md)
- [Branch](docs/Model/Branch.md)
- [BranchAvailability](docs/Model/BranchAvailability.md)
- [BranchAvailabilityStandardsInner](docs/Model/BranchAvailabilityStandardsInner.md)
- [BranchIdentification](docs/Model/BranchIdentification.md)
- [BranchPhone](docs/Model/BranchPhone.md)
- [BranchPostalAddress](docs/Model/BranchPostalAddress.md)
- [BranchService](docs/Model/BranchService.md)
- [BranchesBrand](docs/Model/BranchesBrand.md)
- [BranchesCompany](docs/Model/BranchesCompany.md)
- [Brand](docs/Model/Brand.md)
- [CNPJ](docs/Model/CNPJ.md)
- [ElectronicChannel](docs/Model/ElectronicChannel.md)
- [ElectronicChannelIdentification](docs/Model/ElectronicChannelIdentification.md)
- [ElectronicChannelService](docs/Model/ElectronicChannelService.md)
- [ElectronicChannelsBrand](docs/Model/ElectronicChannelsBrand.md)
- [ElectronicChannelsCompanies](docs/Model/ElectronicChannelsCompanies.md)
- [GeographicCoordinates](docs/Model/GeographicCoordinates.md)
- [Links](docs/Model/Links.md)
- [Meta](docs/Model/Meta.md)
- [Phone](docs/Model/Phone.md)
- [PhoneChannel](docs/Model/PhoneChannel.md)
- [PhoneChannelIdentification](docs/Model/PhoneChannelIdentification.md)
- [PhoneChannelPhone](docs/Model/PhoneChannelPhone.md)
- [PhoneChannelService](docs/Model/PhoneChannelService.md)
- [PhoneChannelsBrand](docs/Model/PhoneChannelsBrand.md)
- [PhoneChannelsCompany](docs/Model/PhoneChannelsCompany.md)
- [PostalAddress](docs/Model/PostalAddress.md)
- [ResponseBankingAgentsList](docs/Model/ResponseBankingAgentsList.md)
- [ResponseBankingAgentsListData](docs/Model/ResponseBankingAgentsListData.md)
- [ResponseBranchesList](docs/Model/ResponseBranchesList.md)
- [ResponseBranchesListData](docs/Model/ResponseBranchesListData.md)
- [ResponseElectronicChannelsList](docs/Model/ResponseElectronicChannelsList.md)
- [ResponseElectronicChannelsListData](docs/Model/ResponseElectronicChannelsListData.md)
- [ResponsePhoneChannelsList](docs/Model/ResponsePhoneChannelsList.md)
- [ResponsePhoneChannelsListData](docs/Model/ResponsePhoneChannelsListData.md)
- [ResponseSharedAutomatedTellerMachinesList](docs/Model/ResponseSharedAutomatedTellerMachinesList.md)
- [ResponseSharedAutomatedTellerMachinesListData](docs/Model/ResponseSharedAutomatedTellerMachinesListData.md)
- [SharedAutomatedTellerMachines](docs/Model/SharedAutomatedTellerMachines.md)
- [SharedAutomatedTellerMachinesAvailability](docs/Model/SharedAutomatedTellerMachinesAvailability.md)
- [SharedAutomatedTellerMachinesAvailabilityStandardsInner](docs/Model/SharedAutomatedTellerMachinesAvailabilityStandardsInner.md)
- [SharedAutomatedTellerMachinesBrand](docs/Model/SharedAutomatedTellerMachinesBrand.md)
- [SharedAutomatedTellerMachinesCompanies](docs/Model/SharedAutomatedTellerMachinesCompanies.md)
- [SharedAutomatedTellerMachinesCompany](docs/Model/SharedAutomatedTellerMachinesCompany.md)
- [SharedAutomatedTellerMachinesIdentification](docs/Model/SharedAutomatedTellerMachinesIdentification.md)
- [SharedAutomatedTellerMachinesPostalAddress](docs/Model/SharedAutomatedTellerMachinesPostalAddress.md)
- [SharedAutomatedTellerMachinesServices](docs/Model/SharedAutomatedTellerMachinesServices.md)

## Authorization
Endpoints do not require authorization.

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
