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
        $client = new Client([
            'base_uri' => rtrim((string) $config['base_url'], '/').'/',
            'timeout' => $this->timeoutSeconds,
            'connect_timeout' => 5,
            'cert' => $config['certificate_path'] ?? null,
            'ssl_key' => isset($config['private_key_path'])
                ? [$config['private_key_path'], $config['private_key_passphrase'] ?? '']
                : null,
            'http_errors' => false,
            ...($this->handler ? ['handler' => $this->handler] : []),
        ]);

        $options['headers'] = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.$this->accessToken($context, $product, $config),
            ...($config['headers'] ?? []),
            ...($options['headers'] ?? []),
        ];

        try {
            $response = $client->request($method, ltrim($path, '/'), $options);
        } catch (GuzzleException $exception) {
            throw new SicrediProviderException('Falha de transporte na API Sicredi: '.$exception->getMessage(), previous: $exception);
        }

        $body = (string) $response->getBody();
        $decoded = $body === '' ? [] : json_decode($body, true);

        if ($response->getStatusCode() >= 400) {
            throw new SicrediProviderException(
                message: (string) ($decoded['message'] ?? $decoded['error']['message'] ?? 'A API Sicredi rejeitou a operação.'),
                responseStatus: $response->getStatusCode(),
                providerCode: $decoded['code'] ?? $decoded['error']['code'] ?? null,
            );
        }

        return is_array($decoded) ? $decoded : [];
    }

    /** @param array<string,mixed> $config */
    private function accessToken(ConnectionContext $context, string $product, array $config): string
    {
        $key = 'sicredi:token:'.hash('sha256', implode('|', [
            $context->connectionId,
            $product,
            (string) $config['client_id'],
            (string) $config['token_url'],
        ]));

        if ($token = $this->cache->get($key)) {
            return (string) $token;
        }

        $client = new Client([
            'timeout' => $this->timeoutSeconds,
            'connect_timeout' => 5,
            'cert' => $config['certificate_path'] ?? null,
            'ssl_key' => isset($config['private_key_path'])
                ? [$config['private_key_path'], $config['private_key_passphrase'] ?? '']
                : null,
            'http_errors' => false,
            ...($this->handler ? ['handler' => $this->handler] : []),
        ]);

        try {
            $response = $client->post((string) $config['token_url'], [
                'form_params' => [
                    'grant_type' => $config['grant_type'] ?? 'client_credentials',
                    'client_id' => $config['client_id'],
                    'client_secret' => $config['client_secret'],
                    'scope' => $config['scope'] ?? null,
                ],
                'headers' => ['Accept' => 'application/json'],
            ]);
        } catch (GuzzleException $exception) {
            throw new SicrediProviderException('Falha ao autenticar na API Sicredi: '.$exception->getMessage(), previous: $exception);
        }

        $decoded = json_decode((string) $response->getBody(), true);
        $token = is_array($decoded) ? ($decoded['access_token'] ?? null) : null;

        if ($response->getStatusCode() >= 400 || ! is_string($token)) {
            throw new SicrediProviderException('Não foi possível obter o token OAuth2 do Sicredi.', $response->getStatusCode());
        }

        $ttl = max(30, ((int) ($decoded['expires_in'] ?? 300)) - 30);
        $this->cache->set($key, $token, $ttl);

        return $token;
    }

    /** @return array<string,mixed> */
    private function productConfig(ConnectionContext $context, string $product): array
    {
        $config = $context->credentials['products'][$product] ?? null;

        if (! is_array($config) || empty($config['base_url']) || empty($config['token_url'])) {
            throw new SicrediProviderException("Credenciais do produto Sicredi [{$product}] não configuradas.");
        }

        return $config;
    }
}
