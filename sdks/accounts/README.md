# OpenAPIClient-php

API de contas de depósito à vista, contas de poupança e contas pré-pagas do Open Finance Brasil – Fase 2.
API que retorna informações de contas de depósito à vista, contas de poupança e contas de pagamento pré-pagas mantidas nas instituições transmissoras por seus clientes,
incluindo dados de identificação da conta, saldos, limites e transações.\\
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
Relacionamos a seguir as `permissions` necessárias para a consulta de dados em cada `endpoint` da presente API.

## Permissions necessárias para a API Accounts

Para cada um dos paths desta API, além dos escopos (`scopes`) indicados existem `permissions` que deverão ser observadas:

### `/accounts`
  - permissions:
    - GET: **ACCOUNTS_READ**
### `/accounts/{accountId}`
  - permissions:
    - GET: **ACCOUNTS_READ**
### `/accounts/{accountId}/balances`
  - permissions:
    - GET: **ACCOUNTS_BALANCES_READ**
### `/accounts/{accountId}/transactions`
  - permissions:
    - GET: **ACCOUNTS_TRANSACTIONS_READ**
### `/accounts/{accountId}/transactions-current`
  - permissions:
    - GET: **ACCOUNTS_TRANSACTIONS_READ**
### `/accounts/{accountId}/overdraft-limits`
  - permissions:
    - GET: **ACCOUNTS_OVERDRAFT_LIMITS_READ**

## Data de imutabilidade por tipo de transação​
O identificador de transações de contas é de envio obrigatório no Open Finance Brasil. De acordo com o tipo da transação deve haver o envio de um identificador único, estável e imutável em D0 ou D+1, conforme tabela abaixo
```
|---------------------------------------|-------------------------|-----------------------|
| Tipo de Transação                     | Data da Obrigatoriedade | Data da Imutabilidade |
|---------------------------------------|-------------------------|-----------------------|
| TED                                   | DO                      | DO                    |
|---------------------------------------|-------------------------|-----------------------|
| PIX                                   | DO                      | DO                    |
|---------------------------------------|-------------------------|-----------------------|
| TRANSFERENCIA MESMA INSTITUIÇÃO (TEF) | DO                      | DO                    |
|---------------------------------------|-------------------------|-----------------------|
| TARIFA SERVIÇOS AVULSOS               | DO                      | DO                    |
|---------------------------------------|-------------------------|-----------------------|
| FOLHA DE PAGAMENTO                    | DO                      | DO                    |
|---------------------------------------|-------------------------|-----------------------|
| DOC                                   | DO                      | D+1                   |
|---------------------------------------|-------------------------|-----------------------|
| BOLETO                                | DO                      | D+1                   |
|---------------------------------------|-------------------------|-----------------------|
| CONVÊNIO ARRECADAÇÃO                  | DO                      | D+1                   |
|---------------------------------------|-------------------------|-----------------------|
| PACOTE TARIFA SERVIÇOS                | DO                      | D+1                   |
|---------------------------------------|-------------------------|-----------------------|
| DEPÓSITO                              | DO                      | D+1                   |
|---------------------------------------|-------------------------|-----------------------|
| SAQUE                                 | DO                      | D+1                   |
|---------------------------------------|-------------------------|-----------------------|
| CARTÃO                                | DO                      | D+1                   |
|---------------------------------------|-------------------------|-----------------------|
| ENCARGOS JUROS CHEQUE ESPECIAL        | DO                      | D+1                   |
|---------------------------------------|-------------------------|-----------------------|
| RENDIMENTO APLICAÇÃO FINANCEIRA       | DO                      | D+1                   |
|---------------------------------------|-------------------------|-----------------------|
| PORTABILIDADE SALÁRIO                 | DO                      | D+1                   |
|---------------------------------------|-------------------------|-----------------------|
| RESGATE APLICAÇÃO FINANCEIRA          | DO                      | D+1                   |
|---------------------------------------|-------------------------|-----------------------|
| OPERAÇÃO DE CRÉDITO                   | DO                      | D+1                   |
|---------------------------------------|-------------------------|-----------------------|
| OUTROS                                | DO                      | D+1                   |
|---------------------------------------|-------------------------|-----------------------|
```

