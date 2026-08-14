<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { MailPlus, Users } from '@lucide/vue';

defineProps<{
    members: Record<string, any>[];
    invitations: Record<string, any>[];
}>();
const form = useForm({ email: '', role: 'operator' });
const submit = () =>
    form.post('/members/invitations', { onSuccess: () => form.reset('email') });
</script>

<template>
    <Head title="Membros" />
    <div class="flex flex-1 flex-col gap-6 p-4 md:p-7">
        <header>
            <p
                class="text-sm font-medium text-emerald-700 dark:text-emerald-400"
            >
                Identidade e acesso
            </p>
            <h1 class="mt-1 text-2xl font-semibold">Membros</h1>
            <p class="mt-1 text-sm text-muted-foreground">
                Cadastro fechado, controlado por convite e papéis.
            </p>
        </header>
        <section class="grid gap-4 xl:grid-cols-[minmax(0,1fr)_380px]">
            <div class="overflow-hidden rounded-xl border bg-card shadow-sm">
                <div class="flex items-center gap-2 border-b px-5 py-4">
                    <Users class="size-5 text-emerald-600" />
                    <h2 class="font-semibold">Equipe</h2>
                </div>
                <div class="divide-y">
                    <div
                        v-for="member in members"
                        :key="member.id"
                        class="flex items-center justify-between px-5 py-4"
                    >
                        <div>
                            <p class="text-sm font-medium">{{ member.name }}</p>
                            <p class="text-xs text-muted-foreground">
                                {{ member.email }}
                            </p>
                        </div>
                        <span
                            class="rounded-full bg-emerald-500/10 px-2 py-1 text-xs font-medium text-emerald-700"
                            >{{ member.pivot.role }}</span
                        >
                    </div>
                </div>
            </div>
            <form
                class="rounded-xl border bg-card p-5 shadow-sm"
                @submit.prevent="submit"
            >
                <div class="flex items-center gap-2">
                    <MailPlus class="size-5 text-emerald-600" />
                    <h2 class="font-semibold">Convidar membro</h2>
                </div>
                <p class="mt-1 text-sm text-muted-foreground">
                    O link expira em 72 horas.
                </p>
                <label class="mt-5 flex flex-col gap-1.5 text-sm font-medium"
                    >E-mail<input
                        v-model="form.email"
                        type="email"
                        required
                        class="rounded-md border bg-background px-3 py-2"
                /></label>
                <label class="mt-4 flex flex-col gap-1.5 text-sm font-medium"
                    >Papel<select
                        v-model="form.role"
                        class="rounded-md border bg-background px-3 py-2"
                    >
                        <option value="owner">Proprietário</option>
                        <option value="admin">Administrador</option>
                        <option value="operator">Operador</option>
                        <option value="developer">Desenvolvedor</option>
                        <option value="auditor">Auditor</option>
                    </select></label
                >
                <button
                    class="mt-5 h-9 w-full rounded-md bg-emerald-700 text-sm font-medium text-white"
                    :disabled="form.processing"
                >
                    Enviar convite
                </button>
                <div v-if="invitations.length" class="mt-6 border-t pt-4">
                    <p
                        class="mb-2 text-xs font-semibold text-muted-foreground uppercase"
                    >
                        Pendentes
                    </p>
                    <p
                        v-for="invitation in invitations"
                        :key="invitation.id"
                        class="py-1 text-xs text-muted-foreground"
                    >
                        {{ invitation.email }} · {{ invitation.role }}
                    </p>
                </div>
            </form>
        </section>
    </div>
</template>
