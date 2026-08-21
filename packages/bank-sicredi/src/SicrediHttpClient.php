<?php

namespace Sabba\OpenFinance\Sicredi;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\HandlerStack;
use Psr\SimpleCache\CacheInterface;
use Sabba\OpenFinance\Core\DTO\ConnectionContext;

final readonly class SicrediHttpClient
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
            ...(! empty($config['api_key']) ? ['x-api-key' => $config['api_key']] : []),
            ...($config['headers'] ?? []),
            ...($options['headers'] ?? []),
        ];

        try {
            $response = $client->request($method, ltrim($path, '/'), $options);
        } catch (GuzzleException $exception) {
            throw new SicrediProviderException('Falha de transporte na API Sicredi: '.$exception->getMessage(), previous: $exception);
        }

        return $this->decodeResponse($response->getStatusCode(), (string) $response->getBody(), 'A API Sicredi rejeitou a operação.');
    }

    /**
     * Valida OAuth2 e, quando aplicável, mTLS sem executar uma operação financeira.
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
        $key = 'sicredi:token:'.hash('sha256', implode('|', [
            $context->connectionId,
            $product,
            (string) ($config['client_id'] ?? $config['username'] ?? ''),
            (string) $config['token_url'],
        ]));
        $accessTokenKey = $key.':access';
        $refreshTokenKey = $key.':refresh';

        if ($token = $this->cache->get($accessTokenKey)) {
            return (string) $token;
        }

        $refreshToken = $this->cache->get($refreshTokenKey);
        if (is_string($refreshToken) && $refreshToken !== '') {
            try {
                $decoded = $this->fetchAccessToken($config, $refreshToken);
            } catch (SicrediProviderException $exception) {
                if (! in_array($exception->responseStatus, [400, 401], true)) {
                    throw $exception;
                }

                $this->cache->delete($refreshTokenKey);
                $decoded = $this->fetchAccessToken($config);
                $refreshToken = null;
            }
        } else {
            $decoded = $this->fetchAccessToken($config);
            $refreshToken = null;
        }

        $token = (string) $decoded['access_token'];
        $accessTokenTtl = max(1, ((int) ($decoded['expires_in'] ?? 300)) - 30);
        $this->cache->set($accessTokenKey, $token, $accessTokenTtl);

        $nextRefreshToken = $decoded['refresh_token'] ?? $refreshToken;
        if (is_string($nextRefreshToken) && $nextRefreshToken !== '') {
            $refreshTokenTtl = max(1, ((int) ($decoded['refresh_expires_in'] ?? 1800)) - 30);
            $this->cache->set($refreshTokenKey, $nextRefreshToken, $refreshTokenTtl);
        }

        return $token;
    }

    /**
     * @param  array<string,mixed>  $config
     * @return array<string,mixed>
     */
    private function fetchAccessToken(array $config, ?string $refreshToken = null): array
    {
        $client = $this->client($config);
        $grantType = $refreshToken !== null
            ? 'refresh_token'
            : (string) ($config['grant_type'] ?? 'client_credentials');
        $request = [
            'form_params' => array_filter([
                'grant_type' => $grantType,
                'username' => $grantType === 'password' ? ($config['username'] ?? null) : null,
                'password' => $grantType === 'password' ? ($config['password'] ?? null) : null,
                'refresh_token' => $grantType === 'refresh_token' ? $refreshToken : null,
                'scope' => $grantType !== 'refresh_token' ? ($config['scope'] ?? null) : null,
            ], fn (mixed $value): bool => $value !== null && $value !== ''),
            'headers' => [
                'Accept' => 'application/json',
                ...(! empty($config['api_key']) ? ['x-api-key' => $config['api_key']] : []),
                ...($config['token_headers'] ?? []),
            ],
        ];
        if ($grantType === 'client_credentials') {
            $request['auth'] = [(string) $config['client_id'], (string) $config['client_secret']];
        }

        try {
            $response = $client->post((string) $config['token_url'], $request);
        } catch (GuzzleException $exception) {
            throw new SicrediProviderException('Falha ao autenticar na API Sicredi: '.$exception->getMessage(), previous: $exception);
        }

        $decoded = $this->decodeResponse(
            $response->getStatusCode(),
            (string) $response->getBody(),
            'Não foi possível obter o token OAuth2 do Sicredi.',
        );
        if (! is_string($decoded['access_token'] ?? null) || $decoded['access_token'] === '') {
            throw new SicrediProviderException(
                'Não foi possível obter o token OAuth2 do Sicredi.',
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
            ...(! empty($config['certificate_path']) ? ['cert' => $config['certificate_path']] : []),
            ...(! empty($config['private_key_path']) ? [
                'ssl_key' => [$config['private_key_path'], $config['private_key_passphrase'] ?? ''],
            ] : []),
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
            $violation = is_array($decoded['violacoes'][0] ?? null)
                ? ($decoded['violacoes'][0]['razao'] ?? $decoded['violacoes'][0]['reason'] ?? null)
                : null;
            $message = $decoded['error_description']
                ?? $decoded['detail']
                ?? $decoded['message']
                ?? $violation
                ?? $decoded['title']
                ?? (is_array($decoded['error'] ?? null) ? ($decoded['error']['message'] ?? null) : null);
            $code = $decoded['code']
                ?? $decoded['type']
                ?? (is_string($decoded['error'] ?? null) ? $decoded['error'] : null)
                ?? (is_array($decoded['error'] ?? null) ? ($decoded['error']['code'] ?? null) : null);

            throw new SicrediProviderException(
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

        if (! is_array($config) || empty($config['base_url']) || empty($config['token_url'])) {
            throw new SicrediProviderException("Credenciais do produto Sicredi [{$product}] não configuradas.");
        }

        $grantType = (string) ($config['grant_type'] ?? 'client_credentials');
        $credentialsMissing = $grantType === 'password'
            ? empty($config['username']) || empty($config['password']) || empty($config['api_key'])
            : empty($config['client_id']) || empty($config['client_secret']);
        if ($credentialsMissing) {
            throw new SicrediProviderException("Credenciais do produto Sicredi [{$product}] não configuradas.");
        }

        return $config;
    }
}
