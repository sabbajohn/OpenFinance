<?php

namespace Sabba\OpenFinance\Core\Enums;

enum TransactionStatus: string
{
    case Pending = 'pending';
    case Posted = 'posted';
    case Reversed = 'reversed';
    case Deleted = 'deleted';
    case Failed = 'failed';
}
