<script setup lang="ts">
import { Head, router, useForm } from '@inertiajs/vue3';
import {
    BanknoteArrowDown,
    ChevronRight,
    Pencil,
    Plus,
    RefreshCw,
    ShieldCheck,
    X,
} from '@lucide/vue';
import { computed, ref } from 'vue';

type Column = { key: string; label: string; format?: 'money' };
type RecordRow = Record<string, any>;
type Option = { id: string; trade_name?: string; legal_name?: string };
type ReceivableConnection = { id: string; company_id: string; name: string };
type ReceivableTitle = {
    id: string;
    company_id: string;
    external_id: string;
    description: string;
    open_amount_minor: number;
    currency: string;
};
type ReceivableOptions = {
    connections: ReceivableConnection[];
    titles: ReceivableTitle[];
};

const props = defineProps<{
    title: string;
    section: string;
    records: RecordRow[];
    columns: Column[];
    options?: Option[] | ReceivableOptions | null;
}>();

const showForm = ref(false);
const companyForm = useForm({
    legal_name: '',
    trade_name: '',
    tax_id: '',
    timezone: 'America/Sao_Paulo',
});
const erpForm = useForm({
    company_id: '',
    name: '',
    base_url: '',
    webhook_url: '',
    webhook_secret: '',
});
const bankForm = useForm({
    company_id: '',
    name: 'Sicredi',
    environment: 'sandbox',
    capabilities: [
        'accounts',
        'balances',
        'transactions',
        'pix',
        'boleto',
        'webhooks',
    ],
    certificate_expires_at: '',
    credentials: {
        webhook_secret: '',
        products: {
            accounts: {
                base_url: '',
                token_url: '',
                client_id: '',
                client_secret: '',
                certificate_pem: '',
                private_key_pem: '',
            },
            pix: {
                base_url: '',
                token_url: '',
                client_id: '',
                client_secret: '',
                certificate_pem: '',
                private_key_pem: '',
            },
            boleto: {
                base_url: '',
                token_url: '',
                client_id: '',
                client_secret: '',
                certificate_pem: '',
                private_key_pem: '',
            },
        },
    },
});
const receivableForm = useForm({
    erp_title_id: '',
    bank_connection_id: '',
    amount_minor: null as number | null,
    subtype: 'immediate',
    due_at: '',
    reference: '',
    idempotency_key: crypto.randomUUID(),
});
const companyOptions = computed(() =>
    Array.isArray(props.options) ? props.options : [],
);
const receivableOptions = computed<ReceivableOptions>(() =>
    !Array.isArray(props.options) && props.options
        ? props.options
        : { connections: [], titles: [] },
);
const selectedConnection = computed(() =>
    receivableOptions.value.connections.find(
        (connection) => connection.id === receivableForm.bank_connection_id,
    ),
);
const availableTitles = computed(() =>
    receivableOptions.value.titles.filter(
        (title) =>
            !selectedConnection.value ||
            title.company_id === selectedConnection.value.company_id,
    ),
);

const valueAt = (row: RecordRow, path: string) =>
    path.split('.').reduce((value, key) => value?.[key], row);
const display = (row: RecordRow, column: Column) => {
    const value = valueAt(row, column.key);

    if (column.format === 'money') {
        return new Intl.NumberFormat('pt-BR', {
            style: 'currency',
            currency: 'BRL',
        }).format((Number(value) || 0) / 100);
    }

    if (typeof value === 'boolean') {
        return value ? 'Sim' : 'Não';
    }

    if (typeof value === 'string' && /^\d{4}-\d{2}-\d{2}T/.test(value)) {
        return new Date(value).toLocaleString('pt-BR');
    }

    return value ?? '—';
};
const statusClass = (value: unknown) => {
    const status = String(value);

    if (
        [
            'active',
            'completed',
            'confirmed',
            'resolved',
            'paid',
            'posted',
        ].includes(status)
    ) {
        return 'bg-emerald-500/10 text-emerald-700 dark:text-emerald-400';
    }

    if (['failed', 'degraded', 'rejected', 'conflict'].includes(status)) {
        return 'bg-rose-500/10 text-rose-700 dark:text-rose-400';
    }

    return 'bg-amber-500/10 text-amber-700 dark:text-amber-400';
};
const submit = () => {
    if (props.section === 'companies') {
        companyForm.post('/companies', {
            onSuccess: () => (showForm.value = false),
        });
    }

    if (props.section === 'erp') {
        erpForm.post('/erp-integrations', {
            onSuccess: () => (showForm.value = false),
        });
    }

    if (props.section === 'bank-connections') {
        bankForm.post('/bank-connections', {
            onSuccess: () => (showForm.value = false),
        });
    }

    if (['pix', 'boleto'].includes(props.section)) {
        receivableForm.subtype =
            props.section === 'pix' ? 'immediate' : 'normal';
        receivableForm.post(props.section === 'pix' ? '/pix' : '/boletos', {
            onSuccess: () => {
                showForm.value = false;
                receivableForm.reset();
                receivableForm.idempotency_key = crypto.randomUUID();
            },
        });
    }
};
const sync = (id: string) => router.post(`/bank-connections/${id}/sync`);
const decide = (row: RecordRow) => {
    const candidate = row.candidates?.[0];

    if (!candidate) {
        return;
    }

    router.post(`/reconciliations/${row.id}/decisions`, {
        action: 'match',
        expected_version: row.version,
        idempotency_key: crypto.randomUUID(),
        payload: {
            allocations: [
                {
                    erp_title_id: candidate.erp_title_id,
                    amount_minor: candidate.suggested_amount_minor,
                },
            ],
        },
    });
};
const refreshReceivable = (id: string) =>
    router.post(`/receivables/${id}/refresh`, {
        idempotency_key: crypto.randomUUID(),
    });
