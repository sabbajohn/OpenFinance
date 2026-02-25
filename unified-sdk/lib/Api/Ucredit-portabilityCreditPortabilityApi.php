<?php
/**
 * CreditPortabilityApi
 * PHP version 8.1
 *
 * @category Class
 * @package  OpenAPI\Client
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 */

/**
 * API Credit Portability - Open Finance Brasil
 *
 * A API de Portabilidade de Crédito permite que usuários transfiram suas operações de crédito e arrendamento mercantil entre instituições financeiras em busca de melhores condições para o Open Finance Brasil.  # Orientações  ## Assinatura de payloads:   No contexto da API de Portabilidade de crédito, os payloads de mensagem que trafegam tanto por parte da instituição credora quanto por parte da instituição proponente devem estar assinados. Para o processo de assinatura destes payloads as instituições devem seguir as especificações de segurança publicadas no Portal do desenvolvedor       &nbsp;&nbsp;- Certificados exigidos para assinatura de mensagens: [[PT] Padrão de Certificados Open Finance Brasil 2.1](https://openfinancebrasil.atlassian.net/wiki/spaces/OF/pages/245694518/PT+Padr+o+de+Certificados+Open+Finance+Brasil+2.1)       &nbsp;&nbsp;- Como assinar o payload JWS: [Como Assinar o Payload](https://openfinancebrasil.atlassian.net/wiki/spaces/OF/pages/905740608/Como+assinar+o+payload+-+PC+Portabilidade+de+Cr+dito+-+CPC)  ## Controle de Acesso - Os endpoints [GET] /portabilities/{portabilityId}, [GET] /portabilities/{portabilityId}/account-data, [POST] /portabilities/{portabilityId}/payment, [PATCH] / portabilities/{portabilityId}/cancel da API de Portabilidade de crédito devem utilizar o escopo client_credentials - Os endpoints [GET] /credit-operations/{contractId}/portability-eligibility e [POST] /portabilities devem utilizar o escopo authorization_code para validar a permissions de LOANS  ## Validações para Portabilidade de Crédito **- Validações** (após o processo de DCR e obtenção de token client credential - não escopo dessa documentação):      &nbsp;Durante o processo de portabilidade de crédito, diferentes validações são necessárias pela instituição credora e devem ocorrer conforme a seguir:     **- Casos de erro relacionados às permissões de segurança para acesso à API** (ex. certificado, access_token, jwt, assinatura):       Validação de Certificado: Valida utilização de certificado correto durante processo de DCR - HTTP Code 401 (INVALID_CLIENT);      Validação de Access_Token: Verifica se Access_Token utilizado está correto - HTTP Code 401 (UNAUTHORIZED);      Validação de assinatura da mensagem: Valida se assinatura das mensagens enviadas está correta – HTTP Code 400 (BAD_SIGNATURE);      Validação de Claims (exceto data);        &emsp;- Valida se dados (aud, iss, iat e jti) são válidos - HTTP status code 403 - (INVALID_CLIENT);       &emsp;- Valida reuso de jti - HTTP Code 403 (INVALID_CLIENT).      ## Validações de erros sintáticos e semânticos, previstas com retorno HTTP Code 422 - Unprocessable Entity   **- Para todos os endpoints:**        &nbsp;&nbsp;**Sintáticos**          &emsp;- Envio de campos obrigatórios: Valida se todos os campos obrigatórios são informados (PARAMETRO_NAO_INFORMADO);          &emsp;- Formatação de parâmetros: Valida se parâmetros informados obedecem a formatação especificada (PARAMETRO_INVALIDO).          &emsp;- Demais validações não explicitamente informadas (NAO_INFORMADO)    **- Para endpoint ([POST] /portabilities):**        &nbsp;&nbsp;**Semânticos**        &emsp;- Portabilidade em andamento: Valida se já existe um pedido de portabilidade de crédito para o contrato solicitado pelo trilho do OFB ou da Registradora (EM_ANDAMENTO);        &emsp;- Prazo do empréstimo maior ao restante das parcelas a serem liquidadas no contrato original (PRAZO_ACIMA_LIMITE);        &emsp;- ID de contrato inválida (CONTRATO_INVALIDO);        &emsp;- Contrato não elegível para portabilidade dentro do trilho do OFB (CONTRATO_NAO_ELEGIVEL);        &emsp;- Idempotência: Valida se há divergência entre chave de idempotência e informações enviadas (ERRO_IDEMPOTENCIA);        &emsp;- Evidência de assinatura do contrato: Valida se o objeto de assinatura do contrato foi preenchido pela instituição proponente devidamente, em caso de ausência (SEM_EVIDENCIA_ASSINATURA);        &emsp;- Periodicidade: Valida se não houve mudança na periodicidade entre o novo contrato e o contrato original, caso tenha sido alterado a periodicidade (PERIODICIDADE_INVALIDA);        &emsp;- Campo com preenchimento incorreto: Valida se o preenchimento de alguns campos estão corretos      Ex.: CNPJ da instituição credora deve ser o mesmo retornado pela API de Empréstimos (CAMPO_INCONSISTENTE)    **- Para endpoint ([POST] /portabilities/{portabilityId}/payment):**      &nbsp;&nbsp;**Semânticos**        &emsp;- Estado da portabilidade diferente de ACCEPTED_SETTLEMENT_IN_PROGRESS ou PAYMENT_ISSUE (PAGAMENTO_EFETUADO_FORA_PRAZO). Obs.: Caso o pagamento tenha sido feito por engano a Instituição Proponente deve solicitar o estorno.    **- Para endpoint ([PATCH] /portabilities/{portabilityId}/cancel):**      &nbsp;&nbsp;**Semânticos**        &emsp;- Estado da portabilidade diferente de RECEIVED, PENDING ou ACCEPTED_SETTLEMENT_IN_PROGRESS (CANCELAMENTO_NÃO_EFETUADO). Obs.: De acordo com o PRD o usuário poderá cancelar o pedido de portabilidade até a etapa de liquidação, após esta etapa não será mais permitido o cancelamento da portabilidade
 *
 * The version of the OpenAPI document: 1.0.0
 * Contact: gt-interfaces@openbankingbr.org
 * Generated by: https://openapi-generator.tech
 * Generator version: 7.17.0
 */

/**
 * NOTE: This class is auto generated by OpenAPI Generator (https://openapi-generator.tech).
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */

namespace OpenAPI\Client\Api;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\MultipartStream;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use OpenAPI\Client\ApiException;
use OpenAPI\Client\Configuration;
use OpenAPI\Client\FormDataProcessor;
use OpenAPI\Client\HeaderSelector;
use OpenAPI\Client\ObjectSerializer;

/**
 * CreditPortabilityApi Class Doc Comment
 *
 * @category Class
 * @package  OpenAPI\Client
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 */
class Ucredit-portabilityCreditPortabilityApi
{
    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @var Configuration
     */
    protected $config;

    /**
     * @var HeaderSelector
     */
    protected $headerSelector;

    /**
     * @var int Host index
     */
    protected $hostIndex;

    /** @var string[] $contentTypes **/
    public const contentTypes = [
        'creditPortabilityGetPortabilitiesByPortabilityId' => [
            'application/json',
        ],
        'creditPortabilityPatchPortabilitiesPortabilityIdCancel' => [
            'application/jwt',
        ],
        'creditPortabilityPostPortabilities' => [
            'application/jwt',
        ],
    ];

    /**
     * @param ClientInterface $client
     * @param Configuration   $config
     * @param HeaderSelector  $selector
     * @param int             $hostIndex (Optional) host index to select the list of hosts if defined in the OpenAPI spec
     */
    public function __construct(
        ?ClientInterface $client = null,
        ?Configuration $config = null,
        ?HeaderSelector $selector = null,
        int $hostIndex = 0
    ) {
        $this->client = $client ?: new Client();
        $this->config = $config ?: Configuration::getDefaultConfiguration();
        $this->headerSelector = $selector ?: new HeaderSelector();
        $this->hostIndex = $hostIndex;
    }

    /**
     * Set the host index
     *
     * @param int $hostIndex Host index (required)
     */
    public function setHostIndex($hostIndex): void
    {
        $this->hostIndex = $hostIndex;
    }

    /**
     * Get the host index
     *
     * @return int Host index
     */
    public function getHostIndex()
    {
        return $this->hostIndex;
    }

