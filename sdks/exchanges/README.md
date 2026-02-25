# OpenAPIClient-php

API de informações de operações de Câmbio Open Finance Brasil – Fase 4. 
API que retorna informações de operações de Câmbio realizadas nas instituições transmissoras por seus clientes, incluindo dados como informações da operação contratada, valor da operação em moeda nacional e moeda estrangeira, classificação da operação, forma de entrega, VET e, quando aplicável, valor a liquidar. 
Também serão compartilhados os eventos de alteração da operação, caso existam, com as informações modificadas. 
Não possui segregação entre pessoa natural e pessoa jurídica. 
Requer consentimento do cliente para todos os endpoints. 

__São escopo de compartilhamento as operações negociadas no mercado primário, pronto (inclusive espécie, cartão pré pago, cartão de débito) e futuro (inclusive ACC, ACE ou trava cambial).__

__Devem ser compartilhadas as operações contratadas e disponibilizadas nos canais eletrônicos da instituição, mesmo nas situações nas quais a operação ainda não tenha sido registrada junto ao Banco Central. Caso o evento de contratação seja anulado no Sistema de Câmbio, o que significa que a operação foi anulada, então esta operação deixa de ser escopo de exposição. Caso o registro aconteça a operação deve ser complementada com o número de operação registrado e os eventos ocorridos.__

__Eventos de vinculação de operações não são escopo de exposição.__

__A exposição se dará por cada operação de câmbio contratada pelo cliente.__


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


$apiInstance = new OpenAPI\Client\Api\EventsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$operation_id = 92792126019929200000000000000000000000000; // string | Identifica de forma única  o relacionamento do cliente com o produto, mantendo as regras de imutabilidade dentro da instituição transmissora.
$authorization = 'authorization_example'; // string | Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado
$x_fapi_interaction_id = d78fc4e5-37ca-4da3-adf2-9b082bf92280; // string | Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela receptora (client) e o seu valor deve ser “espelhado” pela transmissora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a transmissora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A receptora deve acatar o valor recebido da transmissora.
$x_fapi_auth_date = 'x_fapi_auth_date_example'; // string | Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a RFC7231. Exemplo: Sun, 10 Sep 2017 19:43:31 UTC.
$x_fapi_customer_ip_address = 'x_fapi_customer_ip_address_example'; // string | O endereço IP do usuário se estiver atualmente logado com o receptor.
$x_customer_user_agent = 'x_customer_user_agent_example'; // string | Indica o user-agent que o usuário utiliza.
$page = 1; // int | Número da página que está sendo requisitada (o valor da primeira página é 1).
$page_size = 25; // int | Quantidade total de registros por páginas.  A transmissora deve considerar entrada como 25, caso seja informado algum valor menor pela receptora.  Enquanto houver mais que 25 registros a enviar, a transmissora deve considerar o mínimo por página como 25.  Somente a última página retornada (ou primeira, no caso de página única) pode conter menos de 25 registros.  Mais informações, acesse Especificações de APIs > Padrões > Paginação.
$pagination_key = 'pagination_key_example'; // string | Identificador de rechamada, utilizado para evitar a contagem de chamadas ao endpoint durante a paginação.

try {
    $result = $apiInstance->exchangesGetOperationsOperationIdEvents($operation_id, $authorization, $x_fapi_interaction_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $page, $page_size, $pagination_key);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling EventsApi->exchangesGetOperationsOperationIdEvents: ', $e->getMessage(), PHP_EOL;
}

```

## API Endpoints

All URIs are relative to *https://api.banco.com.br/open-banking/exchanges/v1*

Class | Method | HTTP request | Description
------------ | ------------- | ------------- | -------------
*EventsApi* | [**exchangesGetOperationsOperationIdEvents**](docs/Api/EventsApi.md#exchangesgetoperationsoperationidevents) | **GET** /operations/{operationId}/events | Obtém os dados dos eventos da operação de Câmbio identificada por operationId.
*OperationDetailsApi* | [**exchangesGetOperationsOperationId**](docs/Api/OperationDetailsApi.md#exchangesgetoperationsoperationid) | **GET** /operations/{operationId} | Obtém os dados da operação de Câmbio identificada por operationId.
*ProductListApi* | [**exchangesGetOperations**](docs/Api/ProductListApi.md#exchangesgetoperations) | **GET** /operations | Obtém a lista de operações de Câmbio mantidas pelo cliente na instituição transmissora e para as quais ele tenha fornecido consentimento.

## Models

- [EnumExchangesDeliveryForeignCurrency](docs/Model/EnumExchangesDeliveryForeignCurrency.md)
- [EnumExchangesEventType](docs/Model/EnumExchangesEventType.md)
- [EnumExchangesOperationType](docs/Model/EnumExchangesOperationType.md)
- [Events](docs/Model/Events.md)
- [EventsForeignPartie](docs/Model/EventsForeignPartie.md)
- [EventsLocalCurrencyOperationTax](docs/Model/EventsLocalCurrencyOperationTax.md)
- [EventsOperationOutstandingBalance](docs/Model/EventsOperationOutstandingBalance.md)
- [Links](docs/Model/Links.md)
- [LinksOperationId](docs/Model/LinksOperationId.md)
- [MetaWithAbleAdditionalProperties](docs/Model/MetaWithAbleAdditionalProperties.md)
- [OKResponseEvents](docs/Model/OKResponseEvents.md)
- [OKResponseOperationDetails](docs/Model/OKResponseOperationDetails.md)
- [OKResponseProductList](docs/Model/OKResponseProductList.md)
- [OpenDataMeta](docs/Model/OpenDataMeta.md)
- [OpenDataMetaOperationId](docs/Model/OpenDataMetaOperationId.md)
- [OperationDetails](docs/Model/OperationDetails.md)
- [OperationDetailsForeignOperationValue](docs/Model/OperationDetailsForeignOperationValue.md)
- [OperationDetailsLocalCurrencyOperationTax](docs/Model/OperationDetailsLocalCurrencyOperationTax.md)
- [OperationDetailsLocalCurrencyOperationValue](docs/Model/OperationDetailsLocalCurrencyOperationValue.md)
- [OperationDetailsOperationOutstandingBalance](docs/Model/OperationDetailsOperationOutstandingBalance.md)
- [OperationDetailsVetAmount](docs/Model/OperationDetailsVetAmount.md)
- [ProductList](docs/Model/ProductList.md)
- [ResponseErrorWithAbleAdditionalProperties](docs/Model/ResponseErrorWithAbleAdditionalProperties.md)
- [ResponseErrorWithAbleAdditionalPropertiesErrorsInner](docs/Model/ResponseErrorWithAbleAdditionalPropertiesErrorsInner.md)

## Authorization

Authentication schemes defined for the API:
### OAuth2AuthorizationCode

- **Type**: `OAuth`
- **Flow**: `accessCode`
- **Authorization URL**: `https://authserver.example/authorization`
- **Scopes**: 
    - **exchanges**: Escopo necessário para acesso à API exchanges. O controle dos endpoints específicos é feito via permissions.

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

- API version: `1.0.0`
    - Generator version: `7.17.0`
- Build package: `org.openapitools.codegen.languages.PhpClientCodegen`
