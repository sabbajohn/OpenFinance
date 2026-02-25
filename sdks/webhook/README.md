# OpenAPIClient-php

API de Webhook é responsável por notificar eventos definidos em cada uma das APIs que possuem a funcionalidade no Open Finance Brasil.  

Informações sobre endpoints suportados e funcionamento podem ser encontrados na página <a href=\"https://openfinancebrasil.atlassian.net/wiki/spaces/OF/pages/105021661/Conven+o+de+Webhook\">Convenção de Webhook</a>, disponível no portal do desenvolvedor do Open Finance Brasil.


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




$apiInstance = new OpenAPI\Client\Api\AutomaticPaymentsConsentsAndPixPaymentsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$recurring_consent_id = 'recurring_consent_id_example'; // string | O recurringConsentId é o identificador único do consentimento e deverá ser um URN - Uniform Resource Name. Um URN, conforme definido na [RFC8141](https://datatracker.ietf.org/doc/html/rfc8141) é um Uniform Resource Identifier - URI - que é atribuído sob o URI scheme \"urn\" e um namespace URN específico, com a intenção de que o URN seja um identificador de recurso persistente e independente da localização. Considerando a string urn:bancoex:C1DD33123 como exemplo para recurringConsentId temos:  - o namespace(urn).  - o identificador associado ao namespace da instituição transnmissora (bancoex).  - o identificador específico dentro do namespace (C1DD33123). Informações mais detalhadas sobre a construção de namespaces devem ser consultadas na [RFC8141](https://datatracker.ietf.org/doc/html/rfc8141).
$version_api = 'version_api_example'; // string | Identifica a versão da API que deverá ser utilizada para recebimento da notificação via webhook
$x_webhook_interaction_id = 'x_webhook_interaction_id_example'; // string | Identificador único para cada tentativa de notificação realizada. O identificador deverá seguir o padrão UID [RFC4122](https://tools.ietf.org/html/rfc4122).
$request_body_webhook = new \OpenAPI\Client\Model\RequestBodyWebhook(); // \OpenAPI\Client\Model\RequestBodyWebhook | Payload enviado para notificar a alteração na entidade do consentimento de longa duração.

try {
    $apiInstance->recurringConsentIdNotification($recurring_consent_id, $version_api, $x_webhook_interaction_id, $request_body_webhook);
} catch (Exception $e) {
    echo 'Exception when calling AutomaticPaymentsConsentsAndPixPaymentsApi->recurringConsentIdNotification: ', $e->getMessage(), PHP_EOL;
}

```

## API Endpoints

All URIs are relative to *https://api.banco.com.br/open-banking/webhook/v1*

Class | Method | HTTP request | Description
------------ | ------------- | ------------- | -------------
*AutomaticPaymentsConsentsAndPixPaymentsApi* | [**recurringConsentIdNotification**](docs/Api/AutomaticPaymentsConsentsAndPixPaymentsApi.md#recurringconsentidnotification) | **POST** /automatic-payments/{versionApi}/recurring-consents/{recurringConsentId} | Notificações de mudanças da entidade de consentimentos da API de Pagamentos automáticos.
*AutomaticPaymentsConsentsAndPixPaymentsApi* | [**recurringPaymentIdNotification**](docs/Api/AutomaticPaymentsConsentsAndPixPaymentsApi.md#recurringpaymentidnotification) | **POST** /automatic-payments/{versionApi}/pix/recurring-payments/{recurringPaymentId} | Notificações de mudanças da entidade de pagamentos da API de Pagamentos automáticos.
*NoRedirectEnrollmentIdNotificationApi* | [**enrollmentIdNotification**](docs/Api/NoRedirectEnrollmentIdNotificationApi.md#enrollmentidnotification) | **POST** /enrollments/{versionApi}/enrollments/{enrollmentId} | Notificações de mudanças de estados do vínculo de conta da API - Pagamentos sem Redirecionamento.
*PaymentsConsentsAndPixPaymentsApi* | [**consentNotification**](docs/Api/PaymentsConsentsAndPixPaymentsApi.md#consentnotification) | **POST** /payments/{versionApi}/consents/{consentId} | Notificações de mudanças de estados de consentimentos da API de Iniciação de Pagamentos.
*PaymentsConsentsAndPixPaymentsApi* | [**pixPaymentNotification**](docs/Api/PaymentsConsentsAndPixPaymentsApi.md#pixpaymentnotification) | **POST** /payments/{versionApi}/pix/payments/{paymentId} | Notificações de mudanças de estados do pagamento: Arranjo Pix da API de Iniciação de Pagamentos.

## Models

- [RequestBodyWebhook](docs/Model/RequestBodyWebhook.md)
- [RequestBodyWebhookData](docs/Model/RequestBodyWebhookData.md)

## Authorization
Endpoints do not require authorization.

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

- API version: `1.2.0`
    - Generator version: `7.17.0`
- Build package: `org.openapitools.codegen.languages.PhpClientCodegen`
