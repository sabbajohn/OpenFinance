# OpenAPIClient-php

API de informações de operações de empréstimos do Open Finance Brasil – Fase 2. API que retorna informações de operações de crédito do tipo empréstimo, mantidas nas instituições transmissoras por seus clientes, incluindo dados como denominação, modalidade, número do contrato, tarifas, prazo, prestações, pagamentos(ao menos para os últimos 12 meses), amortizações, garantias, encargos e taxas de juros remuneratórios.
Não possui segregação entre pessoa natural e pessoa jurídica.
Requer consentimento do cliente para todos os `endpoints.`

# Orientações
A `Role`  do diretório de participantes relacionada à presente API é a `DADOS`.\\
Para todos os `endpoints` desta API é previsto o envio de um `token` através do header `Authorization`.\\
Este token deverá estar relacionado ao consentimento (`consentId`) mantido na instituição transmissora dos dados, o qual permitirá a pesquisa e retorno, na API em questão, dos
dados relacionados ao `consentId` específico relacionado.\\
Os dados serão devolvidos na consulta desde que o `consentId` relacionado corresponda a um consentimento válido e com o status `AUTHORISED`.\\
É também necessário que o recurso em questão (conta, contrato, etc) esteja disponível na instituição transmissora (ou seja, sem boqueios de qualquer natureza e com todas as autorizações/consentimentos já autorizados).\\
Além disso as `permissions` necessárias deverão ter sido solicitadas quando da criação do consentimento relacionado (`consentId`).\\
Relacionamos a seguir as `permissions` necessárias para a consulta de dados em cada `endpoint` da presente API.   

## Permissions necessárias para a API Loans

Para cada um dos paths desta API, além dos escopos (`scopes`) indicados existem `permissions` que deverão ser observadas:

### `/contracts`
  - permissions:
    - GET: **LOANS_READ**
### `/contracts/{contractId}`
  - permissions:
    - GET **LOANS_READ**
### `/contracts/{contractId}/warranties`
  - permissions:
    - GET: **LOANS_WARRANTIES_READ**
### `/contracts/{contractId}/scheduled-instalments`
  - permissions:
    - GET: **LOANS_SCHEDULED_INSTALMENTS_READ**
### `/contracts/{contractId}/payments`
  - permissions:
    - GET: **LOANS_PAYMENTS_READ**


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



// Configure OAuth2 access token for authorization: OAuth2Security
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');



$apiInstance = new OpenAPI\Client\Api\LoansApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$authorization = 'authorization_example'; // string | Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado
$x_fapi_interaction_id = d78fc4e5-37ca-4da3-adf2-9b082bf92280; // string | Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser “espelhado” pela transmissora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a transmissora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A receptora deve acatar o valor recebido da transmissora.
$x_fapi_auth_date = 'x_fapi_auth_date_example'; // string | Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC
$x_fapi_customer_ip_address = 'x_fapi_customer_ip_address_example'; // string | O endereço IP do usuário se estiver atualmente logado com o receptor.
$x_customer_user_agent = 'x_customer_user_agent_example'; // string | Indica o user-agent que o usuário utiliza.
$page = 1; // int | Número da página que está sendo requisitada (o valor da primeira página é 1).
$page_size = 25; // int | Quantidade total de registros por páginas.
$pagination_key = 'pagination_key_example'; // string | Identificador de rechamada, utilizado para evitar a contagem de chamadas ao endpoint durante a paginação.

try {
    $result = $apiInstance->loansGetContracts($authorization, $x_fapi_interaction_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $page, $page_size, $pagination_key);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling LoansApi->loansGetContracts: ', $e->getMessage(), PHP_EOL;
}

