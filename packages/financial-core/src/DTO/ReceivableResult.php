<?php

namespace Sabba\OpenFinance\Core\DTO;

use DateTimeImmutable;

final readonly class ReceivableResult
{
    /** @param array<string,mixed> $metadata */
    public function __construct(
        public string $externalId,
        public string $status,
        public Money $amount,
        public ?string $copyAndPaste = null,
        public ?string $barcode = null,
        public ?string $digitableLine = null,
        public ?DateTimeImmutable $paidAt = null,
        public array $metadata = [],
    ) {}
}
