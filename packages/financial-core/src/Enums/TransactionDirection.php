<?php

namespace Sabba\OpenFinance\Core\Enums;

enum TransactionDirection: string
{
    case Credit = 'credit';
    case Debit = 'debit';
}
