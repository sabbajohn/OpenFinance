<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import {
    Activity,
    AlertTriangle,
    ArrowDownLeft,
    ArrowRight,
    ArrowUpRight,
    Building2,
    CheckCircle2,
    Circle,
    CircleDollarSign,
    GitCompareArrows,
    Landmark,
    Link2,
    ShieldCheck,
} from '@lucide/vue';
import { computed } from 'vue';

type Transaction = {
    id: string;
    description: string | null;
    direction: 'credit' | 'debit';
    amount_minor: number;
    occurred_at: string;
    status: string;
};

type ConnectionHealth = {
    id: string;
    name: string;
    provider: string;
    company_name: string;
    environment: 'sandbox' | 'production';
    status: string;
    last_synced_at?: string | null;
    last_error?: string | null;
};

type OnboardingKey =
    | 'company'
    | 'bank_connection'
    | 'bank_authenticated'
    | 'erp_connection'
    | 'two_factor';

const props = defineProps<{
    metrics: {
        balance_minor: number;
        transactions_today: number;
        open_reconciliations: number;
        connections_attention: number;
        active_connections: number;
    };
    recentTransactions: Transaction[];
    queueHealth: Record<string, number>;
    onboarding: Record<OnboardingKey, boolean>;
    connectionHealth: ConnectionHealth[];
}>();

const money = (minor: number) =>
    new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL',
    }).format((minor ?? 0) / 100);

const onboardingSteps: Array<{
    key: OnboardingKey;
    title: string;
    description: string;
    href: string;
    icon: typeof Building2;
}> = [
    {
        key: 'company',
        title: 'Cadastre a empresa',
        description: 'Defina o CNPJ que será isolado na operação.',
        href: '/companies',
        icon: Building2,
    },
    {
        key: 'two_factor',
        title: 'Proteja os acessos',
        description: 'Ative 2FA ou uma passkey para ações sensíveis.',
        href: '/settings/security',
        icon: ShieldCheck,
    },
    {
        key: 'bank_connection',
        title: 'Configure um banco',
        description: 'Informe credenciais e certificado mTLS.',
        href: '/bank-connections',
        icon: Landmark,
    },
    {
        key: 'bank_authenticated',
        title: 'Valide a autenticação',
        description: 'Teste o handshake e a emissão do token.',
        href: '/bank-connections',
        icon: CheckCircle2,
    },
    {
        key: 'erp_connection',
        title: 'Conecte o ERP',
        description: 'Habilite o fluxo de conciliação e retorno.',
        href: '/erp-integrations',
        icon: Link2,
    },
];

const completedSteps = computed(
    () => onboardingSteps.filter((step) => props.onboarding[step.key]).length,
);
const onboardingPercent = computed(() =>
    Math.round((completedSteps.value / onboardingSteps.length) * 100),
);

const statusLabel = (status: string) =>
    ({
        active: 'Conectada',
        draft: 'Aguardando teste',
        action_required: 'Requer atenção',
        degraded: 'Instável',
    })[status] ?? status;

const statusClass = (status: string) => {
    if (status === 'active') {
        return 'bg-emerald-500';
    }

    if (['action_required', 'degraded'].includes(status)) {
        return 'bg-rose-500';
    }

    return 'bg-amber-500';
};

const providerLabel = (provider: string) =>
    ({ bradesco: 'Bradesco', sicredi: 'Sicredi' })[provider] ?? provider;

const formatDate = (value?: string | null) =>
    value ? new Date(value).toLocaleString('pt-BR') : 'Ainda não sincronizada';
</script>

