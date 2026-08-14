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

    public function label(): string
    {
        return match ($this) {
            self::Owner => 'Proprietário',
            self::Admin => 'Administrador',
            self::Operator => 'Operador financeiro',
            self::Developer => 'Desenvolvedor de integrações',
            self::Auditor => 'Auditor',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::Owner => 'Controle total, incluindo proprietários, credenciais e integrações.',
            self::Admin => 'Administra equipe, empresas, bancos e ERP, sem transferir a propriedade.',
            self::Operator => 'Opera cobranças, conciliações e sincronizações bancárias.',
            self::Developer => 'Acompanha integrações, webhooks, operações técnicas e testes em sandbox.',
            self::Auditor => 'Consulta dados financeiros, configurações e trilhas de auditoria sem alterar registros.',
        };
    }

    /** @return list<OrganizationPermission> */
    public function permissions(): array
    {
        $readOnly = [
            OrganizationPermission::ViewDashboard,
            OrganizationPermission::ViewCompanies,
            OrganizationPermission::ViewBankConnections,
            OrganizationPermission::ViewFinancial,
            OrganizationPermission::ViewErpIntegrations,
            OrganizationPermission::ViewWebhooks,
            OrganizationPermission::ViewAudit,
            OrganizationPermission::ViewOperations,
        ];

        return match ($this) {
            self::Owner, self::Admin => OrganizationPermission::cases(),
            self::Operator => [
                ...$readOnly,
                OrganizationPermission::RunBankTests,
                OrganizationPermission::RunBankSync,
                OrganizationPermission::OperateFinancial,
                OrganizationPermission::ApproveReconciliation,
            ],
            self::Developer => [
                OrganizationPermission::ViewDashboard,
                OrganizationPermission::ViewCompanies,
                OrganizationPermission::ViewBankConnections,
                OrganizationPermission::RunBankTests,
                OrganizationPermission::ViewErpIntegrations,
                OrganizationPermission::ViewWebhooks,
                OrganizationPermission::ViewAudit,
                OrganizationPermission::ViewOperations,
            ],
            self::Auditor => $readOnly,
        };
    }

    public function allows(OrganizationPermission $permission): bool
    {
        return in_array($permission, $this->permissions(), true);
    }

    /** @return list<string> */
    public function permissionValues(): array
    {
        return array_map(
            static fn (OrganizationPermission $permission): string => $permission->value,
            $this->permissions(),
        );
    }

    public function canApproveReconciliation(): bool
    {
        return $this->allows(OrganizationPermission::ApproveReconciliation);
    }
}
