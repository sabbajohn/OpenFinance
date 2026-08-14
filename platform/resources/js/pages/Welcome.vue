<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ArrowRight, Building2, Landmark, ShieldCheck } from '@lucide/vue';
import { dashboard, login } from '@/routes';
</script>

<template>
    <Head title="Hub bancário multiempresa" />
    <main class="min-h-screen bg-slate-950 text-slate-100">
        <nav
            class="mx-auto flex max-w-6xl items-center justify-between px-6 py-6"
        >
            <div class="flex items-center gap-3">
                <div
                    class="flex size-9 items-center justify-center rounded-lg bg-emerald-500 font-bold text-slate-950"
                >
                    OF
                </div>
                <span class="font-semibold">OpenFinance Platform</span>
            </div>
            <Link
                :href="$page.props.auth.user ? dashboard() : login()"
                class="rounded-md border border-slate-700 px-4 py-2 text-sm font-medium hover:border-emerald-500"
            >
                {{ $page.props.auth.user ? 'Abrir painel' : 'Entrar' }}
            </Link>
        </nav>

        <section
            class="mx-auto grid min-h-[calc(100vh-88px)] max-w-6xl items-center gap-12 px-6 py-16 lg:grid-cols-[1.1fr_.9fr]"
        >
            <div>
                <p
                    class="text-sm font-semibold tracking-[0.2em] text-emerald-400 uppercase"
                >
                    Orquestração financeira
                </p>
                <h1
                    class="mt-5 max-w-3xl text-4xl font-semibold tracking-tight sm:text-6xl"
                >
                    Bancos e ERPs operando como um único fluxo financeiro.
                </h1>
                <p class="mt-6 max-w-2xl text-lg leading-8 text-slate-400">
                    Sincronização bancária, conciliação auditável, Pix, boletos
                    e entrega idempotente para múltiplas empresas.
                </p>
                <Link
                    :href="$page.props.auth.user ? dashboard() : login()"
                    class="mt-8 inline-flex items-center gap-2 rounded-md bg-emerald-500 px-5 py-3 font-semibold text-slate-950 hover:bg-emerald-400"
                >
                    {{
                        $page.props.auth.user
                            ? 'Ir para a operação'
                            : 'Acessar por convite'
                    }}
                    <ArrowRight class="size-4" />
                </Link>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <article
                    class="rounded-2xl border border-slate-800 bg-slate-900 p-6 sm:col-span-2"
                >
                    <ShieldCheck class="size-6 text-emerald-400" />
                    <h2 class="mt-8 text-xl font-semibold">
                        Decisões financeiras duráveis
                    </h2>
                    <p class="mt-2 text-sm leading-6 text-slate-400">
                        Inbox/outbox, concorrência otimista, trilha de auditoria
                        e confirmação transacional pelo ERP.
                    </p>
                </article>
                <article
                    class="rounded-2xl border border-slate-800 bg-slate-900 p-6"
                >
                    <Landmark class="size-6 text-sky-400" />
                    <h2 class="mt-8 font-semibold">Multi-banco</h2>
                    <p class="mt-2 text-sm text-slate-400">
                        Bradesco e Sicredi com adapters por capacidade.
                    </p>
                </article>
                <article
                    class="rounded-2xl border border-slate-800 bg-slate-900 p-6"
                >
                    <Building2 class="size-6 text-amber-400" />
                    <h2 class="mt-8 font-semibold">Multiempresa</h2>
                    <p class="mt-2 text-sm text-slate-400">
                        Isolamento obrigatório por organização e CNPJ.
                    </p>
                </article>
            </div>
        </section>
    </main>
</template>
