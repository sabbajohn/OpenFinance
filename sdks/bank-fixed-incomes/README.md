# OpenAPIClient-php

API de informações de operações de Renda Fixa Bancária Open Finance Brasil (Fase 4). API que retorna informações de operações de investimento do tipo Renda Fixa Bancária (CDB/RDB, LCI e LCA) mantidas nas instituições transmissoras por seus clientes, incluindo dados como denominação do produto, rentabilidade, quantidade, prazos, saldos em posição do cliente e movimentações financeiras. Não possui segregação entre pessoa natural e pessoa jurídica. Requer consentimento do cliente para todos os endpoints. A exposição se dará por cada operação de renda fixa contratada pelo cliente.


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



// Configure OAuth2 access token for authorization: OAuth2AuthorizationCode
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\BalancesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$investment_id = 92792126019929200000000000000000000000000; // string | Identifica de forma única  o relacionamento do cliente com o produto, mantendo as regras de imutabilidade dentro da instituição transmissora.
$authorization = 'authorization_example'; // string | Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado
$x_fapi_interaction_id = 'x_fapi_interaction_id_example'; // string | Um UUID RFC4122 usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser \"espelhado\" pela transmissora (server) no cabeçalho de resposta.
$x_fapi_auth_date = 'x_fapi_auth_date_example'; // string | Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC
$x_fapi_customer_ip_address = 'x_fapi_customer_ip_address_example'; // string | O endereço IP do usuário se estiver atualmente logado com o receptor.
$x_customer_user_agent = 'x_customer_user_agent_example'; // string | Indica o user-agent que o usuário utiliza.

