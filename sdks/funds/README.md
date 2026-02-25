# OpenAPIClient-php

API de informações de operações de Fundos de Investimento Open Finance Brasil – Fase 4. 
API que retorna informações de operações de investimento do tipo Fundos de Investimento mantidas nas instituições transmissoras por seus clientes, incluindo dados como informações do produto, quantidade, saldos em posição do cliente e movimentações financeiras. 
Não possui segregação entre pessoa natural e pessoa jurídica. Requer consentimento do cliente para todos os endpoints. 
Devem ser considerados como escopo de exposição todos os fundos de investimento classificados como: Renda Fixa, Ações, Multimercado e Cambial. 
Para identificação do produto e posição do cliente, a exposição será de forma consolidada por Fundo de Investimento. 
Para movimentações, a exposição se dará pela Ordem do Cliente, por exemplo, uma Ordem de Resgate é compartilhada como uma única movimentação, mesmo que esteja associada a diferentes Certificados (Cautelas).

As instituições podem apresentar cenários distintos no que diz respeito ao sincronismo entre posição `/balances` e movimentação `/transactions` e `/transactions-current` da API:

- Algumas instituições refletem movimentações ainda não convertidas na posição do cliente em seus canais eletrônicos. Isso implica que pode ocorrer compartilhamento de posição atualizada, cujas movimentações relacionadas serão expostas no ecossistema apenas após a conversão das mesmas;

- Outras instituições refletem na posição apenas movimentações convertidas nos seus canais eletrônicos. Isso implica que o compartilhamento da posição em relação às movimentações é feito de forma sincronizada no ecossistema.

Para o identificador do investimento (investmentId) deve ser adotado o seguinte comportamento:

- Após 12 meses sem movimentações e com quantidade de ativos zerada, o resourceId correspondente ao investmentId em questão deve passar ao status UNAVAILABLE (considerando consentimento válido);

- Nas situações em que o cliente compre novamente o ativo após um período de 12 meses sem movimentação e com quantidade de ativos zerada, o mesmo identificador (investmentId) deve ser utilizado. Especificamente para tais produtos, o status do recurso na resources deve passar de UNAVAILABLE para AVAILABLE.


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
$investment_id = 92792126019929200000000000000000000000000; // string | Identifica de forma única o relacionamento do cliente com o fundo, mantendo as regras de imutabilidade dentro da instituição transmissora.
$authorization = 'authorization_example'; // string | Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado
$x_fapi_interaction_id = 'x_fapi_interaction_id_example'; // string | Um UUID RFC4122 usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser \"espelhado\" pela transmissora (server) no cabeçalho de resposta.
$x_fapi_auth_date = 'x_fapi_auth_date_example'; // string | Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a RFC7231. Exemplo: Sun, 10 Sep 2017 19:43:31 UTC.
$x_fapi_customer_ip_address = 'x_fapi_customer_ip_address_example'; // string | O endereço IP do usuário se estiver atualmente logado com o receptor.
$x_customer_user_agent = 'x_customer_user_agent_example'; // string | Indica o user-agent que o usuário utiliza.

