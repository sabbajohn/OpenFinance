<script setup lang="ts">
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import {
    AlertCircle,
    CheckCircle2,
    ExternalLink,
    FileKey,
    KeyRound,
    Landmark,
    LoaderCircle,
    Lock,
    Pencil,
    Plug,
    Plus,
    RefreshCw,
    ShieldCheck,
    X,
} from '@lucide/vue';
import { computed, ref } from 'vue';

type Company = { id: string; trade_name?: string | null; legal_name: string };
type EnvironmentPreset = {
    label: string;
    base_url: string;
    token_url: string;
};
type ProviderKey = 'sicredi' | 'bradesco';
type ProviderOption = {
    value: ProviderKey;
    label: string;
    default_name: string;
    portal_url: string;
    capabilities: string[];
};
type ConnectionTest = {
    status: 'pending' | 'passed' | 'failed';
    token_type?: string;
    expires_in?: number;
    scope?: string[];
    http_status?: number | null;
    provider_code?: string | null;
};
type Connection = {
    id: string;
    company_id: string;
    company_name: string;
    name: string;
    provider: string;
    environment: 'sandbox' | 'production';
    status: string;
    capabilities: string[];
    accounts_count: number;
    certificate_expires_at?: string | null;
    last_synced_at?: string | null;
    last_error?: string | null;
    last_test_at?: string | null;
    last_test?: ConnectionTest | null;
    configured: boolean;
    client_id_hint?: string | null;
    scope?: string | null;
    webhook_url?: string | null;
    can_sync: boolean;
};

const props = defineProps<{
    connections: Connection[];
    companies: Company[];
    providers: ProviderOption[];
    presets: Record<
        ProviderKey,
        Record<'sandbox' | 'production', EnvironmentPreset>
    >;
}>();

const featureOptions = [
    {
        value: 'pix.immediate',
        title: 'Pix imediato',
        description: 'Criar e consultar cobranças sem vencimento.',
        scopes: 'cob.read · cob.write',
    },
    {
        value: 'pix.due',
        title: 'Pix com vencimento',
        description: 'Criar e consultar cobranças com data de vencimento.',
        scopes: 'cobv.read · cobv.write',
    },
    {
        value: 'pix.refund',
        title: 'Consulta e devolução',
        description: 'Consultar Pix recebidos e solicitar devoluções.',
        scopes: 'pix.read · pix.write',
    },
    {
        value: 'webhooks',
        title: 'Webhooks',
        description: 'Cadastrar e consultar notificações por chave Pix.',
        scopes: 'webhook.read · webhook.write',
    },
];

const showForm = ref(false);
const editingId = ref<string | null>(null);
const testingId = ref<string | null>(null);
const syncingId = ref<string | null>(null);
const form = useForm({
    provider: 'sicredi' as ProviderKey,
    company_id: props.companies[0]?.id ?? '',
    name: 'Sicredi Pix',
    environment: 'sandbox' as 'sandbox' | 'production',
    capabilities: ['pix.immediate', 'pix.due', 'pix.refund', 'webhooks'],
    client_id: '',
    client_secret: '',
    pix_key: '',
    certificate: null as File | null,
    certificate_chain: null as File | null,
    private_key: null as File | null,
    private_key_passphrase: '',
    webhook_secret: '',
});

const selectedProvider = computed(
    () =>
        props.providers.find((provider) => provider.value === form.provider) ??
        props.providers[0],
);
const availableFeatureOptions = computed(() =>
    featureOptions.filter((feature) =>
        selectedProvider.value?.capabilities.includes(feature.value),
    ),
);
const selectedPreset = computed(
    () => props.presets[form.provider]?.[form.environment],
);
const isEditing = computed(() => editingId.value !== null);

const resetForm = () => {
    form.reset();
    form.clearErrors();
    form.provider = 'sicredi';
    form.company_id = props.companies[0]?.id ?? '';
    form.name = 'Sicredi Pix';
    form.environment = 'sandbox';
    form.capabilities = ['pix.immediate', 'pix.due', 'pix.refund', 'webhooks'];
    form.client_id = '';
    form.client_secret = '';
    form.pix_key = '';
    form.certificate = null;
    form.certificate_chain = null;
    form.private_key = null;
    form.private_key_passphrase = '';
    form.webhook_secret = '';
};