try {
    $result = $apiInstance->banktFixedIncomesGetInvestmentsInvestmentIdBalances($investment_id, $authorization, $x_fapi_interaction_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling BalancesApi->banktFixedIncomesGetInvestmentsInvestmentIdBalances: ', $e->getMessage(), PHP_EOL;
}

```

## API Endpoints

All URIs are relative to *https://api.banco.com.br/open-banking/bank-fixed-incomes/v1*

Class | Method | HTTP request | Description
------------ | ------------- | ------------- | -------------
*BalancesApi* | [**banktFixedIncomesGetInvestmentsInvestmentIdBalances**](docs/Api/BalancesApi.md#banktfixedincomesgetinvestmentsinvestmentidbalances) | **GET** /investments/{investmentId}/balances | Obtém a posição da operação de Renda Fixa Bancária identificada por investmentId.
*ProductIdentificationApi* | [**banktFixedIncomesGetInvestmentsInvestmentId**](docs/Api/ProductIdentificationApi.md#banktfixedincomesgetinvestmentsinvestmentid) | **GET** /investments/{investmentId} | Obtém os dados da operação de Renda Fixa Bancária identificada por investmentId.
*ProductListApi* | [**banktFixedIncomesGetInvestments**](docs/Api/ProductListApi.md#banktfixedincomesgetinvestments) | **GET** /investments | Obtém a lista de operações de Renda Fixa Bancária mantidas pelo cliente na instituição transmissora e para as quais ele tenha fornecido consentimento.
*TransactionsApi* | [**banktFixedIncomesGetInvestmentsInvestmentIdTransactions**](docs/Api/TransactionsApi.md#banktfixedincomesgetinvestmentsinvestmentidtransactions) | **GET** /investments/{investmentId}/transactions | Obtém as movimentações históricas (últimos 12 meses) da operação de Renda Fixa Bancária identificada por investmentId.
*TransactionsCurrentApi* | [**banktFixedIncomesGetInvestmentsInvestmentIdTransactionsCurrent**](docs/Api/TransactionsCurrentApi.md#banktfixedincomesgetinvestmentsinvestmentidtransactionscurrent) | **GET** /investments/{investmentId}/transactions-current | Obtém as movimentações recentes da operação de Renda Fixa Bancária identificada por investmentId. O período a ser considerado para apresentação de movimentações será de até 7 dias - 7 dias anteriores da consulta, incluindo o dia da consulta (D-6).

## Models

- [BankFixedIncomeLinks](docs/Model/BankFixedIncomeLinks.md)
- [BankFixedIncomeProductListLinks](docs/Model/BankFixedIncomeProductListLinks.md)
- [BankFixedIncomeTransactionsLinks](docs/Model/BankFixedIncomeTransactionsLinks.md)
- [BankFixedIncomesMeta](docs/Model/BankFixedIncomesMeta.md)
- [BankFixedIncomesProductMovement](docs/Model/BankFixedIncomesProductMovement.md)
- [BankFixedIncomesProductMovementFinancialTransactionTax](docs/Model/BankFixedIncomesProductMovementFinancialTransactionTax.md)
- [BankFixedIncomesProductMovementIncomeTax](docs/Model/BankFixedIncomesProductMovementIncomeTax.md)
- [BankFixedIncomesProductMovementTransactionGrossValue](docs/Model/BankFixedIncomesProductMovementTransactionGrossValue.md)
- [BankFixedIncomesProductMovementTransactionNetValue](docs/Model/BankFixedIncomesProductMovementTransactionNetValue.md)
- [BankFixedIncomesProductMovementTransactionUnitPrice](docs/Model/BankFixedIncomesProductMovementTransactionUnitPrice.md)
- [BankFixedIncomesTransactionsMeta](docs/Model/BankFixedIncomesTransactionsMeta.md)
- [EnumBankFixedIncomeIndexer](docs/Model/EnumBankFixedIncomeIndexer.md)
- [EnumBankFixedIncomeMovementType](docs/Model/EnumBankFixedIncomeMovementType.md)
- [EnumBankFixedIncomeTransactionType](docs/Model/EnumBankFixedIncomeTransactionType.md)
- [EnumCalculation](docs/Model/EnumCalculation.md)
- [EnumInvestmentType](docs/Model/EnumInvestmentType.md)
- [EnumRatePeriodicity](docs/Model/EnumRatePeriodicity.md)
- [EnumRateType](docs/Model/EnumRateType.md)
- [IdentifyProduct](docs/Model/IdentifyProduct.md)
- [IdentifyProductIssueUnitPrice](docs/Model/IdentifyProductIssueUnitPrice.md)
- [Remuneration](docs/Model/Remuneration.md)
- [ResponseBankFixedIncomesBalances](docs/Model/ResponseBankFixedIncomesBalances.md)
- [ResponseBankFixedIncomesBalancesData](docs/Model/ResponseBankFixedIncomesBalancesData.md)
- [ResponseBankFixedIncomesBalancesDataBlockedBalance](docs/Model/ResponseBankFixedIncomesBalancesDataBlockedBalance.md)
- [ResponseBankFixedIncomesBalancesDataFinancialTransactionTax](docs/Model/ResponseBankFixedIncomesBalancesDataFinancialTransactionTax.md)
- [ResponseBankFixedIncomesBalancesDataGrossAmount](docs/Model/ResponseBankFixedIncomesBalancesDataGrossAmount.md)
- [ResponseBankFixedIncomesBalancesDataIncomeTax](docs/Model/ResponseBankFixedIncomesBalancesDataIncomeTax.md)
- [ResponseBankFixedIncomesBalancesDataNetAmount](docs/Model/ResponseBankFixedIncomesBalancesDataNetAmount.md)
- [ResponseBankFixedIncomesBalancesDataPurchaseUnitPrice](docs/Model/ResponseBankFixedIncomesBalancesDataPurchaseUnitPrice.md)
- [ResponseBankFixedIncomesBalancesDataUpdatedUnitPrice](docs/Model/ResponseBankFixedIncomesBalancesDataUpdatedUnitPrice.md)
- [ResponseBankFixedIncomesProductIdentification](docs/Model/ResponseBankFixedIncomesProductIdentification.md)
- [ResponseBankFixedIncomesProductList](docs/Model/ResponseBankFixedIncomesProductList.md)
- [ResponseBankFixedIncomesProductListDataInner](docs/Model/ResponseBankFixedIncomesProductListDataInner.md)
- [ResponseBankFixedIncomesTransactions](docs/Model/ResponseBankFixedIncomesTransactions.md)
- [ResponseError](docs/Model/ResponseError.md)
- [ResponseErrorErrorsInner](docs/Model/ResponseErrorErrorsInner.md)
- [ResponseErrorMeta](docs/Model/ResponseErrorMeta.md)
- [ResponseErrorMetaSingle](docs/Model/ResponseErrorMetaSingle.md)
- [ResponseErrorMetaSingleErrorsInner](docs/Model/ResponseErrorMetaSingleErrorsInner.md)
- [ResponseErrorMetaSingleMeta](docs/Model/ResponseErrorMetaSingleMeta.md)

## Authorization

Authentication schemes defined for the API:
### OAuth2AuthorizationCode

- **Type**: `OAuth`
- **Flow**: `accessCode`
- **Authorization URL**: `https://authserver.example/authorization`
- **Scopes**: 
    - **bank-fixed-incomes**: Escopo necessário para acesso à API Bank Fixed Incomes. O controle dos endpoints específicos é feito via permissions.

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

- API version: `1.0.4`
    - Generator version: `7.17.0`
- Build package: `org.openapitools.codegen.languages.PhpClientCodegen`
