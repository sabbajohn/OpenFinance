# OpenAPIClient-php

API que trata da criação, consulta, renovação e revogação de consentimentos para o Open Finance Brasil Dados cadastrais e transacionais - customer-data.  
Não possui segregação entre pessoa natural e pessoa jurídica.    

# Orientações importantes
A API Consents trata exclusivamente dos consentimentos para Dados Cadastrais e Transacionais do Open Finance Brasil.
- A API consents é composta de endpoints que permitem:
  - Pedido de criação do consentimento pela receptora: `POST /consents`
  - Devolução do pedido de criação pela transmissora: `GET /consents/{consentId}`
  - Pedido de renovação de consentimento do cliente pela receptora: `POST /consents/{consentId}/extends`
  - Devolução de lista com histórico de renovações efetuadas: `GET /consents/{consentId}/extensions`
  - Pedido de revogação do consentimento: `DELETE /consents/{consentId}`
- Recomenda-se fortemente a leitura da seção [Orientações - [DC] Consentimento](https://openfinancebrasil.atlassian.net/wiki/spaces/OF/pages/219480491) para maiores detalhes, regras e restrições referente aos endpoints da API Consents
- As informações da instituição receptora não trafegam na API Consents – a autenticação da receptora se dá através do [DCR](https://openfinancebrasil.atlassian.net/wiki/spaces/OF/pages/17378307/Dynamic+Client+Registration).
- Na chamada para a criação, consulta e revogação do consentimento deve-se utilizar um token gerado via `client_credentials`. Na chamada para renovação do consentimento deve-se utilizar um token gerado via `authorization_code`.
- Após o `POST` de criação do consentimento, o `STATUS` devolvido na resposta deverá ser `AWAITING_AUTHORISATION`.
- O `STATUS` será alterado para `AUTHORISED` somente após autenticação e confirmação por parte do usuário na instituição transmissora dos dados.
- Caso não haja confirmação por parte do usuário na transmissora, o status do consentimento deve ser alterado de `AWAITING_AUTHORISATION` para `REJECTED` após 60 minutos.
- Todas as datas trafegadas nesta API seguem o padrão da [RFC3339](https://tools.ietf.org/html/rfc3339) e formato \"zulu\".
- A descrição do fluxo de consentimento encontra-se disponível no [Portal do desenvolvedor](https://openfinancebrasil.atlassian.net/wiki/spaces/OF/pages/17369300/Dados+Cadastrais+e+Transacionais#Fluxo-b%C3%A1sico-de-consentimento).
- O arquivo com o mapeamento completo entre `Roles`, `scopes` e `permissions` está disponibilizado no Portal do desenvolvedor, no mesmo item acima - descrição do fluxo de consentimento.
- A receptora deve enviar obrigatoriamente, no pedido de criação de consentimento, todas as permissions dos agrupamentos de dados as quais ela deseja consentimento, conforme tabela abaixo:

  ```
  |-------|----------------------|-------------------------------|----------------------------------------------------------|-------------------------------|
  | ROLE  | CATEGORIA DE DADOS   | AGRUPAMENTO                   | PERMISSIONS                                              | SCOPE OAUTH 2.0               |
  |-------|----------------------|-------------------------------|----------------------------------------------------------|-------------------------------|
  |       |                      |                               | CUSTOMERS_PERSONAL_IDENTIFICATIONS_READ                  | customers                     |
  |       | Cadastro             | Dados Cadastrais PF           |----------------------------------------------------------|                               |
  |       |                      |                               | RESOURCES_READ                                           | resources                     |
  |       |----------------------|-------------------------------|----------------------------------------------------------|-------------------------------|
  |       |                      |                               | CUSTOMERS_PERSONAL_ADITTIONALINFO_READ                   | customers                     |
  |       | Cadastro             | Informações complementares PF |----------------------------------------------------------|                               |
  |       |                      |                               | RESOURCES_READ                                           | resources                     |
  | DADOS |----------------------|-------------------------------|----------------------------------------------------------|-------------------------------|
  |       |                      |                               | CUSTOMERS_BUSINESS_IDENTIFICATIONS_READ                  | customers                     |
  |       | Cadastro             | Dados Cadastrais PJ           |----------------------------------------------------------|                               |
  |       |                      |                               | RESOURCES_READ                                           | resources                     |
  |       |----------------------|-------------------------------|----------------------------------------------------------|-------------------------------|
  |       |                      |                               | CUSTOMERS_BUSINESS_ADITTIONALINFO_READ                   | customers                     |
  |       | Cadastro             | Informações complementares PJ |----------------------------------------------------------|                               |
  |       |                      |                               | RESOURCES_READ                                           | resources                     |
  |-------|----------------------|-------------------------------|----------------------------------------------------------|-------------------------------|
  |       |                      |                               | ACCOUNTS_READ                                            |                               |
  |       |                      |                               |----------------------------------------------------------| accounts                      |
  |       | Contas               | Saldos                        | ACCOUNTS_BALANCES_READ                                   |                               |
  |       |                      |                               |----------------------------------------------------------| resources                     |
  |       |                      |                               | RESOURCES_READ                                           |                               |
  |       |----------------------|-------------------------------|----------------------------------------------------------|-------------------------------|
  |       |                      |                               | ACCOUNTS_READ                                            |                               |
  |       |                      |                               |----------------------------------------------------------| accounts                      |
  | DADOS | Contas               | Limites                       | ACCOUNTS_OVERDRAFT_LIMITS_READ                           |                               |
  |       |                      |                               |----------------------------------------------------------| resources                     |
  |       |                      |                               | RESOURCES_READ                                           |                               |
  |       |----------------------|-------------------------------|----------------------------------------------------------|-------------------------------|
  |       |                      |                               | ACCOUNTS_READ                                            |                               |
  |       |                      |                               |----------------------------------------------------------| accounts                      |
  |       | Contas               | Extratos                      | ACCOUNTS_TRANSACTIONS_READ                               |                               |
  |       |                      |                               |----------------------------------------------------------| resources                     |
  |       |                      |                               | RESOURCES_READ                                           |                               |
  |-------|----------------------|-------------------------------|----------------------------------------------------------|-------------------------------|
  |       |                      |                               | CREDIT_CARDS_ACCOUNTS_READ                               |                               |
  |       |                      |                               |----------------------------------------------------------| credit-cards-accounts         |
  |       | Cartão de Crédito    | Limites                       | CREDIT_CARDS_ACCOUNTS_LIMITS_READ                        |                               |
  |       |                      |                               |----------------------------------------------------------| resources                     |
  |       |                      |                               | RESOURCES_READ                                           |                               |
  |       |----------------------|-------------------------------|----------------------------------------------------------|-------------------------------|
  |       |                      |                               | CREDIT_CARDS_ACCOUNTS_READ                               |                               |
  |       |                      |                               |----------------------------------------------------------| credit-cards-accounts         |
  |       | Cartão de Crédito    | Transações                    | CREDIT_CARDS_ACCOUNTS_TRANSACTIONS_READ                  |                               |
  | DADOS |                      |                               |----------------------------------------------------------| resources                     |
  |       |                      |                               | RESOURCES_READ                                           |                               |
  |       |----------------------|-------------------------------|----------------------------------------------------------|-------------------------------|
  |       |                      |                               | CREDIT_CARDS_ACCOUNTS_READ                               |                               |
  |       |                      |                               |----------------------------------------------------------|                               |
  |       |                      |                               | CREDIT_CARDS_ACCOUNTS_BILLS_READ                         | credit-cards-accounts         |
  |       | Cartão de Crédito    | Faturas                       |----------------------------------------------------------|                               |
  |       |                      |                               | CREDIT_CARDS_ACCOUNTS_BILLS_TRANSACTIONS_READ            | resources                     |
  |       |                      |                               |----------------------------------------------------------|                               |
  |       |                      |                               | RESOURCES_READ                                           |                               |
  |-------|----------------------|-------------------------------|----------------------------------------------------------|-------------------------------|
  |       |                      |                               | LOANS_READ                                               |                               |
  |       |                      |                               |----------------------------------------------------------|                               |
  |       |                      |                               | LOANS_WARRANTIES_READ                                    |                               |
  |       |                      |                               |----------------------------------------------------------|                               |
  |       |                      |                               | LOANS_SCHEDULED_INSTALMENTS_READ                         |                               |
  |       |                      |                               |----------------------------------------------------------|                               |
  |       |                      |                               | LOANS_PAYMENTS_READ                                      |                               |
  |       |                      |                               |----------------------------------------------------------|                               |
  |       |                      |                               | FINANCINGS_READ                                          |                               |
  |       |                      |                               |----------------------------------------------------------|                               |
  |       |                      |                               | FINANCINGS_WARRANTIES_READ                               |                               |
  |       |                      |                               |----------------------------------------------------------|                               |
  |       |                      |                               | FINANCINGS_SCHEDULED_INSTALMENTS_READ                    | loans                         |
  |       |                      |                               |----------------------------------------------------------|                               |
  |       |                      |                               | FINANCINGS_PAYMENTS_READ                                 | financings                    |
  |       |                      |                               |----------------------------------------------------------|                               |
  | DADOS | Operações de Crédito | Dados do Contrato             | UNARRANGED_ACCOUNTS_OVERDRAFT_READ                       | unarranged-accounts-overdraft |
  |       |                      |                               |----------------------------------------------------------|                               |
  |       |                      |                               | UNARRANGED_ACCOUNTS_OVERDRAFT_WARRANTIES_READ            | invoice-financings            |
  |       |                      |                               |----------------------------------------------------------|                               |
  |       |                      |                               | UNARRANGED_ACCOUNTS_OVERDRAFT_SCHEDULED_INSTALMENTS_READ | resources                     |
  |       |                      |                               |----------------------------------------------------------|                               |
  |       |                      |                               | UNARRANGED_ACCOUNTS_OVERDRAFT_PAYMENTS_READ              |                               |
  |       |                      |                               |----------------------------------------------------------|                               |
  |       |                      |                               | INVOICE_FINANCINGS_READ                                  |                               |
  |       |                      |                               |----------------------------------------------------------|                               |
  |       |                      |                               | INVOICE_FINANCINGS_WARRANTIES_READ                       |                               |
  |       |                      |                               |----------------------------------------------------------|                               |
  |       |                      |                               | INVOICE_FINANCINGS_SCHEDULED_INSTALMENTS_READ            |                               |
  |       |                      |                               |----------------------------------------------------------|                               |
  |       |                      |                               | INVOICE_FINANCINGS_PAYMENTS_READ                         |                               |
  |       |                      |                               |----------------------------------------------------------|                               |
  |       |                      |                               | RESOURCES_READ                                           |                               |
  |-------|----------------------|-------------------------------|----------------------------------------------------------|-------------------------------|
  |       |                      |                               | BANK_FIXED_INCOMES_READ                                  | bank-fixed-incomes            |
  |       |                      |                               |----------------------------------------------------------|                               |
  |       |                      |                               | CREDIT_FIXED_INCOMES_READ                                | credit-fixed-incomes          |
  |       |                      |                               |----------------------------------------------------------|                               |
  | DADOS | Investimento         | Dados da Operação             | FUNDS_READ                                               | variable-incomes              |
  |       |                      |                               |----------------------------------------------------------|                               |
  |       |                      |                               | VARIABLE_INCOMES_READ                                    | treasure-titles               |
  |       |                      |                               |----------------------------------------------------------|                               |
  |       |                      |                               | TREASURE_TITLES_READ                                     | funds                         |
  |       |                      |                               |----------------------------------------------------------|                               |
  |       |                      |                               | RESOURCES_READ                                           | resources                     |
  |-------|----------------------|-------------------------------|----------------------------------------------------------|-------------------------------|
  |       |                      |                               | EXCHANGES_READ                                           |                               |
  | DADOS | Câmbio               | Dados da Operação             |----------------------------------------------------------| exchanges                     |
  |       |                      |                               | RESOURCES_READ                                           |                               |
  |-------|----------------------|-------------------------------|----------------------------------------------------------|-------------------------------|
  
  ```
- A instituição transmissora deve validar o preenchimento correto dos agrupamentos acima no momento da geração do consentimento.
- Caso a instiuição receptora envie permissões não existentes nos agrupamentos especificados na tabela, a transmissora deve rejeitar o pedido da receptora dando retorno HTTP Status Code 400.
- A transmissora deve retornar, da lista de permissions requisitadas, apenas o subconjunto de permissions por ela suportada, removendo da lista as permissions de produtos não suportados e retornando HTTP Status Code 201. A única exceção a este comportamento são os casos de produtos agrupados, como Operações de Crédito, Investimentos e Câmbio, para os quais todas as permissões do agrupamento devem ser mantidas. Caso não restem permissões funcionais, a instituição transmissora deve retornar o erro HTTP Code \"422 Unprocessable Entity\".
- A renovação de consentimento não pode ser efetuada em situações determinadas. É esperado status 401 ou 403 para situações em que o erro for tratado na camada de segurança. Para erros tratados em camada de negócio, a transmissora deve retornar 422 conforme mensagens especificadas na página [Orientações – [DC] Consentimento](https://openfinancebrasil.atlassian.net/wiki/spaces/DraftOF/pages/232915037)
- Caso o método `DELETE` seja chamado para um consentimento que já se encontra no `STATUS REJECTED` deve se retornar o STATUS CODE 422.
- Pedidos de renovação de consentimento somente podem alterar a data de validade (conforme as regras definidas em [Orientações – [DC] Consentimento](https://openfinancebrasil.atlassian.net/wiki/spaces/DraftOF/pages/232915037)) e a finalidade do consentimento, e aplica-se somente a consentimentos ativos (status `AUTHORISED`).
- No caso de criação ou renovação de consentimentos com prazo indeterminado, a receptora não deve enviar o atributo expirationDateTime. Para prazos determinados o campo deve ser enviado.
- A renovação de consentimento (`POST /consents/{consentId}/extends`) deve ser possível por apenas um cliente logado. 
Isso implica que qualquer usuário (`loggedUser`) com permissão para o consentimento Pessoa Jurídica deve ser capaz de finalizar o fluxo de renovação sem redirecionamento. Para consentimentos Pessoa Natural apenas o `loggedUser` criador do consentimento consegue renovar sem redirecionamento.


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


$apiInstance = new OpenAPI\Client\Api\ConsentsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$consent_id = 'consent_id_example'; // string | O consentId é o identificador único do consentimento e deverá ser um URN - Uniform Resource Name.   Um URN, conforme definido na [RFC8141](https://tools.ietf.org/html/rfc8141) é um Uniform Resource  Identifier - URI - que é atribuído sob o URI scheme \"urn\" e um namespace URN específico, com a intenção de que o URN  seja um identificador de recurso persistente e independente da localização.   Considerando a string urn:bancoex:C1DD33123 como exemplo para consentId temos: - o namespace(urn) - o identificador associado ao namespace da instituição transnmissora (bancoex) - o identificador específico dentro do namespace (C1DD33123). Informações mais detalhadas sobre a construção de namespaces devem ser consultadas na [RFC8141](https://tools.ietf.org/html/rfc8141).
$authorization = 'authorization_example'; // string | Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado
$x_fapi_interaction_id = d78fc4e5-37ca-4da3-adf2-9b082bf92280; // string | Um UUID RFC4122 usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser “espelhado” pela transmissora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a transmissora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A receptora deve acatar o valor recebido da transmissora.
$x_fapi_auth_date = 'x_fapi_auth_date_example'; // string | Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC
$x_fapi_customer_ip_address = 'x_fapi_customer_ip_address_example'; // string | O endereço IP do usuário se estiver atualmente logado com o receptor.
$x_customer_user_agent = 'x_customer_user_agent_example'; // string | Indica o user-agent que o usuário utiliza.

try {
    $apiInstance->consentsDeleteConsentsConsentId($consent_id, $authorization, $x_fapi_interaction_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent);
} catch (Exception $e) {
    echo 'Exception when calling ConsentsApi->consentsDeleteConsentsConsentId: ', $e->getMessage(), PHP_EOL;
}

```

## API Endpoints

All URIs are relative to *https://api.banco.com.br/open-banking/consents/v3*

Class | Method | HTTP request | Description
------------ | ------------- | ------------- | -------------
*ConsentsApi* | [**consentsDeleteConsentsConsentId**](docs/Api/ConsentsApi.md#consentsdeleteconsentsconsentid) | **DELETE** /consents/{consentId} | Deletar / Revogar o consentimento identificado por consentId.
*ConsentsApi* | [**consentsGetConsentsConsentId**](docs/Api/ConsentsApi.md#consentsgetconsentsconsentid) | **GET** /consents/{consentId} | Obter detalhes do consentimento identificado por consentId.
*ConsentsApi* | [**consentsGetConsentsConsentIdExtensions**](docs/Api/ConsentsApi.md#consentsgetconsentsconsentidextensions) | **GET** /consents/{consentId}/extensions | Obter detalhes de extensões feitas no consentimento identificado por consentId.
*ConsentsApi* | [**consentsPostConsents**](docs/Api/ConsentsApi.md#consentspostconsents) | **POST** /consents | Criar novo pedido de consentimento.
*ConsentsApi* | [**consentsPostConsentsConsentIdExtends**](docs/Api/ConsentsApi.md#consentspostconsentsconsentidextends) | **POST** /consents/{consentId}/extends | Renovar consentimento identificado por consentId.

## Models

- [422ResponseErrorCreateConsentErrorsInner](docs/Model/422ResponseErrorCreateConsentErrorsInner.md)
- [BusinessEntity](docs/Model/BusinessEntity.md)
- [BusinessEntityDocument](docs/Model/BusinessEntityDocument.md)
- [BusinessEntityDocumentExtensions](docs/Model/BusinessEntityDocumentExtensions.md)
- [BusinessEntityExtensions](docs/Model/BusinessEntityExtensions.md)
- [ConsentsPostConsents529Response](docs/Model/ConsentsPostConsents529Response.md)
- [ConsentsPostConsents529ResponseErrorsInner](docs/Model/ConsentsPostConsents529ResponseErrorsInner.md)
- [ConsentsPostConsents529ResponseMeta](docs/Model/ConsentsPostConsents529ResponseMeta.md)
- [CreateConsent](docs/Model/CreateConsent.md)
- [CreateConsentData](docs/Model/CreateConsentData.md)
- [CreateConsentExtensions](docs/Model/CreateConsentExtensions.md)
- [CreateConsentExtensionsData](docs/Model/CreateConsentExtensionsData.md)
- [EnumRejectedBy](docs/Model/EnumRejectedBy.md)
- [Links](docs/Model/Links.md)
- [LinksConsents](docs/Model/LinksConsents.md)
- [LoggedUser](docs/Model/LoggedUser.md)
- [LoggedUserDocument](docs/Model/LoggedUserDocument.md)
- [LoggedUserDocumentExtensions](docs/Model/LoggedUserDocumentExtensions.md)
- [LoggedUserExtensions](docs/Model/LoggedUserExtensions.md)
- [Meta](docs/Model/Meta.md)
- [MetaError](docs/Model/MetaError.md)
- [MetaExtensions](docs/Model/MetaExtensions.md)
- [Model422ResponseErrorCreateConsent](docs/Model/Model422ResponseErrorCreateConsent.md)
- [ResponseConsent](docs/Model/ResponseConsent.md)
- [ResponseConsentData](docs/Model/ResponseConsentData.md)
- [ResponseConsentExtensions](docs/Model/ResponseConsentExtensions.md)
- [ResponseConsentExtensionsData](docs/Model/ResponseConsentExtensionsData.md)
- [ResponseConsentRead](docs/Model/ResponseConsentRead.md)
- [ResponseConsentReadData](docs/Model/ResponseConsentReadData.md)
- [ResponseConsentReadDataJourney](docs/Model/ResponseConsentReadDataJourney.md)
- [ResponseConsentReadDataRejection](docs/Model/ResponseConsentReadDataRejection.md)
- [ResponseConsentReadDataRejectionReason](docs/Model/ResponseConsentReadDataRejectionReason.md)
- [ResponseConsentReadExtensions](docs/Model/ResponseConsentReadExtensions.md)
- [ResponseConsentReadExtensionsDataInner](docs/Model/ResponseConsentReadExtensionsDataInner.md)
- [ResponseError](docs/Model/ResponseError.md)
- [ResponseErrorErrorsInner](docs/Model/ResponseErrorErrorsInner.md)
- [ResponseErrorUnprocessableEntity](docs/Model/ResponseErrorUnprocessableEntity.md)
- [ResponseErrorUnprocessableEntityDelete](docs/Model/ResponseErrorUnprocessableEntityDelete.md)
- [ResponseErrorUnprocessableEntityDeleteErrorsInner](docs/Model/ResponseErrorUnprocessableEntityDeleteErrorsInner.md)
- [ResponseErrorUnprocessableEntityErrorsInner](docs/Model/ResponseErrorUnprocessableEntityErrorsInner.md)

## Authorization

Authentication schemes defined for the API:
### OAuth2Security

- **Type**: `OAuth`
- **Flow**: `application`
- **Authorization URL**: ``
- **Scopes**: 
    - **consents**: Criação do consentimento.

### OAuth2AuthorizationCode

- **Type**: `OAuth`
- **Flow**: `accessCode`
- **Authorization URL**: `https://authserver.example/code`
- **Scopes**: 
    - **openid**: Indica que a autorização está sendo realizada utilizando o protocolo definido pela openid.
    - **consent:consentId**: Escopo contendo o identificador único do consentimento criado para compartilhamento de dados solicitada.

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

- API version: `3.3.0`
    - Generator version: `7.17.0`
- Build package: `org.openapitools.codegen.languages.PhpClientCodegen`
