# OpenAPI\Client\CobPayloadApi

Reúne endpoints (locations) utilizados pelo software do PSP pagador para recuperar o payload JSON que representa uma cobrança.  Os endpoints listados nesta Tag apresentam requisitos de autenticação e autorização diferenciados em relação aos outros endpoints listados na presente API.  São endpoints __abertos__ para que qualquer cliente da internet possa se conectar. Cada _location_ é uma _[url de capacidade](https://www.w3.org/TR/capability-urls/)_, funcionalidade implementada via o fragmento &#x60;{pixUrlAccessToken}&#x60;. Para mais informações, consultar o [Manual de Padrões para iniciação do Pix](https://www.bcb.gov.br/estabilidadefinanceira/pix).

All URIs are relative to https://pix.example.com/api, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**cobvPixUrlAccessTokenGet()**](CobPayloadApi.md#cobvPixUrlAccessTokenGet) | **GET** /cobv/{pixUrlAccessToken} | Recuperar o payload JSON que representa a cobrança com vencimento. |
| [**pixUrlAccessTokenGet()**](CobPayloadApi.md#pixUrlAccessTokenGet) | **GET** /{pixUrlAccessToken} | Recuperar o payload JSON que representa a cobrança imediata. |


## `cobvPixUrlAccessTokenGet()`

```php
cobvPixUrlAccessTokenGet($pix_url_access_token, $cod_mun, $dpp): \OpenAPI\Client\Model\CobVPayload
```
### URI(s):
- https://{fdqnPSPRecebedor}/{endpointOpcional} 
    - Variables:
      - fdqnPSPRecebedor: Endpoint base para que os usuários devedores possam acessar o payload JSON que representa a cobrança com vencimento.
        - Default value: example.com

Recuperar o payload JSON que representa a cobrança com vencimento.

## Endpoint (location) que serve um payload que representa uma cobrança com vencimento.  No momento que o usuário devedor efetua a leitura de um QR Code dinâmico gerado pelo recebedor, esta URL será acessada e seu conteúdo consiste em uma estrutura JWS. As informações sobre a segurança no acesso às urls encontram-se no Manual de Segurança do Pix disponível em nesse __[link](https://www.bcb.gov.br/estabilidadefinanceira/comunicacaodados)__.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\CobPayloadApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$pix_url_access_token = 'pix_url_access_token_example'; // string
$cod_mun = 'cod_mun_example'; // string | Código baseado na Tabela de Códigos de Municípios do __[IBGE](https://www.ibge.gov.br/explica/codigos-dos-municipios.php)__ que apresenta a lista dos municípios brasileiros associados a um código composto de 7 dígitos, sendo os dois primeiros referentes ao código da Unidade da Federação.
$dpp = Wed Mar 31 21:00:00 BRT 2021; // \DateTime | Data de pagamento pretendida. Trata-se de uma data, no formato `YYYY-MM-DD`, segundo ISO 8601.

$hostIndex = 0;
$variables = [
    'fdqnPSPRecebedor' => 'YOUR_VALUE',
];

try {
    $result = $apiInstance->cobvPixUrlAccessTokenGet($pix_url_access_token, $cod_mun, $dpp, $hostIndex, $variables);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling CobPayloadApi->cobvPixUrlAccessTokenGet: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **pix_url_access_token** | **string**|  | |
| **cod_mun** | **string**| Código baseado na Tabela de Códigos de Municípios do __[IBGE](https://www.ibge.gov.br/explica/codigos-dos-municipios.php)__ que apresenta a lista dos municípios brasileiros associados a um código composto de 7 dígitos, sendo os dois primeiros referentes ao código da Unidade da Federação. | [optional] |
| **dpp** | **\DateTime**| Data de pagamento pretendida. Trata-se de uma data, no formato &#x60;YYYY-MM-DD&#x60;, segundo ISO 8601. | [optional] |
| hostIndex | null|int | Host index. Defaults to null. If null, then the library will use $this->hostIndex instead | [optional] |
| variables | array | Associative array of variables to pass to the host. Defaults to empty array. | [optional] |

### Return type

[**\OpenAPI\Client\Model\CobVPayload**](../Model/CobVPayload.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/jose`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `pixUrlAccessTokenGet()`

```php
pixUrlAccessTokenGet($pix_url_access_token): \OpenAPI\Client\Model\CobPayload
```
### URI(s):
- https://{fdqnPSPRecebedor}/{endpointOpcional} 
    - Variables:
      - fdqnPSPRecebedor: Endpoint base para que os usuários devedores possam acessar o payload JSON que representa a cobrança imediata.
        - Default value: example.com

Recuperar o payload JSON que representa a cobrança imediata.

## Endpoint (location) que serve um payload que representa uma cobrança imediata.  No momento que o usuário devedor efetua a leitura de um QR Code dinâmico gerado pelo recebedor, esta URL será acessada e seu conteúdo consiste em uma estrutura JWS. As informações sobre a segurança no acesso às urls encontram-se no Manual de Segurança do Pix disponível em nesse __[link](https://www.bcb.gov.br/estabilidadefinanceira/comunicacaodados)__.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\CobPayloadApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$pix_url_access_token = 'pix_url_access_token_example'; // string

$hostIndex = 0;
$variables = [
    'fdqnPSPRecebedor' => 'YOUR_VALUE',
];

try {
    $result = $apiInstance->pixUrlAccessTokenGet($pix_url_access_token, $hostIndex, $variables);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling CobPayloadApi->pixUrlAccessTokenGet: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **pix_url_access_token** | **string**|  | |
| hostIndex | null|int | Host index. Defaults to null. If null, then the library will use $this->hostIndex instead | [optional] |
| variables | array | Associative array of variables to pass to the host. Defaults to empty array. | [optional] |

### Return type

[**\OpenAPI\Client\Model\CobPayload**](../Model/CobPayload.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/jose`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
