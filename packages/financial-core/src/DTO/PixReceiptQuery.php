<?php

namespace Sabba\OpenFinance\Core\DTO;

use DateTimeImmutable;

final readonly class PixReceiptQuery
{
    public function __construct(
        public ConnectionContext $context,
        public DateTimeImmutable $from,
        public DateTimeImmutable $to,
        public ?string $cursor = null,
        public int $limit = 100,
        public ?string $txid = null,
        public ?string $payerTaxId = null,
        public ?bool $hasTxid = null,
        public ?bool $hasRefund = null,
    ) {}
}