const changeProvider = () => {
    const provider = selectedProvider.value;

    if (!provider) {
        return;
    }

    form.name = provider.default_name;
    form.capabilities = [...provider.capabilities];
};

const openCreate = () => {
    editingId.value = null;
    resetForm();
    showForm.value = true;
    window.scrollTo({ top: 0, behavior: 'smooth' });
};

const openEdit = (connection: Connection) => {
    resetForm();
    editingId.value = connection.id;
    form.provider = connection.provider as ProviderKey;
    form.company_id = connection.company_id;
    form.name = connection.name;
    form.environment = connection.environment;
    form.capabilities = [...connection.capabilities];
    showForm.value = true;
    window.scrollTo({ top: 0, behavior: 'smooth' });
};

const closeForm = () => {
    showForm.value = false;
    editingId.value = null;
    resetForm();
};

const toggleCapability = (capability: string) => {
    form.capabilities = form.capabilities.includes(capability)
        ? form.capabilities.filter((item) => item !== capability)
        : [...form.capabilities, capability];
};

const setFile = (
    field: 'certificate' | 'certificate_chain' | 'private_key',
    event: Event,
) => {
    const input = event.target as HTMLInputElement;
    form[field] = input.files?.[0] ?? null;
};

const submit = () => {
    const options = {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => closeForm(),
    };

    if (editingId.value) {
        form.transform((data) => ({ ...data, _method: 'patch' })).post(
            `/bank-connections/${editingId.value}`,
            {
                ...options,
                onFinish: () => form.transform((data) => data),
            },
        );

        return;
    }

    form.post('/bank-connections', options);
};

const testConnection = (connection: Connection) => {
    testingId.value = connection.id;
    router.post(
        `/bank-connections/${connection.id}/test`,
        {},
        {
            preserveScroll: true,
            onFinish: () => (testingId.value = null),
        },
    );
};

const syncConnection = (connection: Connection) => {
    syncingId.value = connection.id;
    router.post(
        `/bank-connections/${connection.id}/sync`,
        {},
        {
            preserveScroll: true,
            onFinish: () => (syncingId.value = null),
        },
    );
};

const statusLabel = (status: string) =>
    ({
        active: 'Conectada',
        draft: 'Aguardando teste',
        action_required: 'Requer atenção',
        degraded: 'Instável',
    })[status] ?? status;

const statusClass = (status: string) => {
    if (status === 'active') {
        return 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-900 dark:bg-emerald-950/40 dark:text-emerald-300';
    }

    if (['action_required', 'degraded'].includes(status)) {
        return 'border-rose-200 bg-rose-50 text-rose-700 dark:border-rose-900 dark:bg-rose-950/40 dark:text-rose-300';
    }

    return 'border-amber-200 bg-amber-50 text-amber-700 dark:border-amber-900 dark:bg-amber-950/40 dark:text-amber-300';
};

const featureLabel = (capability: string) =>
    featureOptions.find((feature) => feature.value === capability)?.title ??
    capability;

const providerLabel = (provider: string) =>
    props.providers.find((option) => option.value === provider)?.label ??
    provider;

const environmentLabel = (connection: Connection) =>
    props.presets[connection.provider as ProviderKey]?.[connection.environment]
        ?.label ?? connection.environment;

const formatDate = (value?: string | null) =>
    value ? new Date(value).toLocaleString('pt-BR') : 'Ainda não executado';
</script>

