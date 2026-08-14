import { usePage } from '@inertiajs/vue3';

type OrganizationContext = {
    id: string;
    name: string;
    role: string;
    role_label: string;
    permissions: string[];
};

export function useOrganizationAccess() {
    const organization = usePage().props.organization as
        OrganizationContext | null | undefined;
    const permissions = new Set(organization?.permissions ?? []);

    return {
        organization,
        can: (permission: string) => permissions.has(permission),
    };
}