try {
    $result = $apiInstance->fundsGetInvestmentsInvestmentIdBalances($investment_id, $authorization, $x_fapi_interaction_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling BalancesApi->fundsGetInvestmentsInvestmentIdBalances: ', $e->getMessage(), PHP_EOL;
}

```

## API Endpoints

All URIs are relative to *https://api.banco.com.br/open-banking/funds/v1*

Class | Method | HTTP request | Description
------------ | ------------- | ------------- | -------------
*BalancesApi* | [**fundsGetInvestmentsInvestmentIdBalances**](docs/Api/BalancesApi.md#fundsgetinvestmentsinvestmentidbalances) | **GET** /investments/{investmentId}/balances | Obtém a posição da operação de Fundos de Investimento identificada por investmentId.
*ProductIdentificationApi* | [**fundsGetInvestmentsInvestmentId**](docs/Api/ProductIdentificationApi.md#fundsgetinvestmentsinvestmentid) | **GET** /investments/{investmentId} | Obtém os dados da operação de Fundos de Investimento identificada por investmentId.
*ProductListApi* | [**fundsGetInvestments**](docs/Api/ProductListApi.md#fundsgetinvestments) | **GET** /investments | Obtém a lista de operações de Fundos de Investimento mantidas pelo cliente na instituição transmissora e para as quais ele tenha fornecido consentimento.
*TransactionsApi* | [**fundsGetInvestmentsInvestmentIdTransactions**](docs/Api/TransactionsApi.md#fundsgetinvestmentsinvestmentidtransactions) | **GET** /investments/{investmentId}/transactions | Obtém as movimentações históricas (últimos 12 meses) da operação de Fundos de Investimento identificada por investmentId.
*TransactionsCurrentApi* | [**fundsGetInvestmentsInvestmentIdTransactionsCurrent**](docs/Api/TransactionsCurrentApi.md#fundsgetinvestmentsinvestmentidtransactionscurrent) | **GET** /investments/{investmentId}/transactions-current | Obtém as movimentações recentes da operação de Fundos de Investimento identificada por investmentId. O período a ser considerado para apresentação de movimentações será de até 7 dias - 7 dias anteriores da consulta, incluindo o dia da consulta (D-6).

## Models

- [EnumFundsTransactionsCurrentTransactionType](docs/Model/EnumFundsTransactionsCurrentTransactionType.md)
- [EnumFundsTransactionsCurrentType](docs/Model/EnumFundsTransactionsCurrentType.md)
- [EnumFundsTransactionsTransactionType](docs/Model/EnumFundsTransactionsTransactionType.md)
- [EnumFundsTransactionsType](docs/Model/EnumFundsTransactionsType.md)
- [FundsBalancesBlockedAmount](docs/Model/FundsBalancesBlockedAmount.md)
- [FundsBalancesFinancialTransactionTaxProvision](docs/Model/FundsBalancesFinancialTransactionTaxProvision.md)
- [FundsBalancesGrossAmount](docs/Model/FundsBalancesGrossAmount.md)
- [FundsBalancesIncomeTaxProvision](docs/Model/FundsBalancesIncomeTaxProvision.md)
- [FundsBalancesNetAmount](docs/Model/FundsBalancesNetAmount.md)
- [FundsBalancesQuotaGrossPriceValue](docs/Model/FundsBalancesQuotaGrossPriceValue.md)
- [FundsLinks](docs/Model/FundsLinks.md)
- [FundsMeta](docs/Model/FundsMeta.md)
- [FundsProductListLinks](docs/Model/FundsProductListLinks.md)
- [FundsTransactionsLinks](docs/Model/FundsTransactionsLinks.md)
- [MetaOnlyRequestDateTime](docs/Model/MetaOnlyRequestDateTime.md)
- [MetaSingle](docs/Model/MetaSingle.md)
- [MetaWithAbleAdditionalProperties](docs/Model/MetaWithAbleAdditionalProperties.md)
- [ResponseErrorMetaSingle](docs/Model/ResponseErrorMetaSingle.md)
- [ResponseErrorMetaSingleErrorsInner](docs/Model/ResponseErrorMetaSingleErrorsInner.md)
- [ResponseErrorWithAbleAdditionalProperties](docs/Model/ResponseErrorWithAbleAdditionalProperties.md)
- [ResponseFundsBalanceData](docs/Model/ResponseFundsBalanceData.md)
- [ResponseFundsBalances](docs/Model/ResponseFundsBalances.md)
- [ResponseFundsProductIdentification](docs/Model/ResponseFundsProductIdentification.md)
- [ResponseFundsProductIdentificationData](docs/Model/ResponseFundsProductIdentificationData.md)
- [ResponseFundsProductList](docs/Model/ResponseFundsProductList.md)
- [ResponseFundsProductListData](docs/Model/ResponseFundsProductListData.md)
- [ResponseFundsTransactions](docs/Model/ResponseFundsTransactions.md)
- [ResponseFundsTransactionsCurrent](docs/Model/ResponseFundsTransactionsCurrent.md)
- [ResponseFundsTransactionsCurrentData](docs/Model/ResponseFundsTransactionsCurrentData.md)
- [ResponseFundsTransactionsData](docs/Model/ResponseFundsTransactionsData.md)
- [ResponseFundsTransactionsDataFinancialTransactionTax](docs/Model/ResponseFundsTransactionsDataFinancialTransactionTax.md)
- [ResponseFundsTransactionsDataIncomeTax](docs/Model/ResponseFundsTransactionsDataIncomeTax.md)
- [ResponseFundsTransactionsDataTransactionExitFee](docs/Model/ResponseFundsTransactionsDataTransactionExitFee.md)
- [ResponseFundsTransactionsDataTransactionGrossValue](docs/Model/ResponseFundsTransactionsDataTransactionGrossValue.md)
- [ResponseFundsTransactionsDataTransactionNetValue](docs/Model/ResponseFundsTransactionsDataTransactionNetValue.md)
- [ResponseFundsTransactionsDataTransactionQuotaPrice](docs/Model/ResponseFundsTransactionsDataTransactionQuotaPrice.md)
- [ResponseFundsTransactionsDataTransactionValue](docs/Model/ResponseFundsTransactionsDataTransactionValue.md)

## Authorization

Authentication schemes defined for the API:
### OAuth2AuthorizationCode

- **Type**: `OAuth`
- **Flow**: `accessCode`
- **Authorization URL**: `https://authserver.example/authorization`
- **Scopes**: 
    - **funds**: Escopo necessário para acesso à API funds. O controle dos endpoints específicos é feito via permissions.

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
