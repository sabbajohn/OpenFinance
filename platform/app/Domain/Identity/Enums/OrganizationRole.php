<?php

namespace App\Domain\Identity\Enums;

enum OrganizationRole: string
{
    case Owner = 'owner';
    case Admin = 'admin';
    case Operator = 'operator';
    case Developer = 'developer';
    case Auditor = 'auditor';

    public function requiresTwoFactor(): bool
    {
        return in_array($this, [self::Owner, self::Admin, self::Operator], true);
    }

    public function canApproveReconciliation(): bool
    {
        return in_array($this, [self::Owner, self::Admin, self::Operator], true);
    }
}
