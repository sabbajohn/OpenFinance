<?php

namespace Sabba\OpenFinance\Core\DTO;

use DateTimeImmutable;

final readonly class ReceivableCommand
{
    /** @param array<string,mixed> $payer */
    public function __construct(
        public string $idempotencyKey,
        public string $reference,
        public Money $amount,
        public ?DateTimeImmutable $dueAt,
        public array $payer = [],
        public array $options = [],
    ) {}
}