Para consultar as regras aplicáveis ao comportamento do transacionID de acordo com o status da transação, consultar a página [Orientações - Contas](https://openfinancebrasil.atlassian.net/wiki/spaces/OF/pages/193658890)


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



$apiInstance = new OpenAPI\Client\Api\AccountsApi(
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
$account_type = new \OpenAPI\Client\Model\\OpenAPI\Client\Model\EnumAccountType(); // \OpenAPI\Client\Model\EnumAccountType | Tipos de contas. Modalidades tradicionais previstas pela Resolução 4.753, não contemplando contas vinculadas, conta de domiciliados no exterior, contas em moedas estrangeiras e conta correspondente moeda eletrônica. Vide Enum.
$pagination_key = 'pagination_key_example'; // string | Identificador de rechamada, utilizado para evitar a contagem de chamadas ao endpoint durante a paginação.

try {
    $result = $apiInstance->accountsGetAccounts($authorization, $x_fapi_interaction_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $page, $page_size, $account_type, $pagination_key);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountsApi->accountsGetAccounts: ', $e->getMessage(), PHP_EOL;
}

```

## API Endpoints

All URIs are relative to *https://api.banco.com.br/open-banking/accounts/v2*

Class | Method | HTTP request | Description
------------ | ------------- | ------------- | -------------
*AccountsApi* | [**accountsGetAccounts**](docs/Api/AccountsApi.md#accountsgetaccounts) | **GET** /accounts | Obtém a lista de contas consentidas pelo cliente.
*AccountsApi* | [**accountsGetAccountsAccountId**](docs/Api/AccountsApi.md#accountsgetaccountsaccountid) | **GET** /accounts/{accountId} | Obtém os dados de identificação da conta identificada por accountId.
*AccountsApi* | [**accountsGetAccountsAccountIdBalances**](docs/Api/AccountsApi.md#accountsgetaccountsaccountidbalances) | **GET** /accounts/{accountId}/balances | Obtém os saldos da conta identificada por accountId.
*AccountsApi* | [**accountsGetAccountsAccountIdOverdraftLimits**](docs/Api/AccountsApi.md#accountsgetaccountsaccountidoverdraftlimits) | **GET** /accounts/{accountId}/overdraft-limits | Obtém os limites da conta identificada por accountId.
*AccountsApi* | [**accountsGetAccountsAccountIdTransactions**](docs/Api/AccountsApi.md#accountsgetaccountsaccountidtransactions) | **GET** /accounts/{accountId}/transactions | Obtém a lista de transações da conta identificada por accountId.
*AccountsApi* | [**accountsGetAccountsAccountIdTransactionsCurrent**](docs/Api/AccountsApi.md#accountsgetaccountsaccountidtransactionscurrent) | **GET** /accounts/{accountId}/transactions-current | Obtém a lista de transações recentes (últimos 7 dias) da conta identificada por accountId.

## Models

- [AccountBalancesData](docs/Model/AccountBalancesData.md)
- [AccountBalancesDataAutomaticallyInvestedAmount](docs/Model/AccountBalancesDataAutomaticallyInvestedAmount.md)
- [AccountBalancesDataAvailableAmount](docs/Model/AccountBalancesDataAvailableAmount.md)
- [AccountBalancesDataBlockedAmount](docs/Model/AccountBalancesDataBlockedAmount.md)
- [AccountData](docs/Model/AccountData.md)
- [AccountIdentificationData](docs/Model/AccountIdentificationData.md)
- [AccountOverdraftLimitsData](docs/Model/AccountOverdraftLimitsData.md)
- [AccountOverdraftLimitsDataOverdraftContractedLimit](docs/Model/AccountOverdraftLimitsDataOverdraftContractedLimit.md)
- [AccountOverdraftLimitsDataOverdraftUsedLimit](docs/Model/AccountOverdraftLimitsDataOverdraftUsedLimit.md)
- [AccountOverdraftLimitsDataUnarrangedOverdraftAmount](docs/Model/AccountOverdraftLimitsDataUnarrangedOverdraftAmount.md)
- [AccountTransactionsData](docs/Model/AccountTransactionsData.md)
- [AccountTransactionsDataAmount](docs/Model/AccountTransactionsDataAmount.md)
- [EnumAccountSubType](docs/Model/EnumAccountSubType.md)
- [EnumAccountType](docs/Model/EnumAccountType.md)
- [EnumCompletedAuthorisedPaymentIndicator](docs/Model/EnumCompletedAuthorisedPaymentIndicator.md)
- [EnumCreditDebitIndicator](docs/Model/EnumCreditDebitIndicator.md)
- [EnumPartiePersonType](docs/Model/EnumPartiePersonType.md)
- [EnumTransactionTypes](docs/Model/EnumTransactionTypes.md)
- [Links](docs/Model/Links.md)
- [LinksAccountId](docs/Model/LinksAccountId.md)
- [Meta](docs/Model/Meta.md)
- [MetaOnlyRequestDateTime](docs/Model/MetaOnlyRequestDateTime.md)
- [ResponseAccountBalances](docs/Model/ResponseAccountBalances.md)
- [ResponseAccountIdentification](docs/Model/ResponseAccountIdentification.md)
- [ResponseAccountList](docs/Model/ResponseAccountList.md)
- [ResponseAccountOverdraftLimits](docs/Model/ResponseAccountOverdraftLimits.md)
- [ResponseAccountTransactions](docs/Model/ResponseAccountTransactions.md)
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
    - **accounts**: Escopo necessário para acesso à API Accounts. O controle dos endpoints específicos é feito via permissions.

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

- API version: `2.4.2`
    - Generator version: `7.17.0`
- Build package: `org.openapitools.codegen.languages.PhpClientCodegen`
