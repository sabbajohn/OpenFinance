<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { Boxes, DatabaseZap, RadioTower, Webhook } from '@lucide/vue';

defineProps<{
    syncRuns: Record<string, any>[];
    queues: Record<string, number>;
    deliveries: Record<string, number>;
    rawPayloads: Record<string, number>;
}>();
</script>

<template>
    <Head title="Saúde operacional" />
    <div class="flex flex-1 flex-col gap-6 p-4 md:p-7">
        <header>
            <p
                class="text-sm font-medium text-emerald-700 dark:text-emerald-400"
            >
                Operações
            </p>
            <h1 class="mt-1 text-2xl font-semibold tracking-tight">
                Saúde operacional
            </h1>
            <p class="mt-1 text-sm text-muted-foreground">
                Visão rápida dos mecanismos reconstruíveis e duráveis.
            </p>
        </header>
        <section class="grid gap-4 md:grid-cols-3">
            <article
                v-for="card in [
                    { title: 'Outbox', icon: Boxes, values: queues },
                    {
                        title: 'Webhooks ERP',
                        icon: Webhook,
                        values: deliveries,
                    },
                    {
                        title: 'Payloads brutos',
                        icon: DatabaseZap,
                        values: rawPayloads,
                    },
                ]"
                :key="card.title"
                class="rounded-xl border bg-card p-5 shadow-sm"
            >
                <component :is="card.icon" class="size-5 text-emerald-600" />
                <h2 class="mt-4 font-semibold">{{ card.title }}</h2>
                <div class="mt-3 space-y-2">
                    <div
                        v-for="(total, status) in card.values"
                        :key="status"
                        class="flex justify-between text-sm"
                    >
                        <span class="text-muted-foreground capitalize">{{
                            status
                        }}</span
                        ><span class="font-mono">{{ total }}</span>
                    </div>
                    <p
                        v-if="!Object.keys(card.values).length"
                        class="text-sm text-muted-foreground"
                    >
                        Sem ocorrências
                    </p>
                </div>
            </article>
        </section>
        <section class="overflow-hidden rounded-xl border bg-card shadow-sm">
            <div class="flex items-center gap-2 border-b px-5 py-4">
                <RadioTower class="size-5 text-emerald-600" />
                <h2 class="font-semibold">Sincronizações recentes</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="border-b bg-muted/40 text-muted-foreground">
                        <tr>
                            <th class="px-4 py-3">Conexão</th>
                            <th class="px-4 py-3">Capacidade</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Itens</th>
                            <th class="px-4 py-3">Início</th>
                            <th class="px-4 py-3">Erro</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        <tr v-for="run in syncRuns" :key="run.id">
                            <td class="px-4 py-3 font-mono text-xs">
                                {{ run.bank_connection_id }}
                            </td>
                            <td class="px-4 py-3">{{ run.capability }}</td>
                            <td class="px-4 py-3">{{ run.status }}</td>
                            <td class="px-4 py-3 tabular-nums">
                                {{ run.items_seen }}
                            </td>
                            <td class="px-4 py-3">
                                {{
                                    run.started_at
                                        ? new Date(
                                              run.started_at,
                                          ).toLocaleString('pt-BR')
                                        : '—'
                                }}
                            </td>
                            <td
                                class="max-w-80 truncate px-4 py-3 text-rose-600"
                            >
                                {{ run.error || '—' }}
                            </td>
                        </tr>
                        <tr v-if="!syncRuns.length">
                            <td
                                colspan="6"
                                class="px-4 py-14 text-center text-muted-foreground"
                            >
                                Nenhuma sincronização executada.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</template>
