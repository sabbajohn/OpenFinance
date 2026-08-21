<script setup lang="ts">
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import {
    AlertCircle,
    BookOpenCheck,
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
    ReceiptText,
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
type ProductKey = 'boleto' | 'pix';
type ProductOption = {
    value: ProductKey;
    label: string;
    default_name: string;
    documentation_url?: string;
    contract: string;
    auth_mode: 'oauth_password' | 'mtls_client_credentials';
    capabilities: string[];
};
type ProviderOption = {
    value: ProviderKey;
    label: string;
    portal_url: string;
    products: ProductOption[];
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
    product: ProductKey;
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
    credential_hint?: string | null;
    scope?: string | null;
    webhook_url?: string | null;
    can_sync: boolean;
};

const props = defineProps<{
    access: { manage: boolean; test: boolean; sync: boolean };
    connections: Connection[];
    companies: Company[];
    providers: ProviderOption[];
    presets: Record<
        ProviderKey,
        Partial<
            Record<
                ProductKey,
                Record<'sandbox' | 'production', EnvironmentPreset>
            >
        >
    >;
}>();

const featureOptions = [
    {
        value: 'boleto.normal',
        title: 'Boleto tradicional',
        description: 'Emitir boletos com código de barras e linha digitável.',
        resources: {
            sicredi: 'Cobrança NORMAL',
            bradesco: 'Cobrança v1.7.2',
        },
    },
    {
        value: 'boleto.hybrid',
        title: 'Boleto híbrido',
        description: 'Emitir boleto com QR Code Pix integrado.',
        resources: {
            sicredi: 'Cobrança HÍBRIDA',
            bradesco: 'Cobrança com QR Code v1.8.3',
        },
    },
    {
        value: 'pix.immediate',
        title: 'Pix imediato',
        description: 'Criar e consultar cobranças sem vencimento.',
        resources: {
            sicredi: 'cob.read · cob.write',
            bradesco: 'PIX Cobranças Imediatas 2.0.0',
        },
    },
    {
        value: 'pix.due',
        title: 'Pix com vencimento',
        description: 'Criar e consultar cobranças com data de vencimento.',
        resources: {
            sicredi: 'cobv.read · cobv.write',
            bradesco: 'PIX Cobrança com Vencimento 2.0.0',
        },
    },
    {
        value: 'pix.refund',
        title: 'Consulta e devolução',
        description:
            'Sincronizar Pix recebidos, conciliar pagamentos e solicitar devoluções.',
        resources: {
            sicredi: 'pix.read · pix.write',
            bradesco: 'PIX Gerenciamento de Recebidos 2.0.1',
        },
    },
    {
        value: 'webhooks',
        title: 'Webhooks',
        description: 'Cadastrar e consultar notificações por chave Pix.',
        resources: {
            sicredi: 'webhook.read · webhook.write',
            bradesco: 'Callback protegido por mTLS no edge',
        },
    },
];

const showForm = ref(false);
const editingId = ref<string | null>(null);
const testingId = ref<string | null>(null);
const syncingId = ref<string | null>(null);
const form = useForm({
    provider: 'sicredi' as ProviderKey,
    product: 'boleto' as ProductKey,
    company_id: props.companies[0]?.id ?? '',
    name: 'Sicredi Cobrança',
    environment: 'sandbox' as 'sandbox' | 'production',
    capabilities: ['boleto.normal', 'boleto.hybrid'],
    client_id: '',
    client_secret: '',
    x_api_key: '',
    beneficiary_code: '12345',
    cooperative_code: '6789',
    branch_code: '03',
    access_code: 'teste123',
    wallet_code: '',
    negotiation_number: '',
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
const selectedProduct = computed(
    () =>
        selectedProvider.value?.products.find(
            (product) => product.value === form.product,
        ) ?? selectedProvider.value?.products[0],
);
const availableFeatureOptions = computed(() =>
    featureOptions.filter((feature) =>
        selectedProduct.value?.capabilities.includes(feature.value),
    ),
);
const environmentPresets = computed<Record<string, EnvironmentPreset>>(
    () => props.presets[form.provider]?.[form.product] ?? {},
);
const selectedPreset = computed(
    () => environmentPresets.value[form.environment],
);
const isEditing = computed(() => editingId.value !== null);
const usesMtls = computed(
    () => selectedProduct.value?.auth_mode === 'mtls_client_credentials',
);
const requiresPixKey = computed(
    () =>
        form.provider === 'sicredi' &&
        form.product === 'pix' &&
        form.capabilities.some((capability) =>
            ['pix.immediate', 'pix.due', 'webhooks'].includes(capability),
        ),
);

const applySandboxDefaults = () => {
    if (
        form.provider === 'sicredi' &&
        form.product === 'boleto' &&
        form.environment === 'sandbox'
    ) {
        form.beneficiary_code ||= '12345';
        form.cooperative_code ||= '6789';
        form.branch_code ||= '03';
        form.access_code ||= 'teste123';
    }
};

const changeEnvironment = () => {
    if (
        form.provider === 'sicredi' &&
        form.product === 'boleto' &&
        form.environment === 'production'
    ) {
        if (form.beneficiary_code === '12345') {
            form.beneficiary_code = '';
        }

        if (form.cooperative_code === '6789') {
            form.cooperative_code = '';
        }

        if (form.branch_code === '03') {
            form.branch_code = '';
        }

        if (form.access_code === 'teste123') {
            form.access_code = '';
        }

        return;
    }

    applySandboxDefaults();
};

const resetForm = () => {
    form.reset();
    form.clearErrors();
    form.provider = 'sicredi';
    form.product = 'boleto';
    form.company_id = props.companies[0]?.id ?? '';
    form.name = 'Sicredi Cobrança';
    form.environment = 'sandbox';
    form.capabilities = ['boleto.normal', 'boleto.hybrid'];
    form.client_id = '';
    form.client_secret = '';
    form.x_api_key = '';
    form.beneficiary_code = '12345';
    form.cooperative_code = '6789';
    form.branch_code = '03';
    form.access_code = 'teste123';
    form.wallet_code = '';
    form.negotiation_number = '';
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

    form.product = provider.products[0]?.value ?? 'pix';
    changeProduct();
};

const changeProduct = () => {
    const product = selectedProduct.value;

    if (!product) {
        return;
    }

    form.name = product.default_name;
    form.capabilities = [...product.capabilities];
    form.clearErrors();
    applySandboxDefaults();
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
    form.product = connection.product;
    form.company_id = connection.company_id;
    form.name = connection.name;
    form.environment = connection.environment;
    form.capabilities = [...connection.capabilities];
    form.x_api_key = '';
    form.beneficiary_code = '';
    form.cooperative_code = '';
    form.branch_code = '';
    form.access_code = '';
    form.wallet_code = '';
    form.negotiation_number = '';
    applySandboxDefaults();
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
    props.presets[connection.provider as ProviderKey]?.[connection.product]?.[
        connection.environment
    ]?.label ?? connection.environment;

const productLabel = (connection: Connection) =>
    props.providers
        .find((provider) => provider.value === connection.provider)
        ?.products.find((product) => product.value === connection.product)
        ?.label ?? connection.product;

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
                    Configure Cobrança ou Pix com o método de autenticação
                    exigido por cada produto e valide tudo no Sandbox.
                </p>
            </div>
            <button
                v-if="!showForm && access.manage"
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
                                    : `Configurar ${selectedProduct?.label || ''} ${selectedProvider?.label || ''}`
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
                                    Produto
                                    <select
                                        v-model="form.product"
                                        required
                                        @change="changeProduct"
                                    >
                                        <option
                                            v-for="product in selectedProvider?.products ||
                                            []"
                                            :key="product.value"
                                            :value="product.value"
                                        >
                                            {{ product.label }}
                                        </option>
                                    </select>
                                    <span
                                        v-if="form.errors.product"
                                        class="field-error"
                                        >{{ form.errors.product }}</span
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
                                    v-for="(preset, key) in environmentPresets"
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
                                        @change="changeEnvironment"
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
                                Funcionalidades cobertas
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
                                            >{{
                                                feature.resources[form.provider]
                                            }}</span
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
                                <span class="step-number">3</span>
                                {{
                                    usesMtls
                                        ? 'Credenciais OAuth2'
                                        : 'Credenciais da Cobrança'
                                }}
                            </legend>
                            <div
                                v-if="usesMtls"
                                class="grid gap-4 md:grid-cols-2"
                            >
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
                                <label
                                    v-if="form.product === 'pix'"
                                    class="field md:col-span-2"
                                >
                                    Chave Pix padrão
                                    <span
                                        v-if="!requiresPixKey"
                                        class="font-normal text-muted-foreground"
                                        >(opcional)</span
                                    >
                                    <input
                                        v-model="form.pix_key"
                                        :required="requiresPixKey"
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
                                        form.product === 'boleto'
                                    "
                                    class="field"
                                >
                                    Carteira de cobrança
                                    <input
                                        v-model="form.wallet_code"
                                        required
                                        inputmode="numeric"
                                        maxlength="2"
                                        placeholder="1 ou 2 dígitos"
                                    />
                                    <span
                                        v-if="form.errors.wallet_code"
                                        class="field-error"
                                        >{{ form.errors.wallet_code }}</span
                                    >
                                </label>
                                <label
                                    v-if="
                                        form.provider === 'bradesco' &&
                                        form.product === 'boleto'
                                    "
                                    class="field"
                                >
                                    Número da negociação
                                    <input
                                        v-model="form.negotiation_number"
                                        required
                                        inputmode="numeric"
                                        maxlength="18"
                                        placeholder="18 dígitos"
                                    />
                                    <span
                                        v-if="form.errors.negotiation_number"
                                        class="field-error"
                                        >{{
                                            form.errors.negotiation_number
                                        }}</span
                                    >
                                </label>
                                <label
                                    v-if="
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
                                        O proxy que valida o certificado mTLS do
                                        banco deve injetar este valor como
                                        Bearer antes de encaminhar o callback.
                                    </span>
                                    <span
                                        v-if="form.errors.webhook_secret"
                                        class="field-error"
                                        >{{ form.errors.webhook_secret }}</span
                                    >
                                </label>
                            </div>
                            <div v-else class="grid gap-4 md:grid-cols-2">
                                <label class="field md:col-span-2">
                                    x-api-key
                                    <input
                                        v-model="form.x_api_key"
                                        required
                                        type="password"
                                        autocomplete="new-password"
                                        placeholder="Liberada nos detalhes da aplicação"
                                    />
                                    <span
                                        v-if="form.errors.x_api_key"
                                        class="field-error"
                                        >{{ form.errors.x_api_key }}</span
                                    >
                                </label>
                                <label class="field">
                                    Código do beneficiário
                                    <input
                                        v-model="form.beneficiary_code"
                                        required
                                        inputmode="numeric"
                                        maxlength="5"
                                        placeholder="5 dígitos"
                                    />
                                    <span
                                        v-if="form.errors.beneficiary_code"
                                        class="field-error"
                                        >{{
                                            form.errors.beneficiary_code
                                        }}</span
                                    >
                                </label>
                                <label class="field">
                                    Cooperativa
                                    <input
                                        v-model="form.cooperative_code"
                                        required
                                        inputmode="numeric"
                                        maxlength="4"
                                        placeholder="4 dígitos"
                                    />
                                    <span
                                        v-if="form.errors.cooperative_code"
                                        class="field-error"
                                        >{{
                                            form.errors.cooperative_code
                                        }}</span
                                    >
                                </label>
                                <label class="field">
                                    Posto
                                    <input
                                        v-model="form.branch_code"
                                        required
                                        inputmode="numeric"
                                        maxlength="2"
                                        placeholder="2 dígitos"
                                    />
                                    <span
                                        v-if="form.errors.branch_code"
                                        class="field-error"
                                        >{{ form.errors.branch_code }}</span
                                    >
                                </label>
                                <label class="field">
                                    Código de acesso
                                    <input
                                        v-model="form.access_code"
                                        required
                                        type="password"
                                        autocomplete="new-password"
                                    />
                                    <span
                                        v-if="form.errors.access_code"
                                        class="field-error"
                                        >{{ form.errors.access_code }}</span
                                    >
                                </label>
                                <p
                                    class="text-xs leading-5 text-muted-foreground md:col-span-2"
                                >
                                    No Sandbox, o Sicredi publica os códigos de
                                    teste. Em produção, use os dados da conta e
                                    o código de acesso gerado no Internet
                                    Banking.
                                </p>
                            </div>
                        </fieldset>

                        <fieldset v-if="usesMtls" class="space-y-4">
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
                            <p
                                v-if="form.provider === 'bradesco'"
                                class="text-xs leading-5 text-muted-foreground"
                            >
                                O Bradesco exige certificado A1 com razão social
                                e CNPJ no CN, validade mínima de 2 meses e
                                máxima de 3 anos. Em sandbox, o certificado pode
                                ser autoassinado.
                            </p>
                            <p
                                v-else-if="form.provider === 'sicredi'"
                                class="text-xs leading-5 text-muted-foreground"
                            >
                                Use o certificado emitido a partir do CSR no
                                Portal do Desenvolvedor. Se o .CER estiver em
                                DER, ele será convertido para PEM no envio.
                            </p>
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
                                        {{
                                            selectedPreset?.token_url ||
                                            'Fornecido pelo Sicredi após a liberação'
                                        }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-muted-foreground">
                                        Servidor de recursos
                                    </dt>
                                    <dd
                                        class="mt-1 font-mono leading-5 break-all"
                                    >
                                        {{
                                            selectedPreset?.base_url ||
                                            'Fornecido pelo Sicredi após a liberação'
                                        }}
                                    </dd>
                                </div>
                            </dl>
                            <p
                                v-if="
                                    form.provider === 'sicredi' &&
                                    form.product === 'pix' &&
                                    form.environment === 'sandbox'
                                "
                                class="mt-4 border-t pt-3 text-xs leading-5 text-muted-foreground"
                            >
                                As URLs de homologação não são públicas. Após o
                                chamado, configure
                                <code>SICREDI_PIX_HOMOLOGATION_BASE_URL</code> e
                                <code>SICREDI_PIX_HOMOLOGATION_TOKEN_URL</code>.
                            </p>
                        </div>
                        <div
                            v-if="selectedProduct?.documentation_url"
                            class="rounded-xl border border-sky-200 bg-sky-50 p-4 text-sky-950 dark:border-sky-900 dark:bg-sky-950/30 dark:text-sky-100"
                        >
                            <div class="flex gap-2">
                                <BookOpenCheck
                                    class="mt-0.5 size-4 shrink-0 text-sky-700 dark:text-sky-300"
                                />
                                <div class="text-xs leading-5">
                                    <p class="font-semibold">
                                        Contrato oficial validado
                                    </p>
                                    <p
                                        class="mt-1 text-sky-800 dark:text-sky-200"
                                    >
                                        {{ selectedProduct?.contract }}.
                                        {{
                                            usesMtls
                                                ? 'OAuth2 Client Credentials e certificado da aplicação via mTLS.'
                                                : 'OAuth2 Password com x-api-key, sem certificado mTLS.'
                                        }}
                                    </p>
                                    <a
                                        v-if="
                                            selectedProduct?.documentation_url
                                        "
                                        :href="
                                            selectedProduct.documentation_url
                                        "
                                        target="_blank"
                                        rel="noreferrer"
                                        class="mt-2 inline-flex items-center gap-1 font-medium underline underline-offset-2"
                                    >
                                        Abrir documentação oficial
                                        <ExternalLink class="size-3" />
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div
                            v-if="
                                form.provider === 'sicredi' &&
                                form.product === 'boleto' &&
                                form.environment === 'sandbox' &&
                                !form.x_api_key
                            "
                            class="rounded-xl border border-amber-200 bg-amber-50 p-4 text-amber-950 dark:border-amber-900 dark:bg-amber-950/30 dark:text-amber-100"
                        >
                            <div class="flex gap-2">
                                <ReceiptText class="mt-0.5 size-4 shrink-0" />
                                <div class="text-xs leading-5">
                                    <p class="font-semibold">
                                        Liberação do Sandbox em andamento
                                    </p>
                                    <p class="mt-1">
                                        Quando o Sicredi concluir o chamado,
                                        copie o x-api-key exibido em “Minhas
                                        Apps → detalhes” para finalizar esta
                                        conexão.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div
                            class="rounded-xl border border-amber-200 bg-amber-50 p-4 text-amber-900 dark:border-amber-900 dark:bg-amber-950/30 dark:text-amber-200"
                        >
                            <div class="flex gap-2">
                                <AlertCircle class="mt-0.5 size-4 shrink-0" />
                                <p class="text-xs leading-5">
                                    O teste solicita somente um token de acesso.
                                    Nenhuma cobrança ou movimentação financeira
                                    é criada.
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
                                : form.product === 'boleto'
                                  ? 'Salvar conexão de Cobrança'
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
                                {{ productLabel(connection) }} ·
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
                                {{
                                    connection.provider === 'sicredi' &&
                                    connection.product === 'boleto'
                                        ? 'x-api-key'
                                        : 'Client ID'
                                }}
                            </p>
                            <p class="mt-1 font-mono text-xs">
                                {{
                                    connection.credential_hint ||
                                    'Não configurado'
                                }}
                            </p>
                        </div>
                        <div v-if="connection.certificate_expires_at">
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
                            {{
                                connection.provider === 'sicredi' &&
                                connection.product === 'boleto'
                                    ? 'OAuth2 e x-api-key.'
                                    : 'mTLS e OAuth2.'
                            }}
                        </p>
                    </div>

                    <div class="flex flex-wrap justify-end gap-2 border-t pt-4">
                        <button
                            v-if="access.manage"
                            class="inline-flex h-9 items-center gap-2 rounded-lg border px-3 text-sm font-medium hover:bg-muted"
                            @click="openEdit(connection)"
                        >
                            <Pencil class="size-3.5" /> Credenciais
                        </button>
                        <button
                            v-if="
                                connection.can_sync &&
                                access.sync &&
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
                            {{
                                connection.capabilities.includes('pix.refund')
                                    ? 'Sincronizar Pix'
                                    : 'Sincronizar'
                            }}
                        </button>
                        <Link
                            v-if="
                                connection.capabilities.includes(
                                    'pix.refund',
                                ) &&
                                connection.can_sync &&
                                connection.status === 'active'
                            "
                            href="/bank-transactions"
                            class="inline-flex h-9 items-center gap-2 rounded-lg border px-3 text-sm font-medium hover:bg-muted"
                        >
                            Ver recebimentos
                        </Link>
                        <Link
                            v-if="
                                connection.product === 'pix' &&
                                connection.status === 'active'
                            "
                            href="/pix"
                            class="inline-flex h-9 items-center gap-2 rounded-lg border px-3 text-sm font-medium hover:bg-muted"
                        >
                            Ir para Pix
                        </Link>
                        <Link
                            v-if="
                                connection.product === 'boleto' &&
                                connection.status === 'active'
                            "
                            href="/boletos"
                            class="inline-flex h-9 items-center gap-2 rounded-lg border px-3 text-sm font-medium hover:bg-muted"
                        >
                            Ir para Boletos
                        </Link>
                        <button
                            v-if="access.test"
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
                        <Link
                            v-if="connection.environment === 'sandbox'"
                            :href="`/sandbox?connection=${connection.id}`"
                            class="inline-flex h-9 items-center gap-2 rounded-lg border border-emerald-300 px-3 text-sm font-medium text-emerald-800 hover:bg-emerald-50 dark:border-emerald-900 dark:text-emerald-300 dark:hover:bg-emerald-950/30"
                        >
                            Abrir Sandbox
                        </Link>
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
                    Comece pela API de Cobrança ou Pix. A tela solicitará apenas
                    as credenciais exigidas pelo produto e pelo ambiente
                    escolhidos.
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
                    Libere o produto no banco. Para Cobrança, obtenha o
                    x-api-key e o código de acesso; para Pix, emita o
                    certificado.
                </p>
            </article>
            <article class="rounded-xl border bg-card p-5">
                <div
                    class="flex size-9 items-center justify-center rounded-lg bg-muted"
                >
                    <FileKey class="size-4" />
                </div>
                <h2 class="mt-4 text-sm font-semibold">
                    2. Informe somente o necessário
                </h2>
                <p class="mt-2 text-xs leading-5 text-muted-foreground">
                    O formulário muda conforme o produto e mantém chaves, senhas
                    e certificados criptografados.
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
                    A conexão só fica ativa depois que o banco aceitar o método
                    de autenticação correto, sem gerar cobrança no teste.
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
