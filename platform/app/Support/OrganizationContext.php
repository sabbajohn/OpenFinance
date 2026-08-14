<?php

namespace App\Support;

use App\Domain\Identity\Models\Organization;
use LogicException;

class OrganizationContext
{
    private ?Organization $organization = null;

    public function set(Organization $organization): void
    {
        $this->organization = $organization;
    }

    public function clear(): void
    {
        $this->organization = null;
    }

    public function has(): bool
    {
        return $this->organization !== null;
    }

    public function get(): Organization
    {
        return $this->organization ?? throw new LogicException('Organization context is not set.');
    }

    public function id(): string
    {
        return (string) $this->get()->getKey();
    }
}