    /**
     * @return Configuration
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Operation creditPortabilityGetPortabilitiesByPortabilityId
     *
     * Consulta portabilidade de crédito através da propriedade portabilityId.
     *
     * @param  string $portability_id Identificador do pedido de portabilidade de crédito. (required)
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado. (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela IF Proponente (client) e o seu valor deve ser “espelhado” pela IF Credora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a IF Credora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A IF Proponente deve acatar o valor recebido da IF Credora. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['creditPortabilityGetPortabilitiesByPortabilityId'] to see the possible values for this operation
     *
     * @throws \OpenAPI\Client\ApiException on non-2xx response or if the response body is not in the expected format
     * @throws \InvalidArgumentException
     * @return \OpenAPI\Client\Model\ResponsePortabilitiesByPortabilityId|\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties|\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties|\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties|\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties|\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties|\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties|\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties|\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties|\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties|\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties|\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties
     */
    public function creditPortabilityGetPortabilitiesByPortabilityId($portability_id, $authorization, $x_fapi_interaction_id, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, string $contentType = self::contentTypes['creditPortabilityGetPortabilitiesByPortabilityId'][0])
    {
        list($response) = $this->creditPortabilityGetPortabilitiesByPortabilityIdWithHttpInfo($portability_id, $authorization, $x_fapi_interaction_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $contentType);
        return $response;
    }

