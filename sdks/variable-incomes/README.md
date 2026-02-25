# OpenAPIClient-php

API de informações de operações de Renda Variável Open Finance Brasil – Fase 4. 
API que retorna informações de operações de investimento do tipo Renda Variável mantidas nas instituições transmissoras por seus clientes, incluindo dados como informações do produto, quantidade, saldos em posição do cliente, movimentações financeiras e detalhes da nota de negociação. 
Não possui segregação entre pessoa natural e pessoa jurídica. Requer consentimento do cliente para todos os endpoints. 
A granularidade de exposição de operações de renda variável se dá por cada ativo (ticker) da carteira do cliente. 
Compartilhamento considera lote padrão e fracionário, entretanto, no Open Finance Brasil, as informações são consolidadas via ticker do lote padrão. 
A defasagem em relação ao canal eletrônico da instituição deve ser o fechamento (pregão) do dia anterior (d-1). 

Em relação ao aluguel de ações: neste momento não faz parte do escopo de compartilhamento a carteira/posição de aluguel do cliente (ativos alugados e movimentações relacionadas a esses ativos). 
Apenas deve ser compartilhado as transações de pagamento ou recebimento de juros oriundos dos contratos de ações alugadas (ou doadas) pelos clientes.

Em relação aos produtos FIAGRO e FII: quando negociados em balcão, estão fora do escopo do Open Finance.

Para o identificador do investimento (investmentId) deve ser adotado o seguinte comportamento:

- Após 12 meses sem movimentações e com quantidade de ativos zerada, o resourceId correspondente ao investmentId em questão deve passar ao status UNAVAILABLE (considerando consentimento válido);

- Nas situações em que o cliente compre novamente o ativo após um período de 12 meses sem movimentação e com quantidade de ativos zerada, o mesmo identificador (investmentId) deve ser utilizado. Especificamente para tais produtos, o status do recurso na resources deve passar de UNAVAILABLE para AVAILABLE.

Segue abaixo tabela com o escopo de produtos a ser considerado para compartilhamento:
 ```
   |----------------------|-------------------------------|----------------------|-----------------------------------|
   | CLASSE DE ATIVOS     | PRODUTO                       | SUBPRODUTO           | DENOMINAÇÃO                       |
   |----------------------|-------------------------------|----------------------|-----------------------------------|
   | Renda Variável       | Fundos de Investimentos       |     -                | FIAGRO Listado                    |
   |----------------------|-------------------------------|----------------------|-----------------------------------|
   | Renda Variável       | Ações                         | Subscrição           | Bonus / Direito / Recibo          |
   |----------------------|-------------------------------|----------------------|-----------------------------------|
   | Renda Variável       | Fundos de Investimentos       | Fundo imobiliario    | FII Listado                       |
   |----------------------|-------------------------------|----------------------|-----------------------------------|
   | Renda Variável       | Ações                         | À vista              | ON / PN / UNIT                    |
   |----------------------|-------------------------------|----------------------|-----------------------------------|
   | Renda Variável       | Fundos de índices             | ETF                  | ETF de Renda Variável             |
   |----------------------|-------------------------------|----------------------|-----------------------------------|
   | Renda Variável       | Fundos de índices             | ETF                  | ETF Internacional                 |
   |----------------------|-------------------------------|----------------------|-----------------------------------|
   | Renda Variável       | Fundos de índices             | ETF Renda Fixa       | ETF Renda Fixa                    |
   |----------------------|-------------------------------|----------------------|-----------------------------------|
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



// Configure OAuth2 access token for authorization: OAuth2AuthorizationCode
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\BalancesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$investment_id = 92792126019929200000000000000000000000000; // string | Identifica de forma única o relacionamento do cliente com o produto, mantendo as regras de imutabilidade dentro da instituição transmissora.
$authorization = 'authorization_example'; // string | Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado
$x_fapi_interaction_id = 'x_fapi_interaction_id_example'; // string | Um UUID RFC4122 usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser \"espelhado\" pela transmissora (server) no cabeçalho de resposta.
$x_fapi_auth_date = 'x_fapi_auth_date_example'; // string | Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a RFC7231. Exemplo: Sun, 10 Sep 2017 19:43:31 UTC.
$x_fapi_customer_ip_address = 'x_fapi_customer_ip_address_example'; // string | O endereço IP do usuário se estiver atualmente logado com o receptor.
$x_customer_user_agent = 'x_customer_user_agent_example'; // string | Indica o user-agent que o usuário utiliza.

try {
    $result = $apiInstance->variableIncomesGetInvestmentsInvestmentIdBalances($investment_id, $authorization, $x_fapi_interaction_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling BalancesApi->variableIncomesGetInvestmentsInvestmentIdBalances: ', $e->getMessage(), PHP_EOL;
}

```

