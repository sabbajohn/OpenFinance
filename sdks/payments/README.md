# OpenAPIClient-php

API de Iniciação de Pagamentos, responsável por viabilizar as operações de iniciação de pagamentos para o Open Finance Brasil.
Para cada uma das formas de pagamento previstas é necessário obter prévio consentimento do cliente através dos `endpoints` dedicados ao consentimento nesta API.

# Orientações
No diretório de participantes duas `Roles` estão relacionadas à presente API:
- `CONTA`, referente às instituições detentoras de conta participantes do Open Finance Brasil;
- `PAGTO`, referente às instituições iniciadoras de pagamento participantes do Open Finance Brasil. 
Os tokens utilizados para consumo nos endpoints de consentimentos devem possuir o scope `payments` e os `endpoints` de pagamentos devem possuir os `scopes`, `openid` e `payments`. 
Esta API não requer a implementação de `permissions` para sua utilização. Todas as requisições e respostas devem ser assinadas seguindo o protocolo estabelecido na sessão <a href=\"https://openbanking-brasil.github.io/areadesenvolvedor/#assinaturas\" target=\"_blank\">Assinaturas</a> do guia de segurança.

## Regras do arranjo Pix
A implementação e o uso da API de Pagamentos Pix devem seguir as regras do arranjo Pix do Banco Central, que podem ser encontradas no link abaixo:   
[https://www.bcb.gov.br/estabilidadefinanceira/pix?modalAberto=regulamentacao_pix](https://www.bcb.gov.br/estabilidadefinanceira/pix?modalAberto=regulamentacao_pix)

## Assinatura de payloads

No contexto da API Payment Initiation, os `payloads` de mensagem que trafegam tanto por parte da instituição iniciadora de transação de pagamento quanto por parte da instituição detentora
de conta devem estar assinados. Para o processo de assinatura destes `payloads` as instituições devem seguir as especificações de segurança publicadas no Portal do desenvolvedor:

- Certificados exigidos para assinatura de mensagens:
[[EN] Padrão de Certificados Open Finance Brasil 2.1](https://openfinancebrasil.atlassian.net/wiki/spaces/OF/pages/82084176/EN+Padr+o+de+Certificados+Open+Finance+Brasil+2.1%20%E2%80%8B)

- Como assinar o payload JWS: [Como Assinar o Payload](https://openfinancebrasil.atlassian.net/wiki/spaces/OF/pages/180061175/Como+Assinar+o+Payload+-+v3.0.0+-+Pagamentos)

## Controle de acesso

Os endpoints de consulta e cancelamento devem suportar somente acesso a partir de access_token emitido por meio de um grant_type do tipo client_credentials.

Para a criação do consentimento deve-se utilizar client_credentials e para criação de pagamentos deve-se utilizar authorization_code.

## Aprovações de múltipla alçada

- Para o caso de pagamento imediato, todas as aprovações necessárias devem ser realizadas nos canais da detentora até às 23:59 (horário de Brasília) da data de solicitação do pagamento. 
Já para o caso de pagamento agendado, todas as aprovações devem ser realizadas até o exato dia anterior à data/hora prevista para primeira liquidação, respeitando a data/hora limite suportada pela detentora. 
Caso não seja possível aprovação, o consentimento deve ser rejeitado pelo detentor.

## Validações para pagamentos recorrentes

- No cenário onde o usuário pagador tenha agendado recorrências para os dias 29, 30 ou 31 de cada mês e o dia previsto na recorrência não exista no respectivo mês, 
o iniciador deve enviar a ordem de pagamento para liquidação com o endToEndId representando o dia seguinte à data prevista para a liquidação. 
Se identificado pelo detentor que a data enviada no endToEndId corresponde a um dia inexistente, ele deve rejeitar o pagamento com erro 422, 
com código PARAMETRO_INVALIDO e detalhe “Data de liquidação inválida”

- Quando o detentor receber mais de um item na lista de pagamentos enviados pelo iniciador e optar por responder 
assincronamente, é de responsabilidade do detentor realizar a transição para o status SCHD de todos os itens enviados na 
lista de pagamentos em até 60 minutos (contados a partir da resposta de sucesso da solicitação). 
Caso não seja possível realizar a transição de todos os pagamentos para SCHD, o detentor deverá mover todos os pagamentos 
enviados pelo iniciador naquela mesma requisição para RJCT e preencher o motivo de rejeição correspondente, 
FALHA_AGENDAMENTO_PAGAMENTOS. O consentimento irá para CONSUMED.

## Validações
**Validações** (*após o processo de DCR e obtenção de token client credential*– não escopo dessa documentação)  
Durante a jornada de iniciação de pagamento, diferentes validações são necessárias pela instituição detentora 
de conta e devem ocorrer conforme a seguir: 

1. Na criação do consentimento (*POST /consents*); 
2. Na criação do pagamento - Síncrono (*POST /payments*); 
3. Validações na consulta do pagamento (*GET /pix/payments/{paymentId}*);
4. Demais validações executadas durante o processamento assíncrono do pagamento pela detentora, poderão ser consultados pela iniciadora através do endpoint (*GET /pix/payments/{paymentId}*) previstos com retorno HTTP Code 200 - OK com status RJCT (Rejected) e rejectionReason;
5. Demais validações executadas durante o processamento assíncrono do consentimento pela detentora poderão ser consultados pela iniciadora através do endpoint (*GET /consents/{consentId}*) previstos com retorno HTTP Code 200 – OK com status REJECTED e rejectionReason

**Os tipos de validações dispostas abaixo não determinam a ordem em que as instituições devem implementá-las**

1. **Validações na criação do consentimento (_POST /consents_)**  
  1.1 **Orientações Iniciais**  
    &ensp;1.1.1 Não devem ser retornadas na resposta deste endpointinformações associadas ao usuário/cliente (ex.  insuficiência de saldo, conta inexistente/bloqueada).  
    &ensp;1.1.2 Não devem ser executadas validações no DICT (Diretório de Identificadores de Contas Transacionais do Pix), a partir dos dados compartilhados nesse *endpoint*. Tais  validações podem ocorrer somente na criação do pagamento;   
    &ensp;1.1.3 Não devem ser realizadas validações de informações sobre o usuário/cliente durante a criação do consentimento.
  1.2 **Casos de erro relacionados às permissões de segurança para acesso à API (ex. certificado, access_token, jwt, assinatura)**  
    &ensp;1.2.1 Validação de Certificado: Valida utilização de certificado correto durante processo de DCR - HTTP Code 401 (INVALID_CLIENT);  
    &ensp;1.2.2 Validação de Access_Token: Verifica se Access_Token utilizado está correto - HTTP Code 401 (UNAUTHORIZED);  
    &ensp;1.2.3 Validação de assinatura da mensagem: Valida se assinatura das mensagens enviadas está correta – HTTP Code 400 (BAD_SIGNATURE);  
    &ensp;1.2.4 Validação de Claims (exceto data);  
      &emsp;1.2.4.1 Valida se dados (aud, iss, iat e jti) são válidos - HTTP status code 403 – (INVALID_CLIENT);  
      &emsp;1.2.4.2 Valida reuso de jti - HTTP Code 403 (INVALID_CLIENT).  
  1.3 **Casos de erro sintáticos e semânticos, previstos com retorno HTTP Code 422 - Unprocessable Entity (detalhamento adicional na documentação técnica da API):**   
    &ensp;1.3.1 **Sintáticos**  
      &emsp;1.3.1.1 Envio de campos obrigatórios: Valida se todos os campos obrigatórios são informados (PARAMETRO_NAO_INFORMADO);  
      &emsp;1.3.1.2 Formatação de parâmetros: Valida se parâmetros informados obedecem a formatação especificada (PARAMETRO_INVALIDO).  
    &ensp;1.3.2 **Semânticos**  
      &emsp;1.3.2.1 Forma de pagamento: Valida se a forma de pagamento é suportada pela detentora (FORMA_PAGAMENTO_INVALIDA) **Obs. No detalhe do erro, a variável “modalidade” deve ser comunicada pela detentora da forma mais clara possível - ex. modalidade de pagamento não suportada (_localInstrument_ - QRES) ou tipo de arranjo pagamento não suportado (_type_ – ex. Pix / TED – previsto para inclusão futura);**  
      &emsp;1.3.2.2 Data de pagamento: Valida se a data de pagamento enviada é válida para a forma de pagamento selecionada (DATA_PAGAMENTO_INVALIDA);  
      &emsp;1.3.2.3 Detalhes do pagamento: Valida se determinado parâmetro informado obedece às regras de negócio (DETALHE_PAGAMENTO_INVALIDO);  
      &emsp;1.3.2.4 Demais validações não explicitamente informadas (ex. suspeita de fraude): (NAO_INFORMADO);  
      &emsp;1.3.2.5 Idempotência: Valida se há divergência entre chave de idempotência e informações enviadas (ERRO_IDEMPOTENCIA).  

2. **Validações na criação do pagamento - Síncrono (_POST /payments_)**  
  2.1 **Casos de erro relacionados às permissões de segurança para acesso à API (ex. certificado, access_token, jwt, assinatura)**  
    &ensp;2.1.1 Validação de Certificado: Valida utilização de certificado correto durante processo de DCR - HTTP Code 401 (INVALID_CLIENT);  
    &ensp;2.1.2 Validação de Access_Token: Verifica se Access_Token utilizado está correto - HTTP Code 401 (UNAUTHORIZED);  
    &ensp;2.1.3 Validação de assinatura da mensagem: Valida se assinatura das mensagens enviadas está correta – HTTP Code 400 (BAD_SIGNATURE);  
    &ensp;2.1.4 Validação de Claims (exceto data);  
      &emsp;2.1.4.1 Valida se dados (aud, iss, iat e jti) são válidos - HTTP status code 403 – (INVALID_CLIENT);  
      &emsp;2.1.4.2 Valida reuso de jti - HTTP Code 403 (INVALID_CLIENT).  
  2.2 **Casos de erro sintáticos e semânticos, previstos com retorno HTTP Code 422 - Unprocessable Entity (detalhamento adicional na documentação técnica da API):**  
    &ensp;2.2.1 **Sintáticos**  
      &emsp;2.3.1.1 Envio de campos obrigatórios: Valida se todos os campos obrigatórios são informados (PARAMETRO_NAO_INFORMADO);  
      &emsp;2.3.1.2 Formatação de parâmetros: Valida se parâmetros informados obedecem a formatação especificada (PARAMETRO_INVALIDO).  
    &ensp;2.2.2 **Semânticos**  
      &emsp;2.2.2.1 Saldo do usuário: Valida se a conta selecionada possui saldo suficiente para realizar o pagamento (SALDO_INSUFICIENTE);  
      &emsp;2.2.2.2 Limites da transação: Valida se valor (ou quantidade de transações) ultrapassa faixa de limite parametrizada na detentora (VALOR_ACIMA_LIMITE);  
      &emsp;2.2.2.3 Valor informado (QR Code): Valida se valor enviado é válido para o QR Code informado (VALOR_INVALIDO);  
      &emsp;2.2.2.4 Cobrança inválida: Valida expiração, vencimento e status (COBRANCA_INVALIDA);  
      &emsp;2.2.2.5 Status Consentimento: Valida se o consentimento encontra-se em um dos estados finais “CONSUMED” ou “REJECTED\" (CONSENTIMENTO_INVALIDO);  
      &emsp;2.2.2.6 Divergência entre pagamento e consentimento: Valida se dados do pagamento são diferentes dos dados do consentimento (PAGAMENTO_DIVERGENTE_CONSENTIMENTO)  
      &emsp;2.2.2.7 Recusado pela detentora: Valida se pagamento foi recusado pela detentora (PAGAMENTO_RECUSADO_DETENTORA), com a descrição do motivo de recusa (ex. chave Pix inválida, QRCode inválido, conta bloqueada);  
      &emsp;2.2.2.8 Detalhes do pagamento: Valida se determinado parâmetro informado obedece às regras de negócio (DETALHE_PAGAMENTO_INVALIDO);  
      &emsp;2.2.2.9 Demais validações não explicitamente informadas (ex. suspeita de fraude): (NAO_INFORMADO);  
      &emsp;2.2.2.10 Idempotência: Valida se há divergência entre chave de idempotência e informações enviadas (ERRO_IDEMPOTENCIA);  
      &emsp;2.2.2.11 Consentimento pendente de autorização: Em `PARTIALLY_ACCEPTED` aguardando aprovação de múltiplas alçadas. Não consome nem invalida o consentimento (CONSENTIMENTO_PENDENTE_AUTORIZACAO).  
  2.3 **Casos de erro para validações síncronas no DICT**  
    &ensp;Nesse cenário, o pagamento não é criado, porém o consentimento deve ser alterado para o status CONSUMED Retorno esperado do endpoint POST/Payments: HTTP Code 422 - Unprocessable Entity:  
    &ensp;• Erro por dados inválidos: Conforme item **2.2.2.8**  
    &ensp;• Erro por suspeita de fraude: Conforme item **2.2.2.9**  

3. **Validações na consulta do pagamento (_GET /pix/payments/{paymentId}_)**  
  3.1 **Casos de erro relacionados às permissões de segurança para acesso à API (ex. certificado, access_token)**  
    &ensp;3.1.1 Validação de Certificado: Valida utilização de certificado correto durante processo de DCR - HTTP Code 401 (INVALID_CLIENT);  
    &ensp;3.1.2 Validação de Access_Token: Verifica se Access_Token utilizado está correto - HTTP Code 401 (UNAUTHORIZED).  

4. **Demais validações executadas durante o processamento assíncrono do pagamento pela detentora, poderão ser consultados pela iniciadora através do endpoint _GET /pix/payments/{paymentId}_ previstos com retorno HTTP Code 200 - OK com status RJCT (Rejected) e rejectionReason conforme abaixo (detalhamento adicional na documentação técnica da API):**  
  4.1 **Demais validações durante processamento assíncrono**  
    &ensp;4.1.1 Saldo do usuário: Valida se a conta selecionada possui saldo suficiente para realizar o pagamento. No caso de um pagamento agendado, a validação só ocorre na tentativa de liquidação do pagamento (SALDO_INSUFICIENTE);  
    &ensp;4.1.2 Limites da transação: Valida se valor (ou quantidade de transações) ultrapassa faixa de limite parametrizada na detentora (VALOR_ACIMA_LIMITE);  
    &ensp;4.1.3 Valor informado (QR Code): Valida se valor enviado é válido para o QR Code informado (VALOR_INVALIDO);  
    &ensp;4.1.4 Cobrança inválida: Valida expiração, vencimento e status (COBRANCA_INVALIDA);  
    &ensp;4.1.5 Divergência entre pagamento e consentimento: Valida se dados do pagamento são diferentes dos dados do consentimento (PAGAMENTO_DIVERGENTE_CONSENTIMENTO);  
    &ensp;4.1.6 Recusado pela detentora: Valida se pagamento foi recusado pela detentora (PAGAMENTO_RECUSADO_DETENTORA), com a descrição do motivo de recusa (ex. chave Pix inválida, QRCode inválido, conta bloqueada);  
    &ensp;4.1.7 Detalhes do pagamento: Valida se determinado parâmetro informado obedece às regras de negócio (DETALHE_PAGAMENTO_INVALIDO);  
    &ensp;4.1.8 Demais validações não explicitamente informadas (ex. suspeita de fraude): (NAO_INFORMADO);  
    &ensp;4.1.9 Validação SPI: Externaliza validações no SPI (PAGAMENTO_RECUSADO_SPI);  
    &ensp;4.1.10 Falha em agendamentos: Uma ou mais incidências de pagamento não foram possíveis de ser agendadas (FALHA_AGENDAMENTO_PAGAMENTOS);  
  4.2 **Casos de erro para validações assíncronas no DICT**  
    &ensp;Neste cenário o pagamento é criado com sucesso (status RCVD) e o consentimento é consumido (status CONSUMED), porém, as validações contra o DICT só ocorrerão de forma assíncrona e em caso de negativa será percebido pela iniciadora na consulta do pagamento (GET /Payments).  
    &ensp;Retorno esperado do endpoint GET /Payments: HTTP Code 200 - OK.  
    &ensp;Status do Pagamento: RJCT (Rejected), com as seguintes opções rejectionReason:  
    &ensp;• Erro por dados inválidos: Conforme item **4.1.7**;  
    &ensp;• Erro por suspeita de fraude: Conforme item **4.1.8**.

5. **Demais validações executadas durante o processamento assíncrono do consentimento pela detentora poderão ser consultados pela iniciadora através do endpoint _GET /consents/{consentId}_ previstos com retorno HTTP Code 200 – OK com status REJECTED e rejectionReason conforme abaixo:**  
  5.1 **Validações durante o processamento assíncrono**  
    &ensp;5.1.1 - Falha de infraestrutura: Ocorreu algum erro interno na detentora durante processamento da criação do consentimento (FALHA_INFRAESTRUTURA)  
    &ensp;5.1.2 - Tempo de autorização expirado: O usuário não confirmou o consentimento e o mesmo expirou (TEMPO_EXPIRADO_AUTORIZACAO);  
    &ensp;5.1.3 - Rejeitado pelo usuário: O usuário explicitamente rejeitou a autorização do consentimento (REJEITADO_USUARIO);  
    &ensp;5.1.4 - Mesma conta origem/destino: A conta indicada pelo usuário para recebimento é a mesma selecionada para o pagamento (CONTAS_ORIGEM_DESTINO_IGUAIS);  
    &ensp;5.1.5 - Tipo de conta inválida: A conta indicada não permite operações de pagamento (CONTA_NAO_PERMITE_PAGAMENTO);  
    &ensp;5.1.6 - Saldo do usuário: Valida se a conta selecionada possui saldo suficiente para realizar o pagamento. Essa validação não deverá ocorrer no caso de um pagamento agendado (SALDO_INSUFICIENTE);  
    &ensp;5.1.7 - Limites da transação: Valida se o valor ultrapassa o limite estabelecido [na instituição/no arranjo/outro] para permitir a realização de transações pelo cliente (VALOR_ACIMA_LIMITE);  
    &ensp;5.1.8 - QRCode inválido: O QRCode utilizado para a iniciação de pagamento não é válido (QRCODE_INVALIDO);  
    &ensp;5.1.9 - Valor inválido: O valor enviado não é válido para o QR Code informado (VALOR_INVALIDO);  
    &ensp;5.1.10 - Não informado: Demais validações não explicitamente informadas (ex. suspeita de fraude) e consentimentos rejeitados em versões que não existiam o campo rejectionReason na API de Pagamentos (NAO_INFORMADO)  
    &ensp;5.1.11 - Tempo expirado consumo: O usuário não finalizou o fluxo de pagamento e o consentimento expirou (TEMPO_EXPIRADO_CONSUMO).  
  5.2 **[Momentos obrigatórios de validação dos rejectionReasons de acordo com o funil de consentimentos.](https://openfinancebrasil.atlassian.net/wiki/spaces/OF/pages/150863940) Para casos em que um consentimento for rejeitado por mais de um motivo, seguir a ordem de prioridade da tabela.**
  
  ```
  |----------------------------------|------------------------------|---------------------|
  | Etapas do funil de consentimento | rejectionReason/code         | Ordem de prioridade |
  |----------------------------------|------------------------------|---------------------|
  |                                  | TEMPO_EXPIRADO_AUTORIZACAO   |          1          |
  | Início da autenticação           | FALHA_INFRAESTRUTURA         |          2          |
  |                                  | NAO_INFORMADO                |          3          |
  |----------------------------------|------------------------------|---------------------|
  |                                  | TEMPO_EXPIRADO_AUTORIZACAO   |          1          |
  |                                  | REJEITADO_USUARIO            |          2          |
  | Conclusão da autenticação        | FALHA_INFRAESTRUTURA         |          3          |
  |                                  | NAO_INFORMADO                |          4          |
  |----------------------------------|------------------------------|---------------------|
  |                                  | CONTA_NAO_PERMITE_PAGAMENTO  |          1          |
  |                                  | CONTAS_ORIGEM_DESTINO_IGUAIS |          2          |
  |                                  | VALOR_INVALIDO               |          3          |
  | Autorização do cliente           | QRCODE_INVALIDO              |          4          |
  |                                  | VALOR_ACIMA_LIMITE           |          5          |
  |                                  | SALDO_INSUFICIENTE           |          6          |
  |                                  | FALHA_INFRAESTRUTURA         |          7          |
  |                                  | NAO_INFORMADO                |          8          |
  |----------------------------------|------------------------------|---------------------|
  |                                  | FALHA_INFRAESTRUTURA         |          1          |
  | Authorisation code emitido       | NAO_INFORMADO                |          2          |
  |                                  | TEMPO_EXPIRADO_CONSUMO       |          3          |
  |----------------------------------|------------------------------|---------------------|
  ```
  Existem dois `endpoints` para cancelamento de pagamentos, um deles é o _PATCH /pix/payments/{paymentId}_ e o outro é o _PATCH /pix/payments/consents/{consentId}_.
  - O _PATCH /pix/payments/{paymentId}_ deve ser utilizado para o cancelamento de um pagamento de forma unitária. Não deve ser utilizado para o cancelamento de todos os agendamentos recorrentes associados a um consentimento.
  - O _PATCH /pix/payments/consents/{consentId}_ deve ser utilizado no cancelamento de todas as ocorrências de pagamentos agendados presentes em uma recorrência de pagamentos. Todos os pagamentos associados ao consentimento informado e passíveis de cancelamento (ainda não liquidados, com os status PDNG e SCHD) deverão ser cancelados.
  
  ## Quantidade máxima permitida para agendamentos recorrentes
  A quantidade máxima de pagamentos que podem transitar do iniciador para o detentor são de 60 pagamentos, independente do modelo de recorrência definido no consentimento, respeitando o prazo máximo de dois anos para agendamentos. 
  Caso a opção de recorrência enviada pelo iniciador não respeite a regra acima, o detentor deve retornar o erro 422 \"PARAMETRO_INVALIDO\" com o detalhe \"Quantidade permitida de pagamentos excedida\". 


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


$apiInstance = new OpenAPI\Client\Api\PagamentosApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$consent_id = 'consent_id_example'; // string | O consentId é o identificador único do consentimento e deverá ser um URN - Uniform Resource Name.   Um URN, conforme definido na [RFC8141](https://tools.ietf.org/html/rfc8141) é um Uniform Resource  Identifier - URI - que é atribuído sob o URI scheme \"urn\" e um namespace URN específico, com a intenção de que o URN  seja um identificador de recurso persistente e independente da localização.   Considerando a string urn:bancoex:C1DD33123 como exemplo para consentId temos: - o namespace(urn) - o identificador associado ao namespace da instituição transnmissora (bancoex)  - o identificador específico dentro do namespace (C1DD33123).   Informações mais detalhadas sobre a construção de namespaces devem ser consultadas na [RFC8141](https://tools.ietf.org/html/rfc8141).
$authorization = 'authorization_example'; // string | Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado
$x_fapi_interaction_id = d78fc4e5-37ca-4da3-adf2-9b082bf92280; // string | Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser “espelhado” pela detentora (server) no cabeçalho de resposta. Caso não seja enviado pela iniciadora, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. Caso recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400 ou 422 (com o código PARAMETRO_INVALIDO). A iniciadora deve acatar o valor gerado pelo detentor e recebido na resposta.
$x_fapi_auth_date = 'x_fapi_auth_date_example'; // string | Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC
$x_fapi_customer_ip_address = 'x_fapi_customer_ip_address_example'; // string | O endereço IP do usuário se estiver atualmente logado com o receptor.
$x_customer_user_agent = 'x_customer_user_agent_example'; // string | Indica o user-agent que o usuário utiliza.

try {
    $result = $apiInstance->paymentsGetConsentsConsentId($consent_id, $authorization, $x_fapi_interaction_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PagamentosApi->paymentsGetConsentsConsentId: ', $e->getMessage(), PHP_EOL;
}

```

## API Endpoints

All URIs are relative to *https://api.banco.com.br/open-banking/payments/v4*

Class | Method | HTTP request | Description
------------ | ------------- | ------------- | -------------
*PagamentosApi* | [**paymentsGetConsentsConsentId**](docs/Api/PagamentosApi.md#paymentsgetconsentsconsentid) | **GET** /consents/{consentId} | Consultar consentimento para iniciação de pagamento.
*PagamentosApi* | [**paymentsGetPixPaymentsPaymentId**](docs/Api/PagamentosApi.md#paymentsgetpixpaymentspaymentid) | **GET** /pix/payments/{paymentId} | Consultar iniciação de pagamento.
*PagamentosApi* | [**paymentsPatchPixPaymentsConsentId**](docs/Api/PagamentosApi.md#paymentspatchpixpaymentsconsentid) | **PATCH** /pix/payments/consents/{consentId} | Cancelar todos os pagamentos referentes ao mesmo Consentimento.
*PagamentosApi* | [**paymentsPatchPixPaymentsPaymentId**](docs/Api/PagamentosApi.md#paymentspatchpixpaymentspaymentid) | **PATCH** /pix/payments/{paymentId} | Cancelar iniciação de pagamento.
*PagamentosApi* | [**paymentsPostConsents**](docs/Api/PagamentosApi.md#paymentspostconsents) | **POST** /consents | Criar consentimento para a iniciação de pagamento.
*PagamentosApi* | [**paymentsPostPixPayments**](docs/Api/PagamentosApi.md#paymentspostpixpayments) | **POST** /pix/payments | Criar iniciação de pagamento.

## Models

- [422ResponseErrorCreateConsentErrorsInner](docs/Model/422ResponseErrorCreateConsentErrorsInner.md)
- [422ResponseErrorCreatePixPaymentErrorsInner](docs/Model/422ResponseErrorCreatePixPaymentErrorsInner.md)
- [422ResponseErrorCreatePixPaymentsErrorsInner](docs/Model/422ResponseErrorCreatePixPaymentsErrorsInner.md)
- [BusinessEntity](docs/Model/BusinessEntity.md)
- [BusinessEntityDocument](docs/Model/BusinessEntityDocument.md)
- [ConsentRejectionReason](docs/Model/ConsentRejectionReason.md)
- [ConsentsDebtorAccount](docs/Model/ConsentsDebtorAccount.md)
- [CreatePaymentConsent](docs/Model/CreatePaymentConsent.md)
- [CreatePaymentConsentData](docs/Model/CreatePaymentConsentData.md)
- [CreatePaymentConsentDataPayment](docs/Model/CreatePaymentConsentDataPayment.md)
- [CreatePixPayment](docs/Model/CreatePixPayment.md)
- [CreatePixPaymentDataInner](docs/Model/CreatePixPaymentDataInner.md)
- [CreditorAccount](docs/Model/CreditorAccount.md)
- [DebtorAccount](docs/Model/DebtorAccount.md)
- [Details](docs/Model/Details.md)
- [EnumAccountPaymentsType](docs/Model/EnumAccountPaymentsType.md)
- [EnumAuthorisationStatusType](docs/Model/EnumAuthorisationStatusType.md)
- [EnumConsentRejectionReasonType](docs/Model/EnumConsentRejectionReasonType.md)
- [EnumErrorsCreatePayment](docs/Model/EnumErrorsCreatePayment.md)
- [EnumErrorsCreatePixPayment](docs/Model/EnumErrorsCreatePixPayment.md)
- [EnumLocalInstrument](docs/Model/EnumLocalInstrument.md)
- [EnumPaymentCancellationFromType](docs/Model/EnumPaymentCancellationFromType.md)
- [EnumPaymentCancellationReasonType](docs/Model/EnumPaymentCancellationReasonType.md)
- [EnumPaymentCancellationStatusType](docs/Model/EnumPaymentCancellationStatusType.md)
- [EnumPaymentPersonType](docs/Model/EnumPaymentPersonType.md)
- [EnumPaymentStatusType](docs/Model/EnumPaymentStatusType.md)
- [EnumPaymentType](docs/Model/EnumPaymentType.md)
- [EnumRejectionReasonType](docs/Model/EnumRejectionReasonType.md)
- [EnumRejectionReasonTypeGetPix](docs/Model/EnumRejectionReasonTypeGetPix.md)
- [Identification](docs/Model/Identification.md)
- [LinkSingle](docs/Model/LinkSingle.md)
- [LinkSinglePost](docs/Model/LinkSinglePost.md)
- [LoggedUser](docs/Model/LoggedUser.md)
- [LoggedUserDocument](docs/Model/LoggedUserDocument.md)
- [Meta](docs/Model/Meta.md)
- [Model422ResponseErrorCreateConsent](docs/Model/Model422ResponseErrorCreateConsent.md)
- [Model422ResponseErrorCreatePixPayment](docs/Model/Model422ResponseErrorCreatePixPayment.md)
- [Model422ResponseErrorCreatePixPayments](docs/Model/Model422ResponseErrorCreatePixPayments.md)
- [PatchPixPayment](docs/Model/PatchPixPayment.md)
- [PatchPixPaymentCancellation](docs/Model/PatchPixPaymentCancellation.md)
- [PatchPixPaymentData](docs/Model/PatchPixPaymentData.md)
- [PatchPixPaymentDataCancellation](docs/Model/PatchPixPaymentDataCancellation.md)
- [PatchPixPaymentDataCancellationCancelledBy](docs/Model/PatchPixPaymentDataCancellationCancelledBy.md)
- [PatchPixPaymentDataCancellationCancelledByDocument](docs/Model/PatchPixPaymentDataCancellationCancelledByDocument.md)
- [PaymentConsent](docs/Model/PaymentConsent.md)
- [PaymentPix](docs/Model/PaymentPix.md)
- [PixPaymentCancellation](docs/Model/PixPaymentCancellation.md)
- [RejectionReason](docs/Model/RejectionReason.md)
- [RejectionReasonGetPix](docs/Model/RejectionReasonGetPix.md)
- [ResponseCreatePaymentConsent](docs/Model/ResponseCreatePaymentConsent.md)
- [ResponseCreatePaymentConsentData](docs/Model/ResponseCreatePaymentConsentData.md)
- [ResponseCreatePixPayment](docs/Model/ResponseCreatePixPayment.md)
- [ResponseCreatePixPaymentDataInner](docs/Model/ResponseCreatePixPaymentDataInner.md)
- [ResponseCreatePixPaymentDataInnerPayment](docs/Model/ResponseCreatePixPaymentDataInnerPayment.md)
- [ResponseError](docs/Model/ResponseError.md)
- [ResponseErrorErrorsInner](docs/Model/ResponseErrorErrorsInner.md)
- [ResponseErrorMeta](docs/Model/ResponseErrorMeta.md)
- [ResponsePatchPixConsent](docs/Model/ResponsePatchPixConsent.md)
- [ResponsePatchPixConsentDataInner](docs/Model/ResponsePatchPixConsentDataInner.md)
- [ResponsePatchPixPayment](docs/Model/ResponsePatchPixPayment.md)
- [ResponsePatchPixPaymentData](docs/Model/ResponsePatchPixPaymentData.md)
- [ResponsePaymentConsent](docs/Model/ResponsePaymentConsent.md)
- [ResponsePaymentConsentData](docs/Model/ResponsePaymentConsentData.md)
- [ResponsePixPayment](docs/Model/ResponsePixPayment.md)
- [ResponsePixPaymentData](docs/Model/ResponsePixPaymentData.md)
- [Schedule](docs/Model/Schedule.md)
- [ScheduleCustom](docs/Model/ScheduleCustom.md)
- [ScheduleCustomCustom](docs/Model/ScheduleCustomCustom.md)
- [ScheduleDaily](docs/Model/ScheduleDaily.md)
- [ScheduleDailyDaily](docs/Model/ScheduleDailyDaily.md)
- [ScheduleMonthly](docs/Model/ScheduleMonthly.md)
- [ScheduleMonthlyMonthly](docs/Model/ScheduleMonthlyMonthly.md)
- [ScheduleSingle](docs/Model/ScheduleSingle.md)
- [ScheduleSingleSingle](docs/Model/ScheduleSingleSingle.md)
- [ScheduleWeekly](docs/Model/ScheduleWeekly.md)
- [ScheduleWeeklyWeekly](docs/Model/ScheduleWeeklyWeekly.md)

## Authorization

Authentication schemes defined for the API:
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
    - **consent:consentId**: Escopo contendo o identificador único do consentimento criado para a iniciação de pagamento solicitada

### NonRedirectAuthorizationCode

- **Type**: `OAuth`
- **Flow**: `accessCode`
- **Authorization URL**: `https://authserver.example/token`
- **Scopes**: 
    - **payments**: Escopo necessário para acesso à  API Payment Initiation.
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

gt-interfaces@openbankingbr.org

## About this package

This PHP package is automatically generated by the [OpenAPI Generator](https://openapi-generator.tech) project:

- API version: `4.0.0`
    - Generator version: `7.17.0`
- Build package: `org.openapitools.codegen.languages.PhpClientCodegen`
