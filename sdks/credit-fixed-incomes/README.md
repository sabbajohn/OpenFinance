# OpenAPIClient-php

API de informações de operações de Renda Fixa Crédito do Open Finance Brasil – Fase 4. API que retorna informações de operações de investimento do tipo Renda Fixa Crédito (Debêntures, CRI/CRA) mantidas nas instituições transmissoras por seus clientes, incluindo dados como denominação do produto, rentabilidade, quantidade, prazos, saldos em posição do cliente e movimentações financeiras.
Não possui segregação entre pessoa natural e pessoa jurídica. Requer consentimento do cliente para todos os endpoints. A exposição se dará por cada operação de renda fixa contratada pelo cliente.


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
$x_fapi_auth_date = 'x_fapi_auth_date_example'; // string | Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a RFC7231. Exemplo: Sun, 10 Sep 2017 19:43:31 UTC.
$x_fapi_customer_ip_address = 'x_fapi_customer_ip_address_example'; // string | O endereço IP do usuário se estiver atualmente logado com o receptor.
$x_customer_user_agent = 'x_customer_user_agent_example'; // string | Indica o user-agent que o usuário utiliza.

try {
    $result = $apiInstance->creditFixedIncomesGetInvestmentsInvestmentIdBalances($investment_id, $authorization, $x_fapi_interaction_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling BalancesApi->creditFixedIncomesGetInvestmentsInvestmentIdBalances: ', $e->getMessage(), PHP_EOL;
}

```

## API Endpoints

All URIs are relative to *https://api.banco.com.br/open-banking/credit-fixed-incomes/v1*

Class | Method | HTTP request | Description
------------ | ------------- | ------------- | -------------
*BalancesApi* | [**creditFixedIncomesGetInvestmentsInvestmentIdBalances**](docs/Api/BalancesApi.md#creditfixedincomesgetinvestmentsinvestmentidbalances) | **GET** /investments/{investmentId}/balances | Obtém a posição da operação de Renda Fixa Crédito identificada por investmentId.
*ProductIdentificationApi* | [**creditFixedIncomesGetInvestmentsInvestmentId**](docs/Api/ProductIdentificationApi.md#creditfixedincomesgetinvestmentsinvestmentid) | **GET** /investments/{investmentId} | Obtém os dados da operação de Renda Fixa Crédito identificada por investmentId.
*ProductListApi* | [**creditFixedIncomesGetInvestments**](docs/Api/ProductListApi.md#creditfixedincomesgetinvestments) | **GET** /investments | Obtém a lista de operações de Renda Fixa Crédito mantidas pelo cliente na instituição transmissora e para as quais ele tenha fornecido consentimento.
*TransactionsApi* | [**creditFixedIncomesGetInvestmentsInvestmentIdTransactions**](docs/Api/TransactionsApi.md#creditfixedincomesgetinvestmentsinvestmentidtransactions) | **GET** /investments/{investmentId}/transactions | Obtém as movimentações históricas (últimos 12 meses) da operação de Renda Fixa Crédito identificada por investmentId.
*TransactionsCurrentApi* | [**creditFixedIncomesGetInvestmentsInvestmentIdTransactionsCurrent**](docs/Api/TransactionsCurrentApi.md#creditfixedincomesgetinvestmentsinvestmentidtransactionscurrent) | **GET** /investments/{investmentId}/transactions-current | Obtém as movimentações recentes da operação de Renda Fixa Crédito identificada por investmentId. O período a ser considerado para apresentação de movimentações será de até 7 dias - 7 dias anteriores da consulta, incluindo o dia da consulta (D-6).

## Models

- [BlockedBalance](docs/Model/BlockedBalance.md)
- [CreditFixedIdentification](docs/Model/CreditFixedIdentification.md)
- [CreditFixedIncomesLinks](docs/Model/CreditFixedIncomesLinks.md)
- [CreditFixedIncomesMeta](docs/Model/CreditFixedIncomesMeta.md)
- [CreditFixedIncomesMetaTransactions](docs/Model/CreditFixedIncomesMetaTransactions.md)
- [CreditFixedIncomesProductListLinks](docs/Model/CreditFixedIncomesProductListLinks.md)
- [CreditFixedIncomesTransactions](docs/Model/CreditFixedIncomesTransactions.md)
- [CreditFixedIncomesTransactionsLinks](docs/Model/CreditFixedIncomesTransactionsLinks.md)
- [CreditFixedList](docs/Model/CreditFixedList.md)
- [EnumCalculation](docs/Model/EnumCalculation.md)
- [EnumIndexer](docs/Model/EnumIndexer.md)
- [EnumInvestimentType](docs/Model/EnumInvestimentType.md)
- [EnumRatePeriodicity](docs/Model/EnumRatePeriodicity.md)
- [EnumRateType](docs/Model/EnumRateType.md)
- [EnumTaxExemptProduct](docs/Model/EnumTaxExemptProduct.md)
- [FinancialTransactionTax](docs/Model/FinancialTransactionTax.md)
- [Fine](docs/Model/Fine.md)
- [GrossAmount](docs/Model/GrossAmount.md)
- [IncomeTax](docs/Model/IncomeTax.md)
- [IssueUnitPrice](docs/Model/IssueUnitPrice.md)
- [LatePayment](docs/Model/LatePayment.md)
- [NetAmount](docs/Model/NetAmount.md)
- [PurchaseUnitPrice](docs/Model/PurchaseUnitPrice.md)
- [Remuneration](docs/Model/Remuneration.md)
- [ResponseCreditFixedIncomesBalances](docs/Model/ResponseCreditFixedIncomesBalances.md)
- [ResponseCreditFixedIncomesBalancesData](docs/Model/ResponseCreditFixedIncomesBalancesData.md)
- [ResponseCreditFixedIncomesProductIdentification](docs/Model/ResponseCreditFixedIncomesProductIdentification.md)
- [ResponseCreditFixedIncomesProductList](docs/Model/ResponseCreditFixedIncomesProductList.md)
- [ResponseCreditFixedIncomesTransactions](docs/Model/ResponseCreditFixedIncomesTransactions.md)
- [ResponseCreditFixedIncomesTransactionsCurrent](docs/Model/ResponseCreditFixedIncomesTransactionsCurrent.md)
- [ResponseCreditFixedIncomesTransactionsCurrentDataInner](docs/Model/ResponseCreditFixedIncomesTransactionsCurrentDataInner.md)
- [ResponseCreditFixedIncomesTransactionsCurrentDataInnerFinancialTransactionTax](docs/Model/ResponseCreditFixedIncomesTransactionsCurrentDataInnerFinancialTransactionTax.md)
- [ResponseCreditFixedIncomesTransactionsCurrentDataInnerIncomeTax](docs/Model/ResponseCreditFixedIncomesTransactionsCurrentDataInnerIncomeTax.md)
- [ResponseCreditFixedIncomesTransactionsCurrentDataInnerTransactionUnitPrice](docs/Model/ResponseCreditFixedIncomesTransactionsCurrentDataInnerTransactionUnitPrice.md)
- [ResponseError](docs/Model/ResponseError.md)
- [ResponseErrorMeta](docs/Model/ResponseErrorMeta.md)
- [ResponseErrorMetaSingle](docs/Model/ResponseErrorMetaSingle.md)
- [ResponseErrorMetaSingleErrorsInner](docs/Model/ResponseErrorMetaSingleErrorsInner.md)
- [ResponseErrorMetaSingleMeta](docs/Model/ResponseErrorMetaSingleMeta.md)
- [TransactionGrossValue](docs/Model/TransactionGrossValue.md)
- [TransactionNetValue](docs/Model/TransactionNetValue.md)
- [TransactionType](docs/Model/TransactionType.md)
- [Type](docs/Model/Type.md)
- [UpdatedUnitPrice](docs/Model/UpdatedUnitPrice.md)
- [VoucherPaymentIndicator](docs/Model/VoucherPaymentIndicator.md)
- [VoucherPaymentPeriodicity](docs/Model/VoucherPaymentPeriodicity.md)

## Authorization

Authentication schemes defined for the API:
### OAuth2AuthorizationCode

- **Type**: `OAuth`
- **Flow**: `accessCode`
- **Authorization URL**: `https://authserver.example/authorization`
- **Scopes**: 
    - **credit-fixed-incomes**: Escopo necessário para acesso à API Credit-Fixed-Incomes. O controle dos endpoints específicos é feito via permissions.

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

- API version: `1.0.3`
    - Generator version: `7.17.0`
- Build package: `org.openapitools.codegen.languages.PhpClientCodegen`
