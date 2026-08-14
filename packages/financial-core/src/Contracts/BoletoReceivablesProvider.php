<?php

namespace Sabba\OpenFinance\Core\Contracts;

use Sabba\OpenFinance\Core\DTO\ConnectionContext;
use Sabba\OpenFinance\Core\DTO\ReceivableCommand;
use Sabba\OpenFinance\Core\DTO\ReceivableResult;

interface BoletoReceivablesProvider
{
    public function createBoleto(ConnectionContext $context, ReceivableCommand $command): ReceivableResult;

    public function getBoleto(ConnectionContext $context, string $externalId, ?string $subtype = null): ReceivableResult;

    /** @param array<string,mixed> $changes */
    public function updateBoleto(ConnectionContext $context, string $externalId, array $changes, ?string $subtype = null): ReceivableResult;

    public function cancelBoleto(ConnectionContext $context, string $externalId, ?string $subtype = null): ReceivableResult;
}
