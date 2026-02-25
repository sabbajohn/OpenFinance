# OpenAPI\Client\RecPayloadApi

Reúne endpoints (locations) utilizados pelo software do PSP pagador para recuperar o payload JSON que representa uma recorrência.  Os endpoints listados nesta Tag apresentam requisitos de autenticação e autorização diferenciados em relação aos outros endpoints listados na presente API.  São endpoints __abertos__ para que qualquer cliente da internet possa se conectar. Cada _location_ é uma _[url de capacidade](https://www.w3.org/TR/capability-urls/)_, funcionalidade implementada via o fragmento &#x60;{recUrlAccessToken}&#x60;. Para mais informações, consultar o [Manual de Padrões para iniciação do Pix](https://www.bcb.gov.br/estabilidadefinanceira/pix).

All URIs are relative to https://pix.example.com/api, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**recRecUrlAccessTokenGet()**](RecPayloadApi.md#recRecUrlAccessTokenGet) | **GET** /rec/{recUrlAccessToken} | Recuperar o payload JSON que representa a configuração da recorrência. |


## `recRecUrlAccessTokenGet()`

```php
recRecUrlAccessTokenGet($rec_url_access_token): \OpenAPI\Client\Model\RecPayload
```
### URI(s):
- https://{fdqnPSPRecebedor}/{endpointOpcional} 
    - Variables:
      - fdqnPSPRecebedor: Endpoint base para que os usuários devedores possam acessar o payload JSON que representa a recorrência.
        - Default value: example.com

Recuperar o payload JSON que representa a configuração da recorrência.

## Endpoint (location) que serve um payload que representa uma recorrência.  No momento em que o usuário pagador efetua a leitura de um QR Code composto gerado pelo recebedor, esta URL será acessada e seu conteúdo consiste em uma estrutura JWS. As informações sobre a segurança no acesso às urls encontram-se no Manual de Segurança do Pix disponível nesse __[link](https://www.bcb.gov.br/estabilidadefinanceira/comunicacaodados)__.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\RecPayloadApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$rec_url_access_token = 'rec_url_access_token_example'; // string

$hostIndex = 0;
$variables = [
    'fdqnPSPRecebedor' => 'YOUR_VALUE',
];

try {
    $result = $apiInstance->recRecUrlAccessTokenGet($rec_url_access_token, $hostIndex, $variables);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling RecPayloadApi->recRecUrlAccessTokenGet: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **rec_url_access_token** | **string**|  | |
| hostIndex | null|int | Host index. Defaults to null. If null, then the library will use $this->hostIndex instead | [optional] |
| variables | array | Associative array of variables to pass to the host. Defaults to empty array. | [optional] |

### Return type

[**\OpenAPI\Client\Model\RecPayload**](../Model/RecPayload.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/jose`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
