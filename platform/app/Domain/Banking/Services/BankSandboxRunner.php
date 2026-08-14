<?php

namespace App\Domain\Banking\Services;

use App\Domain\Banking\Models\BankConnection;
use App\Domain\Banking\Models\BankSandboxRun;
use App\Models\User;
use DateTimeImmutable;
use DateTimeZone;
use RuntimeException;
use Sabba\OpenFinance\Bradesco\BradescoHttpClient;
use Sabba\OpenFinance\Bradesco\BradescoProviderException;
use Sabba\OpenFinance\Core\Contracts\PixReceiptsProvider;
use Sabba\OpenFinance\Core\DTO\ConnectionContext;
use Sabba\OpenFinance\Core\DTO\PixReceiptQuery;
use Sabba\OpenFinance\Sicredi\SicrediHttpClient;
use Sabba\OpenFinance\Sicredi\SicrediProviderException;
use Throwable;

final readonly class BankSandboxRunner
{
    public function __construct(
        private ConnectionContextFactory $contexts,
        private BankProviderRegistry $providers,
        private SicrediHttpClient $sicredi,
        private BradescoHttpClient $bradesco,
    ) {}

    public function run(BankConnection $connection, User $user, string $suite): BankSandboxRun
    {
        $run = BankSandboxRun::query()->create([
            'organization_id' => $connection->organization_id,
            'company_id' => $connection->company_id,
            'bank_connection_id' => $connection->getKey(),
            'user_id' => $user->getKey(),
            'suite' => $suite,
            'environment' => $connection->environment,
            'status' => 'running',
            'steps' => [],
            'summary' => [],
            'started_at' => now('UTC'),
        ]);
        $steps = [];

        try {
            abort_unless($connection->environment === 'sandbox', 422, 'Os testes do laboratório só podem usar conexões Sandbox.');

            $summary = $this->contexts->with($connection, function ($context) use ($connection, $suite, &$steps): array {
                $settings = is_array($connection->sync_settings) ? $connection->sync_settings : [];
                $product = (string) ($settings['product'] ?? 'pix');
                $authentication = $this->step(
                    $product === 'pix' ? 'mtls_oauth2' : 'oauth2',
                    $product === 'pix' ? 'Autenticação mTLS + OAuth2' : 'Autenticação OAuth2 + x-api-key',
                    function () use ($connection, $context, $product): array {
                        $client = match ($connection->provider) {
                            'sicredi' => $this->sicredi,
                            'bradesco' => $this->bradesco,
                            default => throw new RuntimeException('Banco não suportado pelo laboratório.'),
                        };

                        return $client->testAuthentication($context, $product);
                    },
                );
                $steps[] = $authentication['step'];

                $summary = ['authentication' => $authentication['result']];
                if ($suite !== 'pix_receipts') {
                    return $summary;
                }

                $receipts = $this->step(
                    'pix_receipts',
                    $connection->provider === 'bradesco'
                        ? 'Consulta Pix recebidos (cenário oficial Sandbox)'
                        : 'Consulta Pix recebidos (últimas 24 horas)',
                    function () use ($connection, $context): array {
                        $provider = $this->providers->for($connection);
                        if (! $provider instanceof PixReceiptsProvider) {
                            throw new RuntimeException('O banco não oferece consulta de Pix recebidos.');
                        }
                        $page = $provider->receivedPix($this->pixReceiptQuery($connection, $context));

                        return [
                            'items_found' => count($page->transactions),
                            'has_next_page' => $page->nextCursor !== null,
                        ];
                    },
                );
                $steps[] = $receipts['step'];
                $summary['pix_receipts'] = $receipts['result'];

                return $summary;
            });

            $run->forceFill([
                'status' => 'passed',
                'steps' => $steps,
                'summary' => $summary,
                'finished_at' => now('UTC'),
            ])->save();
        } catch (Throwable $exception) {
            $error = $this->safeError($exception);
            if ($exception instanceof SandboxStepFailedException) {
                $steps[] = $exception->step;
            } elseif ($steps === [] || end($steps)['status'] !== 'failed') {
                $steps[] = [
                    'key' => 'suite',
                    'name' => 'Execução da suíte',
                    'status' => 'failed',
                    'duration_ms' => null,
                    'details' => $error,
                ];
            }
            $run->forceFill([
                'status' => 'failed',
                'steps' => $steps,
                'summary' => [],
                'error' => $error['message'],
                'finished_at' => now('UTC'),
            ])->save();
        }

        return $run->refresh();
    }

    private function pixReceiptQuery(BankConnection $connection, ConnectionContext $context): PixReceiptQuery
    {
        $timezone = new DateTimeZone('UTC');

        if ($connection->provider === 'bradesco') {
            return new PixReceiptQuery(
                context: $context,
                from: new DateTimeImmutable(
                    (string) config('openfinance.bradesco.pix.sandbox_receipts.from'),
                    $timezone,
                ),
                to: new DateTimeImmutable(
                    (string) config('openfinance.bradesco.pix.sandbox_receipts.to'),
                    $timezone,
                ),
            );
        }

        $to = new DateTimeImmutable('now', $timezone);

        return new PixReceiptQuery(
            context: $context,
            from: $to->modify('-24 hours'),
            to: $to,
            limit: 1,
        );
    }

    /**
     * @template T of array<string,mixed>
     *
     * @param  callable(): T  $callback
     * @return array{step:array<string,mixed>,result:T}
     */
    private function step(string $key, string $name, callable $callback): array
    {
        $started = hrtime(true);

        try {
            $result = $callback();

            return [
                'step' => [
                    'key' => $key,
                    'name' => $name,
                    'status' => 'passed',
                    'duration_ms' => $this->elapsedMilliseconds($started),
                    'details' => $result,
                ],
                'result' => $result,
            ];
        } catch (Throwable $exception) {
            throw new SandboxStepFailedException([
                'key' => $key,
                'name' => $name,
                'status' => 'failed',
                'duration_ms' => $this->elapsedMilliseconds($started),
                'details' => $this->safeError($exception),
            ], $exception);
        }
    }

    private function elapsedMilliseconds(int $started): int
    {
        return max(0, (int) round((hrtime(true) - $started) / 1_000_000));
    }

    /** @return array{message:string,http_status:?int,provider_code:?string} */
    private function safeError(Throwable $exception): array
    {
        if ($exception instanceof SandboxStepFailedException) {
            return $this->safeError($exception->getPrevious() ?? $exception);
        }
        if ($exception instanceof SicrediProviderException || $exception instanceof BradescoProviderException) {
            return [
                'message' => $exception instanceof BradescoProviderException
                    ? 'A API Bradesco não concluiu esta etapa do Sandbox.'
                    : 'A API Sicredi não concluiu esta etapa do Sandbox.',
                'http_status' => $exception->responseStatus,
                'provider_code' => $exception->providerCode,
            ];
        }

        return [
            'message' => $exception instanceof RuntimeException
                ? mb_substr($exception->getMessage(), 0, 1000)
                : 'Não foi possível concluir a suíte de testes.',
            'http_status' => null,
            'provider_code' => null,
        ];
    }
}

final class SandboxStepFailedException extends RuntimeException
{
    /** @param array<string,mixed> $step */
    public function __construct(public readonly array $step, Throwable $previous)
    {
        parent::__construct((string) data_get($step, 'details.message', 'Etapa do Sandbox falhou.'), previous: $previous);
    }
}