<template>
    <Head title="Visão geral" />

    <div class="flex flex-1 flex-col gap-6 p-4 md:p-7">
        <header
            class="flex flex-col justify-between gap-4 md:flex-row md:items-end"
        >
            <div>
                <p
                    class="text-sm font-medium text-emerald-700 dark:text-emerald-400"
                >
                    Operação financeira
                </p>
                <h1 class="mt-1 text-2xl font-semibold tracking-tight">
                    Visão geral
                </h1>
                <p class="mt-1 text-sm text-muted-foreground">
                    Saldos, movimentos e integrações em um só lugar.
                </p>
            </div>
            <Link
                href="/bank-connections"
                class="inline-flex h-10 items-center justify-center gap-2 rounded-lg bg-emerald-700 px-4 text-sm font-medium text-white shadow-sm hover:bg-emerald-800"
            >
                <Landmark class="size-4" />
                Configurar banco
            </Link>
        </header>

        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <article class="rounded-xl border bg-card p-5 shadow-sm">
                <CircleDollarSign class="size-5 text-emerald-600" />
                <p class="mt-5 text-sm text-muted-foreground">
                    Saldo consolidado
                </p>
                <p class="mt-1 text-2xl font-semibold tabular-nums">
                    {{ money(props.metrics.balance_minor) }}
                </p>
            </article>
            <article class="rounded-xl border bg-card p-5 shadow-sm">
                <Activity class="size-5 text-sky-600" />
                <p class="mt-5 text-sm text-muted-foreground">
                    Movimentos hoje
                </p>
                <p class="mt-1 text-2xl font-semibold tabular-nums">
                    {{ props.metrics.transactions_today }}
                </p>
            </article>
            <article class="rounded-xl border bg-card p-5 shadow-sm">
                <GitCompareArrows class="size-5 text-amber-600" />
                <p class="mt-5 text-sm text-muted-foreground">
                    Conciliações abertas
                </p>
                <p class="mt-1 text-2xl font-semibold tabular-nums">
                    {{ props.metrics.open_reconciliations }}
                </p>
            </article>
            <article class="rounded-xl border bg-card p-5 shadow-sm">
                <AlertTriangle
                    v-if="props.metrics.connections_attention"
                    class="size-5 text-rose-600"
                />
                <CheckCircle2 v-else class="size-5 text-emerald-600" />
                <p class="mt-5 text-sm text-muted-foreground">
                    Conexões ativas
                </p>
                <div class="mt-1 flex items-end justify-between gap-3">
                    <p class="text-2xl font-semibold tabular-nums">
                        {{ props.metrics.active_connections }}
                    </p>
                    <p
                        v-if="props.metrics.connections_attention"
                        class="text-xs font-medium text-rose-600"
                    >
                        {{ props.metrics.connections_attention }} com atenção
                    </p>
                </div>
            </article>
        </section>

        <section class="grid gap-4 xl:grid-cols-[minmax(0,1fr)_360px]">
            <div class="overflow-hidden rounded-xl border bg-card shadow-sm">
                <div
                    class="flex items-center justify-between gap-3 border-b px-5 py-4"
                >
                    <div>
                        <h2 class="font-semibold">Movimentos recentes</h2>
                        <p class="text-sm text-muted-foreground">
                            Últimos lançamentos normalizados
                        </p>
                    </div>
                    <Link
                        href="/bank-transactions"
                        class="inline-flex items-center gap-1 text-sm font-medium text-emerald-700 hover:underline dark:text-emerald-400"
                    >
                        Ver todos <ArrowRight class="size-3.5" />
                    </Link>
                </div>
                <div v-if="props.recentTransactions.length" class="divide-y">
                    <div
                        v-for="transaction in props.recentTransactions"
                        :key="transaction.id"
                        class="flex items-center gap-3 px-5 py-3.5"
                    >
                        <div
                            class="flex size-9 items-center justify-center rounded-full"
                            :class="
                                transaction.direction === 'credit'
                                    ? 'bg-emerald-500/10 text-emerald-600'
                                    : 'bg-rose-500/10 text-rose-600'
                            "
                        >
                            <ArrowDownLeft
                                v-if="transaction.direction === 'credit'"
                                class="size-4"
                            />
                            <ArrowUpRight v-else class="size-4" />
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-medium">
                                {{
                                    transaction.description ||
                                    'Movimento bancário'
                                }}
                            </p>
                            <p class="text-xs text-muted-foreground">
                                {{
                                    new Date(
                                        transaction.occurred_at,
                                    ).toLocaleString('pt-BR')
                                }}
                            </p>
                        </div>
                        <p
                            class="text-sm font-semibold tabular-nums"
                            :class="
                                transaction.direction === 'credit'
                                    ? 'text-emerald-700 dark:text-emerald-400'
                                    : ''
                            "
                        >
                            {{ transaction.direction === 'credit' ? '+' : '-'
                            }}{{ money(transaction.amount_minor) }}
                        </p>
                    </div>
                </div>
                <div
                    v-else
                    class="px-5 py-14 text-center text-sm text-muted-foreground"
                >
                    Os movimentos aparecerão após a primeira sincronização
                    bancária.
                </div>
            </div>

            <aside
                class="rounded-xl border bg-slate-950 p-5 text-slate-100 shadow-sm dark:border-slate-800"
            >
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h2 class="font-semibold">Preparação da operação</h2>
                        <p class="mt-1 text-sm text-slate-400">
                            {{ completedSteps }} de
                            {{ onboardingSteps.length }} etapas concluídas
                        </p>
                    </div>
                    <span class="text-sm font-semibold text-emerald-400"
                        >{{ onboardingPercent }}%</span
                    >
                </div>
                <div
                    class="mt-4 h-1.5 overflow-hidden rounded-full bg-white/10"
                >
                    <div
                        class="h-full rounded-full bg-emerald-400 transition-all"
                        :style="{ width: onboardingPercent + '%' }"
                    ></div>
                </div>
                <div class="mt-5 space-y-2">
                    <Link
                        v-for="step in onboardingSteps"
                        :key="step.key"
                        :href="step.href"
                        class="flex items-start gap-3 rounded-lg px-2 py-2.5 hover:bg-white/5"
                    >
                        <CheckCircle2
                            v-if="props.onboarding[step.key]"
                            class="mt-0.5 size-4 shrink-0 text-emerald-400"
                        />
                        <Circle
                            v-else
                            class="mt-0.5 size-4 shrink-0 text-slate-600"
                        />
                        <span class="min-w-0">
                            <span class="block text-sm font-medium">{{
                                step.title
                            }}</span>
                            <span class="mt-0.5 block text-xs text-slate-400">{{
                                step.description
                            }}</span>
                        </span>
                    </Link>
                </div>
            </aside>
        </section>

        <section class="overflow-hidden rounded-xl border bg-card shadow-sm">
            <div
                class="flex items-center justify-between gap-3 border-b px-5 py-4"
            >
                <div>
                    <h2 class="font-semibold">Saúde das conexões</h2>
                    <p class="text-sm text-muted-foreground">
                        Autenticação, ambiente e última sincronização
                    </p>
                </div>
                <Link
                    href="/bank-connections"
                    class="inline-flex items-center gap-1 text-sm font-medium text-emerald-700 hover:underline dark:text-emerald-400"
                >
                    Gerenciar <ArrowRight class="size-3.5" />
                </Link>
            </div>
            <div
                v-if="props.connectionHealth.length"
                class="grid divide-y md:grid-cols-2 md:divide-x md:divide-y-0 xl:grid-cols-4"
            >
                <article
                    v-for="connection in props.connectionHealth"
                    :key="connection.id"
                    class="p-5"
                >
                    <div class="flex items-center justify-between gap-3">
                        <div
                            class="flex size-9 items-center justify-center rounded-lg bg-muted"
                        >
                            <Landmark class="size-4" />
                        </div>
                        <span
                            class="size-2.5 rounded-full"
                            :class="statusClass(connection.status)"
                        ></span>
                    </div>
                    <h3 class="mt-4 truncate text-sm font-semibold">
                        {{ connection.name }}
                    </h3>
                    <p class="mt-1 truncate text-xs text-muted-foreground">
                        {{ providerLabel(connection.provider) }} ·
                        {{ connection.company_name }}
                    </p>
                    <div class="mt-4 flex items-center justify-between text-xs">
                        <span class="text-muted-foreground">{{
                            connection.environment === 'production'
                                ? 'Produção'
                                : 'Homologação'
                        }}</span>
                        <span class="font-medium">{{
                            statusLabel(connection.status)
                        }}</span>
                    </div>
                    <p class="mt-3 text-xs text-muted-foreground">
                        {{ formatDate(connection.last_synced_at) }}
                    </p>
                </article>
            </div>
            <div
                v-else
                class="flex flex-col items-center px-5 py-12 text-center"
            >
                <Landmark class="size-6 text-muted-foreground" />
                <p class="mt-3 text-sm font-medium">Nenhuma conexão bancária</p>
                <p class="mt-1 text-sm text-muted-foreground">
                    Configure Bradesco ou Sicredi para iniciar a operação.
                </p>
            </div>
        </section>

        <section class="rounded-xl border bg-card px-5 py-4 shadow-sm">
            <div
                class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between"
            >
                <div>
                    <h2 class="text-sm font-semibold">Outbox durável</h2>
                    <p class="text-xs text-muted-foreground">
                        Eventos aguardando entrega para os sistemas conectados.
                    </p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <span
                        v-for="(total, status) in props.queueHealth"
                        :key="status"
                        class="rounded-md bg-muted px-2.5 py-1 text-xs"
                    >
                        <span class="capitalize">{{ status }}</span>
                        <strong class="ml-1 font-mono">{{ total }}</strong>
                    </span>
                    <span
                        v-if="!Object.keys(props.queueHealth).length"
                        class="inline-flex items-center gap-1.5 text-xs text-emerald-700 dark:text-emerald-400"
                    >
                        <CheckCircle2 class="size-3.5" />
                        Nenhum evento pendente
                    </span>
                </div>
            </div>
        </section>
    </div>
</template>
