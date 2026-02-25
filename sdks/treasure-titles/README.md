# OpenAPIClient-php

API de informações de operações de Títulos do Tesouro Direto Open Finance Brasil – Fase 4. API que retorna informações de operações de investimento do tipo Títulos do Tesouro Direto mantidas nas instituições transmissoras por seus clientes, incluindo dados como informações do produto, quantidade, saldos em posição do cliente e movimentações financeiras. Não possui segregação entre pessoa natural e pessoa jurídica. Requer consentimento do cliente para todos os endpoints. Devem ser considerados como escopo de exposição todos os títulos ofertados pelo Tesouro Direto. A exposição se dará por cada operação de títulos do Tesouro Direto contratada pelo cliente.


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
    $result = $apiInstance->treasureTitlesGetInvestmentsInvestmentIdBalances($investment_id, $authorization, $x_fapi_interaction_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling BalancesApi->treasureTitlesGetInvestmentsInvestmentIdBalances: ', $e->getMessage(), PHP_EOL;
}

```

## API Endpoints

All URIs are relative to *https://api.banco.com.br/open-banking/treasure-titles/v1*

Class | Method | HTTP request | Description
------------ | ------------- | ------------- | -------------
*BalancesApi* | [**treasureTitlesGetInvestmentsInvestmentIdBalances**](docs/Api/BalancesApi.md#treasuretitlesgetinvestmentsinvestmentidbalances) | **GET** /investments/{investmentId}/balances | Obtém a posição da operação de Títulos do Tesouro Direto identificada por investmentId.
*ProductIdentificationApi* | [**treasureTitlesGetInvestmentsInvestmentId**](docs/Api/ProductIdentificationApi.md#treasuretitlesgetinvestmentsinvestmentid) | **GET** /investments/{investmentId} | Obtém os dados da operação de Títulos do Tesouro Direto identificada por investmentId.
*ProductListApi* | [**treasureTitlesGetInvestments**](docs/Api/ProductListApi.md#treasuretitlesgetinvestments) | **GET** /investments | Obtém a lista de operações de Títulos do Tesouro Direto mantidas pelo cliente na instituição transmissora e para as quais ele tenha fornecido consentimento.
*TransactionsApi* | [**treasureTitlesGetInvestmentsInvestmentIdTransactions**](docs/Api/TransactionsApi.md#treasuretitlesgetinvestmentsinvestmentidtransactions) | **GET** /investments/{investmentId}/transactions | Obtém as movimentações da operação (últimos 12 meses) de Títulos do Tesouro Direto identificada por investmentId.
*TransactionsCurrentApi* | [**treasureTitlesGetInvestmentsInvestmentIdTransactionsCurrent**](docs/Api/TransactionsCurrentApi.md#treasuretitlesgetinvestmentsinvestmentidtransactionscurrent) | **GET** /investments/{investmentId}/transactions-current | Obtém as movimentações recentes da operação de Títulos do Tesouro Direto identificada por investmentId. O período a ser considerado para apresentação de movimentações será de até 7 dias - 7 dias anteriores da consulta, incluindo o dia da consulta (D-6).

## Models

- [LockedWithAdditionalProperties](docs/Model/LockedWithAdditionalProperties.md)
- [LockedWithAdditionalPropertiesErrorsInner](docs/Model/LockedWithAdditionalPropertiesErrorsInner.md)
- [LockedWithAdditionalPropertiesMeta](docs/Model/LockedWithAdditionalPropertiesMeta.md)
- [ResponseError](docs/Model/ResponseError.md)
- [ResponseErrorErrorsInner](docs/Model/ResponseErrorErrorsInner.md)
- [ResponseErrorMeta](docs/Model/ResponseErrorMeta.md)
- [ResponseTreasureTitlesBalances](docs/Model/ResponseTreasureTitlesBalances.md)
- [ResponseTreasureTitlesIdentifyProduct](docs/Model/ResponseTreasureTitlesIdentifyProduct.md)
- [ResponseTreasureTitlesListProduct](docs/Model/ResponseTreasureTitlesListProduct.md)
- [ResponseTreasureTitlesListProductData](docs/Model/ResponseTreasureTitlesListProductData.md)
- [ResponseTreasureTitlesProductTransactions](docs/Model/ResponseTreasureTitlesProductTransactions.md)
- [TreasureTitlesBalances](docs/Model/TreasureTitlesBalances.md)
- [TreasureTitlesBlockedBalance](docs/Model/TreasureTitlesBlockedBalance.md)
- [TreasureTitlesCalculation](docs/Model/TreasureTitlesCalculation.md)
- [TreasureTitlesFinancialTransactionTax](docs/Model/TreasureTitlesFinancialTransactionTax.md)
- [TreasureTitlesGrossAmount](docs/Model/TreasureTitlesGrossAmount.md)
- [TreasureTitlesIdentifyProduct](docs/Model/TreasureTitlesIdentifyProduct.md)
- [TreasureTitlesIncomeTax](docs/Model/TreasureTitlesIncomeTax.md)
- [TreasureTitlesIndexer](docs/Model/TreasureTitlesIndexer.md)
- [TreasureTitlesLinks](docs/Model/TreasureTitlesLinks.md)
- [TreasureTitlesMeta](docs/Model/TreasureTitlesMeta.md)
- [TreasureTitlesMetaTransaction](docs/Model/TreasureTitlesMetaTransaction.md)
- [TreasureTitlesNetAmount](docs/Model/TreasureTitlesNetAmount.md)
- [TreasureTitlesProductListLinks](docs/Model/TreasureTitlesProductListLinks.md)
- [TreasureTitlesProductTransaction](docs/Model/TreasureTitlesProductTransaction.md)
- [TreasureTitlesProductTransactionFinancialTransactionTax](docs/Model/TreasureTitlesProductTransactionFinancialTransactionTax.md)
- [TreasureTitlesProductTransactionIncomeTax](docs/Model/TreasureTitlesProductTransactionIncomeTax.md)
- [TreasureTitlesPurchaseUnitPrice](docs/Model/TreasureTitlesPurchaseUnitPrice.md)
- [TreasureTitlesRatePeriodicity](docs/Model/TreasureTitlesRatePeriodicity.md)
- [TreasureTitlesRemuneration](docs/Model/TreasureTitlesRemuneration.md)
- [TreasureTitlesTransactionGrossValue](docs/Model/TreasureTitlesTransactionGrossValue.md)
- [TreasureTitlesTransactionNetValue](docs/Model/TreasureTitlesTransactionNetValue.md)
- [TreasureTitlesTransactionType](docs/Model/TreasureTitlesTransactionType.md)
- [TreasureTitlesTransactionUnitPrice](docs/Model/TreasureTitlesTransactionUnitPrice.md)
- [TreasureTitlesTransactionsLinks](docs/Model/TreasureTitlesTransactionsLinks.md)
- [TreasureTitlesType](docs/Model/TreasureTitlesType.md)
- [TreasureTitlesUpdatedUnitPrice](docs/Model/TreasureTitlesUpdatedUnitPrice.md)
- [TreasureTitlesVoucherPaymentIndicator](docs/Model/TreasureTitlesVoucherPaymentIndicator.md)
- [TreasureTitlesVoucherPaymentPeriodicity](docs/Model/TreasureTitlesVoucherPaymentPeriodicity.md)

## Authorization

Authentication schemes defined for the API:
### OAuth2AuthorizationCode

- **Type**: `OAuth`
- **Flow**: `accessCode`
- **Authorization URL**: `https://authserver.example/authorization`
- **Scopes**: 
    - **treasure-titles**: Escopo necessário para acesso à API Treasure Titles - Open Finance Brasil. O controle dos endpoints específicos é feito via permissions.

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

- API version: `1.0.2`
    - Generator version: `7.17.0`
- Build package: `org.openapitools.codegen.languages.PhpClientCodegen`
