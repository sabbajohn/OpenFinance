<?php

namespace Sabba\OpenFinance\Core\Enums;

enum Capability: string
{
    case Accounts = 'account.list';
    case Balances = 'account.balance';
    case Transactions = 'account.transactions';
    case PixImmediate = 'pix.immediate';
    case PixDue = 'pix.due';
    case PixRefund = 'pix.refund';
    case BoletoNormal = 'boleto.normal';
    case BoletoHybrid = 'boleto.hybrid';
    case Webhooks = 'webhooks';
}
