<?php

namespace Sabba\OpenFinance\Bradesco;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\HandlerStack;
use Psr\SimpleCache\CacheInterface;
use Sabba\OpenFinance\Core\DTO\ConnectionContext;

final readonly class BradescoHttpClient
{
    public function __construct(
        private CacheInterface $cache,
        private int $timeoutSeconds = 15,
        private ?HandlerStack $handler = null,
    ) {}

    /** @return array<string,mixed> */
    public function request(
        ConnectionContext $context,
        string $product,
        string $method,
        string $path,
        array $options = [],
    ): array {
        $config = $this->productConfig($context, $product);
        $client = $this->client($config, true);
        $options['headers'] = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.$this->accessToken($context, $product, $config),
            ...($config['headers'] ?? []),
            ...($options['headers'] ?? []),
        ];

        try {
            $response = $client->request($method, ltrim($path, '/'), $options);
        } catch (GuzzleException $exception) {
            throw new BradescoProviderException('Falha de transporte na API Bradesco: '.$exception->getMessage(), previous: $exception);
        }

        return $this->decodeResponse($response->getStatusCode(), (string) $response->getBody(), 'A API Bradesco rejeitou a operação.');
    }

    /**
     * Valida mTLS e OAuth2 sem executar uma operação financeira.
     *
     * @return array{token_type:string,expires_in:int,scope:list<string>}
     */
    public function testAuthentication(ConnectionContext $context, string $product): array
    {
        $response = $this->fetchAccessToken($this->productConfig($context, $product));

        return [
            'token_type' => (string) ($response['token_type'] ?? 'Bearer'),
            'expires_in' => (int) ($response['expires_in'] ?? 0),
            'scope' => preg_split('/\s+/', trim((string) ($response['scope'] ?? '')), -1, PREG_SPLIT_NO_EMPTY) ?: [],
        ];
    }

    /** @param array<string,mixed> $config */
    private function accessToken(ConnectionContext $context, string $product, array $config): string
    {
        $key = 'bradesco:token:'.hash('sha256', implode('|', [
            $context->connectionId,
            $product,
            (string) $config['client_id'],
            (string) $config['token_url'],
        ]));

        if ($token = $this->cache->get($key)) {
            return (string) $token;
        }

        $decoded = $this->fetchAccessToken($config);
        $token = (string) $decoded['access_token'];
        $ttl = max(30, ((int) ($decoded['expires_in'] ?? 300)) - 30);
        $this->cache->set($key, $token, $ttl);

        return $token;
    }

    /**
     * @param  array<string,mixed>  $config
     * @return array<string,mixed>
     */
    private function fetchAccessToken(array $config): array
    {
        $client = $this->client($config);

        try {
            $response = $client->post((string) $config['token_url'], [
                'form_params' => array_filter([
                    'grant_type' => $config['grant_type'] ?? 'client_credentials',
                    'client_id' => $config['client_id'],
                    'client_secret' => $config['client_secret'],
                    'scope' => $config['scope'] ?? null,
                ], fn (mixed $value): bool => $value !== null && $value !== ''),
                'headers' => ['Accept' => 'application/json'],
            ]);
        } catch (GuzzleException $exception) {
            throw new BradescoProviderException('Falha ao autenticar na API Bradesco: '.$exception->getMessage(), previous: $exception);
        }

        $decoded = $this->decodeResponse(
            $response->getStatusCode(),
            (string) $response->getBody(),
            'Não foi possível obter o token OAuth2 do Bradesco.',
        );
        if (! is_string($decoded['access_token'] ?? null) || $decoded['access_token'] === '') {
            throw new BradescoProviderException(
                'Não foi possível obter o token OAuth2 do Bradesco.',
                $response->getStatusCode(),
            );
        }

        return $decoded;
    }

    /** @param array<string,mixed> $config */
    private function client(array $config, bool $withBaseUri = false): Client
    {
        return new Client([
            ...($withBaseUri ? ['base_uri' => rtrim((string) $config['base_url'], '/').'/'] : []),
            'timeout' => $this->timeoutSeconds,
            'connect_timeout' => 5,
            'cert' => $config['certificate_path'] ?? null,
            'ssl_key' => isset($config['private_key_path'])
                ? [$config['private_key_path'], $config['private_key_passphrase'] ?? '']
                : null,
            'http_errors' => false,
            ...($this->handler ? ['handler' => $this->handler] : []),
        ]);
    }

    /** @return array<string,mixed> */
    private function decodeResponse(int $status, string $body, string $fallbackMessage): array
    {
        $decoded = $body === '' ? [] : json_decode($body, true);
        $decoded = is_array($decoded) ? $decoded : [];

        if ($status >= 400) {
            $message = $decoded['error_description']
                ?? $decoded['message']
                ?? $decoded['mensagem']
                ?? $decoded['detail']
                ?? (is_array($decoded['error'] ?? null) ? ($decoded['error']['message'] ?? null) : null);
            $code = $decoded['code']
                ?? $decoded['codigo']
                ?? (is_string($decoded['error'] ?? null) ? $decoded['error'] : null)
                ?? (is_array($decoded['error'] ?? null) ? ($decoded['error']['code'] ?? null) : null);

            throw new BradescoProviderException(
                is_string($message) && $message !== '' ? $message : $fallbackMessage,
                $status,
                is_scalar($code) ? (string) $code : null,
            );
        }

        return $decoded;
    }

    /** @return array<string,mixed> */
    private function productConfig(ConnectionContext $context, string $product): array
    {
        $config = $context->credentials['products'][$product] ?? null;

        if (! is_array($config) || empty($config['base_url']) || empty($config['token_url']) || empty($config['client_id']) || empty($config['client_secret'])) {
            throw new BradescoProviderException("Credenciais do produto Bradesco [{$product}] não configuradas.");
        }

        return $config;
    }
}
