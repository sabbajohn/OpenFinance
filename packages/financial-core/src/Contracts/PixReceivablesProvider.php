<?php

namespace Sabba\OpenFinance\Core\Contracts;

use Sabba\OpenFinance\Core\DTO\ConnectionContext;
use Sabba\OpenFinance\Core\DTO\Money;
use Sabba\OpenFinance\Core\DTO\ReceivableCommand;
use Sabba\OpenFinance\Core\DTO\ReceivableResult;

interface PixReceivablesProvider
{
    public function createPix(ConnectionContext $context, ReceivableCommand $command): ReceivableResult;

    public function getPix(ConnectionContext $context, string $externalId, ?string $subtype = null): ReceivableResult;

    public function refundPix(ConnectionContext $context, string $externalId, string $refundId, Money $amount): ReceivableResult;
}
