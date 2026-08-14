<?php

namespace Sabba\OpenFinance\Core\DTO;

final readonly class TransactionPage
{
    /** @param list<CanonicalTransaction> $transactions */
    public function __construct(
        public array $transactions,
        public ?string $nextCursor = null,
    ) {}
}
