<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import {
    Activity,
    FlaskConical,
    Building2,
    Landmark,
    LayoutDashboard,
    ListChecks,
    Network,
    QrCode,
    ReceiptText,
    ScrollText,
    Users,
    WalletCards,
    Webhook,
} from '@lucide/vue';
import AppLogo from '@/components/AppLogo.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { useOrganizationAccess } from '@/composables/useOrganizationAccess';
import { dashboard } from '@/routes';
import type { NavItem } from '@/types';

const { can } = useOrganizationAccess();
const visible = (items: NavItem[]) =>
    items.filter((item) => !item.permission || can(item.permission));

const mainNavItems = visible([
    {
        title: 'Visão geral',
        href: '/dashboard',
        icon: LayoutDashboard,
        permission: 'dashboard.view',
    },
]);

const financialNavItems = visible([
    {
        title: 'Contas e saldos',
        href: '/bank-accounts',
        icon: WalletCards,
        permission: 'financial.view',
    },
    {
        title: 'Transações',
        href: '/bank-transactions',
        icon: ListChecks,
        permission: 'financial.view',
    },
    {
        title: 'Conciliação',
        href: '/reconciliations',
        icon: Network,
        permission: 'financial.view',
    },
    {
        title: 'Pix',
        href: '/pix',
        icon: QrCode,
        permission: 'financial.view',
    },
    {
        title: 'Boletos',
        href: '/boletos',
        icon: ReceiptText,
        permission: 'financial.view',
    },
]);

const configurationNavItems = visible([
    {
        title: 'Empresas',
        href: '/companies',
        icon: Building2,
        permission: 'companies.view',
    },
    {
        title: 'Membros e acessos',
        href: '/members',
        icon: Users,
        permission: 'members.manage',
    },
    {
        title: 'Conexões bancárias',
        href: '/bank-connections',
        icon: Landmark,
        permission: 'bank-connections.view',
    },
    {
        title: 'Laboratório Sandbox',
        href: '/sandbox',
        icon: FlaskConical,
        permission: 'bank-connections.view',
    },
    {
        title: 'Integrações ERP',
        href: '/erp-integrations',
        icon: Activity,
        permission: 'erp-integrations.view',
    },
    {
        title: 'Webhooks',
        href: '/webhook-deliveries',
        icon: Webhook,
        permission: 'webhooks.view',
    },
    {
        title: 'Auditoria',
        href: '/audit',
        icon: ScrollText,
        permission: 'audit.view',
    },
    {
        title: 'Operações',
        href: '/operations',
        icon: Activity,
        permission: 'operations.view',
    },
]);
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader
            ><SidebarMenu
                ><SidebarMenuItem
                    ><SidebarMenuButton size="lg" as-child
                        ><Link :href="dashboard()"
                            ><AppLogo /></Link></SidebarMenuButton></SidebarMenuItem></SidebarMenu
        ></SidebarHeader>
        <SidebarContent>
            <NavMain label="Início" :items="mainNavItems" />
            <NavMain label="Financeiro" :items="financialNavItems" />
            <NavMain label="Configuração" :items="configurationNavItems" />
        </SidebarContent>
        <SidebarFooter><NavUser /></SidebarFooter>
    </Sidebar>
    <slot />
</template>
