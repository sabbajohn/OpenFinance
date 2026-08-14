<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { GitCompareArrows, Landmark, ShieldCheck } from '@lucide/vue';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { home } from '@/routes';

defineProps<{
    title?: string;
    description?: string;
}>();

const page = usePage();
const name = page.props.name;
</script>

<template>
    <main class="min-h-svh bg-background lg:grid lg:grid-cols-[1.05fr_.95fr]">
        <aside
            class="relative hidden min-h-svh overflow-hidden bg-slate-950 p-10 text-slate-100 lg:flex lg:flex-col"
        >
            <div
                class="absolute -top-24 -left-24 size-96 rounded-full bg-emerald-500/10 blur-3xl"
            ></div>
            <div
                class="absolute right-0 bottom-0 size-80 rounded-full bg-sky-500/10 blur-3xl"
            ></div>

            <Link
                :href="home()"
                class="relative z-10 flex items-center gap-3 text-sm font-semibold"
            >
                <span
                    class="flex size-10 items-center justify-center rounded-xl bg-emerald-500 text-slate-950"
                >
                    <AppLogoIcon class="size-6" />
                </span>
                {{ name }}
            </Link>

            <div class="relative z-10 my-auto max-w-xl">
                <p
                    class="text-sm font-semibold tracking-[0.18em] text-emerald-400 uppercase"
                >
                    Operação financeira conectada
                </p>
                <h2
                    class="mt-5 text-4xl font-semibold tracking-tight text-balance xl:text-5xl"
                >
                    Controle bancário e conciliação em um único fluxo.
                </h2>
                <p class="mt-5 max-w-lg text-base leading-7 text-slate-400">
                    Integre bancos, Pix e ERP com autenticação forte,
                    rastreabilidade e isolamento por organização.
                </p>

                <div class="mt-10 grid gap-3">
                    <div
                        class="flex items-center gap-3 rounded-xl border border-white/10 bg-white/5 px-4 py-3"
                    >
                        <Landmark class="size-4 text-emerald-400" />
                        <span class="text-sm"
                            >Bradesco e Sicredi com autenticação por
                            produto</span
                        >
                    </div>
                    <div
                        class="flex items-center gap-3 rounded-xl border border-white/10 bg-white/5 px-4 py-3"
                    >
                        <GitCompareArrows class="size-4 text-sky-400" />
                        <span class="text-sm"
                            >Conciliação financeira auditável</span
                        >
                    </div>
                    <div
                        class="flex items-center gap-3 rounded-xl border border-white/10 bg-white/5 px-4 py-3"
                    >
                        <ShieldCheck class="size-4 text-amber-400" />
                        <span class="text-sm"
                            >2FA, passkeys e acesso por convite</span
                        >
                    </div>
                </div>
            </div>

            <p class="relative z-10 text-xs text-slate-500">
                Credenciais e certificados permanecem criptografados.
            </p>
        </aside>

        <section
            class="flex min-h-svh items-center justify-center px-6 py-10 sm:px-10"
        >
            <div class="w-full max-w-md">
                <Link
                    :href="home()"
                    class="mb-10 flex items-center gap-3 font-semibold lg:hidden"
                >
                    <span
                        class="flex size-10 items-center justify-center rounded-xl bg-emerald-700 text-white"
                    >
                        <AppLogoIcon class="size-6" />
                    </span>
                    {{ name }}
                </Link>

                <div class="mb-8">
                    <h1 class="text-2xl font-semibold tracking-tight">
                        {{ title }}
                    </h1>
                    <p
                        v-if="description"
                        class="mt-2 text-sm leading-6 text-muted-foreground"
                    >
                        {{ description }}
                    </p>
                </div>

                <slot />

                <p class="mt-10 text-center text-xs text-muted-foreground">
                    Ambiente protegido · acesso restrito à sua organização
                </p>
            </div>
        </section>
    </main>
</template>
