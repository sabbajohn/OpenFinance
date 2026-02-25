# OpenAPIClient-php

API de dados cadastrais de clientes do Open Finance Brasil – Fase 2.
API que retorna os dados cadastrais de clientes e de seus representantes, incluindo dados de identificação, de qualificação financeira, informações sobre representantes cadastrados e sobre o relacionamento financeiro do cliente com a instituição transmissora dos dados.\\
Possui segregação entre pessoa natural e pessoa jurídica.\\
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

## Permissions necessárias para a API Customers

Para cada um dos paths desta API, além dos escopos (`scopes`) indicados existem `permissions` que deverão ser observadas:

### `/personal/identifications`
  - permissions:
    - GET: **CUSTOMERS_PERSONAL_IDENTIFICATIONS_READ**
### `/personal/qualifications`
  - permissions: **CUSTOMERS_PERSONAL_ADITTIONALINFO_READ**
### `/personal/financial-relations`
  - permissions:
    - GET: **CUSTOMERS_PERSONAL_ADITTIONALINFO_READ**
### `/business/identifications`
  - permissions:
    - GET: **CUSTOMERS_BUSINESS_IDENTIFICATIONS_READ**
### `/business/qualifications`
  - permissions:
    - GET: **CUSTOMERS_BUSINESS_ADITTIONALINFO_READ**
### `/business/financial-relations`
  - permissions:
    - GET: **CUSTOMERS_BUSINESS_ADITTIONALINFO_READ**


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



