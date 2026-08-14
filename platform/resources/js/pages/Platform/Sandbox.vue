<script setup lang="ts">
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import {
    AlertCircle,
    CheckCircle2,
    Clock3,
    FlaskConical,
    Landmark,
    LoaderCircle,
    Play,
    ShieldCheck,
} from '@lucide/vue';
import { computed } from 'vue';
import { useOrganizationAccess } from '@/composables/useOrganizationAccess';

type Connection = {
    id: string;
    name: string;
    provider: string;
    product: 'boleto' | 'pix';
    status: string;
    company_name: string;
    can_test_receipts: boolean;
};
type Step = {
    key: string;
    name: string;
    status: 'passed' | 'failed';
    duration_ms?: number | null;
    details?: Record<string, unknown>;
};
type Run = {
    id: string;
    suite: 'authentication' | 'pix_receipts';
    environment: string;
    status: 'running' | 'passed' | 'failed';
    steps?: Step[] | null;
    summary?: Record<string, unknown> | null;
    error?: string | null;
    started_at: string;
    finished_at?: string | null;
    connection?: { name: string; provider: string } | null;
    user?: { name: string } | null;
};

const props = defineProps<{
    enabled: boolean;
    connections: Connection[];
    runs: Run[];
}>();

const { can, organization } = useOrganizationAccess();
const currentUser = usePage().props.auth.user;
const requestedConnection = new URLSearchParams(window.location.search).get(
    'connection',
);
const form = useForm({
    bank_connection_id:
        props.connections.find(
            (connection) => connection.id === requestedConnection,
        )?.id ??
        props.connections[0]?.id ??
        '',
    suite: 'authentication' as 'authentication' | 'pix_receipts',
});
const selectedConnection = computed(() =>
    props.connections.find(
        (connection) => connection.id === form.bank_connection_id,
    ),
);
const passedRuns = computed(
    () => props.runs.filter((run) => run.status === 'passed').length,
);
const failedRuns = computed(
    () => props.runs.filter((run) => run.status === 'failed').length,
);
const canRun = can('bank-tests.run');
const requiresTwoFactor = ['owner', 'admin', 'operator'].includes(
    organization?.role ?? '',
);
const twoFactorReady =
    !requiresTwoFactor || Boolean(currentUser.two_factor_confirmed_at);
const canExecute = canRun && twoFactorReady;
const submit = () =>
    form.post('/sandbox/runs', {
        preserveScroll: true,
    });
const providerLabel = (provider?: string) =>
    provider === 'bradesco'
        ? 'Bradesco'
        : provider === 'sicredi'
          ? 'Sicredi'
          : provider || 'Banco';
const suiteLabel = (run: Run) =>
    run.suite === 'pix_receipts'
        ? 'Autenticação + Pix recebidos'
        : run.steps?.some((step) => step.key === 'oauth2')
          ? 'Autenticação OAuth2 + x-api-key'
          : 'Autenticação mTLS + OAuth2';
const statusLabel = (status: Run['status']) =>
    ({ running: 'Executando', passed: 'Aprovado', failed: 'Falhou' })[status];
const formatDate = (value?: string | null) =>
    value ? new Date(value).toLocaleString('pt-BR') : '—';
const detailSummary = (step: Step) => {
    const details = step.details ?? {};

    if (
        ['mtls_oauth2', 'oauth2'].includes(step.key) &&
        step.status === 'passed'
    ) {
        return `Token ${String(details.token_type || 'Bearer')} · validade ${String(details.expires_in || '—')}s`;
    }

    if (step.key === 'pix_receipts' && step.status === 'passed') {
        return `${String(details.items_found ?? 0)} item(ns) encontrado(s) na amostra`;
    }

    return String(details.message || 'Etapa concluída.');
};
</script>

