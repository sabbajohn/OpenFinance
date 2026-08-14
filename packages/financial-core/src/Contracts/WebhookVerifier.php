<?php

namespace Sabba\OpenFinance\Core\Contracts;

use Psr\Http\Message\ServerRequestInterface;
use Sabba\OpenFinance\Core\DTO\ConnectionContext;

interface WebhookVerifier
{
    public function verify(ConnectionContext $context, ServerRequestInterface $request): bool;
}
