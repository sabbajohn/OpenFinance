<?php

namespace App\Domain\Identity\Enums;

enum OrganizationPermission: string
{
    case ViewDashboard = 'dashboard.view';
    case ViewCompanies = 'companies.view';
    case ManageCompanies = 'companies.manage';
    case ManageMembers = 'members.manage';
    case ViewBankConnections = 'bank-connections.view';
    case ManageBankConnections = 'bank-connections.manage';
    case RunBankTests = 'bank-tests.run';
    case RunBankSync = 'bank-sync.run';
    case ViewFinancial = 'financial.view';
    case OperateFinancial = 'financial.operate';
    case ApproveReconciliation = 'reconciliation.approve';
    case ViewErpIntegrations = 'erp-integrations.view';
    case ManageErpIntegrations = 'erp-integrations.manage';
    case ViewWebhooks = 'webhooks.view';
    case ViewAudit = 'audit.view';
    case ViewOperations = 'operations.view';
}
