<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import {
    Activity,
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
import { dashboard } from '@/routes';
import type { NavItem } from '@/types';

const mainNavItems: NavItem[] = [
    { title: 'Visão geral', href: '/dashboard', icon: LayoutDashboard },
    { title: 'Empresas', href: '/companies', icon: Building2 },
    { title: 'Membros', href: '/members', icon: Users },
    { title: 'Conexões bancárias', href: '/bank-connections', icon: Landmark },
    { title: 'Contas e saldos', href: '/bank-accounts', icon: WalletCards },
    { title: 'Transações', href: '/bank-transactions', icon: ListChecks },
    { title: 'Conciliação', href: '/reconciliations', icon: Network },
    { title: 'Pix', href: '/pix', icon: QrCode },
    { title: 'Boletos', href: '/boletos', icon: ReceiptText },
    { title: 'Integrações ERP', href: '/erp-integrations', icon: Activity },
    { title: 'Webhooks', href: '/webhook-deliveries', icon: Webhook },
    { title: 'Auditoria', href: '/audit', icon: ScrollText },
    { title: 'Operações', href: '/operations', icon: Activity },
];
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
        <SidebarContent><NavMain :items="mainNavItems" /></SidebarContent>
        <SidebarFooter><NavUser /></SidebarFooter>
    </Sidebar>
    <slot />
</template>
