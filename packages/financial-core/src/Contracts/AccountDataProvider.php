<?php

namespace Sabba\OpenFinance\Core\Contracts;

use Sabba\OpenFinance\Core\DTO\BalanceSnapshot;
use Sabba\OpenFinance\Core\DTO\ConnectionContext;
use Sabba\OpenFinance\Core\DTO\TransactionPage;
use Sabba\OpenFinance\Core\DTO\TransactionQuery;

interface AccountDataProvider
{
    /** @return iterable<\Sabba\OpenFinance\Core\DTO\AccountSnapshot> */
    public function accounts(ConnectionContext $context): iterable;

    public function balance(ConnectionContext $context, string $accountExternalId): BalanceSnapshot;

    public function transactions(TransactionQuery $query): TransactionPage;
}
