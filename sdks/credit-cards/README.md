# OpenAPIClient-php

API de contas de pagamento pós-pagas do Open Finance Brasil – Fase 2.
API que retorna informações de contas de pagamento pós-paga mantidas nas instituições transmissoras por seus clientes, incluindo dados como denominação, produto, bandeira, limites de crédito, informações sobre transações de pagamento efetuadas e faturas.

Não possui segregação entre pessoa natural e pessoa jurídica.\\
Requer consentimento do cliente para todos os `endpoints`.

# Orientações
A `Role`  do diretório de participantes relacionada à presente API é a `DADOS`.\\
Para todos os `endpoints` desta API é previsto o envio de um `token` através do header `Authorization`.\\
Este token deverá estar relacionado ao consentimento (`consentId`) mantido na instituição transmissora dos dados, o qual permitirá a pesquisa e retorno, na API em questão, dos
dados relacionados ao `consentId` específico relacionado.\\
Os dados serão devolvidos na consulta desde que o `consentId` relacionado corresponda a um consentimento válido e com o status `AUTHORISED`.\\
É também necessário que o recurso em questão (conta, contrato, etc) esteja disponível na instituição transmissora (ou seja, sem boqueios de qualquer natureza e com todas as autorizações/consentimentos já autorizados).\\
Além disso as `permissions` necessárias deverão ter sido solicitadas quando da criação do consentimento relacionado (`consentId`).\\
Relacionamos a seguir as `permissions` necessárias para a consulta de dados em cada `endpoint` da presente API.\\
### `/accounts/{creditCardAccountId}/bills`
  - description:
    - Só deve ser informada uma fatura já fechada.
    - Qualquer pagamento deve ser contado para a última fatura fechada.
### `/accounts/{creditCardAccountId}/bills/{billId}/transactions`
  - description:
    - A lista a retornar se refere a transações após conciliado
### `/accounts/{creditCardAccountId}/transactions`
  - description:
    - A lista a retornar se refere a transações após conciliado

## Permissions necessárias para a API Credit-cards-accounts

Para cada um dos paths desta API, além dos escopos (`scopes`) indicados existem `permissions` que deverão ser observadas:

### `/accounts`
  - permissions:
    - GET: **CREDIT_CARDS_ACCOUNTS_READ**
### `/accounts/{creditCardAccountId}`
  - permissions:
    - GET: **CREDIT_CARDS_ACCOUNTS_READ**
### `/accounts/{creditCardAccountId}/bills`
  - permissions:
    - GET: **CREDIT_CARDS_ACCOUNTS_BILLS_READ**
### `/accounts/{creditCardAccountId}/bills/{billId}/transactions`
  - permissions:
    - GET: **CREDIT_CARDS_ACCOUNTS_BILLS_TRANSACTIONS_READ**
### `/accounts/{creditCardAccountId}/limits`
  - permissions:
    - GET: **CREDIT_CARDS_ACCOUNTS_LIMITS_READ**
### `/accounts/{creditCardAccountId}/transactions`
  - permissions:
    - GET: **CREDIT_CARDS_ACCOUNTS_TRANSACTIONS_READ**
### `/accounts/{creditCardAccountId}/transactions-current`
  - permissions:
    - GET: **CREDIT_CARDS_ACCOUNTS_TRANSACTIONS_READ**

## Data de imutabilidade por tipo de transação
O identificador de transações de cartão de crédito é de envio obrigatório no Open Finance Brasil. De acordo com o tipo da transação deve haver o envio de um identificador único, estável e imutável, conforme tabela abaixo.
```
  |-------------------|-------------------------|-----------------------|
  | Tipo de Transação | Data da Obrigatoriedade | Data da Imutabilidade |
  |-------------------|-------------------------|-----------------------|
  | PAGAMENTO         | DO                      | Fatura fechada        |
  |-------------------|-------------------------|-----------------------|
  | TARIFA            | DO                      | Fatura fechada        |
  |-------------------|-------------------------|-----------------------|
  | OPERACOES_CRED    | DO                      | Fatura fechada        |
  |-------------------|-------------------------|-----------------------|
  | ESTORNO           | DO                      | Fatura fechada        |
  |-------------------|-------------------------|-----------------------|
  | CASHBACK          | DO                      | Fatura fechada        |
  |-------------------|-------------------------|-----------------------|
  | OUTROS            | DO                      | Fatura fechada        |
  |-------------------|-------------------------|-----------------------|
  ```


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



