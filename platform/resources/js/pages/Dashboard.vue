<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import {
    Activity,
    AlertTriangle,
    ArrowDownLeft,
    ArrowUpRight,
    CircleDollarSign,
    GitCompareArrows,
} from '@lucide/vue';

type Transaction = {
    id: string;
    description: string | null;
    direction: 'credit' | 'debit';
    amount_minor: number;
    occurred_at: string;
    status: string;
};

const props = defineProps<{
    metrics: {
        balance_minor: number;
        transactions_today: number;
        open_reconciliations: number;
        connections_attention: number;
    };
    recentTransactions: Transaction[];
    queueHealth: Record<string, number>;
}>();

const money = (minor: number) =>
    new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL',
    }).format((minor ?? 0) / 100);
</script>

<template>
    <Head title="Visão geral" />

    <div class="flex flex-1 flex-col gap-6 p-4 md:p-7">
        <header
            class="flex flex-col justify-between gap-3 md:flex-row md:items-end"
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
            <div class="flex items-center gap-2 text-xs text-muted-foreground">
                <span class="size-2 rounded-full bg-emerald-500"></span>
                Pipeline operacional
            </div>
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
                <AlertTriangle class="size-5 text-rose-600" />
                <p class="mt-5 text-sm text-muted-foreground">
                    Conexões com atenção
                </p>
                <p class="mt-1 text-2xl font-semibold tabular-nums">
                    {{ props.metrics.connections_attention }}
                </p>
            </article>
        </section>

        <section class="grid gap-4 xl:grid-cols-[minmax(0,1fr)_320px]">
            <div class="overflow-hidden rounded-xl border bg-card shadow-sm">
                <div class="border-b px-5 py-4">
                    <h2 class="font-semibold">Movimentos recentes</h2>
                    <p class="text-sm text-muted-foreground">
                        Últimos lançamentos normalizados
                    </p>
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
                    class="px-5 py-12 text-center text-sm text-muted-foreground"
                >
                    Os movimentos aparecerão após a primeira sincronização
                    bancária.
                </div>
            </div>

            <aside
                class="rounded-xl border bg-slate-950 p-5 text-slate-100 shadow-sm dark:border-slate-800"
            >
                <div class="flex items-center justify-between">
                    <h2 class="font-semibold">Outbox durável</h2>
                    <span class="size-2 rounded-full bg-emerald-400"></span>
                </div>
                <p class="mt-1 text-sm text-slate-400">
                    Estado dos eventos aguardando entrega.
                </p>
                <div class="mt-6 space-y-3">
                    <div
                        v-for="(total, status) in props.queueHealth"
                        :key="status"
                        class="flex items-center justify-between rounded-lg bg-white/5 px-3 py-2.5"
                    >
                        <span class="text-sm text-slate-300 capitalize">{{
                            status
                        }}</span>
                        <span class="font-mono text-sm">{{ total }}</span>
                    </div>
                    <div
                        v-if="!Object.keys(props.queueHealth).length"
                        class="rounded-lg bg-white/5 px-3 py-5 text-center text-sm text-slate-400"
                    >
                        Nenhum evento pendente
                    </div>
                </div>
            </aside>
        </section>
    </div>
</template>