$apiInstance = new OpenAPI\Client\Api\CustomersApi(
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

try {
    $result = $apiInstance->customersGetBusinessFinancialRelations($authorization, $x_fapi_interaction_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling CustomersApi->customersGetBusinessFinancialRelations: ', $e->getMessage(), PHP_EOL;
}

```

## API Endpoints

All URIs are relative to *https://api.banco.com.br/open-banking/customers/v2*

Class | Method | HTTP request | Description
------------ | ------------- | ------------- | -------------
*CustomersApi* | [**customersGetBusinessFinancialRelations**](docs/Api/CustomersApi.md#customersgetbusinessfinancialrelations) | **GET** /business/financial-relations | Obtém os registros de relacionamentos com a instituição financeira e de representantes da pessoa jurídica.
*CustomersApi* | [**customersGetBusinessIdentifications**](docs/Api/CustomersApi.md#customersgetbusinessidentifications) | **GET** /business/identifications | Obtém os registros de identificação da pessoa jurídica.
*CustomersApi* | [**customersGetBusinessQualifications**](docs/Api/CustomersApi.md#customersgetbusinessqualifications) | **GET** /business/qualifications | Obtém os registros de qualificação da pessoa jurídica.
*CustomersApi* | [**customersGetPersonalFinancialRelations**](docs/Api/CustomersApi.md#customersgetpersonalfinancialrelations) | **GET** /personal/financial-relations | Obtém os registros de relacionamentos com a instituição financeira e de representantes da pessoa natural.
*CustomersApi* | [**customersGetPersonalIdentifications**](docs/Api/CustomersApi.md#customersgetpersonalidentifications) | **GET** /personal/identifications | Obtém os registros de identificação da pessoa natural.
*CustomersApi* | [**customersGetPersonalQualifications**](docs/Api/CustomersApi.md#customersgetpersonalqualifications) | **GET** /personal/qualifications | Obtém os registros de qualificação da pessoa natural.

## Models

- [BusinessAccount](docs/Model/BusinessAccount.md)
- [BusinessContacts](docs/Model/BusinessContacts.md)
- [BusinessFinancialRelationData](docs/Model/BusinessFinancialRelationData.md)
- [BusinessIdentificationData](docs/Model/BusinessIdentificationData.md)
- [BusinessInformedPatrimony](docs/Model/BusinessInformedPatrimony.md)
- [BusinessOtherDocument](docs/Model/BusinessOtherDocument.md)
- [BusinessPostalAddress](docs/Model/BusinessPostalAddress.md)
- [BusinessProcurator](docs/Model/BusinessProcurator.md)
- [BusinessQualificationData](docs/Model/BusinessQualificationData.md)
- [CustomerEmail](docs/Model/CustomerEmail.md)
- [CustomerPhone](docs/Model/CustomerPhone.md)
- [EconomicActivity](docs/Model/EconomicActivity.md)
- [EnumAccountTypeCustomers](docs/Model/EnumAccountTypeCustomers.md)
- [EnumCountrySubDivision](docs/Model/EnumCountrySubDivision.md)
- [EnumCustomerPhoneType](docs/Model/EnumCustomerPhoneType.md)
- [EnumFiliationType](docs/Model/EnumFiliationType.md)
- [EnumInformedIncomeFrequency](docs/Model/EnumInformedIncomeFrequency.md)
- [EnumInformedRevenueFrequency](docs/Model/EnumInformedRevenueFrequency.md)
- [EnumMaritalStatusCode](docs/Model/EnumMaritalStatusCode.md)
- [EnumOccupationMainCodeType](docs/Model/EnumOccupationMainCodeType.md)
- [EnumPartiesParticipationDocumentType](docs/Model/EnumPartiesParticipationDocumentType.md)
- [EnumPersonalOtherDocumentType](docs/Model/EnumPersonalOtherDocumentType.md)
- [EnumProcuratorsTypePersonal](docs/Model/EnumProcuratorsTypePersonal.md)
- [EnumProductServiceType](docs/Model/EnumProductServiceType.md)
- [EnumSex](docs/Model/EnumSex.md)
- [GeographicCoordinates](docs/Model/GeographicCoordinates.md)
- [InformedIncome](docs/Model/InformedIncome.md)
- [InformedIncomeAmount](docs/Model/InformedIncomeAmount.md)
- [InformedPatrimonyAmount](docs/Model/InformedPatrimonyAmount.md)
- [InformedRevenue](docs/Model/InformedRevenue.md)
- [InformedRevenueAmount](docs/Model/InformedRevenueAmount.md)
- [Links](docs/Model/Links.md)
- [Meta](docs/Model/Meta.md)
- [Nationality](docs/Model/Nationality.md)
- [NationalityOtherDocument](docs/Model/NationalityOtherDocument.md)
- [PartiesParticipation](docs/Model/PartiesParticipation.md)
- [PaychecksBankLink](docs/Model/PaychecksBankLink.md)
- [PersonalAccount](docs/Model/PersonalAccount.md)
- [PersonalContacts](docs/Model/PersonalContacts.md)
- [PersonalDocument](docs/Model/PersonalDocument.md)
- [PersonalFinancialRelationData](docs/Model/PersonalFinancialRelationData.md)
- [PersonalIdentificationData](docs/Model/PersonalIdentificationData.md)
- [PersonalIdentificationDataFiliationInner](docs/Model/PersonalIdentificationDataFiliationInner.md)
- [PersonalInformedPatrimony](docs/Model/PersonalInformedPatrimony.md)
- [PersonalOtherDocument](docs/Model/PersonalOtherDocument.md)
- [PersonalPassport](docs/Model/PersonalPassport.md)
- [PersonalPostalAddress](docs/Model/PersonalPostalAddress.md)
- [PersonalProcurator](docs/Model/PersonalProcurator.md)
- [PersonalQualificationData](docs/Model/PersonalQualificationData.md)
- [PortabilitiesReceived](docs/Model/PortabilitiesReceived.md)
- [ResponseBusinessCustomersFinancialRelation](docs/Model/ResponseBusinessCustomersFinancialRelation.md)
- [ResponseBusinessCustomersIdentification](docs/Model/ResponseBusinessCustomersIdentification.md)
- [ResponseBusinessCustomersQualification](docs/Model/ResponseBusinessCustomersQualification.md)
- [ResponseError](docs/Model/ResponseError.md)
- [ResponseErrorErrorsInner](docs/Model/ResponseErrorErrorsInner.md)
- [ResponsePersonalCustomersFinancialRelation](docs/Model/ResponsePersonalCustomersFinancialRelation.md)
- [ResponsePersonalCustomersIdentification](docs/Model/ResponsePersonalCustomersIdentification.md)
- [ResponsePersonalCustomersQualification](docs/Model/ResponsePersonalCustomersQualification.md)

## Authorization

Authentication schemes defined for the API:
### OpenId

### OAuth2Security

- **Type**: `OAuth`
- **Flow**: `accessCode`
- **Authorization URL**: `https://authserver.example/authorization`
- **Scopes**: 
    - **customers**: Escopo necessário para acesso à API Customers. O controle dos endpoints específicos é feito via permissions.

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

- API version: `2.2.1`
    - Generator version: `7.17.0`
- Build package: `org.openapitools.codegen.languages.PhpClientCodegen`
