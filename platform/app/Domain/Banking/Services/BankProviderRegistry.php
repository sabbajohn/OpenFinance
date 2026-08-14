<?php

namespace App\Domain\Banking\Services;

use App\Domain\Banking\Models\BankConnection;
use InvalidArgumentException;
use Sabba\OpenFinance\Sicredi\SicrediProvider;

final readonly class BankProviderRegistry
{
    public function __construct(private SicrediProvider $sicredi) {}

    public function for(BankConnection $connection): object
    {
        return match ($connection->provider) {
            'sicredi' => $this->sicredi,
            default => throw new InvalidArgumentException("Banco [{$connection->provider}] não suportado."),
        };
    }
}
