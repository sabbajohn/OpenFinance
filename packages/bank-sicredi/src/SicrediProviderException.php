<?php

namespace Sabba\OpenFinance\Sicredi;

use RuntimeException;
use Throwable;

class SicrediProviderException extends RuntimeException
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
