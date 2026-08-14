<?php

namespace Sabba\OpenFinance\Core\DTO;

final readonly class ConnectionContext
{
    /** @param array<string,mixed> $credentials */
    public function __construct(
        public string $connectionId,
        public string $companyId,
        public string $environment,
        public array $credentials,
    ) {}
}
