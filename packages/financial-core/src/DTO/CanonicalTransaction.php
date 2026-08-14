<?php

namespace Sabba\OpenFinance\Core\DTO;

use DateTimeImmutable;
use Sabba\OpenFinance\Core\Enums\TransactionDirection;
use Sabba\OpenFinance\Core\Enums\TransactionStatus;

final readonly class CanonicalTransaction
{
    /**
     * @param array<string,string|null> $identifiers
     * @param array<string,mixed> $metadata
     */
    public function __construct(
        public ?string $externalId,
        public string $type,
        public TransactionDirection $direction,
        public TransactionStatus $status,
        public Money $amount,
        public DateTimeImmutable $occurredAt,
        public DateTimeImmutable $observedAt,
        public ?string $description,
        public ?string $counterpartyName,
        public ?string $counterpartyTaxId,
        public array $identifiers = [],
        public array $metadata = [],
    ) {}
}
