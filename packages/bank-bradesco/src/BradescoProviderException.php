<?php

namespace Sabba\OpenFinance\Bradesco;

use RuntimeException;
use Throwable;

class BradescoProviderException extends RuntimeException
{
    public function __construct(
        string $message,
        public readonly ?int $responseStatus = null,
        public readonly ?string $providerCode = null,
        ?Throwable $previous = null,
    ) {
        parent::__construct($message, 0, $previous);
    }
}
