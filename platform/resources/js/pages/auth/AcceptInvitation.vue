<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    email: string;
    organization: string;
    token: string;
    existingUser: boolean;
}>();
const form = useForm({ name: '', password: '', password_confirmation: '' });
const submit = () => form.post(`/invitations/${props.token}`);
</script>

<template>
    <Head title="Aceitar convite" />
    <div class="space-y-6">
        <div class="text-center">
            <p class="text-sm font-medium text-emerald-700">
                OpenFinance Platform
            </p>
            <h1 class="mt-2 text-2xl font-semibold">
                Entrar em {{ props.organization }}
            </h1>
            <p class="mt-2 text-sm text-muted-foreground">
                Convite para {{ props.email }}
            </p>
        </div>
        <form class="space-y-4" @submit.prevent="submit">
            <label class="flex flex-col gap-1.5 text-sm font-medium"
                >Nome<input
                    v-model="form.name"
                    required
                    class="rounded-md border bg-background px-3 py-2"
            /></label>
            <label class="flex flex-col gap-1.5 text-sm font-medium"
                >{{ props.existingUser ? 'Sua senha atual' : 'Crie uma senha'
                }}<input
                    v-model="form.password"
                    type="password"
                    required
                    class="rounded-md border bg-background px-3 py-2"
            /></label>
            <label class="flex flex-col gap-1.5 text-sm font-medium"
                >Confirme a senha<input
                    v-model="form.password_confirmation"
                    type="password"
                    required
                    class="rounded-md border bg-background px-3 py-2"
            /></label>
            <button
                class="h-10 w-full rounded-md bg-emerald-700 text-sm font-medium text-white"
                :disabled="form.processing"
            >
                Aceitar convite
            </button>
        </form>
    </div>
</template>
