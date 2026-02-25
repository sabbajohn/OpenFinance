# OpenAPIClient-php

A API de Portabilidade de Crédito permite que usuários transfiram suas operações de crédito e arrendamento mercantil entre instituições financeiras em busca de melhores condições para o Open Finance Brasil.

# Orientações

## Assinatura de payloads:
  No contexto da API de Portabilidade de crédito, os payloads de mensagem que trafegam tanto por parte da instituição credora quanto por parte da instituição proponente devem estar assinados. Para o processo de assinatura destes payloads as instituições devem seguir as especificações de segurança publicadas no Portal do desenvolvedor  
    &nbsp;&nbsp;- Certificados exigidos para assinatura de mensagens: [[PT] Padrão de Certificados Open Finance Brasil 2.1](https://openfinancebrasil.atlassian.net/wiki/spaces/OF/pages/245694518/PT+Padr+o+de+Certificados+Open+Finance+Brasil+2.1)  
    &nbsp;&nbsp;- Como assinar o payload JWS: [Como Assinar o Payload](https://openfinancebrasil.atlassian.net/wiki/spaces/OF/pages/905740608/Como+assinar+o+payload+-+PC+Portabilidade+de+Cr+dito+-+CPC)

## Controle de Acesso
- Os endpoints [GET] /portabilities/{portabilityId}, [GET] /portabilities/{portabilityId}/account-data, [POST] /portabilities/{portabilityId}/payment, [PATCH] / portabilities/{portabilityId}/cancel da API de Portabilidade de crédito devem utilizar o escopo client_credentials
- Os endpoints [GET] /credit-operations/{contractId}/portability-eligibility e [POST] /portabilities devem utilizar o escopo authorization_code para validar a permissions de LOANS

## Validações para Portabilidade de Crédito
**- Validações** (após o processo de DCR e obtenção de token client credential - não escopo dessa documentação):   
  &nbsp;Durante o processo de portabilidade de crédito, diferentes validações são necessárias pela instituição credora e devem ocorrer conforme a seguir:    
**- Casos de erro relacionados às permissões de segurança para acesso à API** (ex. certificado, access_token, jwt, assinatura):    
  Validação de Certificado: Valida utilização de certificado correto durante processo de DCR - HTTP Code 401 (INVALID_CLIENT);   
  Validação de Access_Token: Verifica se Access_Token utilizado está correto - HTTP Code 401 (UNAUTHORIZED);   
  Validação de assinatura da mensagem: Valida se assinatura das mensagens enviadas está correta – HTTP Code 400 (BAD_SIGNATURE);   
  Validação de Claims (exceto data);   
    &emsp;- Valida se dados (aud, iss, iat e jti) são válidos - HTTP status code 403 - (INVALID_CLIENT);  
    &emsp;- Valida reuso de jti - HTTP Code 403 (INVALID_CLIENT).
    
## Validações de erros sintáticos e semânticos, previstas com retorno HTTP Code 422 - Unprocessable Entity
  **- Para todos os endpoints:**   
    &nbsp;&nbsp;**Sintáticos**   
      &emsp;- Envio de campos obrigatórios: Valida se todos os campos obrigatórios são informados (PARAMETRO_NAO_INFORMADO);   
      &emsp;- Formatação de parâmetros: Valida se parâmetros informados obedecem a formatação especificada (PARAMETRO_INVALIDO).   
      &emsp;- Demais validações não explicitamente informadas (NAO_INFORMADO)   
**- Para endpoint ([POST] /portabilities):**   
    &nbsp;&nbsp;**Semânticos**   
    &emsp;- Portabilidade em andamento: Valida se já existe um pedido de portabilidade de crédito para o contrato solicitado pelo trilho do OFB ou da Registradora (EM_ANDAMENTO);   
    &emsp;- Prazo do empréstimo maior ao restante das parcelas a serem liquidadas no contrato original (PRAZO_ACIMA_LIMITE);   
    &emsp;- ID de contrato inválida (CONTRATO_INVALIDO);   
    &emsp;- Contrato não elegível para portabilidade dentro do trilho do OFB (CONTRATO_NAO_ELEGIVEL);   
    &emsp;- Idempotência: Valida se há divergência entre chave de idempotência e informações enviadas (ERRO_IDEMPOTENCIA);   
    &emsp;- Evidência de assinatura do contrato: Valida se o objeto de assinatura do contrato foi preenchido pela instituição proponente devidamente, em caso de ausência (SEM_EVIDENCIA_ASSINATURA);   
    &emsp;- Periodicidade: Valida se não houve mudança na periodicidade entre o novo contrato e o contrato original, caso tenha sido alterado a periodicidade (PERIODICIDADE_INVALIDA);   
    &emsp;- Campo com preenchimento incorreto: Valida se o preenchimento de alguns campos estão corretos 
    Ex.: CNPJ da instituição credora deve ser o mesmo retornado pela API de Empréstimos (CAMPO_INCONSISTENTE)   
**- Para endpoint ([POST] /portabilities/{portabilityId}/payment):**   
  &nbsp;&nbsp;**Semânticos**   
    &emsp;- Estado da portabilidade diferente de ACCEPTED_SETTLEMENT_IN_PROGRESS ou PAYMENT_ISSUE (PAGAMENTO_EFETUADO_FORA_PRAZO). Obs.: Caso o pagamento tenha sido feito por engano a Instituição Proponente deve solicitar o estorno.   
**- Para endpoint ([PATCH] /portabilities/{portabilityId}/cancel):**   
  &nbsp;&nbsp;**Semânticos**   
    &emsp;- Estado da portabilidade diferente de RECEIVED, PENDING ou ACCEPTED_SETTLEMENT_IN_PROGRESS (CANCELAMENTO_NÃO_EFETUADO). Obs.: De acordo com o PRD o usuário poderá cancelar o pedido de portabilidade até a etapa de liquidação, após esta etapa não será mais permitido o cancelamento da portabilidade   


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



// Configure OAuth2 access token for authorization: OAuth2ClientCredentials
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\AccountDataApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$portability_id = 'portability_id_example'; // string | Identificador do pedido de portabilidade de crédito.
$authorization = 'authorization_example'; // string | Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado.
$x_fapi_interaction_id = d78fc4e5-37ca-4da3-adf2-9b082bf92280; // string | Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela IF Proponente (client) e o seu valor deve ser “espelhado” pela IF Credora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a IF Credora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A IF Proponente deve acatar o valor recebido da IF Credora.
$x_fapi_auth_date = 'x_fapi_auth_date_example'; // string | Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC
$x_fapi_customer_ip_address = 'x_fapi_customer_ip_address_example'; // string | O endereço IP do usuário se estiver atualmente logado com o receptor.
$x_customer_user_agent = 'x_customer_user_agent_example'; // string | Indica o user-agent que o usuário utiliza.

try {
    $result = $apiInstance->creditPortabilityGetAccountData($portability_id, $authorization, $x_fapi_interaction_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountDataApi->creditPortabilityGetAccountData: ', $e->getMessage(), PHP_EOL;
}

```

## API Endpoints

All URIs are relative to *https://api.banco.com.br/open-banking/credit-portability/v1*

Class | Method | HTTP request | Description
------------ | ------------- | ------------- | -------------
*AccountDataApi* | [**creditPortabilityGetAccountData**](docs/Api/AccountDataApi.md#creditportabilitygetaccountdata) | **GET** /portabilities/{portabilityId}/account-data | Obtém os dados necessários para realização do pagamento da operação via TED.
*ConcurrencyManagementApi* | [**creditPortabilityGetCreditOperationsContratIdPortabilityEligibility**](docs/Api/ConcurrencyManagementApi.md#creditportabilitygetcreditoperationscontratidportabilityeligibility) | **GET** /credit-operations/{contractId}/portability-eligibility | Informa se um contrato pertencente a um determinado cliente estará habilitado para a realização do pedido de portabilidade de crédito considerando a regra de só existir um pedido de portabilidade para um determinado contrato.
*CreditPortabilityApi* | [**creditPortabilityGetPortabilitiesByPortabilityId**](docs/Api/CreditPortabilityApi.md#creditportabilitygetportabilitiesbyportabilityid) | **GET** /portabilities/{portabilityId} | Consulta portabilidade de crédito através da propriedade portabilityId.
*CreditPortabilityApi* | [**creditPortabilityPatchPortabilitiesPortabilityIdCancel**](docs/Api/CreditPortabilityApi.md#creditportabilitypatchportabilitiesportabilityidcancel) | **PATCH** /portabilities/{portabilityId}/cancel | Comunica a Instituição Credora a respeito do cancelamento da portabilidade de crédito.
*CreditPortabilityApi* | [**creditPortabilityPostPortabilities**](docs/Api/CreditPortabilityApi.md#creditportabilitypostportabilities) | **POST** /portabilities | Realiza pedido de portabilidade de crédito para um determinado contrato junto a instituição credora
*PaymentsApi* | [**creditPortabilityPostPortabilitiesPortabilityIdPayment**](docs/Api/PaymentsApi.md#creditportabilitypostportabilitiesportabilityidpayment) | **POST** /portabilities/{portabilityId}/payment | Comunica a Instituição Credora a respeito da liquidação da portabilidade de crédito.

## Models

- [EnumReferentialRateIndexerSubType](docs/Model/EnumReferentialRateIndexerSubType.md)
- [Links](docs/Model/Links.md)
- [LoansContractInterestRate](docs/Model/LoansContractInterestRate.md)
- [Meta](docs/Model/Meta.md)
- [POSTResponseCreditPortability](docs/Model/POSTResponseCreditPortability.md)
- [POSTResponseCreditPortabilityData](docs/Model/POSTResponseCreditPortabilityData.md)
- [POSTResponseCreditPortabilityPayment](docs/Model/POSTResponseCreditPortabilityPayment.md)
- [POSTResponseCreditPortabilityPaymentData](docs/Model/POSTResponseCreditPortabilityPaymentData.md)
- [POSTResponseCreditPortabilityPaymentMeta](docs/Model/POSTResponseCreditPortabilityPaymentMeta.md)
- [PatchResponseCreditPortabilityCancel](docs/Model/PatchResponseCreditPortabilityCancel.md)
- [RequestCreditPortability](docs/Model/RequestCreditPortability.md)
- [RequestCreditPortabilityCancel](docs/Model/RequestCreditPortabilityCancel.md)
- [RequestCreditPortabilityCancelData](docs/Model/RequestCreditPortabilityCancelData.md)
- [RequestCreditPortabilityCancelDataReason](docs/Model/RequestCreditPortabilityCancelDataReason.md)
- [RequestCreditPortabilityData](docs/Model/RequestCreditPortabilityData.md)
- [RequestCreditPortabilityDataContractIdentification](docs/Model/RequestCreditPortabilityDataContractIdentification.md)
- [RequestCreditPortabilityDataCustomerContactInner](docs/Model/RequestCreditPortabilityDataCustomerContactInner.md)
- [RequestCreditPortabilityDataInstitution](docs/Model/RequestCreditPortabilityDataInstitution.md)
- [RequestCreditPortabilityDataInstitutionCreditor](docs/Model/RequestCreditPortabilityDataInstitutionCreditor.md)
- [RequestCreditPortabilityDataInstitutionProposing](docs/Model/RequestCreditPortabilityDataInstitutionProposing.md)
- [RequestCreditPortabilityDataInstitutionProposingContactInner](docs/Model/RequestCreditPortabilityDataInstitutionProposingContactInner.md)
- [RequestCreditPortabilityDataProposedContract](docs/Model/RequestCreditPortabilityDataProposedContract.md)
- [RequestCreditPortabilityDataProposedContractContractAmount](docs/Model/RequestCreditPortabilityDataProposedContractContractAmount.md)
- [RequestCreditPortabilityDataProposedContractContractedFeesInner](docs/Model/RequestCreditPortabilityDataProposedContractContractedFeesInner.md)
- [RequestCreditPortabilityDataProposedContractContractedFeesInnerFeeAmount](docs/Model/RequestCreditPortabilityDataProposedContractContractedFeesInnerFeeAmount.md)
- [RequestCreditPortabilityDataProposedContractContractedFinanceChargesInner](docs/Model/RequestCreditPortabilityDataProposedContractContractedFinanceChargesInner.md)
- [RequestCreditPortabilityDataProposedContractDigitalSignatureProof](docs/Model/RequestCreditPortabilityDataProposedContractDigitalSignatureProof.md)
- [RequestCreditPortabilityDataProposedContractInstallmentAmount](docs/Model/RequestCreditPortabilityDataProposedContractInstallmentAmount.md)
- [RequestCreditPortabilityPayment](docs/Model/RequestCreditPortabilityPayment.md)
- [RequestCreditPortabilityPaymentData](docs/Model/RequestCreditPortabilityPaymentData.md)
- [ResponseAccountData](docs/Model/ResponseAccountData.md)
- [ResponseAccountDataData](docs/Model/ResponseAccountDataData.md)
- [ResponseAccountDataDataStrCode](docs/Model/ResponseAccountDataDataStrCode.md)
- [ResponseErrorWithAbleAdditionalProperties](docs/Model/ResponseErrorWithAbleAdditionalProperties.md)
- [ResponseErrorWithAbleAdditionalPropertiesErrorsInner](docs/Model/ResponseErrorWithAbleAdditionalPropertiesErrorsInner.md)
- [ResponsePortabilitiesByPortabilityId](docs/Model/ResponsePortabilitiesByPortabilityId.md)
- [ResponsePortabilitiesByPortabilityIdData](docs/Model/ResponsePortabilitiesByPortabilityIdData.md)
- [ResponsePortabilitiesByPortabilityIdDataInstitution](docs/Model/ResponsePortabilitiesByPortabilityIdDataInstitution.md)
- [ResponsePortabilitiesByPortabilityIdDataInstitutionCreditor](docs/Model/ResponsePortabilitiesByPortabilityIdDataInstitutionCreditor.md)
- [ResponsePortabilitiesByPortabilityIdDataLoanSettlementInstruction](docs/Model/ResponsePortabilitiesByPortabilityIdDataLoanSettlementInstruction.md)
- [ResponsePortabilitiesByPortabilityIdDataLoanSettlementInstructionSettlementAmount](docs/Model/ResponsePortabilitiesByPortabilityIdDataLoanSettlementInstructionSettlementAmount.md)
- [ResponsePortabilitiesByPortabilityIdDataProposedContract](docs/Model/ResponsePortabilitiesByPortabilityIdDataProposedContract.md)
- [ResponsePortabilitiesByPortabilityIdDataProposedContractContractedFinanceChargesInner](docs/Model/ResponsePortabilitiesByPortabilityIdDataProposedContractContractedFinanceChargesInner.md)
- [ResponsePortabilitiesByPortabilityIdDataRejection](docs/Model/ResponsePortabilitiesByPortabilityIdDataRejection.md)
- [ResponsePortabilitiesByPortabilityIdDataRejectionReason](docs/Model/ResponsePortabilitiesByPortabilityIdDataRejectionReason.md)
- [ResponsePortabilitiesByPortabilityIdDataStatusReason](docs/Model/ResponsePortabilitiesByPortabilityIdDataStatusReason.md)
- [ResponsePortabilitiesByPortabilityIdDataStatusReasonDigitalSignatureProof](docs/Model/ResponsePortabilitiesByPortabilityIdDataStatusReasonDigitalSignatureProof.md)
- [ResponsePortabilityEligibility](docs/Model/ResponsePortabilityEligibility.md)
- [ResponsePortabilityEligibilityData](docs/Model/ResponsePortabilityEligibilityData.md)
- [ResponsePortabilityEligibilityDataPortability](docs/Model/ResponsePortabilityEligibilityDataPortability.md)
- [ResponsePortabilityEligibilityDataPortabilityIneligible](docs/Model/ResponsePortabilityEligibilityDataPortabilityIneligible.md)

## Authorization

Authentication schemes defined for the API:
### OpenId

### OAuth2ClientCredentials

- **Type**: `OAuth`
- **Flow**: `application`
- **Authorization URL**: ``
- **Scopes**: 
    - **credit-portability**: Escopo necessário para acesso à API Portabilidade de Crédito.

### OAuth2AuthorizationCodeLoans

- **Type**: `OAuth`
- **Flow**: `accessCode`
- **Authorization URL**: `https://authserver.example/authorization`
- **Scopes**: 
    - **loans**: Escopo necessário para acesso à API Loans. O controle dos endpoints específicos é feito via permissions.
    - **openId**: Indica que a autorização está sendo realizada utilizando o protocolo definido pela openid.
    - **consent:consentId**: Fluxo OAuth necessário para que a instituição proponente tenha acesso aos dados na instituição credora. Requer o processo de redirecionamento e autenticação do usuário a que se referem os dados.

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
