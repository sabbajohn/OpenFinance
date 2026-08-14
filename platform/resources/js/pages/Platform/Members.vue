<script setup lang="ts">
import { Head, router, useForm } from '@inertiajs/vue3';
import { MailPlus, ShieldCheck, Trash2, UserCog, Users } from '@lucide/vue';
import { computed, ref } from 'vue';

type Member = {
    id: number;
    name: string;
    email: string;
    role: string;
    accepted_at?: string | null;
    can_edit: boolean;
    can_remove: boolean;
};
type Invitation = {
    id: string;
    email: string;
    role: string;
    expires_at: string;
    can_cancel: boolean;
};
type Role = {
    value: string;
    label: string;
    description: string;
    permissions: string[];
};

const props = defineProps<{
    members: Member[];
    invitations: Invitation[];
    roles: Role[];
    assignableRoles: string[];
}>();

const form = useForm({ email: '', role: 'operator' });
const updatingId = ref<number | null>(null);
const removingId = ref<number | null>(null);
const assignableRoleOptions = computed(() =>
    props.roles.filter((role) => props.assignableRoles.includes(role.value)),
);

const submit = () =>
    form.post('/members/invitations', {
        preserveScroll: true,
        onSuccess: () => form.reset('email'),
    });
const roleLabel = (value: string) =>
    props.roles.find((role) => role.value === value)?.label ?? value;
