<?php

namespace Sabba\OpenFinance\Core\Contracts;

use Sabba\OpenFinance\Core\DTO\CanonicalTransaction;
use Sabba\OpenFinance\Core\DTO\ConnectionContext;
use Sabba\OpenFinance\Core\DTO\PixReceiptQuery;
use Sabba\OpenFinance\Core\DTO\TransactionPage;

interface PixReceiptsProvider
{
    public function receivedPix(PixReceiptQuery $query): TransactionPage;

    public function receivedPixById(ConnectionContext $context, string $endToEndId): CanonicalTransaction;
}