$apiInstance = new OpenAPI\Client\Api\CreditCardApi(
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
    $result = $apiInstance->creditCardsGetAccounts($authorization, $x_fapi_interaction_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $page, $page_size, $pagination_key);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling CreditCardApi->creditCardsGetAccounts: ', $e->getMessage(), PHP_EOL;
}

```

## API Endpoints

All URIs are relative to *https://api.banco.com.br/open-banking/credit-cards-accounts/v2*

Class | Method | HTTP request | Description
------------ | ------------- | ------------- | -------------
*CreditCardApi* | [**creditCardsGetAccounts**](docs/Api/CreditCardApi.md#creditcardsgetaccounts) | **GET** /accounts | Conjunto de informações das Contas de pagamento pós paga
*CreditCardApi* | [**creditCardsGetAccountsCreditCardAccountId**](docs/Api/CreditCardApi.md#creditcardsgetaccountscreditcardaccountid) | **GET** /accounts/{creditCardAccountId} | Obtém os dados de identificação da conta identificada por creditCardAccountId.
*CreditCardApi* | [**creditCardsGetAccountsCreditCardAccountIdBills**](docs/Api/CreditCardApi.md#creditcardsgetaccountscreditcardaccountidbills) | **GET** /accounts/{creditCardAccountId}/bills | Obtém a lista de faturas da conta identificada por creditCardAccountId.
*CreditCardApi* | [**creditCardsGetAccountsCreditCardAccountIdBillsBillIdTransactions**](docs/Api/CreditCardApi.md#creditcardsgetaccountscreditcardaccountidbillsbillidtransactions) | **GET** /accounts/{creditCardAccountId}/bills/{billId}/transactions | Obtém a lista de transações da conta identificada por creditCardAccountId e billId.
*CreditCardApi* | [**creditCardsGetAccountsCreditCardAccountIdLimits**](docs/Api/CreditCardApi.md#creditcardsgetaccountscreditcardaccountidlimits) | **GET** /accounts/{creditCardAccountId}/limits | Obtém os limites da conta identificada por creditCardAccountId.
*CreditCardApi* | [**creditCardsGetAccountsCreditCardAccountIdTransactions**](docs/Api/CreditCardApi.md#creditcardsgetaccountscreditcardaccountidtransactions) | **GET** /accounts/{creditCardAccountId}/transactions | Obtém a lista de transações da conta identificada por creditCardAccountId.
*CreditCardApi* | [**creditCardsGetAccountsCreditCardAccountIdTransactionsCurrent**](docs/Api/CreditCardApi.md#creditcardsgetaccountscreditcardaccountidtransactionscurrent) | **GET** /accounts/{creditCardAccountId}/transactions-current | Obtém a lista de transações recentes (últimos 7 dias) da conta identificada por creditCardAccountId.

## Models

- [CreditCardAccountsBillMinimumAmount](docs/Model/CreditCardAccountsBillMinimumAmount.md)
- [CreditCardAccountsBillsData](docs/Model/CreditCardAccountsBillsData.md)
- [CreditCardAccountsBillsFinanceCharge](docs/Model/CreditCardAccountsBillsFinanceCharge.md)
- [CreditCardAccountsBillsPayment](docs/Model/CreditCardAccountsBillsPayment.md)
- [CreditCardAccountsBillsTransactions](docs/Model/CreditCardAccountsBillsTransactions.md)
- [CreditCardAccountsData](docs/Model/CreditCardAccountsData.md)
- [CreditCardAccountsLimitsData](docs/Model/CreditCardAccountsLimitsData.md)
- [CreditCardAccountsLimitsDataCustomizedLimitAmount](docs/Model/CreditCardAccountsLimitsDataCustomizedLimitAmount.md)
- [CreditCardAccountsTransaction](docs/Model/CreditCardAccountsTransaction.md)
- [CreditCardAccountsTransactionAmount](docs/Model/CreditCardAccountsTransactionAmount.md)
- [CreditCardAccountsTransactionBrazilianAmount](docs/Model/CreditCardAccountsTransactionBrazilianAmount.md)
- [CreditCardsAccountPaymentMethod](docs/Model/CreditCardsAccountPaymentMethod.md)
- [CreditCardsAccountsIdentificationData](docs/Model/CreditCardsAccountsIdentificationData.md)
- [CreditCardsAvailableAmount](docs/Model/CreditCardsAvailableAmount.md)
- [CreditCardsBillTotalAmount](docs/Model/CreditCardsBillTotalAmount.md)
- [CreditCardsGetAccountsCreditCardAccountIdBillsBillIdTransactions200Response](docs/Model/CreditCardsGetAccountsCreditCardAccountIdBillsBillIdTransactions200Response.md)
- [CreditCardsLimitAmount](docs/Model/CreditCardsLimitAmount.md)
- [CreditCardsUsedAmount](docs/Model/CreditCardsUsedAmount.md)
- [EnumCreditCardAccountFee](docs/Model/EnumCreditCardAccountFee.md)
- [EnumCreditCardAccountNetwork](docs/Model/EnumCreditCardAccountNetwork.md)
- [EnumCreditCardAccountsBillingValueType](docs/Model/EnumCreditCardAccountsBillingValueType.md)
- [EnumCreditCardAccountsConsolidationType](docs/Model/EnumCreditCardAccountsConsolidationType.md)
- [EnumCreditCardAccountsFinanceChargeType](docs/Model/EnumCreditCardAccountsFinanceChargeType.md)
- [EnumCreditCardAccountsLineLimitType](docs/Model/EnumCreditCardAccountsLineLimitType.md)
- [EnumCreditCardAccountsOtherCreditType](docs/Model/EnumCreditCardAccountsOtherCreditType.md)
- [EnumCreditCardAccountsPaymentMode](docs/Model/EnumCreditCardAccountsPaymentMode.md)
- [EnumCreditCardAccountsPaymentType](docs/Model/EnumCreditCardAccountsPaymentType.md)
- [EnumCreditCardAccountsProductType](docs/Model/EnumCreditCardAccountsProductType.md)
- [EnumCreditCardTransactionType](docs/Model/EnumCreditCardTransactionType.md)
- [EnumCreditDebitIndicator](docs/Model/EnumCreditDebitIndicator.md)
- [Links](docs/Model/Links.md)
- [Meta](docs/Model/Meta.md)
- [MetaOnlyRequestDateTime](docs/Model/MetaOnlyRequestDateTime.md)
- [ResponseCreditCardAccountsBills](docs/Model/ResponseCreditCardAccountsBills.md)
- [ResponseCreditCardAccountsIdentification](docs/Model/ResponseCreditCardAccountsIdentification.md)
- [ResponseCreditCardAccountsLimits](docs/Model/ResponseCreditCardAccountsLimits.md)
- [ResponseCreditCardAccountsList](docs/Model/ResponseCreditCardAccountsList.md)
- [ResponseCreditCardAccountsTransactions](docs/Model/ResponseCreditCardAccountsTransactions.md)
- [ResponseError](docs/Model/ResponseError.md)
- [ResponseErrorMetaSingle](docs/Model/ResponseErrorMetaSingle.md)
- [ResponseErrorMetaSingleErrorsInner](docs/Model/ResponseErrorMetaSingleErrorsInner.md)
- [TransactionsLinks](docs/Model/TransactionsLinks.md)

## Authorization

Authentication schemes defined for the API:
### OpenId

### OAuth2Security

- **Type**: `OAuth`
- **Flow**: `accessCode`
- **Authorization URL**: `https://authserver.example/authorization`
- **Scopes**: 
    - **credit-cards-accounts**: Escopo necessário para acesso à API Credit-cards-accounts. O controle dos endpoints específicos é feito via permissions.

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

- API version: `2.3.1`
    - Generator version: `7.17.0`
- Build package: `org.openapitools.codegen.languages.PhpClientCodegen`