## API Endpoints

All URIs are relative to *https://api.banco.com.br/open-banking/variable-incomes/v1*

Class | Method | HTTP request | Description
------------ | ------------- | ------------- | -------------
*BalancesApi* | [**variableIncomesGetInvestmentsInvestmentIdBalances**](docs/Api/BalancesApi.md#variableincomesgetinvestmentsinvestmentidbalances) | **GET** /investments/{investmentId}/balances | Obtém a posição da operação de Renda Variável identificada por investmentId.
*BrokerNoteDetailsApi* | [**variableIncomesGetInvestmentsInvestmentIdBrokerNotesBrokerNoteId**](docs/Api/BrokerNoteDetailsApi.md#variableincomesgetinvestmentsinvestmentidbrokernotesbrokernoteid) | **GET** /broker-notes/{brokerNoteId} | Obtém as informações da nota de negociação identificado nas movimentações de compra e venda de ativos em bolsa. O brokerNoteId é enviado nos movimentos de compra ou venda de ativos e deve ser passada como parâmetro de entrada no endpoint “Nota de Negociação”.Como conteúdo do campo brokerNoteId é esperado que a transmissora gere um identificar único, imutável, para cada número (natural) de nota de negociação.
*ProductIdentificationApi* | [**variableIncomesGetInvestmentsInvestmentId**](docs/Api/ProductIdentificationApi.md#variableincomesgetinvestmentsinvestmentid) | **GET** /investments/{investmentId} | Obtém os dados da operação de Renda Variável identificada por investmentId.
*ProductListApi* | [**variableIncomesGetInvestments**](docs/Api/ProductListApi.md#variableincomesgetinvestments) | **GET** /investments | Obtém a lista de operações de Renda Variável mantidas pelo cliente na instituição transmissora e para as quais ele tenha fornecido consentimento.
*TransactionsApi* | [**variableIncomesGetInvestmentsInvestmentIdTransactions**](docs/Api/TransactionsApi.md#variableincomesgetinvestmentsinvestmentidtransactions) | **GET** /investments/{investmentId}/transactions | Obtém as movimentações históricas (últimos 12 meses) da operação de Renda Variável identificada por investmentId.
*TransactionsCurrentApi* | [**variableIncomesGetInvestmentsInvestmentIdTransactionsCurrent**](docs/Api/TransactionsCurrentApi.md#variableincomesgetinvestmentsinvestmentidtransactionscurrent) | **GET** /investments/{investmentId}/transactions-current | Obtém as movimentações recentes da operação de Renda Variável identificada por investmentId. O período a ser considerado para apresentação de movimentações será de até 7 dias - 7 dias anteriores da consulta, incluindo o dia da consulta (D-6).

## Models

- [EnumVariableIncomesTransactionsCurrentTransactionType](docs/Model/EnumVariableIncomesTransactionsCurrentTransactionType.md)
- [EnumVariableIncomesTransactionsCurrentType](docs/Model/EnumVariableIncomesTransactionsCurrentType.md)
- [EnumVariableIncomesTransactionsTransactionType](docs/Model/EnumVariableIncomesTransactionsTransactionType.md)
- [EnumVariableIncomesTransactionsType](docs/Model/EnumVariableIncomesTransactionsType.md)
- [MetaOnlyRequestDateTime](docs/Model/MetaOnlyRequestDateTime.md)
- [MetaSingle](docs/Model/MetaSingle.md)
- [MetaWithAbleAdditionalProperties](docs/Model/MetaWithAbleAdditionalProperties.md)
- [ResponseErrorMetaSingle](docs/Model/ResponseErrorMetaSingle.md)
- [ResponseErrorWithAbleAdditionalProperties](docs/Model/ResponseErrorWithAbleAdditionalProperties.md)
- [ResponseVariableIncomesBalanceData](docs/Model/ResponseVariableIncomesBalanceData.md)
- [ResponseVariableIncomesBalances](docs/Model/ResponseVariableIncomesBalances.md)
- [ResponseVariableIncomesBroker](docs/Model/ResponseVariableIncomesBroker.md)
- [ResponseVariableIncomesBrokerData](docs/Model/ResponseVariableIncomesBrokerData.md)
- [ResponseVariableIncomesBrokerDataBrokerageFee](docs/Model/ResponseVariableIncomesBrokerDataBrokerageFee.md)
- [ResponseVariableIncomesBrokerDataClearingCustodyFee](docs/Model/ResponseVariableIncomesBrokerDataClearingCustodyFee.md)
- [ResponseVariableIncomesBrokerDataClearingRegistrationFee](docs/Model/ResponseVariableIncomesBrokerDataClearingRegistrationFee.md)
- [ResponseVariableIncomesBrokerDataClearingSettlementFee](docs/Model/ResponseVariableIncomesBrokerDataClearingSettlementFee.md)
- [ResponseVariableIncomesBrokerDataGrossValue](docs/Model/ResponseVariableIncomesBrokerDataGrossValue.md)
- [ResponseVariableIncomesBrokerDataIncomeTax](docs/Model/ResponseVariableIncomesBrokerDataIncomeTax.md)
- [ResponseVariableIncomesBrokerDataNetValue](docs/Model/ResponseVariableIncomesBrokerDataNetValue.md)
- [ResponseVariableIncomesBrokerDataStockExchangeAssetTradeNoticeFee](docs/Model/ResponseVariableIncomesBrokerDataStockExchangeAssetTradeNoticeFee.md)
- [ResponseVariableIncomesBrokerDataStockExchangeFee](docs/Model/ResponseVariableIncomesBrokerDataStockExchangeFee.md)
- [ResponseVariableIncomesBrokerDataTaxes](docs/Model/ResponseVariableIncomesBrokerDataTaxes.md)
- [ResponseVariableIncomesProductIdentification](docs/Model/ResponseVariableIncomesProductIdentification.md)
- [ResponseVariableIncomesProductIdentificationData](docs/Model/ResponseVariableIncomesProductIdentificationData.md)
- [ResponseVariableIncomesProductList](docs/Model/ResponseVariableIncomesProductList.md)
- [ResponseVariableIncomesProductListData](docs/Model/ResponseVariableIncomesProductListData.md)
- [ResponseVariableIncomesTransactions](docs/Model/ResponseVariableIncomesTransactions.md)
- [ResponseVariableIncomesTransactionsCurrent](docs/Model/ResponseVariableIncomesTransactionsCurrent.md)
- [ResponseVariableIncomesTransactionsCurrentData](docs/Model/ResponseVariableIncomesTransactionsCurrentData.md)
- [ResponseVariableIncomesTransactionsData](docs/Model/ResponseVariableIncomesTransactionsData.md)
- [ResponseVariableIncomesTransactionsDataTransactionUnitPrice](docs/Model/ResponseVariableIncomesTransactionsDataTransactionUnitPrice.md)
- [ResponseVariableIncomesTransactionsDataTransactionValue](docs/Model/ResponseVariableIncomesTransactionsDataTransactionValue.md)
- [VariableIncomesBalancesBlockedBalance](docs/Model/VariableIncomesBalancesBlockedBalance.md)
- [VariableIncomesBalancesClosingPrice](docs/Model/VariableIncomesBalancesClosingPrice.md)
- [VariableIncomesBalancesGrossAmount](docs/Model/VariableIncomesBalancesGrossAmount.md)
- [VariableIncomesGetInvestments423Response](docs/Model/VariableIncomesGetInvestments423Response.md)
- [VariableIncomesGetInvestments423ResponseErrorsInner](docs/Model/VariableIncomesGetInvestments423ResponseErrorsInner.md)
- [VariableIncomesGetInvestments423ResponseMeta](docs/Model/VariableIncomesGetInvestments423ResponseMeta.md)
- [VariableIncomesGetInvestmentsInvestmentIdTransactions423Response](docs/Model/VariableIncomesGetInvestmentsInvestmentIdTransactions423Response.md)
- [VariableIncomesGetInvestmentsInvestmentIdTransactions423ResponseMeta](docs/Model/VariableIncomesGetInvestmentsInvestmentIdTransactions423ResponseMeta.md)
- [VariableIncomesLinks](docs/Model/VariableIncomesLinks.md)
- [VariableIncomesMeta](docs/Model/VariableIncomesMeta.md)
- [VariableIncomesTransactionsLinks](docs/Model/VariableIncomesTransactionsLinks.md)

## Authorization

Authentication schemes defined for the API:
### OAuth2AuthorizationCode

- **Type**: `OAuth`
- **Flow**: `accessCode`
- **Authorization URL**: `https://authserver.example/authorization`
- **Scopes**: 
    - **variable-incomes**: Escopo necessário para acesso à API Variable Incomes. O controle dos endpoints específicos é feito via permissions.

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

- API version: `1.2.1`
    - Generator version: `7.17.0`
- Build package: `org.openapitools.codegen.languages.PhpClientCodegen`
