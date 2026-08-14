<?php

namespace Sabba\OpenFinance\Sdk;

use RuntimeException;

final class OpenFinanceException extends RuntimeException
{
    /** @param array<string,mixed> $details */
    public function __construct(
        string $message,
        public readonly int $status = 0,
        public readonly array $details = [],
    ) {
        parent::__construct($message, $status);
    }
}
