<?php

namespace App\Domain\Banking\Services;

use App\Domain\Banking\Models\BankConnection;
use InvalidArgumentException;
use Sabba\OpenFinance\Bradesco\BradescoProvider;
use Sabba\OpenFinance\Sicredi\SicrediProvider;

final readonly class BankProviderRegistry
{
    public function __construct(
        private SicrediProvider $sicredi,
        private BradescoProvider $bradesco,
    ) {}

    public function for(BankConnection $connection): object
    {
        return match ($connection->provider) {
            'sicredi' => $this->sicredi,
            'bradesco' => $this->bradesco,
            default => throw new InvalidArgumentException("Banco [{$connection->provider}] não suportado."),
        };
    }
}
