<?php

namespace App\Domain\Banking\Services;

use App\Domain\Banking\Models\BankConnection;
use Illuminate\Filesystem\Filesystem;
use RuntimeException;
use Sabba\OpenFinance\Core\DTO\ConnectionContext;

final readonly class ConnectionContextFactory
{
    public function __construct(private Filesystem $files) {}

    /**
     * Materializa certificados em arquivos 0600 somente durante a operação.
     *
     * @template T
     *
     * @param  callable(ConnectionContext): T  $callback
     * @return T
     */
    public function with(BankConnection $connection, callable $callback): mixed
    {
        $credentials = $connection->encrypted_credentials;
        if (! is_array($credentials)) {
            throw new RuntimeException('Credenciais bancárias não configuradas.');
        }
        $temporary = $this->emptyPathList();

        try {
            foreach (($credentials['products'] ?? []) as $product => $config) {
                if (! is_array($config)) {
                    continue;
                }

                foreach ([
                    'certificate_pem' => 'certificate_path',
                    'private_key_pem' => 'private_key_path',
                ] as $pemKey => $pathKey) {
                    if (empty($config[$pemKey])) {
                        continue;
                    }

                    $path = storage_path('app/private/mtls/'.bin2hex(random_bytes(16)).'.pem');
                    $this->files->ensureDirectoryExists(dirname($path), 0700);
                    if ($this->files->put($path, (string) $config[$pemKey], true) === false) {
                        throw new RuntimeException('Não foi possível materializar o certificado mTLS.');
                    }
                    chmod($path, 0600);
                    $credentials['products'][$product][$pathKey] = $path;
                    unset($credentials['products'][$product][$pemKey]);
                    $temporary[] = $path;
                }
            }

            return $callback(new ConnectionContext(
                connectionId: (string) $connection->getKey(),
                companyId: (string) $connection->company_id,
                environment: $connection->environment,
                credentials: $credentials,
            ));
        } finally {
            foreach ($temporary as $path) {
                $this->files->delete($path);
            }
        }
    }

    /** @return list<string> */
    private function emptyPathList(): array
    {
        return [];
    }
}
