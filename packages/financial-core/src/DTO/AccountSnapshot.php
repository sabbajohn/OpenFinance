<?php

namespace Sabba\OpenFinance\Core\DTO;

final readonly class AccountSnapshot
{
    /** @param array<string,mixed> $metadata */
    public function __construct(
        public string $externalId,
        public string $type,
        public string $currency,
        public ?string $bankCode,
        public ?string $branch,
        public ?string $numberMasked,
        public array $metadata = [],
    ) {}
}
