<?php

namespace Sabba\OpenFinance\Core\DTO;

use DateTimeImmutable;

final readonly class TransactionQuery
{
    public function __construct(
        public ConnectionContext $context,
        public string $accountExternalId,
        public DateTimeImmutable $from,
        public DateTimeImmutable $to,
        public ?string $cursor = null,
        public int $limit = 500,
    ) {}
}