```

## API Endpoints

All URIs are relative to *https://api.banco.com.br/open-banking/loans/v2*

Class | Method | HTTP request | Description
------------ | ------------- | ------------- | -------------
*LoansApi* | [**loansGetContracts**](docs/Api/LoansApi.md#loansgetcontracts) | **GET** /contracts | Conjunto de informações  de contratos de empréstimo mantidos pelo cliente na instituição transmissora e para os quais ele tenha fornecido consentimento
*LoansApi* | [**loansGetContractsContractId**](docs/Api/LoansApi.md#loansgetcontractscontractid) | **GET** /contracts/{contractId} | Obtém os dados do contrato de empréstimo identificado por contractId
*LoansApi* | [**loansGetContractsContractIdPayments**](docs/Api/LoansApi.md#loansgetcontractscontractidpayments) | **GET** /contracts/{contractId}/payments | Obtém os dados de pagamentos do contrato de empréstimo identificado por contractId
*LoansApi* | [**loansGetContractsContractIdScheduledInstalments**](docs/Api/LoansApi.md#loansgetcontractscontractidscheduledinstalments) | **GET** /contracts/{contractId}/scheduled-instalments | Obtém os dados do cronograma de parcelas do contrato de empréstimo identificado por contractId
*LoansApi* | [**loansGetContractsContractIdWarranties**](docs/Api/LoansApi.md#loansgetcontractscontractidwarranties) | **GET** /contracts/{contractId}/warranties | Obtém a lista de garantias vinculadas ao contrato de empréstimo identificado por contractId

## Models

- [EnumContractAmortizationScheduled](docs/Model/EnumContractAmortizationScheduled.md)
- [EnumContractCalculation](docs/Model/EnumContractCalculation.md)
- [EnumContractFeeCharge](docs/Model/EnumContractFeeCharge.md)
- [EnumContractFeeChargeType](docs/Model/EnumContractFeeChargeType.md)
- [EnumContractFinanceChargeType](docs/Model/EnumContractFinanceChargeType.md)
- [EnumContractInstalmentPeriodicity](docs/Model/EnumContractInstalmentPeriodicity.md)
- [EnumContractInterestRateType](docs/Model/EnumContractInterestRateType.md)
- [EnumContractProductSubTypeCategory](docs/Model/EnumContractProductSubTypeCategory.md)
- [EnumContractProductSubTypeLoans](docs/Model/EnumContractProductSubTypeLoans.md)
- [EnumContractProductTypeLoans](docs/Model/EnumContractProductTypeLoans.md)
- [EnumContractReferentialRateIndexerSubType](docs/Model/EnumContractReferentialRateIndexerSubType.md)
- [EnumContractReferentialRateIndexerType](docs/Model/EnumContractReferentialRateIndexerType.md)
- [EnumContractTaxPeriodicity](docs/Model/EnumContractTaxPeriodicity.md)
- [EnumContractTaxType](docs/Model/EnumContractTaxType.md)
- [EnumWarrantySubType](docs/Model/EnumWarrantySubType.md)
- [EnumWarrantyType](docs/Model/EnumWarrantyType.md)
- [Links](docs/Model/Links.md)
- [LinksNonPaginated](docs/Model/LinksNonPaginated.md)
- [LoansBalloonPayment](docs/Model/LoansBalloonPayment.md)
- [LoansBalloonPaymentAmount](docs/Model/LoansBalloonPaymentAmount.md)
- [LoansChargeOverParcel](docs/Model/LoansChargeOverParcel.md)
- [LoansContract](docs/Model/LoansContract.md)
- [LoansContractInterestRate](docs/Model/LoansContractInterestRate.md)
- [LoansContractedFee](docs/Model/LoansContractedFee.md)
- [LoansFeeOverParcel](docs/Model/LoansFeeOverParcel.md)
- [LoansFinanceCharge](docs/Model/LoansFinanceCharge.md)
- [LoansInstalments](docs/Model/LoansInstalments.md)
- [LoansListContract](docs/Model/LoansListContract.md)
- [LoansPayments](docs/Model/LoansPayments.md)
- [LoansReleases](docs/Model/LoansReleases.md)
- [LoansReleasesOverParcel](docs/Model/LoansReleasesOverParcel.md)
- [LoansWarranties](docs/Model/LoansWarranties.md)
- [Meta](docs/Model/Meta.md)
- [MetaError](docs/Model/MetaError.md)
- [MetaNonPaginated](docs/Model/MetaNonPaginated.md)
- [ResponseErrorWithAbleAdditionalProperties](docs/Model/ResponseErrorWithAbleAdditionalProperties.md)
- [ResponseErrorWithAbleAdditionalPropertiesErrorsInner](docs/Model/ResponseErrorWithAbleAdditionalPropertiesErrorsInner.md)
- [ResponseLoansContract](docs/Model/ResponseLoansContract.md)
- [ResponseLoansContractList](docs/Model/ResponseLoansContractList.md)
- [ResponseLoansInstalments](docs/Model/ResponseLoansInstalments.md)
- [ResponseLoansPayments](docs/Model/ResponseLoansPayments.md)
- [ResponseLoansWarranties](docs/Model/ResponseLoansWarranties.md)

## Authorization

Authentication schemes defined for the API:
### OpenId

### OAuth2Security

- **Type**: `OAuth`
- **Flow**: `accessCode`
- **Authorization URL**: `https://authserver.example/authorization`
- **Scopes**: 
    - **loans**: Escopo necessário para acesso à API Loans. O controle dos endpoints específicos é feito via permissions.

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

- API version: `2.5.0`
    - Generator version: `7.17.0`
- Build package: `org.openapitools.codegen.languages.PhpClientCodegen`
