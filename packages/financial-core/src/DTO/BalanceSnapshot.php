<?php

namespace Sabba\OpenFinance\Core\DTO;

use DateTimeImmutable;

final readonly class BalanceSnapshot
{
    public function __construct(
        public string $accountExternalId,
        public Money $available,
        public Money $current,
        public DateTimeImmutable $observedAt,
    ) {}
}