const refundPix = (row: RecordRow) => {
    const raw = window.prompt(
        `Valor da devolução em centavos (máximo ${row.amount_minor}):`,
        String(row.amount_minor),
    );
    const amount = Number(raw);

    if (!Number.isInteger(amount) || amount < 1) {
        return;
    }

    router.post(`/pix/${row.id}/refund`, {
        amount_minor: amount,
        idempotency_key: crypto.randomUUID(),
    });
};
const updateBoleto = (row: RecordRow) => {
    const dueAt = window.prompt(
        'Novo vencimento (AAAA-MM-DD):',
        row.due_at || '',
    );

    if (!dueAt) {
        return;
    }

    router.patch(`/boletos/${row.id}`, {
        due_at: dueAt,
        idempotency_key: crypto.randomUUID(),
    });
};
const cancelBoleto = (row: RecordRow) => {
    if (!window.confirm('Confirma a baixa/cancelamento deste boleto?')) {
        return;
    }

    router.post(`/boletos/${row.id}/cancel`, {
        idempotency_key: crypto.randomUUID(),
    });
};
const canCreate = [
    'companies',
    'bank-connections',
    'erp',
    'pix',
    'boleto',
].includes(props.section);
</script>

<template>
    <Head :title="props.title" />
    <div class="flex flex-1 flex-col gap-6 p-4 md:p-7">
        <header
            class="flex flex-col justify-between gap-3 md:flex-row md:items-center"
        >
            <div>
                <p
                    class="text-sm font-medium text-emerald-700 dark:text-emerald-400"
                >
                    OpenFinance Platform
                </p>
                <h1 class="mt-1 text-2xl font-semibold tracking-tight">
                    {{ props.title }}
                </h1>
                <p class="mt-1 text-sm text-muted-foreground">
                    {{ props.records.length }} registros no contexto atual
                </p>
            </div>
            <button
                v-if="canCreate"
                class="inline-flex h-9 items-center justify-center gap-2 rounded-md bg-emerald-700 px-4 text-sm font-medium text-white hover:bg-emerald-800"
                @click="showForm = !showForm"
            >
                <Plus class="size-4" /> Novo registro
            </button>
        </header>

        <form
            v-if="showForm"
            class="rounded-xl border bg-card p-5 shadow-sm"
            @submit.prevent="submit"
        >
            <div class="mb-5 flex items-center gap-2">
                <ShieldCheck class="size-5 text-emerald-600" />
                <div>
                    <h2 class="font-semibold">Configuração segura</h2>
                    <p class="text-xs text-muted-foreground">
                        Segredos são criptografados e não voltam a ser exibidos.
                    </p>
                </div>
            </div>

            <div
                v-if="props.section === 'companies'"
                class="grid gap-4 md:grid-cols-2"
            >
                <label class="field"
                    >Razão social<input
                        v-model="companyForm.legal_name"
                        required
                /></label>
                <label class="field"
                    >Nome fantasia<input v-model="companyForm.trade_name"
                /></label>
                <label class="field"
                    >CNPJ<input v-model="companyForm.tax_id" required
                /></label>
                <label class="field"
                    >Fuso horário<input v-model="companyForm.timezone" required
                /></label>
            </div>

            <div
                v-if="props.section === 'erp'"
                class="grid gap-4 md:grid-cols-2"
            >
                <label class="field"
                    >Empresa<select v-model="erpForm.company_id" required>
                        <option value="" disabled>Selecione</option>
                        <option
                            v-for="option in companyOptions"
                            :key="option.id"
                            :value="option.id"
                        >
                            {{ option.trade_name || option.legal_name }}
                        </option>
                    </select></label
                >
                <label class="field"
                    >Nome da integração<input v-model="erpForm.name" required
                /></label>
                <label class="field"
                    >URL do SimplesLaravel<input
                        v-model="erpForm.base_url"
                        type="url"
                /></label>
                <label class="field"
                    >URL de webhook<input
                        v-model="erpForm.webhook_url"
                        type="url"
                /></label>
                <label class="field md:col-span-2"
                    >Segredo HMAC<input
                        v-model="erpForm.webhook_secret"
                        type="password"
                        minlength="24"
                /></label>
            </div>

            <div v-if="props.section === 'bank-connections'" class="space-y-5">
                <div class="grid gap-4 md:grid-cols-3">
                    <label class="field"
                        >Empresa<select v-model="bankForm.company_id" required>
                            <option value="" disabled>Selecione</option>
                            <option
                                v-for="option in companyOptions"
                                :key="option.id"
                                :value="option.id"
                            >
                                {{ option.trade_name || option.legal_name }}
                            </option>
                        </select></label
                    >
                    <label class="field"
                        >Nome<input v-model="bankForm.name" required
                    /></label>
                    <label class="field"
                        >Ambiente<select v-model="bankForm.environment">
                            <option value="sandbox">Sandbox</option>
                            <option value="production">Produção</option>
                        </select></label
                    >
                </div>
                <div
                    v-for="product in ['accounts', 'pix', 'boleto'] as const"
                    :key="product"
                    class="rounded-lg border p-4"
                >
                    <h3
                        class="mb-3 text-sm font-semibold tracking-wide text-muted-foreground uppercase"
                    >
                        {{ product }}
                    </h3>
                    <div class="grid gap-3 md:grid-cols-2">
                        <label class="field"
                            >Base URL<input
                                v-model="
                                    bankForm.credentials.products[product]
                                        .base_url
                                "
                        /></label>
                        <label class="field"
                            >Token URL<input
                                v-model="
                                    bankForm.credentials.products[product]
                                        .token_url
                                "
                        /></label>
                        <label class="field"
                            >Client ID<input
                                v-model="
                                    bankForm.credentials.products[product]
                                        .client_id
                                "
                        /></label>
                        <label class="field"
                            >Client secret<input
                                v-model="
                                    bankForm.credentials.products[product]
                                        .client_secret
                                "
                                type="password"
                        /></label>
                        <label class="field"
                            >Certificado PEM<textarea
                                v-model="
                                    bankForm.credentials.products[product]
                                        .certificate_pem
                                "
                                rows="3"
                            ></textarea>
                        </label>
                        <label class="field"
                            >Chave privada PEM<textarea
                                v-model="
                                    bankForm.credentials.products[product]
                                        .private_key_pem
                                "
                                rows="3"
                            ></textarea>
                        </label>
                    </div>
                </div>
            </div>

            <div
                v-if="['pix', 'boleto'].includes(props.section)"
                class="grid gap-4 md:grid-cols-2"
            >
                <label class="field"
                    >Conexão bancária<select
                        v-model="receivableForm.bank_connection_id"
                        required
                    >
                        <option value="" disabled>Selecione</option>
                        <option
                            v-for="connection in receivableOptions.connections"
                            :key="connection.id"
                            :value="connection.id"
                        >
                            {{ connection.name }}
                        </option>
                    </select></label
                >
                <label class="field"
                    >Título a receber<select
                        v-model="receivableForm.erp_title_id"
                        required
                    >
                        <option value="" disabled>Selecione</option>
                        <option
                            v-for="title in availableTitles"
                            :key="title.id"
                            :value="title.id"
                        >
                            {{ title.external_id }} — {{ title.description }}
                        </option>
                    </select></label
                >
                <label class="field"
                    >Valor em centavos<input
                        v-model.number="receivableForm.amount_minor"
                        type="number"
                        min="1"
                        step="1"
                        placeholder="Em branco usa o saldo do título"
                /></label>
                <label class="field"
                    >Modalidade<select v-model="receivableForm.subtype">
                        <template v-if="props.section === 'pix'">
                            <option value="immediate">Imediata</option>
                            <option value="due">Com vencimento</option>
                        </template>
                        <template v-else>
                            <option value="normal">Normal</option>
                            <option value="hybrid">Híbrido</option>
                        </template>
                    </select></label
                >
                <label class="field"
                    >Vencimento<input
                        v-model="receivableForm.due_at"
                        type="date"
                /></label>
                <label class="field"
                    >Referência<input v-model="receivableForm.reference"
                /></label>
            </div>

            <div class="mt-5 flex justify-end gap-2">
                <button
                    type="button"
                    class="h-9 rounded-md border px-4 text-sm"
                    @click="showForm = false"
                >
                    Cancelar
                </button>
                <button
                    type="submit"
                    class="h-9 rounded-md bg-emerald-700 px-4 text-sm font-medium text-white"
                    :disabled="
                        companyForm.processing ||
                        erpForm.processing ||
                        bankForm.processing ||
                        receivableForm.processing
                    "
                >
                    Salvar
                </button>
            </div>
        </form>

        <div class="overflow-hidden rounded-xl border bg-card shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead
                        class="border-b bg-muted/40 text-xs tracking-wide text-muted-foreground uppercase"
                    >
                        <tr>
                            <th
                                v-for="column in props.columns"
                                :key="column.key"
                                class="px-4 py-3 font-medium"
                            >
                                {{ column.label }}
                            </th>
                            <th class="w-20 px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        <tr
                            v-for="row in props.records"
                            :key="row.id"
                            class="hover:bg-muted/30"
                        >
                            <td
                                v-for="column in props.columns"
                                :key="column.key"
                                class="max-w-80 px-4 py-3.5"
                            >
                                <span
                                    v-if="column.key === 'status'"
                                    class="rounded-full px-2 py-1 text-xs font-medium"
                                    :class="
                                        statusClass(valueAt(row, column.key))
                                    "
                                    >{{ display(row, column) }}</span
                                >
                                <span
                                    v-else
                                    class="line-clamp-2"
                                    :class="
                                        column.format === 'money'
                                            ? 'font-medium tabular-nums'
                                            : ''
                                    "
                                    >{{ display(row, column) }}</span
                                >
                            </td>
                            <td class="px-4 py-3 text-right">
                                <button
                                    v-if="props.section === 'bank-connections'"
                                    title="Sincronizar"
                                    class="rounded-md p-2 hover:bg-muted"
                                    @click="sync(row.id)"
                                >
                                    <RefreshCw class="size-4" />
                                </button>
                                <button
                                    v-else-if="
                                        props.section === 'reconciliations' &&
                                        row.status === 'open' &&
                                        row.candidates?.length
                                    "
                                    title="Aprovar melhor candidato"
                                    class="rounded-md p-2 text-emerald-700 hover:bg-emerald-500/10"
                                    @click="decide(row)"
                                >
                                    <ChevronRight class="size-4" />
                                </button>
                                <div
                                    v-else-if="
                                        ['pix', 'boleto'].includes(
                                            props.section,
                                        )
                                    "
                                    class="flex justify-end gap-1"
                                >
                                    <button
                                        title="Consultar no banco"
                                        class="rounded-md p-2 hover:bg-muted"
                                        @click="refreshReceivable(row.id)"
                                    >
                                        <RefreshCw class="size-4" />
                                    </button>
                                    <button
                                        v-if="
                                            props.section === 'pix' &&
                                            row.status === 'paid'
                                        "
                                        title="Devolver Pix"
                                        class="rounded-md p-2 text-amber-700 hover:bg-amber-500/10"
                                        @click="refundPix(row)"
                                    >
                                        <BanknoteArrowDown class="size-4" />
                                    </button>
                                    <button
                                        v-if="
                                            props.section === 'boleto' &&
                                            !['paid', 'cancelled'].includes(
                                                row.status,
                                            )
                                        "
                                        title="Alterar vencimento"
                                        class="rounded-md p-2 hover:bg-muted"
                                        @click="updateBoleto(row)"
                                    >
                                        <Pencil class="size-4" />
                                    </button>
                                    <button
                                        v-if="
                                            props.section === 'boleto' &&
                                            !['paid', 'cancelled'].includes(
                                                row.status,
                                            )
                                        "
                                        title="Baixar boleto"
                                        class="rounded-md p-2 text-rose-700 hover:bg-rose-500/10"
                                        @click="cancelBoleto(row)"
                                    >
                                        <X class="size-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="!props.records.length">
                            <td
                                :colspan="props.columns.length + 1"
                                class="px-4 py-14 text-center text-muted-foreground"
                            >
                                Nenhum registro encontrado.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<style scoped>
.field {
    display: flex;
    flex-direction: column;
    gap: 0.375rem;
    font-size: 0.875rem;
    font-weight: 500;
}

.field :is(input, select, textarea) {
    border: 1px solid var(--border);
    border-radius: 0.375rem;
    background: var(--background);
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
    font-weight: 400;
    outline: none;
}

.field :is(input, select, textarea):focus {
    border-color: hsl(158 64% 35%);
    box-shadow: 0 0 0 2px hsl(158 64% 35% / 15%);
}
</style>