<template>
    <Head title="Conexões bancárias" />
    <div class="flex flex-1 flex-col gap-6 p-4 md:p-7">
        <header
            class="flex flex-col justify-between gap-4 lg:flex-row lg:items-end"
        >
            <div class="max-w-3xl">
                <p
                    class="text-sm font-medium text-emerald-700 dark:text-emerald-400"
                >
                    Integrações bancárias
                </p>
                <h1 class="mt-1 text-2xl font-semibold tracking-tight">
                    Conexões bancárias
                </h1>
                <p class="mt-2 text-sm leading-6 text-muted-foreground">
                    Configure APIs Pix bancárias, valide o certificado mTLS e
                    teste o OAuth2 antes de liberar operações reais.
                </p>
            </div>
            <button
                v-if="!showForm"
                class="inline-flex h-10 items-center justify-center gap-2 rounded-lg bg-emerald-700 px-4 text-sm font-medium text-white shadow-sm hover:bg-emerald-800"
                @click="openCreate"
            >
                <Plus class="size-4" /> Nova conexão
            </button>
        </header>

        <section
            v-if="showForm"
            class="overflow-hidden rounded-2xl border bg-card shadow-sm"
        >
            <div
                class="flex items-start justify-between gap-4 border-b bg-muted/30 px-5 py-5 md:px-7"
            >
                <div class="flex gap-3">
                    <div
                        class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-emerald-600 text-white"
                    >
                        <ShieldCheck class="size-5" />
                    </div>
                    <div>
                        <h2 class="font-semibold">
                            {{
                                isEditing
                                    ? 'Substituir credenciais'
                                    : `Configurar API Pix ${selectedProvider?.label || ''}`
                            }}
                        </h2>
                        <p class="mt-1 text-sm text-muted-foreground">
                            Os arquivos e segredos serão criptografados e não
                            voltarão a ser exibidos.
                        </p>
                    </div>
                </div>
                <button
                    class="rounded-lg p-2 text-muted-foreground hover:bg-muted"
                    title="Fechar"
                    @click="closeForm"
                >
                    <X class="size-4" />
                </button>
            </div>

            <form class="space-y-8 p-5 md:p-7" @submit.prevent="submit">
                <div class="grid gap-8 xl:grid-cols-[1fr_18rem]">
                    <div class="space-y-8">
                        <fieldset class="space-y-4">
                            <legend
                                class="flex items-center gap-2 font-semibold"
                            >
                                <span class="step-number">1</span> Identificação
                            </legend>
                            <div class="grid gap-4 md:grid-cols-2">
                                <label class="field">
                                    Banco
                                    <select
                                        v-model="form.provider"
                                        required
                                        :disabled="isEditing"
                                        @change="changeProvider"
                                    >
                                        <option
                                            v-for="provider in providers"
                                            :key="provider.value"
                                            :value="provider.value"
                                        >
                                            {{ provider.label }}
                                        </option>
                                    </select>
                                    <span
                                        v-if="form.errors.provider"
                                        class="field-error"
                                        >{{ form.errors.provider }}</span
                                    >
                                </label>
                                <label class="field">
                                    Empresa
                                    <select v-model="form.company_id" required>
                                        <option value="" disabled>
                                            Selecione uma empresa
                                        </option>
                                        <option
                                            v-for="company in companies"
                                            :key="company.id"
                                            :value="company.id"
                                        >
                                            {{
                                                company.trade_name ||
                                                company.legal_name
                                            }}
                                        </option>
                                    </select>
                                    <span
                                        v-if="form.errors.company_id"
                                        class="field-error"
                                        >{{ form.errors.company_id }}</span
                                    >
                                </label>
                                <label class="field">
                                    Nome da conexão
                                    <input
                                        v-model="form.name"
                                        required
                                        :placeholder="`Ex.: ${selectedProvider?.label || 'Banco'} Matriz`"
                                    />
                                    <span
                                        v-if="form.errors.name"
                                        class="field-error"
                                        >{{ form.errors.name }}</span
                                    >
                                </label>
                            </div>
                            <div class="grid gap-3 sm:grid-cols-2">
                                <label
                                    v-for="(preset, key) in presets[
                                        form.provider
                                    ]"
                                    :key="key"
                                    class="flex cursor-pointer items-start gap-3 rounded-xl border p-4 transition-colors"
                                    :class="
                                        form.environment === key
                                            ? 'border-emerald-500 bg-emerald-50/70 dark:bg-emerald-950/20'
                                            : 'hover:bg-muted/40'
                                    "
                                >
                                    <input
                                        v-model="form.environment"
                                        type="radio"
                                        :value="key"
                                        class="mt-1 accent-emerald-700"
                                    />
                                    <span>
                                        <span
                                            class="block text-sm font-semibold"
                                            >{{ preset.label }}</span
                                        >
                                        <span
                                            class="mt-1 block text-xs leading-5 text-muted-foreground"
                                        >
                                            {{
                                                key === 'sandbox'
                                                    ? 'Para homologar credenciais e cobranças de teste.'
                                                    : 'Use somente após concluir a homologação.'
                                            }}
                                        </span>
                                    </span>
                                </label>
                            </div>
                        </fieldset>

                        <fieldset class="space-y-4">
                            <legend
                                class="flex items-center gap-2 font-semibold"
                            >
                                <span class="step-number">2</span>
                                Funcionalidades e escopos
                            </legend>
                            <div class="grid gap-3 md:grid-cols-2">
                                <label
                                    v-for="feature in availableFeatureOptions"
                                    :key="feature.value"
                                    class="flex cursor-pointer gap-3 rounded-xl border p-4 hover:bg-muted/30"
                                    :class="
                                        form.capabilities.includes(
                                            feature.value,
                                        )
                                            ? 'border-emerald-400'
                                            : ''
                                    "
                                >
                                    <input
                                        type="checkbox"
                                        class="mt-1 accent-emerald-700"
                                        :checked="
                                            form.capabilities.includes(
                                                feature.value,
                                            )
                                        "
                                        @change="
                                            toggleCapability(feature.value)
                                        "
                                    />
                                    <span>
                                        <span
                                            class="block text-sm font-medium"
                                            >{{ feature.title }}</span
                                        >
                                        <span
                                            class="mt-1 block text-xs leading-5 text-muted-foreground"
                                            >{{ feature.description }}</span
                                        >
                                        <span
                                            class="mt-2 block font-mono text-[11px] text-emerald-700 dark:text-emerald-400"
                                            >{{ feature.scopes }}</span
                                        >
                                    </span>
                                </label>
                            </div>
                            <span
                                v-if="form.errors.capabilities"
                                class="field-error"
                                >{{ form.errors.capabilities }}</span
                            >
                        </fieldset>

                        <fieldset class="space-y-4">
                            <legend
                                class="flex items-center gap-2 font-semibold"
                            >
                                <span class="step-number">3</span> Credenciais
                                OAuth2
                            </legend>
                            <div class="grid gap-4 md:grid-cols-2">
                                <label class="field">
                                    Client ID
                                    <input
                                        v-model="form.client_id"
                                        required
                                        autocomplete="off"
                                    />
                                    <span
                                        v-if="form.errors.client_id"
                                        class="field-error"
                                        >{{ form.errors.client_id }}</span
                                    >
                                </label>
                                <label class="field">
                                    Client secret
                                    <input
                                        v-model="form.client_secret"
                                        required
                                        type="password"
                                        autocomplete="new-password"
                                    />
                                    <span
                                        v-if="form.errors.client_secret"
                                        class="field-error"
                                        >{{ form.errors.client_secret }}</span
                                    >
                                </label>
                                <label class="field md:col-span-2">
                                    Chave Pix padrão
                                    <span
                                        class="font-normal text-muted-foreground"
                                        >(opcional)</span
                                    >
                                    <input
                                        v-model="form.pix_key"
                                        placeholder="E-mail, telefone, CPF/CNPJ ou chave aleatória"
                                    />
                                    <span
                                        v-if="form.errors.pix_key"
                                        class="field-error"
                                        >{{ form.errors.pix_key }}</span
                                    >
                                </label>
                                <label
                                    v-if="
                                        form.provider === 'bradesco' &&
                                        form.capabilities.includes('webhooks')
                                    "
                                    class="field md:col-span-2"
                                >
                                    Segredo interno do edge para callbacks
                                    <input
                                        v-model="form.webhook_secret"
                                        required
                                        type="password"
                                        minlength="24"
                                        autocomplete="new-password"
                                    />
                                    <span
                                        class="font-normal text-muted-foreground"
                                    >
                                        O proxy que valida o mTLS do Bradesco
                                        deve injetar este valor como Bearer
                                        antes de encaminhar o callback.
                                    </span>
                                    <span
                                        v-if="form.errors.webhook_secret"
                                        class="field-error"
                                        >{{ form.errors.webhook_secret }}</span
                                    >
                                </label>
                            </div>
                        </fieldset>

                        <fieldset class="space-y-4">
                            <legend
                                class="flex items-center gap-2 font-semibold"
                            >
                                <span class="step-number">4</span> Certificado
                                mTLS
                            </legend>
                            <div class="grid gap-4 md:grid-cols-3">
                                <label class="file-field">
                                    <FileKey class="size-5 text-emerald-600" />
                                    <span class="text-sm font-medium"
                                        >Certificado da aplicação</span
                                    >
                                    <span class="text-xs text-muted-foreground"
                                        >.CER, .CRT ou .PEM</span
                                    >
                                    <span
                                        class="mt-1 max-w-full truncate text-xs font-medium text-emerald-700"
                                        >{{
                                            form.certificate?.name ||
                                            'Selecionar arquivo'
                                        }}</span
                                    >
                                    <input
                                        required
                                        type="file"
                                        accept=".cer,.crt,.pem"
                                        @change="setFile('certificate', $event)"
                                    />
                                </label>
                                <label class="file-field">
                                    <ShieldCheck
                                        class="size-5 text-emerald-600"
                                    />
                                    <span class="text-sm font-medium"
                                        >Cadeia completa</span
                                    >
                                    <span class="text-xs text-muted-foreground"
                                        >Opcional, recomendada</span
                                    >
                                    <span
                                        class="mt-1 max-w-full truncate text-xs font-medium text-emerald-700"
                                        >{{
                                            form.certificate_chain?.name ||
                                            'Selecionar arquivo'
                                        }}</span
                                    >
                                    <input
                                        type="file"
                                        accept=".cer,.crt,.pem"
                                        @change="
                                            setFile('certificate_chain', $event)
                                        "
                                    />
                                </label>
                                <label class="file-field">
                                    <KeyRound class="size-5 text-emerald-600" />
                                    <span class="text-sm font-medium"
                                        >Chave privada</span
                                    >
                                    <span class="text-xs text-muted-foreground"
                                        >.KEY ou .PEM</span
                                    >
                                    <span
                                        class="mt-1 max-w-full truncate text-xs font-medium text-emerald-700"
                                        >{{
                                            form.private_key?.name ||
                                            'Selecionar arquivo'
                                        }}</span
                                    >
                                    <input
                                        required
                                        type="file"
                                        accept=".key,.pem"
                                        @change="setFile('private_key', $event)"
                                    />
                                </label>
                            </div>
                            <div class="grid gap-1">
                                <span
                                    v-if="form.errors.certificate"
                                    class="field-error"
                                    >{{ form.errors.certificate }}</span
                                >
                                <span
                                    v-if="form.errors.certificate_chain"
                                    class="field-error"
                                    >{{ form.errors.certificate_chain }}</span
                                >
                                <span
                                    v-if="form.errors.private_key"
                                    class="field-error"
                                    >{{ form.errors.private_key }}</span
                                >
                            </div>
                            <label class="field max-w-xl">
                                Frase secreta da chave
                                <span class="font-normal text-muted-foreground"
                                    >(se houver)</span
                                >
                                <input
                                    v-model="form.private_key_passphrase"
                                    type="password"
                                    autocomplete="new-password"
                                />
                                <span
                                    v-if="form.errors.private_key_passphrase"
                                    class="field-error"
                                    >{{
                                        form.errors.private_key_passphrase
                                    }}</span
                                >
                            </label>
                        </fieldset>
                    </div>

                    <aside class="space-y-4">
                        <div class="rounded-xl border bg-muted/30 p-4">
                            <div
                                class="flex items-center gap-2 text-sm font-semibold"
                            >
                                <Lock class="size-4 text-emerald-600" />
                                Ambiente selecionado
                            </div>
                            <dl class="mt-4 space-y-4 text-xs">
                                <div>
                                    <dt class="text-muted-foreground">
                                        Servidor OAuth2
                                    </dt>
                                    <dd
                                        class="mt-1 font-mono leading-5 break-all"
                                    >
                                        {{ selectedPreset?.token_url }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-muted-foreground">
                                        Servidor de recursos
                                    </dt>
                                    <dd
                                        class="mt-1 font-mono leading-5 break-all"
                                    >
                                        {{ selectedPreset?.base_url }}
                                    </dd>
                                </div>
                            </dl>
                        </div>
                        <div
                            class="rounded-xl border border-amber-200 bg-amber-50 p-4 text-amber-900 dark:border-amber-900 dark:bg-amber-950/30 dark:text-amber-200"
                        >
                            <div class="flex gap-2">
                                <AlertCircle class="mt-0.5 size-4 shrink-0" />
                                <p class="text-xs leading-5">
                                    O teste solicita somente um token. Nenhuma
                                    cobrança ou movimentação financeira é
                                    criada.
                                </p>
                            </div>
                        </div>
                        <a
                            :href="selectedProvider?.portal_url"
                            target="_blank"
                            rel="noreferrer"
                            class="flex items-center justify-between rounded-xl border p-4 text-sm font-medium hover:bg-muted/40"
                        >
                            Portal do Desenvolvedor
                            <ExternalLink class="size-4" />
                        </a>
                    </aside>
                </div>

                <div
                    class="flex flex-col-reverse justify-end gap-3 border-t pt-6 sm:flex-row"
                >
                    <button
                        type="button"
                        class="h-10 rounded-lg border px-4 text-sm font-medium hover:bg-muted"
                        @click="closeForm"
                    >
                        Cancelar
                    </button>
                    <button
                        type="submit"
                        class="inline-flex h-10 items-center justify-center gap-2 rounded-lg bg-emerald-700 px-5 text-sm font-medium text-white hover:bg-emerald-800 disabled:opacity-60"
                        :disabled="form.processing"
                    >
                        <LoaderCircle
                            v-if="form.processing"
                            class="size-4 animate-spin"
                        />
                        {{
                            isEditing
                                ? 'Salvar novas credenciais'
                                : 'Salvar e continuar'
                        }}
                    </button>
                </div>
            </form>
        </section>

        <section v-if="!showForm" class="grid gap-4 xl:grid-cols-2">
            <article
                v-for="connection in connections"
                :key="connection.id"
                class="overflow-hidden rounded-2xl border bg-card shadow-sm"
            >
                <div
                    class="flex items-start justify-between gap-4 border-b px-5 py-5"
                >
                    <div class="flex min-w-0 items-start gap-3">
                        <div
                            class="flex size-11 shrink-0 items-center justify-center rounded-xl bg-emerald-700 text-white"
                        >
                            <Landmark class="size-5" />
                        </div>
                        <div class="min-w-0">
                            <h2 class="truncate font-semibold">
                                {{ connection.name }}
                            </h2>
                            <p
                                class="mt-1 truncate text-sm text-muted-foreground"
                            >
                                {{ providerLabel(connection.provider) }} ·
                                {{ connection.company_name }}
                            </p>
                        </div>
                    </div>
                    <span
                        class="shrink-0 rounded-full border px-2.5 py-1 text-xs font-medium"
                        :class="statusClass(connection.status)"
                    >
                        {{ statusLabel(connection.status) }}
                    </span>
                </div>

                <div class="space-y-5 p-5">
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-xs text-muted-foreground">
                                Ambiente
                            </p>
                            <p class="mt-1 font-medium">
                                {{ environmentLabel(connection) }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-muted-foreground">
                                Client ID
                            </p>
                            <p class="mt-1 font-mono text-xs">
                                {{
                                    connection.client_id_hint ||
                                    'Não configurado'
                                }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-muted-foreground">
                                Certificado válido até
                            </p>
                            <p class="mt-1 font-medium">
                                {{
                                    connection.certificate_expires_at
                                        ? new Date(
                                              connection.certificate_expires_at,
                                          ).toLocaleDateString('pt-BR')
                                        : 'Não informado'
                                }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-muted-foreground">
                                Último teste
                            </p>
                            <p class="mt-1 font-medium">
                                {{ formatDate(connection.last_test_at) }}
                            </p>
                        </div>
                    </div>

                    <div>
                        <p class="text-xs text-muted-foreground">
                            Funcionalidades liberadas
                        </p>
                        <div class="mt-2 flex flex-wrap gap-1.5">
                            <span
                                v-for="capability in connection.capabilities"
                                :key="capability"
                                class="rounded-md bg-muted px-2 py-1 text-xs"
                            >
                                {{ featureLabel(capability) }}
                            </span>
                            <span
                                v-if="!connection.capabilities.length"
                                class="text-xs text-muted-foreground"
                                >Nenhuma</span
                            >
                        </div>
                    </div>

                    <div v-if="connection.webhook_url">
                        <p class="text-xs text-muted-foreground">
                            URL de callback Pix
                        </p>
                        <p class="mt-2 font-mono text-xs break-all">
                            {{ connection.webhook_url }}
                        </p>
                    </div>

                    <div
                        v-if="connection.last_test?.status === 'passed'"
                        class="flex gap-3 rounded-xl border border-emerald-200 bg-emerald-50 p-3 text-emerald-800 dark:border-emerald-900 dark:bg-emerald-950/30 dark:text-emerald-200"
                    >
                        <CheckCircle2 class="mt-0.5 size-4 shrink-0" />
                        <div class="text-xs leading-5">
                            <p class="font-semibold">Autenticação validada</p>
                            <p>
                                Token
                                {{
                                    connection.last_test.token_type || 'Bearer'
                                }}
                                aceito · validade de
                                {{ connection.last_test.expires_in || '—' }}
                                segundos.
                            </p>
                        </div>
                    </div>
                    <div
                        v-else-if="connection.last_error"
                        class="flex gap-3 rounded-xl border border-rose-200 bg-rose-50 p-3 text-rose-800 dark:border-rose-900 dark:bg-rose-950/30 dark:text-rose-200"
                    >
                        <AlertCircle class="mt-0.5 size-4 shrink-0" />
                        <div class="min-w-0 text-xs leading-5">
                            <p class="font-semibold">O teste não passou</p>
                            <p class="break-words">
                                {{ connection.last_error }}
                            </p>
                            <p
                                v-if="connection.last_test?.http_status"
                                class="mt-1 font-mono"
                            >
                                HTTP {{ connection.last_test.http_status }}
                            </p>
                        </div>
                    </div>
                    <div
                        v-else
                        class="flex gap-3 rounded-xl border border-amber-200 bg-amber-50 p-3 text-amber-900 dark:border-amber-900 dark:bg-amber-950/30 dark:text-amber-200"
                    >
                        <Plug class="mt-0.5 size-4 shrink-0" />
                        <p class="text-xs leading-5">
                            Credenciais salvas. Execute o teste para validar
                            mTLS e OAuth2.
                        </p>
                    </div>

                    <div class="flex flex-wrap justify-end gap-2 border-t pt-4">
                        <button
                            class="inline-flex h-9 items-center gap-2 rounded-lg border px-3 text-sm font-medium hover:bg-muted"
                            @click="openEdit(connection)"
                        >
                            <Pencil class="size-3.5" /> Credenciais
                        </button>
                        <button
                            v-if="
                                connection.can_sync &&
                                connection.status === 'active'
                            "
                            class="inline-flex h-9 items-center gap-2 rounded-lg border px-3 text-sm font-medium hover:bg-muted disabled:opacity-60"
                            :disabled="syncingId === connection.id"
                            @click="syncConnection(connection)"
                        >
                            <RefreshCw
                                class="size-3.5"
                                :class="
                                    syncingId === connection.id
                                        ? 'animate-spin'
                                        : ''
                                "
                            />
                            Sincronizar
                        </button>
                        <Link
                            v-if="connection.status === 'active'"
                            href="/pix"
                            class="inline-flex h-9 items-center gap-2 rounded-lg border px-3 text-sm font-medium hover:bg-muted"
                        >
                            Ir para Pix
                        </Link>
                        <button
                            class="inline-flex h-9 items-center gap-2 rounded-lg bg-emerald-700 px-3 text-sm font-medium text-white hover:bg-emerald-800 disabled:opacity-60"
                            :disabled="
                                testingId === connection.id ||
                                !connection.configured
                            "
                            @click="testConnection(connection)"
                        >
                            <LoaderCircle
                                v-if="testingId === connection.id"
                                class="size-3.5 animate-spin"
                            />
                            <Plug v-else class="size-3.5" />
                            Testar autenticação
                        </button>
                    </div>
                </div>
            </article>

            <div
                v-if="!connections.length"
                class="col-span-full rounded-2xl border border-dashed bg-card px-6 py-16 text-center"
            >
                <div
                    class="mx-auto flex size-12 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300"
                >
                    <Landmark class="size-6" />
                </div>
                <h2 class="mt-4 font-semibold">Nenhuma conexão configurada</h2>
                <p
                    class="mx-auto mt-2 max-w-md text-sm leading-6 text-muted-foreground"
                >
                    Comece pela homologação da API Pix. Você precisará do Client
                    ID, Client secret, certificado e chave privada emitidos para
                    a aplicação.
                </p>
                <button
                    class="mt-5 inline-flex h-10 items-center gap-2 rounded-lg bg-emerald-700 px-4 text-sm font-medium text-white hover:bg-emerald-800"
                    @click="openCreate"
                >
                    <Plus class="size-4" /> Configurar banco
                </button>
            </div>
        </section>

        <section v-if="!showForm" class="grid gap-4 lg:grid-cols-3">
            <article class="rounded-xl border bg-card p-5">
                <div
                    class="flex size-9 items-center justify-center rounded-lg bg-muted"
                >
                    <KeyRound class="size-4" />
                </div>
                <h2 class="mt-4 text-sm font-semibold">
                    1. Obtenha as credenciais
                </h2>
                <p class="mt-2 text-xs leading-5 text-muted-foreground">
                    Faça a adesão no banco e gere as credenciais vinculadas ao
                    certificado no Portal do Desenvolvedor.
                </p>
            </article>
            <article class="rounded-xl border bg-card p-5">
                <div
                    class="flex size-9 items-center justify-center rounded-lg bg-muted"
                >
                    <FileKey class="size-4" />
                </div>
                <h2 class="mt-4 text-sm font-semibold">
                    2. Confira o par mTLS
                </h2>
                <p class="mt-2 text-xs leading-5 text-muted-foreground">
                    Ao salvar, verificamos formato, validade e se a chave
                    privada realmente corresponde ao certificado.
                </p>
            </article>
            <article class="rounded-xl border bg-card p-5">
                <div
                    class="flex size-9 items-center justify-center rounded-lg bg-muted"
                >
                    <Plug class="size-4" />
                </div>
                <h2 class="mt-4 text-sm font-semibold">
                    3. Teste antes de operar
                </h2>
                <p class="mt-2 text-xs leading-5 text-muted-foreground">
                    A conexão só fica ativa após o banco aceitar o handshake
                    mTLS e emitir o token OAuth2.
                </p>
            </article>
        </section>
    </div>
</template>

<style scoped>
.step-number {
    display: inline-flex;
    width: 1.5rem;
    height: 1.5rem;
    align-items: center;
    justify-content: center;
    border-radius: 9999px;
    background: hsl(158 64% 30%);
    color: white;
    font-size: 0.75rem;
}

.field {
    display: flex;
    flex-direction: column;
    gap: 0.4rem;
    font-size: 0.875rem;
    font-weight: 500;
}

.field :is(input, select) {
    min-height: 2.5rem;
    border: 1px solid var(--border);
    border-radius: 0.5rem;
    background: var(--background);
    padding: 0.55rem 0.75rem;
    font-size: 0.875rem;
    font-weight: 400;
    outline: none;
}

.field :is(input, select):focus {
    border-color: hsl(158 64% 35%);
    box-shadow: 0 0 0 3px hsl(158 64% 35% / 12%);
}

.file-field {
    position: relative;
    display: flex;
    min-height: 9rem;
    cursor: pointer;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 0.35rem;
    overflow: hidden;
    border: 1px dashed var(--border);
    border-radius: 0.75rem;
    padding: 1rem;
    text-align: center;
}

.file-field:hover {
    background: color-mix(in srgb, var(--muted) 50%, transparent);
}

.file-field input {
    position: absolute;
    inset: 0;
    cursor: pointer;
    opacity: 0;
}

.field-error {
    color: hsl(0 72% 51%);
    font-size: 0.75rem;
    font-weight: 400;
}
</style>
