# OpenAPIClient-php

Família de API para permitir o pagamento sem redirecionamento via Open Finance Brasil.  
Permite tanto o gerenciamento dos dispositivos vinculados as contas quanto a autorização de consentimentos criados via fluxo sem redirecionamento.

## Validação de origin nos endpoints FIDO

Durante o cadastro do cliente (DCR/DCM), o software statement assertion possui um atributo nomeado software_origin_uris. 
Esse atributo armazena as origens permitidas para utilização do protocolo FIDO. 
Nas chamadas que possuem o argumento clientDataJSON (fido-registration e authorise), o atributo origin deve ser extraído do clientDataJSON e deve ser realizada a verificação se a origin do mesmo está contida no software_origin_uris informado no momento do DCR/DCM. 
Caso a instituição iniciadora altere ou inclua o valor do atributo software_origin_uris, será necessária realização de um novo processo de DCM com as detentoras


For more information, please visit [https://openfinancebrasil.atlassian.net/wiki/spaces/OF](https://openfinancebrasil.atlassian.net/wiki/spaces/OF).

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


$apiInstance = new OpenAPI\Client\Api\ConsentimentoApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$consent_id = 'consent_id_example'; // string | O consentId é o identificador único do consentimento e deverá ser um URN - Uniform Resource Name.   Um URN, conforme definido na [RFC8141](https://tools.ietf.org/html/rfc8141) é um Uniform Resource  Identifier - URI - que é atribuído sob o URI scheme \"urn\" e um namespace URN específico, com a intenção de que o URN  seja um identificador de recurso persistente e independente da localização.   Considerando a string urn:bancoex:C1DD33123 como exemplo para consentId temos: - o namespace(urn) - o identificador associado ao namespace da instituição detentora de conta (bancoex)  - o identificador específico dentro do namespace (C1DD33123).   Informações mais detalhadas sobre a construção de namespaces devem ser consultadas na [RFC8141](https://tools.ietf.org/html/rfc8141).
$authorization = 'authorization_example'; // string | Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado
$x_fapi_interaction_id = d78fc4e5-37ca-4da3-adf2-9b082bf92280; // string | Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser \"espelhado\" pela detentora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. A iniciadora deve acatar o valor recebido da detentora.
$x_idempotency_key = 'x_idempotency_key_example'; // string | Cabeçalho HTTP personalizado. Identificador de solicitação exclusivo para suportar a idempotência.
$consent_authorization = new \OpenAPI\Client\Model\ConsentAuthorization(); // \OpenAPI\Client\Model\ConsentAuthorization | Payload para criação de vínculo de conta.
$x_fapi_auth_date = 'x_fapi_auth_date_example'; // string | Data em que o usuário logou pela última vez com a iniciadora. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC
$x_fapi_customer_ip_address = 'x_fapi_customer_ip_address_example'; // string | O endereço IP do usuário se estiver atualmente logado com a iniciadora.
$x_customer_user_agent = 'x_customer_user_agent_example'; // string | Indica o user-agent que o usuário utiliza.
$x_bcb_nfc = True; // bool | O campo representa uma transação iniciada via NFC. O envio desse campo é obrigatório nesse cenário. As detentoras devem armazenar a informação e correlacioná-la com o consentimento e o pagamento originado.

try {
    $apiInstance->authorizeConsent($consent_id, $authorization, $x_fapi_interaction_id, $x_idempotency_key, $consent_authorization, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $x_bcb_nfc);
} catch (Exception $e) {
    echo 'Exception when calling ConsentimentoApi->authorizeConsent: ', $e->getMessage(), PHP_EOL;
}

```

## API Endpoints

All URIs are relative to *https://mtls-api.banco.com.br/open-banking/enrollments/v2*

Class | Method | HTTP request | Description
------------ | ------------- | ------------- | -------------
*ConsentimentoApi* | [**authorizeConsent**](docs/Api/ConsentimentoApi.md#authorizeconsent) | **POST** /consents/{consentId}/authorise | Autorização de um consentimento de pagamentos na jornada sem redirecionamento
*VnculoDeDispositivoApi* | [**deleteEnrollment**](docs/Api/VnculoDeDispositivoApi.md#deleteenrollment) | **PATCH** /enrollments/{enrollmentId} | Revogar ou rejeitar vínculo de conta.
*VnculoDeDispositivoApi* | [**enrollmentCreateFidoRegistrationOptions**](docs/Api/VnculoDeDispositivoApi.md#enrollmentcreatefidoregistrationoptions) | **POST** /enrollments/{enrollmentId}/fido-registration-options | Obter parâmetros para criação de credenciais FIDO2.
*VnculoDeDispositivoApi* | [**enrollmentCreateFidoSigningOptions**](docs/Api/VnculoDeDispositivoApi.md#enrollmentcreatefidosigningoptions) | **POST** /enrollments/{enrollmentId}/fido-sign-options | Obter parâmetros para autenticação FIDO2.
*VnculoDeDispositivoApi* | [**enrollmentRegisterFidoCredential**](docs/Api/VnculoDeDispositivoApi.md#enrollmentregisterfidocredential) | **POST** /enrollments/{enrollmentId}/fido-registration | Associação da credencial FIDO2 ao vínculo de conta.
*VnculoDeDispositivoApi* | [**getEnrollment**](docs/Api/VnculoDeDispositivoApi.md#getenrollment) | **GET** /enrollments/{enrollmentId} | Consultar vínculo de conta.
*VnculoDeDispositivoApi* | [**postEnrollments**](docs/Api/VnculoDeDispositivoApi.md#postenrollments) | **POST** /enrollments | Criar vínculo de conta.
*VnculoDeDispositivoApi* | [**riskSignals**](docs/Api/VnculoDeDispositivoApi.md#risksignals) | **POST** /enrollments/{enrollmentId}/risk-signals | Envio de sinais de risco para iniciação do vínculo de dispositivo

## Models

- [422ResponseConsentsAuthorizationErrorsInner](docs/Model/422ResponseConsentsAuthorizationErrorsInner.md)
- [422ResponseErrorCancelEnrollmentErrorsInner](docs/Model/422ResponseErrorCancelEnrollmentErrorsInner.md)
- [422ResponseErrorCreateEnrollmentErrorsInner](docs/Model/422ResponseErrorCreateEnrollmentErrorsInner.md)
- [422ResponseErrorFidoRegistrationErrorsInner](docs/Model/422ResponseErrorFidoRegistrationErrorsInner.md)
- [422ResponseErrorFidoRegistrationOptionsErrorsInner](docs/Model/422ResponseErrorFidoRegistrationOptionsErrorsInner.md)
- [422ResponseErrorFidoSignOptionsErrorsInner](docs/Model/422ResponseErrorFidoSignOptionsErrorsInner.md)
- [422ResponseErrorRiskSignalsErrorsInner](docs/Model/422ResponseErrorRiskSignalsErrorsInner.md)
- [BusinessEntity](docs/Model/BusinessEntity.md)
- [BusinessEntityDocument](docs/Model/BusinessEntityDocument.md)
- [ConsentAuthorization](docs/Model/ConsentAuthorization.md)
- [ConsentAuthorizationData](docs/Model/ConsentAuthorizationData.md)
- [ConsentAuthorizationDataFidoAssertion](docs/Model/ConsentAuthorizationDataFidoAssertion.md)
- [ConsentAuthorizationDataFidoAssertionResponse](docs/Model/ConsentAuthorizationDataFidoAssertionResponse.md)
- [ConsentAuthorizationDataRiskSignals](docs/Model/ConsentAuthorizationDataRiskSignals.md)
- [ConsentAuthorizationDataRiskSignalsGeolocation](docs/Model/ConsentAuthorizationDataRiskSignalsGeolocation.md)
- [ConsentAuthorizationDataRiskSignalsIntegrity](docs/Model/ConsentAuthorizationDataRiskSignalsIntegrity.md)
- [ConsentAuthorizationDataRiskSignalsScreenDimensions](docs/Model/ConsentAuthorizationDataRiskSignalsScreenDimensions.md)
- [CreateEnrollment](docs/Model/CreateEnrollment.md)
- [CreateEnrollmentData](docs/Model/CreateEnrollmentData.md)
- [DebtorAccount](docs/Model/DebtorAccount.md)
- [DeleteEnrollmentRequest](docs/Model/DeleteEnrollmentRequest.md)
- [DeleteEnrollmentRequestData](docs/Model/DeleteEnrollmentRequestData.md)
- [DeleteEnrollmentRequestDataCancellation](docs/Model/DeleteEnrollmentRequestDataCancellation.md)
- [DeleteEnrollmentRequestDataCancellationCancelledBy](docs/Model/DeleteEnrollmentRequestDataCancellationCancelledBy.md)
- [DeleteEnrollmentRequestDataCancellationCancelledByDocument](docs/Model/DeleteEnrollmentRequestDataCancellationCancelledByDocument.md)
- [DeleteEnrollmentRequestDataCancellationReason](docs/Model/DeleteEnrollmentRequestDataCancellationReason.md)
- [DeleteEnrollmentRequestDataCancellationReasonOneOf](docs/Model/DeleteEnrollmentRequestDataCancellationReasonOneOf.md)
- [DeleteEnrollmentRequestDataCancellationReasonOneOf1](docs/Model/DeleteEnrollmentRequestDataCancellationReasonOneOf1.md)
- [EnrollmentCreateFidoSigningOptionsRequest](docs/Model/EnrollmentCreateFidoSigningOptionsRequest.md)
- [EnrollmentCreateFidoSigningOptionsRequestData](docs/Model/EnrollmentCreateFidoSigningOptionsRequestData.md)
- [EnrollmentFidoOptionsInput](docs/Model/EnrollmentFidoOptionsInput.md)
- [EnrollmentFidoOptionsInputData](docs/Model/EnrollmentFidoOptionsInputData.md)
- [EnrollmentFidoRegistration](docs/Model/EnrollmentFidoRegistration.md)
- [EnrollmentFidoRegistrationData](docs/Model/EnrollmentFidoRegistrationData.md)
- [EnrollmentFidoRegistrationDataResponse](docs/Model/EnrollmentFidoRegistrationDataResponse.md)
- [EnrollmentFidoRegistrationOptions](docs/Model/EnrollmentFidoRegistrationOptions.md)
- [EnrollmentFidoRegistrationOptionsData](docs/Model/EnrollmentFidoRegistrationOptionsData.md)
- [EnrollmentFidoSignOptions](docs/Model/EnrollmentFidoSignOptions.md)
- [EnrollmentFidoSignOptionsData](docs/Model/EnrollmentFidoSignOptionsData.md)
- [EnrollmentRejectionReason](docs/Model/EnrollmentRejectionReason.md)
- [EnrollmentRevocationReason](docs/Model/EnrollmentRevocationReason.md)
- [EnumAccountPaymentsType](docs/Model/EnumAccountPaymentsType.md)
- [EnumEnrollmentCancelledFrom](docs/Model/EnumEnrollmentCancelledFrom.md)
- [EnumEnrollmentPermission](docs/Model/EnumEnrollmentPermission.md)
- [EnumEnrollmentStatus](docs/Model/EnumEnrollmentStatus.md)
- [FidoAuthenticatorSelectionCriteria](docs/Model/FidoAuthenticatorSelectionCriteria.md)
- [FidoPublicKeyCredentialCreationOptions](docs/Model/FidoPublicKeyCredentialCreationOptions.md)
- [FidoPublicKeyCredentialDescriptor](docs/Model/FidoPublicKeyCredentialDescriptor.md)
- [FidoRelyingParty](docs/Model/FidoRelyingParty.md)
- [FidoUser](docs/Model/FidoUser.md)
- [LinkSingle](docs/Model/LinkSingle.md)
- [LoggedUser](docs/Model/LoggedUser.md)
- [LoggedUserDocument](docs/Model/LoggedUserDocument.md)
- [Meta](docs/Model/Meta.md)
- [Model422ResponseConsentsAuthorization](docs/Model/Model422ResponseConsentsAuthorization.md)
- [Model422ResponseErrorCancelEnrollment](docs/Model/Model422ResponseErrorCancelEnrollment.md)
- [Model422ResponseErrorCreateEnrollment](docs/Model/Model422ResponseErrorCreateEnrollment.md)
- [Model422ResponseErrorFidoRegistration](docs/Model/Model422ResponseErrorFidoRegistration.md)
- [Model422ResponseErrorFidoRegistrationOptions](docs/Model/Model422ResponseErrorFidoRegistrationOptions.md)
- [Model422ResponseErrorFidoSignOptions](docs/Model/Model422ResponseErrorFidoSignOptions.md)
- [Model422ResponseErrorRiskSignals](docs/Model/Model422ResponseErrorRiskSignals.md)
- [ResponseCreateEnrollment](docs/Model/ResponseCreateEnrollment.md)
- [ResponseCreateEnrollmentData](docs/Model/ResponseCreateEnrollmentData.md)
- [ResponseEnrollment](docs/Model/ResponseEnrollment.md)
- [ResponseEnrollmentData](docs/Model/ResponseEnrollmentData.md)
- [ResponseEnrollmentDataCancellation](docs/Model/ResponseEnrollmentDataCancellation.md)
- [ResponseEnrollmentDataDebtorAccount](docs/Model/ResponseEnrollmentDataDebtorAccount.md)
- [ResponseError](docs/Model/ResponseError.md)
- [ResponseErrorErrorsInner](docs/Model/ResponseErrorErrorsInner.md)
- [ResponseErrorMeta](docs/Model/ResponseErrorMeta.md)
- [RiskSignals](docs/Model/RiskSignals.md)
- [RiskSignalsData](docs/Model/RiskSignalsData.md)
- [RiskSignalsDataGeolocation](docs/Model/RiskSignalsDataGeolocation.md)
- [RiskSignalsDataIntegrity](docs/Model/RiskSignalsDataIntegrity.md)
- [RiskSignalsDataScreenDimensions](docs/Model/RiskSignalsDataScreenDimensions.md)

## Authorization

Authentication schemes defined for the API:
### OpenId

### OAuth2ClientCredentials

- **Type**: `OAuth`
- **Flow**: `application`
- **Authorization URL**: ``
- **Scopes**: 
    - **payments**: Escopo necessário para acesso à API Payment Initiation.

### OAuth2AuthorizationCode

- **Type**: `OAuth`
- **Flow**: `accessCode`
- **Authorization URL**: `https://authserver.example/token`
- **Scopes**: 
    - **payments**: Escopo necessário para acesso à API Payment Initiation.
    - **openid**: Indica que a autorização está sendo realizada utilizando o protocolo definido pela openid.
    - **enrollment:enrollmentId**: Permite realizar atualização de um registro com a permissão do cliente.
    - **nrp-consents**: Consentimento para pagamentos sem redirecionamento.

## Tests

To run the tests, use:

```bash
composer install
vendor/bin/phpunit
```

## Author

squad-jornada@openfinancebrasil.org.br

## About this package

This PHP package is automatically generated by the [OpenAPI Generator](https://openapi-generator.tech) project:

- API version: `2.1.0`
    - Generator version: `7.17.0`
- Build package: `org.openapitools.codegen.languages.PhpClientCodegen`