<template>
    <Head title="Laboratório Sandbox" />
    <div class="flex flex-1 flex-col gap-6 p-4 md:p-7">
        <header
            class="flex flex-col justify-between gap-4 lg:flex-row lg:items-end"
        >
            <div class="max-w-3xl">
                <p
                    class="text-sm font-medium text-emerald-700 dark:text-emerald-400"
                >
                    Homologação bancária
                </p>
                <h1 class="mt-1 text-2xl font-semibold tracking-tight">
                    Laboratório Sandbox
                </h1>
                <p class="mt-2 text-sm leading-6 text-muted-foreground">
                    Execute cenários controlados e acompanhe cada etapa sem
                    exibir tokens, certificados, chaves privadas ou payloads
                    bancários sensíveis.
                </p>
            </div>
            <span
                class="inline-flex w-fit items-center gap-2 rounded-full border px-3 py-1.5 text-xs font-semibold"
                :class="
                    enabled
                        ? 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-900 dark:bg-emerald-950/30 dark:text-emerald-300'
                        : 'border-rose-200 bg-rose-50 text-rose-700'
                "
            >
                <span
                    class="size-2 rounded-full"
                    :class="enabled ? 'bg-emerald-500' : 'bg-rose-500'"
                />
                {{
                    enabled ? 'Modo Sandbox habilitado' : 'Sandbox desabilitado'
                }}
            </span>
        </header>

        <section class="grid gap-4 md:grid-cols-3">
            <article class="rounded-xl border bg-card p-5 shadow-sm">
                <Landmark class="size-5 text-emerald-600" />
                <p class="mt-4 text-2xl font-semibold tabular-nums">
                    {{ connections.length }}
                </p>
                <p class="text-sm text-muted-foreground">Conexões Sandbox</p>
            </article>
            <article class="rounded-xl border bg-card p-5 shadow-sm">
                <CheckCircle2 class="size-5 text-emerald-600" />
                <p class="mt-4 text-2xl font-semibold tabular-nums">
                    {{ passedRuns }}
                </p>
                <p class="text-sm text-muted-foreground">Execuções aprovadas</p>
            </article>
            <article class="rounded-xl border bg-card p-5 shadow-sm">
                <AlertCircle class="size-5 text-rose-600" />
                <p class="mt-4 text-2xl font-semibold tabular-nums">
                    {{ failedRuns }}
                </p>
                <p class="text-sm text-muted-foreground">
                    Falhas diagnosticadas
                </p>
            </article>
        </section>

        <section class="grid gap-4 xl:grid-cols-[400px_minmax(0,1fr)]">
            <form
                class="h-fit rounded-2xl border bg-card p-5 shadow-sm"
                @submit.prevent="submit"
            >
                <div class="flex items-center gap-3">
                    <div
                        class="flex size-10 items-center justify-center rounded-xl bg-emerald-700 text-white"
                    >
                        <FlaskConical class="size-5" />
                    </div>
                    <div>
                        <h2 class="font-semibold">Nova execução</h2>
                        <p class="text-xs text-muted-foreground">
                            Somente endpoints de homologação.
                        </p>
                    </div>
                </div>

                <div
                    v-if="!canRun"
                    class="mt-5 rounded-xl border border-sky-200 bg-sky-50 p-4 text-xs leading-5 text-sky-800 dark:border-sky-900 dark:bg-sky-950/30 dark:text-sky-200"
                >
                    Seu perfil possui acesso de acompanhamento. A execução é
                    liberada para proprietário, administrador, operador e
                    desenvolvedor de integrações.
                </div>
                <div
                    v-else-if="!twoFactorReady"
                    class="mt-5 rounded-xl border border-amber-200 bg-amber-50 p-4 text-xs leading-5 text-amber-900 dark:border-amber-900 dark:bg-amber-950/30 dark:text-amber-200"
                >
                    Ative a autenticação em dois fatores antes de executar
                    testes bancários.
                    <Link
                        href="/settings/security"
                        class="ml-1 font-semibold underline underline-offset-2"
                    >
                        Configurar 2FA
                    </Link>
                </div>

                <label class="mt-5 flex flex-col gap-1.5 text-sm font-medium">
                    Conexão bancária
                    <select
                        v-model="form.bank_connection_id"
                        required
                        class="rounded-lg border bg-background px-3 py-2.5"
                        :disabled="!enabled || !canExecute"
                    >
                        <option value="" disabled>Selecione uma conexão</option>
                        <option
                            v-for="connection in connections"
                            :key="connection.id"
                            :value="connection.id"
                        >
                            {{ connection.name }} ·
                            {{ providerLabel(connection.provider) }} ·
                            {{
                                connection.product === 'boleto'
                                    ? 'Cobrança'
                                    : 'Pix'
                            }}
                        </option>
                    </select>
                </label>

                <fieldset class="mt-5 space-y-2">
                    <legend class="mb-2 text-sm font-medium">Suíte</legend>
                    <label
                        class="flex cursor-pointer gap-3 rounded-xl border p-4"
                        :class="
                            form.suite === 'authentication'
                                ? 'border-emerald-400 bg-emerald-50/50 dark:bg-emerald-950/20'
                                : ''
                        "
                    >
                        <input
                            v-model="form.suite"
                            type="radio"
                            value="authentication"
                            class="mt-1 accent-emerald-700"
                            :disabled="!enabled || !canExecute"
                        />
                        <span>
                            <span class="block text-sm font-medium">
                                Autenticação
                            </span>
                            <span
                                class="mt-1 block text-xs text-muted-foreground"
                            >
                                {{
                                    selectedConnection?.product === 'boleto'
                                        ? 'OAuth2 Password e x-api-key, sem chamada de negócio.'
                                        : 'Certificado mTLS e token OAuth2, sem chamada de negócio.'
                                }}
                            </span>
                        </span>
                    </label>
                    <label
                        class="flex cursor-pointer gap-3 rounded-xl border p-4"
                        :class="
                            form.suite === 'pix_receipts'
                                ? 'border-emerald-400 bg-emerald-50/50 dark:bg-emerald-950/20'
                                : ''
                        "
                    >
                        <input
                            v-model="form.suite"
                            type="radio"
                            value="pix_receipts"
                            class="mt-1 accent-emerald-700"
                            :disabled="
                                !enabled ||
                                !canExecute ||
                                !selectedConnection?.can_test_receipts
                            "
                        />
                        <span>
                            <span class="block text-sm font-medium">
                                Pix recebidos
                            </span>
                            <span
                                class="mt-1 block text-xs text-muted-foreground"
                            >
                                {{
                                    selectedConnection?.provider === 'bradesco'
                                        ? 'Autentica e consulta o cenário oficial de recebimentos do Sandbox Bradesco.'
                                        : 'Autentica e consulta uma amostra somente leitura das últimas 24 horas.'
                                }}
                            </span>
                        </span>
                    </label>
                </fieldset>

                <button
                    class="mt-5 inline-flex h-10 w-full items-center justify-center gap-2 rounded-lg bg-emerald-700 px-4 text-sm font-medium text-white disabled:opacity-50"
                    :disabled="
                        !enabled ||
                        !canExecute ||
                        !form.bank_connection_id ||
                        form.processing
                    "
                >
                    <LoaderCircle
                        v-if="form.processing"
                        class="size-4 animate-spin"
                    />
                    <Play v-else class="size-4" />
                    {{ form.processing ? 'Executando…' : 'Executar suíte' }}
                </button>
            </form>

            <div class="overflow-hidden rounded-2xl border bg-card shadow-sm">
                <div class="flex items-center gap-2 border-b px-5 py-4">
                    <Clock3 class="size-5 text-emerald-600" />
                    <div>
                        <h2 class="font-semibold">Histórico de testes</h2>
                        <p class="text-xs text-muted-foreground">
                            Resultados sanitizados por conexão e executor.
                        </p>
                    </div>
                </div>
                <div class="divide-y">
                    <article v-for="run in runs" :key="run.id" class="p-5">
                        <div
                            class="flex flex-col justify-between gap-3 sm:flex-row sm:items-start"
                        >
                            <div>
                                <div class="flex flex-wrap items-center gap-2">
                                    <h3 class="text-sm font-semibold">
                                        {{
                                            run.connection?.name ||
                                            'Conexão removida'
                                        }}
                                    </h3>
                                    <span
                                        class="rounded-full px-2 py-0.5 text-xs font-medium"
                                        :class="
                                            run.status === 'passed'
                                                ? 'bg-emerald-500/10 text-emerald-700 dark:text-emerald-300'
                                                : run.status === 'failed'
                                                  ? 'bg-rose-500/10 text-rose-700 dark:text-rose-300'
                                                  : 'bg-amber-500/10 text-amber-700'
                                        "
                                    >
                                        {{ statusLabel(run.status) }}
                                    </span>
                                </div>
                                <p class="mt-1 text-xs text-muted-foreground">
                                    {{ suiteLabel(run) }} ·
                                    {{
                                        providerLabel(run.connection?.provider)
                                    }}
                                </p>
                            </div>
                            <div
                                class="text-xs text-muted-foreground sm:text-right"
                            >
                                <p>{{ formatDate(run.started_at) }}</p>
                                <p class="mt-1">
                                    por {{ run.user?.name || 'Sistema' }}
                                </p>
                            </div>
                        </div>

                        <div class="mt-4 grid gap-2">
                            <div
                                v-for="step in run.steps || []"
                                :key="`${run.id}-${step.key}`"
                                class="flex gap-3 rounded-xl border p-3"
                            >
                                <CheckCircle2
                                    v-if="step.status === 'passed'"
                                    class="mt-0.5 size-4 shrink-0 text-emerald-600"
                                />
                                <AlertCircle
                                    v-else
                                    class="mt-0.5 size-4 shrink-0 text-rose-600"
                                />
                                <div class="min-w-0 flex-1">
                                    <div class="flex justify-between gap-3">
                                        <p class="text-xs font-medium">
                                            {{ step.name }}
                                        </p>
                                        <span
                                            class="shrink-0 font-mono text-[11px] text-muted-foreground"
                                        >
                                            {{ step.duration_ms ?? '—' }} ms
                                        </span>
                                    </div>
                                    <p
                                        class="mt-1 text-xs leading-5 break-words text-muted-foreground"
                                    >
                                        {{ detailSummary(step) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </article>
                    <div
                        v-if="!runs.length"
                        class="px-6 py-16 text-center text-sm text-muted-foreground"
                    >
                        <ShieldCheck class="mx-auto size-8 text-emerald-600" />
                        <p class="mt-3 font-medium text-foreground">
                            Nenhum teste executado
                        </p>
                        <p class="mt-1">
                            Selecione uma conexão Sandbox para iniciar a
                            homologação acompanhada.
                        </p>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>
