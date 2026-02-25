# OpenAPI\Client\ConsentimentoApi

Autorização de consentimentos criados via fluxo sem redirecionamento.

All URIs are relative to https://mtls-api.banco.com.br/open-banking/enrollments/v2, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**authorizeConsent()**](ConsentimentoApi.md#authorizeConsent) | **POST** /consents/{consentId}/authorise | Autorização de um consentimento de pagamentos na jornada sem redirecionamento |


## `authorizeConsent()`

```php
authorizeConsent($consent_id, $authorization, $x_fapi_interaction_id, $x_idempotency_key, $consent_authorization, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $x_bcb_nfc)
```

Autorização de um consentimento de pagamentos na jornada sem redirecionamento

Autorização de um consentimento de pagamentos em status `AWAITING_AUTHORISATION` a partir do access_token emitido pela jornada sem redirecionamento e envio de sinais de risco.  Para pagamentos de alçadas únicas, o consentimento de pagamento deve transitar ao status `AUTHORISED` na execução com sucesso desse método.  Para pagamentos com múltiplas alçadas aprovadoras, o consentimento de pagamento ficará em `PARTIALLY_ACCEPTED` até que todos tenham autorizado.  Em caso de falha de negócio (HTTP Status Code 422), o consentimento de pagamento precisa transitar para o status `REJECTED` e seguir os motivos de rejeição presentes na API de pagamentos. Caso a detentora identifique que a conta de débito informada pelo iniciador na criação do consentimento diverge da conta de débito vinculada ao dispositivo, o detentor deve retornar o erro HTTP 422 com código `CONTA_DEBITO_DIVERGENTE_CONSENTIMENTO_VINCULO` e rejeitar o consentimento com o motivo `CONTA_NAO_PERMITE_PAGAMENTO`.  Se o iniciador, durante a criação do consentimento, omitir a conta de débito, o detentor deve considerar a conta de débito associada ao vínculo para o preenchimento do objeto `/data/debtorAccount`, presente no consentimento, após a sua autorização. Os limites relacionados ao vínculo devem ser validados apenas em momento de liquidação do pagamento, independente dele ser agendado ou imediato.

### Example

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

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **consent_id** | **string**| O consentId é o identificador único do consentimento e deverá ser um URN - Uniform Resource Name.   Um URN, conforme definido na [RFC8141](https://tools.ietf.org/html/rfc8141) é um Uniform Resource  Identifier - URI - que é atribuído sob o URI scheme \&quot;urn\&quot; e um namespace URN específico, com a intenção de que o URN  seja um identificador de recurso persistente e independente da localização.   Considerando a string urn:bancoex:C1DD33123 como exemplo para consentId temos: - o namespace(urn) - o identificador associado ao namespace da instituição detentora de conta (bancoex)  - o identificador específico dentro do namespace (C1DD33123).   Informações mais detalhadas sobre a construção de namespaces devem ser consultadas na [RFC8141](https://tools.ietf.org/html/rfc8141). | |
| **authorization** | **string**| Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado | |
| **x_fapi_interaction_id** | **string**| Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser \&quot;espelhado\&quot; pela detentora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. A iniciadora deve acatar o valor recebido da detentora. | |
| **x_idempotency_key** | **string**| Cabeçalho HTTP personalizado. Identificador de solicitação exclusivo para suportar a idempotência. | |
| **consent_authorization** | [**\OpenAPI\Client\Model\ConsentAuthorization**](../Model/ConsentAuthorization.md)| Payload para criação de vínculo de conta. | |
| **x_fapi_auth_date** | **string**| Data em que o usuário logou pela última vez com a iniciadora. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC | [optional] |
| **x_fapi_customer_ip_address** | **string**| O endereço IP do usuário se estiver atualmente logado com a iniciadora. | [optional] |
| **x_customer_user_agent** | **string**| Indica o user-agent que o usuário utiliza. | [optional] |
| **x_bcb_nfc** | **bool**| O campo representa uma transação iniciada via NFC. O envio desse campo é obrigatório nesse cenário. As detentoras devem armazenar a informação e correlacioná-la com o consentimento e o pagamento originado. | [optional] |

### Return type

void (empty response body)

### Authorization

[OAuth2AuthorizationCode](../../README.md#OAuth2AuthorizationCode)

### HTTP request headers

- **Content-Type**: `application/jwt`
- **Accept**: `application/json; charset=utf-8`, `application/jwt`, `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