    /**
     * Operation creditPortabilityGetPortabilitiesByPortabilityIdWithHttpInfo
     *
     * Consulta portabilidade de crédito através da propriedade portabilityId.
     *
     * @param  string $portability_id Identificador do pedido de portabilidade de crédito. (required)
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado. (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela IF Proponente (client) e o seu valor deve ser “espelhado” pela IF Credora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a IF Credora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A IF Proponente deve acatar o valor recebido da IF Credora. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['creditPortabilityGetPortabilitiesByPortabilityId'] to see the possible values for this operation
     *
     * @throws \OpenAPI\Client\ApiException on non-2xx response or if the response body is not in the expected format
     * @throws \InvalidArgumentException
     * @return array of \OpenAPI\Client\Model\ResponsePortabilitiesByPortabilityId|\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties|\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties|\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties|\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties|\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties|\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties|\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties|\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties|\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties|\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties|\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties, HTTP status code, HTTP response headers (array of strings)
     */
    public function creditPortabilityGetPortabilitiesByPortabilityIdWithHttpInfo($portability_id, $authorization, $x_fapi_interaction_id, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, string $contentType = self::contentTypes['creditPortabilityGetPortabilitiesByPortabilityId'][0])
    {
        $request = $this->creditPortabilityGetPortabilitiesByPortabilityIdRequest($portability_id, $authorization, $x_fapi_interaction_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $contentType);

        try {
            $options = $this->createHttpClientOption();
            try {
                $response = $this->client->send($request, $options);
            } catch (RequestException $e) {
                throw new ApiException(
                    "[{$e->getCode()}] {$e->getMessage()}",
                    (int) $e->getCode(),
                    $e->getResponse() ? $e->getResponse()->getHeaders() : null,
                    $e->getResponse() ? (string) $e->getResponse()->getBody() : null
                );
            } catch (ConnectException $e) {
                throw new ApiException(
                    "[{$e->getCode()}] {$e->getMessage()}",
                    (int) $e->getCode(),
                    null,
                    null
                );
            }

            $statusCode = $response->getStatusCode();


            switch($statusCode) {
                case 200:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponsePortabilitiesByPortabilityId',
                        $request,
                        $response,
                    );
                case 400:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties',
                        $request,
                        $response,
                    );
                case 401:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties',
                        $request,
                        $response,
                    );
                case 403:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties',
                        $request,
                        $response,
                    );
                case 404:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties',
                        $request,
                        $response,
                    );
                case 405:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties',
                        $request,
                        $response,
                    );
                case 406:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties',
                        $request,
                        $response,
                    );
                case 422:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties',
                        $request,
                        $response,
                    );
                case 500:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties',
                        $request,
                        $response,
                    );
                case 504:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties',
                        $request,
                        $response,
                    );
                case 529:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties',
                        $request,
                        $response,
                    );
                default:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties',
                        $request,
                        $response,
                    );
            }

            

            if ($statusCode < 200 || $statusCode > 299) {
                throw new ApiException(
                    sprintf(
                        '[%d] Error connecting to the API (%s)',
                        $statusCode,
                        (string) $request->getUri()
                    ),
                    $statusCode,
                    $response->getHeaders(),
                    (string) $response->getBody()
                );
            }

            return $this->handleResponseWithDataType(
                '\OpenAPI\Client\Model\ResponsePortabilitiesByPortabilityId',
                $request,
                $response,
            );
        } catch (ApiException $e) {
            switch ($e->getCode()) {
                case 200:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponsePortabilitiesByPortabilityId',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 400:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 401:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 403:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 404:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 405:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 406:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 422:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 500:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 504:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 529:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                default:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
            }
        

            throw $e;
        }
    }

    /**
     * Operation creditPortabilityGetPortabilitiesByPortabilityIdAsync
     *
     * Consulta portabilidade de crédito através da propriedade portabilityId.
     *
     * @param  string $portability_id Identificador do pedido de portabilidade de crédito. (required)
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado. (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela IF Proponente (client) e o seu valor deve ser “espelhado” pela IF Credora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a IF Credora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A IF Proponente deve acatar o valor recebido da IF Credora. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['creditPortabilityGetPortabilitiesByPortabilityId'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function creditPortabilityGetPortabilitiesByPortabilityIdAsync($portability_id, $authorization, $x_fapi_interaction_id, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, string $contentType = self::contentTypes['creditPortabilityGetPortabilitiesByPortabilityId'][0])
    {
        return $this->creditPortabilityGetPortabilitiesByPortabilityIdAsyncWithHttpInfo($portability_id, $authorization, $x_fapi_interaction_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $contentType)
            ->then(
                function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation creditPortabilityGetPortabilitiesByPortabilityIdAsyncWithHttpInfo
     *
     * Consulta portabilidade de crédito através da propriedade portabilityId.
     *
     * @param  string $portability_id Identificador do pedido de portabilidade de crédito. (required)
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado. (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela IF Proponente (client) e o seu valor deve ser “espelhado” pela IF Credora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a IF Credora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A IF Proponente deve acatar o valor recebido da IF Credora. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['creditPortabilityGetPortabilitiesByPortabilityId'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function creditPortabilityGetPortabilitiesByPortabilityIdAsyncWithHttpInfo($portability_id, $authorization, $x_fapi_interaction_id, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, string $contentType = self::contentTypes['creditPortabilityGetPortabilitiesByPortabilityId'][0])
    {
        $returnType = '\OpenAPI\Client\Model\ResponsePortabilitiesByPortabilityId';
        $request = $this->creditPortabilityGetPortabilitiesByPortabilityIdRequest($portability_id, $authorization, $x_fapi_interaction_id, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $contentType);

        return $this->client
            ->sendAsync($request, $this->createHttpClientOption())
            ->then(
                function ($response) use ($returnType) {
                    if ($returnType === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ($returnType !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, $returnType, []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
                },
                function ($exception) {
                    $response = $exception->getResponse();
                    $statusCode = $response->getStatusCode();
                    throw new ApiException(
                        sprintf(
                            '[%d] Error connecting to the API (%s)',
                            $statusCode,
                            $exception->getRequest()->getUri()
                        ),
                        $statusCode,
                        $response->getHeaders(),
                        (string) $response->getBody()
                    );
                }
            );
    }

    /**
     * Create request for operation 'creditPortabilityGetPortabilitiesByPortabilityId'
     *
     * @param  string $portability_id Identificador do pedido de portabilidade de crédito. (required)
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado. (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela IF Proponente (client) e o seu valor deve ser “espelhado” pela IF Credora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a IF Credora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A IF Proponente deve acatar o valor recebido da IF Credora. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['creditPortabilityGetPortabilitiesByPortabilityId'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Psr7\Request
     */
    public function creditPortabilityGetPortabilitiesByPortabilityIdRequest($portability_id, $authorization, $x_fapi_interaction_id, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, string $contentType = self::contentTypes['creditPortabilityGetPortabilitiesByPortabilityId'][0])
    {

        // verify the required parameter 'portability_id' is set
        if ($portability_id === null || (is_array($portability_id) && count($portability_id) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $portability_id when calling creditPortabilityGetPortabilitiesByPortabilityId'
            );
        }
        if (strlen($portability_id) > 36) {
            throw new \InvalidArgumentException('invalid length for "$portability_id" when calling CreditPortabilityApi.creditPortabilityGetPortabilitiesByPortabilityId, must be smaller than or equal to 36.');
        }
        if (strlen($portability_id) < 36) {
            throw new \InvalidArgumentException('invalid length for "$portability_id" when calling CreditPortabilityApi.creditPortabilityGetPortabilitiesByPortabilityId, must be bigger than or equal to 36.');
        }
        if (!preg_match("/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/", $portability_id)) {
            throw new \InvalidArgumentException("invalid value for \"portability_id\" when calling CreditPortabilityApi.creditPortabilityGetPortabilitiesByPortabilityId, must conform to the pattern /^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/.");
        }
        
        // verify the required parameter 'authorization' is set
        if ($authorization === null || (is_array($authorization) && count($authorization) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $authorization when calling creditPortabilityGetPortabilitiesByPortabilityId'
            );
        }
        if (strlen($authorization) > 2048) {
            throw new \InvalidArgumentException('invalid length for "$authorization" when calling CreditPortabilityApi.creditPortabilityGetPortabilitiesByPortabilityId, must be smaller than or equal to 2048.');
        }
        if (!preg_match("/[\\w\\W\\s]*/", $authorization)) {
            throw new \InvalidArgumentException("invalid value for \"authorization\" when calling CreditPortabilityApi.creditPortabilityGetPortabilitiesByPortabilityId, must conform to the pattern /[\\w\\W\\s]*/.");
        }
        
        // verify the required parameter 'x_fapi_interaction_id' is set
        if ($x_fapi_interaction_id === null || (is_array($x_fapi_interaction_id) && count($x_fapi_interaction_id) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $x_fapi_interaction_id when calling creditPortabilityGetPortabilitiesByPortabilityId'
            );
        }
        if (strlen($x_fapi_interaction_id) > 36) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_interaction_id" when calling CreditPortabilityApi.creditPortabilityGetPortabilitiesByPortabilityId, must be smaller than or equal to 36.');
        }
        if (strlen($x_fapi_interaction_id) < 1) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_interaction_id" when calling CreditPortabilityApi.creditPortabilityGetPortabilitiesByPortabilityId, must be bigger than or equal to 1.');
        }
        if (!preg_match("/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/", $x_fapi_interaction_id)) {
            throw new \InvalidArgumentException("invalid value for \"x_fapi_interaction_id\" when calling CreditPortabilityApi.creditPortabilityGetPortabilitiesByPortabilityId, must conform to the pattern /^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/.");
        }
        
        if ($x_fapi_auth_date !== null && strlen($x_fapi_auth_date) > 29) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_auth_date" when calling CreditPortabilityApi.creditPortabilityGetPortabilitiesByPortabilityId, must be smaller than or equal to 29.');
        }
        if ($x_fapi_auth_date !== null && strlen($x_fapi_auth_date) < 29) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_auth_date" when calling CreditPortabilityApi.creditPortabilityGetPortabilitiesByPortabilityId, must be bigger than or equal to 29.');
        }
        if ($x_fapi_auth_date !== null && !preg_match("/^(Mon|Tue|Wed|Thu|Fri|Sat|Sun), \\d{2} (Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec) \\d{4} \\d{2}:\\d{2}:\\d{2} (GMT|UTC)$/", $x_fapi_auth_date)) {
            throw new \InvalidArgumentException("invalid value for \"x_fapi_auth_date\" when calling CreditPortabilityApi.creditPortabilityGetPortabilitiesByPortabilityId, must conform to the pattern /^(Mon|Tue|Wed|Thu|Fri|Sat|Sun), \\d{2} (Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec) \\d{4} \\d{2}:\\d{2}:\\d{2} (GMT|UTC)$/.");
        }
        
        if ($x_fapi_customer_ip_address !== null && strlen($x_fapi_customer_ip_address) > 100) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_customer_ip_address" when calling CreditPortabilityApi.creditPortabilityGetPortabilitiesByPortabilityId, must be smaller than or equal to 100.');
        }
        if ($x_fapi_customer_ip_address !== null && strlen($x_fapi_customer_ip_address) < 1) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_customer_ip_address" when calling CreditPortabilityApi.creditPortabilityGetPortabilitiesByPortabilityId, must be bigger than or equal to 1.');
        }
        if ($x_fapi_customer_ip_address !== null && !preg_match("/[\\w\\W\\s]*/", $x_fapi_customer_ip_address)) {
            throw new \InvalidArgumentException("invalid value for \"x_fapi_customer_ip_address\" when calling CreditPortabilityApi.creditPortabilityGetPortabilitiesByPortabilityId, must conform to the pattern /[\\w\\W\\s]*/.");
        }
        
        if ($x_customer_user_agent !== null && strlen($x_customer_user_agent) > 100) {
            throw new \InvalidArgumentException('invalid length for "$x_customer_user_agent" when calling CreditPortabilityApi.creditPortabilityGetPortabilitiesByPortabilityId, must be smaller than or equal to 100.');
        }
        if ($x_customer_user_agent !== null && strlen($x_customer_user_agent) < 1) {
            throw new \InvalidArgumentException('invalid length for "$x_customer_user_agent" when calling CreditPortabilityApi.creditPortabilityGetPortabilitiesByPortabilityId, must be bigger than or equal to 1.');
        }
        if ($x_customer_user_agent !== null && !preg_match("/[\\w\\W\\s]*/", $x_customer_user_agent)) {
            throw new \InvalidArgumentException("invalid value for \"x_customer_user_agent\" when calling CreditPortabilityApi.creditPortabilityGetPortabilitiesByPortabilityId, must conform to the pattern /[\\w\\W\\s]*/.");
        }
        

        $resourcePath = '/portabilities/{portabilityId}';
        $formParams = [];
        $queryParams = [];
        $headerParams = [];
        $httpBody = '';
        $multipart = false;


        // header params
        if ($authorization !== null) {
            $headerParams['Authorization'] = ObjectSerializer::toHeaderValue($authorization);
        }
        // header params
        if ($x_fapi_auth_date !== null) {
            $headerParams['x-fapi-auth-date'] = ObjectSerializer::toHeaderValue($x_fapi_auth_date);
        }
        // header params
        if ($x_fapi_customer_ip_address !== null) {
            $headerParams['x-fapi-customer-ip-address'] = ObjectSerializer::toHeaderValue($x_fapi_customer_ip_address);
        }
        // header params
        if ($x_fapi_interaction_id !== null) {
            $headerParams['x-fapi-interaction-id'] = ObjectSerializer::toHeaderValue($x_fapi_interaction_id);
        }
        // header params
        if ($x_customer_user_agent !== null) {
            $headerParams['x-customer-user-agent'] = ObjectSerializer::toHeaderValue($x_customer_user_agent);
        }

        // path params
        if ($portability_id !== null) {
            $resourcePath = str_replace(
                '{' . 'portabilityId' . '}',
                ObjectSerializer::toPathValue($portability_id),
                $resourcePath
            );
        }


        $headers = $this->headerSelector->selectHeaders(
            ['application/jwt', 'application/json; charset=utf-8', ],
            $contentType,
            $multipart
        );

        // for model (json/xml)
        if (count($formParams) > 0) {
            if ($multipart) {
                $multipartContents = [];
                foreach ($formParams as $formParamName => $formParamValue) {
                    $formParamValueItems = is_array($formParamValue) ? $formParamValue : [$formParamValue];
                    foreach ($formParamValueItems as $formParamValueItem) {
                        $multipartContents[] = [
                            'name' => $formParamName,
                            'contents' => $formParamValueItem
                        ];
                    }
                }
                // for HTTP post (form)
                $httpBody = new MultipartStream($multipartContents);

            } elseif (stripos($headers['Content-Type'], 'application/json') !== false) {
                # if Content-Type contains "application/json", json_encode the form parameters
                $httpBody = \GuzzleHttp\Utils::jsonEncode($formParams);
            } else {
                // for HTTP post (form)
                $httpBody = ObjectSerializer::buildQuery($formParams);
            }
        }

        // this endpoint requires OAuth (access token)
        if (!empty($this->config->getAccessToken())) {
            $headers['Authorization'] = 'Bearer ' . $this->config->getAccessToken();
        }

        $defaultHeaders = [];
        if ($this->config->getUserAgent()) {
            $defaultHeaders['User-Agent'] = $this->config->getUserAgent();
        }

        $headers = array_merge(
            $defaultHeaders,
            $headerParams,
            $headers
        );

        $operationHost = $this->config->getHost();
        $query = ObjectSerializer::buildQuery($queryParams);
        return new Request(
            'GET',
            $operationHost . $resourcePath . ($query ? "?{$query}" : ''),
            $headers,
            $httpBody
        );
    }

    /**
     * Operation creditPortabilityPatchPortabilitiesPortabilityIdCancel
     *
     * Comunica a Instituição Credora a respeito do cancelamento da portabilidade de crédito.
     *
     * @param  string $portability_id Identificador do pedido de portabilidade de crédito. (required)
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado. (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela IF Proponente (client) e o seu valor deve ser “espelhado” pela IF Credora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a IF Credora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A IF Proponente deve acatar o valor recebido da IF Credora. (required)
     * @param  \OpenAPI\Client\Model\RequestCreditPortabilityCancel $request_credit_portability_cancel Payload para comunicar a liquidação efetuada pela proponente a credora e iniciar a proxima etapa do fluxo de portabilidade de crédito. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['creditPortabilityPatchPortabilitiesPortabilityIdCancel'] to see the possible values for this operation
     *
     * @throws \OpenAPI\Client\ApiException on non-2xx response or if the response body is not in the expected format
     * @throws \InvalidArgumentException
     * @return \OpenAPI\Client\Model\PatchResponseCreditPortabilityCancel|\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties|\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties|\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties|\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties|\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties|\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties|\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties|\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties|\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties|\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties|\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties
     */
    public function creditPortabilityPatchPortabilitiesPortabilityIdCancel($portability_id, $authorization, $x_fapi_interaction_id, $request_credit_portability_cancel, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, string $contentType = self::contentTypes['creditPortabilityPatchPortabilitiesPortabilityIdCancel'][0])
    {
        list($response) = $this->creditPortabilityPatchPortabilitiesPortabilityIdCancelWithHttpInfo($portability_id, $authorization, $x_fapi_interaction_id, $request_credit_portability_cancel, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $contentType);
        return $response;
    }

    /**
     * Operation creditPortabilityPatchPortabilitiesPortabilityIdCancelWithHttpInfo
     *
     * Comunica a Instituição Credora a respeito do cancelamento da portabilidade de crédito.
     *
     * @param  string $portability_id Identificador do pedido de portabilidade de crédito. (required)
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado. (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela IF Proponente (client) e o seu valor deve ser “espelhado” pela IF Credora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a IF Credora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A IF Proponente deve acatar o valor recebido da IF Credora. (required)
     * @param  \OpenAPI\Client\Model\RequestCreditPortabilityCancel $request_credit_portability_cancel Payload para comunicar a liquidação efetuada pela proponente a credora e iniciar a proxima etapa do fluxo de portabilidade de crédito. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['creditPortabilityPatchPortabilitiesPortabilityIdCancel'] to see the possible values for this operation
     *
     * @throws \OpenAPI\Client\ApiException on non-2xx response or if the response body is not in the expected format
     * @throws \InvalidArgumentException
     * @return array of \OpenAPI\Client\Model\PatchResponseCreditPortabilityCancel|\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties|\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties|\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties|\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties|\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties|\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties|\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties|\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties|\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties|\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties|\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties, HTTP status code, HTTP response headers (array of strings)
     */
    public function creditPortabilityPatchPortabilitiesPortabilityIdCancelWithHttpInfo($portability_id, $authorization, $x_fapi_interaction_id, $request_credit_portability_cancel, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, string $contentType = self::contentTypes['creditPortabilityPatchPortabilitiesPortabilityIdCancel'][0])
    {
        $request = $this->creditPortabilityPatchPortabilitiesPortabilityIdCancelRequest($portability_id, $authorization, $x_fapi_interaction_id, $request_credit_portability_cancel, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $contentType);

        try {
            $options = $this->createHttpClientOption();
            try {
                $response = $this->client->send($request, $options);
            } catch (RequestException $e) {
                throw new ApiException(
                    "[{$e->getCode()}] {$e->getMessage()}",
                    (int) $e->getCode(),
                    $e->getResponse() ? $e->getResponse()->getHeaders() : null,
                    $e->getResponse() ? (string) $e->getResponse()->getBody() : null
                );
            } catch (ConnectException $e) {
                throw new ApiException(
                    "[{$e->getCode()}] {$e->getMessage()}",
                    (int) $e->getCode(),
                    null,
                    null
                );
            }

            $statusCode = $response->getStatusCode();


            switch($statusCode) {
                case 200:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\PatchResponseCreditPortabilityCancel',
                        $request,
                        $response,
                    );
                case 400:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties',
                        $request,
                        $response,
                    );
                case 401:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties',
                        $request,
                        $response,
                    );
                case 403:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties',
                        $request,
                        $response,
                    );
                case 404:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties',
                        $request,
                        $response,
                    );
                case 405:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties',
                        $request,
                        $response,
                    );
                case 406:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties',
                        $request,
                        $response,
                    );
                case 422:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties',
                        $request,
                        $response,
                    );
                case 500:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties',
                        $request,
                        $response,
                    );
                case 504:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties',
                        $request,
                        $response,
                    );
                case 529:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties',
                        $request,
                        $response,
                    );
                default:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties',
                        $request,
                        $response,
                    );
            }

            

            if ($statusCode < 200 || $statusCode > 299) {
                throw new ApiException(
                    sprintf(
                        '[%d] Error connecting to the API (%s)',
                        $statusCode,
                        (string) $request->getUri()
                    ),
                    $statusCode,
                    $response->getHeaders(),
                    (string) $response->getBody()
                );
            }

            return $this->handleResponseWithDataType(
                '\OpenAPI\Client\Model\PatchResponseCreditPortabilityCancel',
                $request,
                $response,
            );
        } catch (ApiException $e) {
            switch ($e->getCode()) {
                case 200:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\PatchResponseCreditPortabilityCancel',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 400:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 401:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 403:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 404:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 405:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 406:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 422:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 500:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 504:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 529:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                default:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
            }
        

            throw $e;
        }
    }

    /**
     * Operation creditPortabilityPatchPortabilitiesPortabilityIdCancelAsync
     *
     * Comunica a Instituição Credora a respeito do cancelamento da portabilidade de crédito.
     *
     * @param  string $portability_id Identificador do pedido de portabilidade de crédito. (required)
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado. (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela IF Proponente (client) e o seu valor deve ser “espelhado” pela IF Credora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a IF Credora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A IF Proponente deve acatar o valor recebido da IF Credora. (required)
     * @param  \OpenAPI\Client\Model\RequestCreditPortabilityCancel $request_credit_portability_cancel Payload para comunicar a liquidação efetuada pela proponente a credora e iniciar a proxima etapa do fluxo de portabilidade de crédito. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['creditPortabilityPatchPortabilitiesPortabilityIdCancel'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function creditPortabilityPatchPortabilitiesPortabilityIdCancelAsync($portability_id, $authorization, $x_fapi_interaction_id, $request_credit_portability_cancel, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, string $contentType = self::contentTypes['creditPortabilityPatchPortabilitiesPortabilityIdCancel'][0])
    {
        return $this->creditPortabilityPatchPortabilitiesPortabilityIdCancelAsyncWithHttpInfo($portability_id, $authorization, $x_fapi_interaction_id, $request_credit_portability_cancel, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $contentType)
            ->then(
                function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation creditPortabilityPatchPortabilitiesPortabilityIdCancelAsyncWithHttpInfo
     *
     * Comunica a Instituição Credora a respeito do cancelamento da portabilidade de crédito.
     *
     * @param  string $portability_id Identificador do pedido de portabilidade de crédito. (required)
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado. (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela IF Proponente (client) e o seu valor deve ser “espelhado” pela IF Credora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a IF Credora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A IF Proponente deve acatar o valor recebido da IF Credora. (required)
     * @param  \OpenAPI\Client\Model\RequestCreditPortabilityCancel $request_credit_portability_cancel Payload para comunicar a liquidação efetuada pela proponente a credora e iniciar a proxima etapa do fluxo de portabilidade de crédito. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['creditPortabilityPatchPortabilitiesPortabilityIdCancel'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function creditPortabilityPatchPortabilitiesPortabilityIdCancelAsyncWithHttpInfo($portability_id, $authorization, $x_fapi_interaction_id, $request_credit_portability_cancel, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, string $contentType = self::contentTypes['creditPortabilityPatchPortabilitiesPortabilityIdCancel'][0])
    {
        $returnType = '\OpenAPI\Client\Model\PatchResponseCreditPortabilityCancel';
        $request = $this->creditPortabilityPatchPortabilitiesPortabilityIdCancelRequest($portability_id, $authorization, $x_fapi_interaction_id, $request_credit_portability_cancel, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $contentType);

        return $this->client
            ->sendAsync($request, $this->createHttpClientOption())
            ->then(
                function ($response) use ($returnType) {
                    if ($returnType === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ($returnType !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, $returnType, []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
                },
                function ($exception) {
                    $response = $exception->getResponse();
                    $statusCode = $response->getStatusCode();
                    throw new ApiException(
                        sprintf(
                            '[%d] Error connecting to the API (%s)',
                            $statusCode,
                            $exception->getRequest()->getUri()
                        ),
                        $statusCode,
                        $response->getHeaders(),
                        (string) $response->getBody()
                    );
                }
            );
    }

    /**
     * Create request for operation 'creditPortabilityPatchPortabilitiesPortabilityIdCancel'
     *
     * @param  string $portability_id Identificador do pedido de portabilidade de crédito. (required)
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado. (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela IF Proponente (client) e o seu valor deve ser “espelhado” pela IF Credora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a IF Credora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A IF Proponente deve acatar o valor recebido da IF Credora. (required)
     * @param  \OpenAPI\Client\Model\RequestCreditPortabilityCancel $request_credit_portability_cancel Payload para comunicar a liquidação efetuada pela proponente a credora e iniciar a proxima etapa do fluxo de portabilidade de crédito. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['creditPortabilityPatchPortabilitiesPortabilityIdCancel'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Psr7\Request
     */
    public function creditPortabilityPatchPortabilitiesPortabilityIdCancelRequest($portability_id, $authorization, $x_fapi_interaction_id, $request_credit_portability_cancel, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, string $contentType = self::contentTypes['creditPortabilityPatchPortabilitiesPortabilityIdCancel'][0])
    {

        // verify the required parameter 'portability_id' is set
        if ($portability_id === null || (is_array($portability_id) && count($portability_id) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $portability_id when calling creditPortabilityPatchPortabilitiesPortabilityIdCancel'
            );
        }
        if (strlen($portability_id) > 36) {
            throw new \InvalidArgumentException('invalid length for "$portability_id" when calling CreditPortabilityApi.creditPortabilityPatchPortabilitiesPortabilityIdCancel, must be smaller than or equal to 36.');
        }
        if (strlen($portability_id) < 36) {
            throw new \InvalidArgumentException('invalid length for "$portability_id" when calling CreditPortabilityApi.creditPortabilityPatchPortabilitiesPortabilityIdCancel, must be bigger than or equal to 36.');
        }
        if (!preg_match("/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/", $portability_id)) {
            throw new \InvalidArgumentException("invalid value for \"portability_id\" when calling CreditPortabilityApi.creditPortabilityPatchPortabilitiesPortabilityIdCancel, must conform to the pattern /^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/.");
        }
        
        // verify the required parameter 'authorization' is set
        if ($authorization === null || (is_array($authorization) && count($authorization) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $authorization when calling creditPortabilityPatchPortabilitiesPortabilityIdCancel'
            );
        }
        if (strlen($authorization) > 2048) {
            throw new \InvalidArgumentException('invalid length for "$authorization" when calling CreditPortabilityApi.creditPortabilityPatchPortabilitiesPortabilityIdCancel, must be smaller than or equal to 2048.');
        }
        if (!preg_match("/[\\w\\W\\s]*/", $authorization)) {
            throw new \InvalidArgumentException("invalid value for \"authorization\" when calling CreditPortabilityApi.creditPortabilityPatchPortabilitiesPortabilityIdCancel, must conform to the pattern /[\\w\\W\\s]*/.");
        }
        
        // verify the required parameter 'x_fapi_interaction_id' is set
        if ($x_fapi_interaction_id === null || (is_array($x_fapi_interaction_id) && count($x_fapi_interaction_id) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $x_fapi_interaction_id when calling creditPortabilityPatchPortabilitiesPortabilityIdCancel'
            );
        }
        if (strlen($x_fapi_interaction_id) > 36) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_interaction_id" when calling CreditPortabilityApi.creditPortabilityPatchPortabilitiesPortabilityIdCancel, must be smaller than or equal to 36.');
        }
        if (strlen($x_fapi_interaction_id) < 1) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_interaction_id" when calling CreditPortabilityApi.creditPortabilityPatchPortabilitiesPortabilityIdCancel, must be bigger than or equal to 1.');
        }
        if (!preg_match("/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/", $x_fapi_interaction_id)) {
            throw new \InvalidArgumentException("invalid value for \"x_fapi_interaction_id\" when calling CreditPortabilityApi.creditPortabilityPatchPortabilitiesPortabilityIdCancel, must conform to the pattern /^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/.");
        }
        
        // verify the required parameter 'request_credit_portability_cancel' is set
        if ($request_credit_portability_cancel === null || (is_array($request_credit_portability_cancel) && count($request_credit_portability_cancel) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $request_credit_portability_cancel when calling creditPortabilityPatchPortabilitiesPortabilityIdCancel'
            );
        }

        if ($x_fapi_auth_date !== null && strlen($x_fapi_auth_date) > 29) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_auth_date" when calling CreditPortabilityApi.creditPortabilityPatchPortabilitiesPortabilityIdCancel, must be smaller than or equal to 29.');
        }
        if ($x_fapi_auth_date !== null && strlen($x_fapi_auth_date) < 29) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_auth_date" when calling CreditPortabilityApi.creditPortabilityPatchPortabilitiesPortabilityIdCancel, must be bigger than or equal to 29.');
        }
        if ($x_fapi_auth_date !== null && !preg_match("/^(Mon|Tue|Wed|Thu|Fri|Sat|Sun), \\d{2} (Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec) \\d{4} \\d{2}:\\d{2}:\\d{2} (GMT|UTC)$/", $x_fapi_auth_date)) {
            throw new \InvalidArgumentException("invalid value for \"x_fapi_auth_date\" when calling CreditPortabilityApi.creditPortabilityPatchPortabilitiesPortabilityIdCancel, must conform to the pattern /^(Mon|Tue|Wed|Thu|Fri|Sat|Sun), \\d{2} (Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec) \\d{4} \\d{2}:\\d{2}:\\d{2} (GMT|UTC)$/.");
        }
        
        if ($x_fapi_customer_ip_address !== null && strlen($x_fapi_customer_ip_address) > 100) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_customer_ip_address" when calling CreditPortabilityApi.creditPortabilityPatchPortabilitiesPortabilityIdCancel, must be smaller than or equal to 100.');
        }
        if ($x_fapi_customer_ip_address !== null && strlen($x_fapi_customer_ip_address) < 1) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_customer_ip_address" when calling CreditPortabilityApi.creditPortabilityPatchPortabilitiesPortabilityIdCancel, must be bigger than or equal to 1.');
        }
        if ($x_fapi_customer_ip_address !== null && !preg_match("/[\\w\\W\\s]*/", $x_fapi_customer_ip_address)) {
            throw new \InvalidArgumentException("invalid value for \"x_fapi_customer_ip_address\" when calling CreditPortabilityApi.creditPortabilityPatchPortabilitiesPortabilityIdCancel, must conform to the pattern /[\\w\\W\\s]*/.");
        }
        
        if ($x_customer_user_agent !== null && strlen($x_customer_user_agent) > 100) {
            throw new \InvalidArgumentException('invalid length for "$x_customer_user_agent" when calling CreditPortabilityApi.creditPortabilityPatchPortabilitiesPortabilityIdCancel, must be smaller than or equal to 100.');
        }
        if ($x_customer_user_agent !== null && strlen($x_customer_user_agent) < 1) {
            throw new \InvalidArgumentException('invalid length for "$x_customer_user_agent" when calling CreditPortabilityApi.creditPortabilityPatchPortabilitiesPortabilityIdCancel, must be bigger than or equal to 1.');
        }
        if ($x_customer_user_agent !== null && !preg_match("/[\\w\\W\\s]*/", $x_customer_user_agent)) {
            throw new \InvalidArgumentException("invalid value for \"x_customer_user_agent\" when calling CreditPortabilityApi.creditPortabilityPatchPortabilitiesPortabilityIdCancel, must conform to the pattern /[\\w\\W\\s]*/.");
        }
        

        $resourcePath = '/portabilities/{portabilityId}/cancel';
        $formParams = [];
        $queryParams = [];
        $headerParams = [];
        $httpBody = '';
        $multipart = false;


        // header params
        if ($authorization !== null) {
            $headerParams['Authorization'] = ObjectSerializer::toHeaderValue($authorization);
        }
        // header params
        if ($x_fapi_auth_date !== null) {
            $headerParams['x-fapi-auth-date'] = ObjectSerializer::toHeaderValue($x_fapi_auth_date);
        }
        // header params
        if ($x_fapi_customer_ip_address !== null) {
            $headerParams['x-fapi-customer-ip-address'] = ObjectSerializer::toHeaderValue($x_fapi_customer_ip_address);
        }
        // header params
        if ($x_fapi_interaction_id !== null) {
            $headerParams['x-fapi-interaction-id'] = ObjectSerializer::toHeaderValue($x_fapi_interaction_id);
        }
        // header params
        if ($x_customer_user_agent !== null) {
            $headerParams['x-customer-user-agent'] = ObjectSerializer::toHeaderValue($x_customer_user_agent);
        }

        // path params
        if ($portability_id !== null) {
            $resourcePath = str_replace(
                '{' . 'portabilityId' . '}',
                ObjectSerializer::toPathValue($portability_id),
                $resourcePath
            );
        }


        $headers = $this->headerSelector->selectHeaders(
            ['application/jwt', 'application/json; charset=utf-8', ],
            $contentType,
            $multipart
        );

        // for model (json/xml)
        if (isset($request_credit_portability_cancel)) {
            if (stripos($headers['Content-Type'], 'application/json') !== false) {
                # if Content-Type contains "application/json", json_encode the body
                $httpBody = \GuzzleHttp\Utils::jsonEncode(ObjectSerializer::sanitizeForSerialization($request_credit_portability_cancel));
            } else {
                $httpBody = $request_credit_portability_cancel;
            }
        } elseif (count($formParams) > 0) {
            if ($multipart) {
                $multipartContents = [];
                foreach ($formParams as $formParamName => $formParamValue) {
                    $formParamValueItems = is_array($formParamValue) ? $formParamValue : [$formParamValue];
                    foreach ($formParamValueItems as $formParamValueItem) {
                        $multipartContents[] = [
                            'name' => $formParamName,
                            'contents' => $formParamValueItem
                        ];
                    }
                }
                // for HTTP post (form)
                $httpBody = new MultipartStream($multipartContents);

            } elseif (stripos($headers['Content-Type'], 'application/json') !== false) {
                # if Content-Type contains "application/json", json_encode the form parameters
                $httpBody = \GuzzleHttp\Utils::jsonEncode($formParams);
            } else {
                // for HTTP post (form)
                $httpBody = ObjectSerializer::buildQuery($formParams);
            }
        }

        // this endpoint requires OAuth (access token)
        if (!empty($this->config->getAccessToken())) {
            $headers['Authorization'] = 'Bearer ' . $this->config->getAccessToken();
        }

        $defaultHeaders = [];
        if ($this->config->getUserAgent()) {
            $defaultHeaders['User-Agent'] = $this->config->getUserAgent();
        }

        $headers = array_merge(
            $defaultHeaders,
            $headerParams,
            $headers
        );

        $operationHost = $this->config->getHost();
        $query = ObjectSerializer::buildQuery($queryParams);
        return new Request(
            'PATCH',
            $operationHost . $resourcePath . ($query ? "?{$query}" : ''),
            $headers,
            $httpBody
        );
    }

    /**
     * Operation creditPortabilityPostPortabilities
     *
     * Realiza pedido de portabilidade de crédito para um determinado contrato junto a instituição credora
     *
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado. (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela IF Proponente (client) e o seu valor deve ser “espelhado” pela IF Credora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a IF Credora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A IF Proponente deve acatar o valor recebido da IF Credora. (required)
     * @param  string $x_idempotency_key Cabeçalho HTTP personalizado. Identificador de solicitação exclusivo para suportar a idempotência. (required)
     * @param  \OpenAPI\Client\Model\RequestCreditPortability $request_credit_portability Payload para o pedido de portabilidade de crédito. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['creditPortabilityPostPortabilities'] to see the possible values for this operation
     *
     * @throws \OpenAPI\Client\ApiException on non-2xx response or if the response body is not in the expected format
     * @throws \InvalidArgumentException
     * @return \OpenAPI\Client\Model\POSTResponseCreditPortability|\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties|\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties|\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties|\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties|\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties|\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties|\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties|\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties|\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties|\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties|\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties
     */
    public function creditPortabilityPostPortabilities($authorization, $x_fapi_interaction_id, $x_idempotency_key, $request_credit_portability, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, string $contentType = self::contentTypes['creditPortabilityPostPortabilities'][0])
    {
        list($response) = $this->creditPortabilityPostPortabilitiesWithHttpInfo($authorization, $x_fapi_interaction_id, $x_idempotency_key, $request_credit_portability, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $contentType);
        return $response;
    }

    /**
     * Operation creditPortabilityPostPortabilitiesWithHttpInfo
     *
     * Realiza pedido de portabilidade de crédito para um determinado contrato junto a instituição credora
     *
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado. (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela IF Proponente (client) e o seu valor deve ser “espelhado” pela IF Credora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a IF Credora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A IF Proponente deve acatar o valor recebido da IF Credora. (required)
     * @param  string $x_idempotency_key Cabeçalho HTTP personalizado. Identificador de solicitação exclusivo para suportar a idempotência. (required)
     * @param  \OpenAPI\Client\Model\RequestCreditPortability $request_credit_portability Payload para o pedido de portabilidade de crédito. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['creditPortabilityPostPortabilities'] to see the possible values for this operation
     *
     * @throws \OpenAPI\Client\ApiException on non-2xx response or if the response body is not in the expected format
     * @throws \InvalidArgumentException
     * @return array of \OpenAPI\Client\Model\POSTResponseCreditPortability|\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties|\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties|\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties|\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties|\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties|\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties|\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties|\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties|\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties|\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties|\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties, HTTP status code, HTTP response headers (array of strings)
     */
    public function creditPortabilityPostPortabilitiesWithHttpInfo($authorization, $x_fapi_interaction_id, $x_idempotency_key, $request_credit_portability, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, string $contentType = self::contentTypes['creditPortabilityPostPortabilities'][0])
    {
        $request = $this->creditPortabilityPostPortabilitiesRequest($authorization, $x_fapi_interaction_id, $x_idempotency_key, $request_credit_portability, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $contentType);

        try {
            $options = $this->createHttpClientOption();
            try {
                $response = $this->client->send($request, $options);
            } catch (RequestException $e) {
                throw new ApiException(
                    "[{$e->getCode()}] {$e->getMessage()}",
                    (int) $e->getCode(),
                    $e->getResponse() ? $e->getResponse()->getHeaders() : null,
                    $e->getResponse() ? (string) $e->getResponse()->getBody() : null
                );
            } catch (ConnectException $e) {
                throw new ApiException(
                    "[{$e->getCode()}] {$e->getMessage()}",
                    (int) $e->getCode(),
                    null,
                    null
                );
            }

            $statusCode = $response->getStatusCode();


            switch($statusCode) {
                case 202:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\POSTResponseCreditPortability',
                        $request,
                        $response,
                    );
                case 400:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties',
                        $request,
                        $response,
                    );
                case 401:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties',
                        $request,
                        $response,
                    );
                case 403:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties',
                        $request,
                        $response,
                    );
                case 404:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties',
                        $request,
                        $response,
                    );
                case 405:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties',
                        $request,
                        $response,
                    );
                case 406:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties',
                        $request,
                        $response,
                    );
                case 422:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties',
                        $request,
                        $response,
                    );
                case 500:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties',
                        $request,
                        $response,
                    );
                case 504:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties',
                        $request,
                        $response,
                    );
                case 529:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties',
                        $request,
                        $response,
                    );
                default:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties',
                        $request,
                        $response,
                    );
            }

            

            if ($statusCode < 200 || $statusCode > 299) {
                throw new ApiException(
                    sprintf(
                        '[%d] Error connecting to the API (%s)',
                        $statusCode,
                        (string) $request->getUri()
                    ),
                    $statusCode,
                    $response->getHeaders(),
                    (string) $response->getBody()
                );
            }

            return $this->handleResponseWithDataType(
                '\OpenAPI\Client\Model\POSTResponseCreditPortability',
                $request,
                $response,
            );
        } catch (ApiException $e) {
            switch ($e->getCode()) {
                case 202:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\POSTResponseCreditPortability',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 400:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 401:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 403:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 404:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 405:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 406:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 422:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 500:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 504:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 529:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                default:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseErrorWithAbleAdditionalProperties',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
            }
        

            throw $e;
        }
    }

    /**
     * Operation creditPortabilityPostPortabilitiesAsync
     *
     * Realiza pedido de portabilidade de crédito para um determinado contrato junto a instituição credora
     *
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado. (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela IF Proponente (client) e o seu valor deve ser “espelhado” pela IF Credora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a IF Credora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A IF Proponente deve acatar o valor recebido da IF Credora. (required)
     * @param  string $x_idempotency_key Cabeçalho HTTP personalizado. Identificador de solicitação exclusivo para suportar a idempotência. (required)
     * @param  \OpenAPI\Client\Model\RequestCreditPortability $request_credit_portability Payload para o pedido de portabilidade de crédito. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['creditPortabilityPostPortabilities'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function creditPortabilityPostPortabilitiesAsync($authorization, $x_fapi_interaction_id, $x_idempotency_key, $request_credit_portability, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, string $contentType = self::contentTypes['creditPortabilityPostPortabilities'][0])
    {
        return $this->creditPortabilityPostPortabilitiesAsyncWithHttpInfo($authorization, $x_fapi_interaction_id, $x_idempotency_key, $request_credit_portability, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $contentType)
            ->then(
                function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation creditPortabilityPostPortabilitiesAsyncWithHttpInfo
     *
     * Realiza pedido de portabilidade de crédito para um determinado contrato junto a instituição credora
     *
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado. (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela IF Proponente (client) e o seu valor deve ser “espelhado” pela IF Credora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a IF Credora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A IF Proponente deve acatar o valor recebido da IF Credora. (required)
     * @param  string $x_idempotency_key Cabeçalho HTTP personalizado. Identificador de solicitação exclusivo para suportar a idempotência. (required)
     * @param  \OpenAPI\Client\Model\RequestCreditPortability $request_credit_portability Payload para o pedido de portabilidade de crédito. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['creditPortabilityPostPortabilities'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function creditPortabilityPostPortabilitiesAsyncWithHttpInfo($authorization, $x_fapi_interaction_id, $x_idempotency_key, $request_credit_portability, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, string $contentType = self::contentTypes['creditPortabilityPostPortabilities'][0])
    {
        $returnType = '\OpenAPI\Client\Model\POSTResponseCreditPortability';
        $request = $this->creditPortabilityPostPortabilitiesRequest($authorization, $x_fapi_interaction_id, $x_idempotency_key, $request_credit_portability, $x_fapi_auth_date, $x_fapi_customer_ip_address, $x_customer_user_agent, $contentType);

        return $this->client
            ->sendAsync($request, $this->createHttpClientOption())
            ->then(
                function ($response) use ($returnType) {
                    if ($returnType === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ($returnType !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, $returnType, []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
                },
                function ($exception) {
                    $response = $exception->getResponse();
                    $statusCode = $response->getStatusCode();
                    throw new ApiException(
                        sprintf(
                            '[%d] Error connecting to the API (%s)',
                            $statusCode,
                            $exception->getRequest()->getUri()
                        ),
                        $statusCode,
                        $response->getHeaders(),
                        (string) $response->getBody()
                    );
                }
            );
    }

    /**
     * Create request for operation 'creditPortabilityPostPortabilities'
     *
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado. (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela IF Proponente (client) e o seu valor deve ser “espelhado” pela IF Credora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a IF Credora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP Status Code 400. A IF Proponente deve acatar o valor recebido da IF Credora. (required)
     * @param  string $x_idempotency_key Cabeçalho HTTP personalizado. Identificador de solicitação exclusivo para suportar a idempotência. (required)
     * @param  \OpenAPI\Client\Model\RequestCreditPortability $request_credit_portability Payload para o pedido de portabilidade de crédito. (required)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o receptor. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o receptor. (optional)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['creditPortabilityPostPortabilities'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Psr7\Request
     */
    public function creditPortabilityPostPortabilitiesRequest($authorization, $x_fapi_interaction_id, $x_idempotency_key, $request_credit_portability, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $x_customer_user_agent = null, string $contentType = self::contentTypes['creditPortabilityPostPortabilities'][0])
    {

        // verify the required parameter 'authorization' is set
        if ($authorization === null || (is_array($authorization) && count($authorization) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $authorization when calling creditPortabilityPostPortabilities'
            );
        }
        if (strlen($authorization) > 2048) {
            throw new \InvalidArgumentException('invalid length for "$authorization" when calling CreditPortabilityApi.creditPortabilityPostPortabilities, must be smaller than or equal to 2048.');
        }
        if (!preg_match("/[\\w\\W\\s]*/", $authorization)) {
            throw new \InvalidArgumentException("invalid value for \"authorization\" when calling CreditPortabilityApi.creditPortabilityPostPortabilities, must conform to the pattern /[\\w\\W\\s]*/.");
        }
        
        // verify the required parameter 'x_fapi_interaction_id' is set
        if ($x_fapi_interaction_id === null || (is_array($x_fapi_interaction_id) && count($x_fapi_interaction_id) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $x_fapi_interaction_id when calling creditPortabilityPostPortabilities'
            );
        }
        if (strlen($x_fapi_interaction_id) > 36) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_interaction_id" when calling CreditPortabilityApi.creditPortabilityPostPortabilities, must be smaller than or equal to 36.');
        }
        if (strlen($x_fapi_interaction_id) < 1) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_interaction_id" when calling CreditPortabilityApi.creditPortabilityPostPortabilities, must be bigger than or equal to 1.');
        }
        if (!preg_match("/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/", $x_fapi_interaction_id)) {
            throw new \InvalidArgumentException("invalid value for \"x_fapi_interaction_id\" when calling CreditPortabilityApi.creditPortabilityPostPortabilities, must conform to the pattern /^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/.");
        }
        
        // verify the required parameter 'x_idempotency_key' is set
        if ($x_idempotency_key === null || (is_array($x_idempotency_key) && count($x_idempotency_key) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $x_idempotency_key when calling creditPortabilityPostPortabilities'
            );
        }
        if (strlen($x_idempotency_key) > 40) {
            throw new \InvalidArgumentException('invalid length for "$x_idempotency_key" when calling CreditPortabilityApi.creditPortabilityPostPortabilities, must be smaller than or equal to 40.');
        }
        if (strlen($x_idempotency_key) < 1) {
            throw new \InvalidArgumentException('invalid length for "$x_idempotency_key" when calling CreditPortabilityApi.creditPortabilityPostPortabilities, must be bigger than or equal to 1.');
        }
        if (!preg_match("/^(?!\\s)(.*)(\\S)$/", $x_idempotency_key)) {
            throw new \InvalidArgumentException("invalid value for \"x_idempotency_key\" when calling CreditPortabilityApi.creditPortabilityPostPortabilities, must conform to the pattern /^(?!\\s)(.*)(\\S)$/.");
        }
        
        // verify the required parameter 'request_credit_portability' is set
        if ($request_credit_portability === null || (is_array($request_credit_portability) && count($request_credit_portability) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $request_credit_portability when calling creditPortabilityPostPortabilities'
            );
        }

        if ($x_fapi_auth_date !== null && strlen($x_fapi_auth_date) > 29) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_auth_date" when calling CreditPortabilityApi.creditPortabilityPostPortabilities, must be smaller than or equal to 29.');
        }
        if ($x_fapi_auth_date !== null && strlen($x_fapi_auth_date) < 29) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_auth_date" when calling CreditPortabilityApi.creditPortabilityPostPortabilities, must be bigger than or equal to 29.');
        }
        if ($x_fapi_auth_date !== null && !preg_match("/^(Mon|Tue|Wed|Thu|Fri|Sat|Sun), \\d{2} (Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec) \\d{4} \\d{2}:\\d{2}:\\d{2} (GMT|UTC)$/", $x_fapi_auth_date)) {
            throw new \InvalidArgumentException("invalid value for \"x_fapi_auth_date\" when calling CreditPortabilityApi.creditPortabilityPostPortabilities, must conform to the pattern /^(Mon|Tue|Wed|Thu|Fri|Sat|Sun), \\d{2} (Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec) \\d{4} \\d{2}:\\d{2}:\\d{2} (GMT|UTC)$/.");
        }
        
        if ($x_fapi_customer_ip_address !== null && strlen($x_fapi_customer_ip_address) > 100) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_customer_ip_address" when calling CreditPortabilityApi.creditPortabilityPostPortabilities, must be smaller than or equal to 100.');
        }
        if ($x_fapi_customer_ip_address !== null && strlen($x_fapi_customer_ip_address) < 1) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_customer_ip_address" when calling CreditPortabilityApi.creditPortabilityPostPortabilities, must be bigger than or equal to 1.');
        }
        if ($x_fapi_customer_ip_address !== null && !preg_match("/[\\w\\W\\s]*/", $x_fapi_customer_ip_address)) {
            throw new \InvalidArgumentException("invalid value for \"x_fapi_customer_ip_address\" when calling CreditPortabilityApi.creditPortabilityPostPortabilities, must conform to the pattern /[\\w\\W\\s]*/.");
        }
        
        if ($x_customer_user_agent !== null && strlen($x_customer_user_agent) > 100) {
            throw new \InvalidArgumentException('invalid length for "$x_customer_user_agent" when calling CreditPortabilityApi.creditPortabilityPostPortabilities, must be smaller than or equal to 100.');
        }
        if ($x_customer_user_agent !== null && strlen($x_customer_user_agent) < 1) {
            throw new \InvalidArgumentException('invalid length for "$x_customer_user_agent" when calling CreditPortabilityApi.creditPortabilityPostPortabilities, must be bigger than or equal to 1.');
        }
        if ($x_customer_user_agent !== null && !preg_match("/[\\w\\W\\s]*/", $x_customer_user_agent)) {
            throw new \InvalidArgumentException("invalid value for \"x_customer_user_agent\" when calling CreditPortabilityApi.creditPortabilityPostPortabilities, must conform to the pattern /[\\w\\W\\s]*/.");
        }
        

        $resourcePath = '/portabilities';
        $formParams = [];
        $queryParams = [];
        $headerParams = [];
        $httpBody = '';
        $multipart = false;


        // header params
        if ($authorization !== null) {
            $headerParams['Authorization'] = ObjectSerializer::toHeaderValue($authorization);
        }
        // header params
        if ($x_fapi_auth_date !== null) {
            $headerParams['x-fapi-auth-date'] = ObjectSerializer::toHeaderValue($x_fapi_auth_date);
        }
        // header params
        if ($x_fapi_customer_ip_address !== null) {
            $headerParams['x-fapi-customer-ip-address'] = ObjectSerializer::toHeaderValue($x_fapi_customer_ip_address);
        }
        // header params
        if ($x_fapi_interaction_id !== null) {
            $headerParams['x-fapi-interaction-id'] = ObjectSerializer::toHeaderValue($x_fapi_interaction_id);
        }
        // header params
        if ($x_customer_user_agent !== null) {
            $headerParams['x-customer-user-agent'] = ObjectSerializer::toHeaderValue($x_customer_user_agent);
        }
        // header params
        if ($x_idempotency_key !== null) {
            $headerParams['x-idempotency-key'] = ObjectSerializer::toHeaderValue($x_idempotency_key);
        }



        $headers = $this->headerSelector->selectHeaders(
            ['application/jwt', 'application/json; charset=utf-8', ],
            $contentType,
            $multipart
        );

        // for model (json/xml)
        if (isset($request_credit_portability)) {
            if (stripos($headers['Content-Type'], 'application/json') !== false) {
                # if Content-Type contains "application/json", json_encode the body
                $httpBody = \GuzzleHttp\Utils::jsonEncode(ObjectSerializer::sanitizeForSerialization($request_credit_portability));
            } else {
                $httpBody = $request_credit_portability;
            }
        } elseif (count($formParams) > 0) {
            if ($multipart) {
                $multipartContents = [];
                foreach ($formParams as $formParamName => $formParamValue) {
                    $formParamValueItems = is_array($formParamValue) ? $formParamValue : [$formParamValue];
                    foreach ($formParamValueItems as $formParamValueItem) {
                        $multipartContents[] = [
                            'name' => $formParamName,
                            'contents' => $formParamValueItem
                        ];
                    }
                }
                // for HTTP post (form)
                $httpBody = new MultipartStream($multipartContents);

            } elseif (stripos($headers['Content-Type'], 'application/json') !== false) {
                # if Content-Type contains "application/json", json_encode the form parameters
                $httpBody = \GuzzleHttp\Utils::jsonEncode($formParams);
            } else {
                // for HTTP post (form)
                $httpBody = ObjectSerializer::buildQuery($formParams);
            }
        }

        // this endpoint requires OAuth (access token)
        if (!empty($this->config->getAccessToken())) {
            $headers['Authorization'] = 'Bearer ' . $this->config->getAccessToken();
        }

        $defaultHeaders = [];
        if ($this->config->getUserAgent()) {
            $defaultHeaders['User-Agent'] = $this->config->getUserAgent();
        }

        $headers = array_merge(
            $defaultHeaders,
            $headerParams,
            $headers
        );

        $operationHost = $this->config->getHost();
        $query = ObjectSerializer::buildQuery($queryParams);
        return new Request(
            'POST',
            $operationHost . $resourcePath . ($query ? "?{$query}" : ''),
            $headers,
            $httpBody
        );
    }

    /**
     * Create http client option
     *
     * @throws \RuntimeException on file opening failure
     * @return array of http client options
     */
    protected function createHttpClientOption()
    {
        $options = [];
        if ($this->config->getDebug()) {
            $options[RequestOptions::DEBUG] = fopen($this->config->getDebugFile(), 'a');
            if (!$options[RequestOptions::DEBUG]) {
                throw new \RuntimeException('Failed to open the debug file: ' . $this->config->getDebugFile());
            }
        }

        if ($this->config->getCertFile()) {
            $options[RequestOptions::CERT] = $this->config->getCertFile();
        }

        if ($this->config->getKeyFile()) {
            $options[RequestOptions::SSL_KEY] = $this->config->getKeyFile();
        }

        return $options;
    }

    private function handleResponseWithDataType(
        string $dataType,
        RequestInterface $request,
        ResponseInterface $response
    ): array {
        if ($dataType === '\SplFileObject') {
            $content = $response->getBody(); //stream goes to serializer
        } else {
            $content = (string) $response->getBody();
            if ($dataType !== 'string') {
                try {
                    $content = json_decode($content, false, 512, JSON_THROW_ON_ERROR);
                } catch (\JsonException $exception) {
                    throw new ApiException(
                        sprintf(
                            'Error JSON decoding server response (%s)',
                            $request->getUri()
                        ),
                        $response->getStatusCode(),
                        $response->getHeaders(),
                        $content
                    );
                }
            }
        }

        return [
            ObjectSerializer::deserialize($content, $dataType, []),
            $response->getStatusCode(),
            $response->getHeaders()
        ];
    }

    private function responseWithinRangeCode(
        string $rangeCode,
        int $statusCode
    ): bool {
        $left = (int) ($rangeCode[0].'00');
        $right = (int) ($rangeCode[0].'99');

        return $statusCode >= $left && $statusCode <= $right;
    }
}