const updateRole = (member: Member, role: string) => {
    updatingId.value = member.id;
    router.patch(
        `/members/${member.id}/role`,
        { role },
        {
            preserveScroll: true,
            onFinish: () => (updatingId.value = null),
        },
    );
};
const removeMember = (member: Member) => {
    if (!window.confirm(`Remover o acesso de ${member.name}?`)) {
        return;
    }

    removingId.value = member.id;
    router.delete(`/members/${member.id}`, {
        preserveScroll: true,
        onFinish: () => (removingId.value = null),
    });
};
const cancelInvitation = (invitation: Invitation) => {
    if (!window.confirm(`Cancelar o convite para ${invitation.email}?`)) {
        return;
    }

    router.delete(`/members/invitations/${invitation.id}`, {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Membros e acessos" />
    <div class="flex flex-1 flex-col gap-6 p-4 md:p-7">
        <header>
            <p
                class="text-sm font-medium text-emerald-700 dark:text-emerald-400"
            >
                Identidade e acesso
            </p>
            <h1 class="mt-1 text-2xl font-semibold">Membros e acessos</h1>
            <p class="mt-1 max-w-3xl text-sm text-muted-foreground">
                Convide usuários e defina exatamente quem administra bancos,
                opera o financeiro, desenvolve integrações ou apenas audita.
            </p>
        </header>

        <section class="grid gap-3 lg:grid-cols-5">
            <article
                v-for="role in roles"
                :key="role.value"
                class="rounded-xl border bg-card p-4 shadow-sm"
            >
                <ShieldCheck class="size-5 text-emerald-600" />
                <h2 class="mt-3 text-sm font-semibold">{{ role.label }}</h2>
                <p class="mt-1 text-xs leading-5 text-muted-foreground">
                    {{ role.description }}
                </p>
            </article>
        </section>

        <section class="grid gap-4 xl:grid-cols-[minmax(0,1fr)_380px]">
            <div class="overflow-hidden rounded-xl border bg-card shadow-sm">
                <div class="flex items-center gap-2 border-b px-5 py-4">
                    <Users class="size-5 text-emerald-600" />
                    <div>
                        <h2 class="font-semibold">Equipe</h2>
                        <p class="text-xs text-muted-foreground">
                            {{ members.length }} usuários ativos
                        </p>
                    </div>
                </div>
                <div class="divide-y">
                    <div
                        v-for="member in members"
                        :key="member.id"
                        class="flex flex-col gap-3 px-5 py-4 sm:flex-row sm:items-center sm:justify-between"
                    >
                        <div class="min-w-0">
                            <p class="truncate text-sm font-medium">
                                {{ member.name }}
                            </p>
                            <p class="truncate text-xs text-muted-foreground">
                                {{ member.email }}
                            </p>
                        </div>
                        <div class="flex items-center gap-2">
                            <select
                                v-if="member.can_edit"
                                :value="member.role"
                                class="h-9 rounded-md border bg-background px-3 text-xs"
                                :disabled="updatingId === member.id"
                                @change="
                                    updateRole(
                                        member,
                                        ($event.target as HTMLSelectElement)
                                            .value,
                                    )
                                "
                            >
                                <option
                                    v-for="role in assignableRoleOptions"
                                    :key="role.value"
                                    :value="role.value"
                                >
                                    {{ role.label }}
                                </option>
                            </select>
                            <span
                                v-else
                                class="rounded-full bg-emerald-500/10 px-2 py-1 text-xs font-medium text-emerald-700"
                            >
                                {{ roleLabel(member.role) }}
                            </span>
                            <button
                                v-if="member.can_remove"
                                class="rounded-md p-2 text-rose-700 hover:bg-rose-500/10 disabled:opacity-50"
                                title="Remover acesso"
                                :disabled="removingId === member.id"
                                @click="removeMember(member)"
                            >
                                <Trash2 class="size-4" />
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <form
                    class="rounded-xl border bg-card p-5 shadow-sm"
                    @submit.prevent="submit"
                >
                    <div class="flex items-center gap-2">
                        <MailPlus class="size-5 text-emerald-600" />
                        <h2 class="font-semibold">Convidar membro</h2>
                    </div>
                    <p class="mt-1 text-sm text-muted-foreground">
                        O link é individual e expira em 72 horas.
                    </p>
                    <label
                        class="mt-5 flex flex-col gap-1.5 text-sm font-medium"
                    >
                        E-mail
                        <input
                            v-model="form.email"
                            type="email"
                            required
                            class="rounded-md border bg-background px-3 py-2"
                        />
                        <span
                            v-if="form.errors.email"
                            class="text-xs text-rose-600"
                        >
                            {{ form.errors.email }}
                        </span>
                    </label>
                    <label
                        class="mt-4 flex flex-col gap-1.5 text-sm font-medium"
                    >
                        Papel
                        <select
                            v-model="form.role"
                            class="rounded-md border bg-background px-3 py-2"
                        >
                            <option
                                v-for="role in assignableRoleOptions"
                                :key="role.value"
                                :value="role.value"
                            >
                                {{ role.label }}
                            </option>
                        </select>
                    </label>
                    <button
                        class="mt-5 h-9 w-full rounded-md bg-emerald-700 text-sm font-medium text-white disabled:opacity-60"
                        :disabled="form.processing"
                    >
                        Enviar convite
                    </button>
                </form>

                <div
                    v-if="invitations.length"
                    class="rounded-xl border bg-card p-5 shadow-sm"
                >
                    <div class="flex items-center gap-2">
                        <UserCog class="size-5 text-emerald-600" />
                        <h2 class="font-semibold">Convites pendentes</h2>
                    </div>
                    <div class="mt-3 divide-y">
                        <div
                            v-for="invitation in invitations"
                            :key="invitation.id"
                            class="flex items-center justify-between gap-3 py-3"
                        >
                            <div class="min-w-0">
                                <p class="truncate text-xs font-medium">
                                    {{ invitation.email }}
                                </p>
                                <p class="text-xs text-muted-foreground">
                                    {{ roleLabel(invitation.role) }}
                                </p>
                            </div>
                            <button
                                v-if="invitation.can_cancel"
                                class="rounded-md p-2 text-rose-700 hover:bg-rose-500/10"
                                title="Cancelar convite"
                                @click="cancelInvitation(invitation)"
                            >
                                <Trash2 class="size-4" />
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>
