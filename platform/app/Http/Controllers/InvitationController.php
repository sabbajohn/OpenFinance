<?php

namespace App\Http\Controllers;

use App\Domain\Identity\Enums\OrganizationRole;
use App\Domain\Identity\Models\OrganizationInvitation;
use App\Models\User;
use App\Notifications\OrganizationInvitationNotification;
use App\Support\OrganizationContext;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;
use Inertia\Response;

class InvitationController extends Controller
{
    public function index(OrganizationContext $context): Response
    {
        $actorRole = request()->user()?->roleFor($context->get());

        return Inertia::render('Platform/Members', [
            'members' => $context->get()->users()->orderBy('name')->get()->map(function (User $member) use ($actorRole): array {
                $pivot = $member->getRelation('pivot');
                $role = $pivot instanceof Pivot ? (string) $pivot->getAttribute('role') : '';

                return [
                    'id' => $member->getKey(),
                    'name' => $member->name,
                    'email' => $member->email,
                    'role' => $role,
                    'accepted_at' => $pivot instanceof Pivot ? $pivot->getAttribute('accepted_at') : null,
                    'can_edit' => $this->canManageRole($actorRole, $role),
                    'can_remove' => $member->getKey() !== request()->user()?->getKey()
                        && $this->canManageRole($actorRole, $role),
                ];
            }),
            'invitations' => OrganizationInvitation::query()->whereNull('accepted_at')->latest()->get()->map(
                fn (OrganizationInvitation $invitation): array => [
                    'id' => $invitation->getKey(),
                    'email' => $invitation->email,
                    'role' => $invitation->role,
                    'expires_at' => $invitation->expires_at,
                    'can_cancel' => $this->canManageRole($actorRole, $invitation->role),
                ],
            ),
            'roles' => collect(OrganizationRole::cases())->map(fn (OrganizationRole $role): array => [
                'value' => $role->value,
                'label' => $role->label(),
                'description' => $role->description(),
                'permissions' => $role->permissionValues(),
            ])->values(),
            'assignableRoles' => $this->roleValues($this->assignableRoles($actorRole)),
        ]);
    }

    public function store(Request $request, OrganizationContext $context): RedirectResponse
    {
        $this->assertAdministrator($request, $context);
        $assignableRoles = $this->assignableRoles($request->user()->roleFor($context->get()));
        $data = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'role' => ['required', 'in:'.implode(',', $this->roleValues($assignableRoles))],
        ]);
        $plain = Str::random(64);
        $invitation = OrganizationInvitation::query()->updateOrCreate(
            ['organization_id' => $context->id(), 'email' => Str::lower($data['email'])],
            [
                'role' => $data['role'],
                'token_hash' => hash('sha256', $plain),
                'invited_by' => $request->user()->getKey(),
                'expires_at' => now('UTC')->addHours(72),
                'accepted_at' => null,
            ],
        );
        $url = route('invitations.show', ['token' => $plain]);
        Notification::route('mail', $invitation->email)
            ->notify(new OrganizationInvitationNotification($context->get()->name, $url));

        return back()->with('success', 'Convite enviado.');
    }

    public function updateRole(Request $request, User $user, OrganizationContext $context): RedirectResponse
    {
        $this->assertAdministrator($request, $context);
        $membership = $context->get()->users()->whereKey($user->getKey())->firstOrFail();
        $currentRole = OrganizationRole::from((string) $membership->pivot->getAttribute('role'));
        $actorRole = $request->user()->roleFor($context->get());
        abort_unless($this->canManageRole($actorRole, $currentRole->value), 403);

        $assignableRoles = $this->assignableRoles($actorRole);
        $data = $request->validate([
            'role' => ['required', 'in:'.implode(',', $this->roleValues($assignableRoles))],
        ]);
        $newRole = OrganizationRole::from($data['role']);
        $this->protectLastOwner($context, $currentRole, $newRole);

        $context->get()->users()->updateExistingPivot($user->getKey(), ['role' => $newRole->value]);

        return back()->with('success', 'Perfil de acesso atualizado.');
    }

    public function destroy(Request $request, User $user, OrganizationContext $context): RedirectResponse
    {
        $this->assertAdministrator($request, $context);
        abort_if($request->user()->is($user), 422, 'Você não pode remover o próprio acesso.');
        $membership = $context->get()->users()->whereKey($user->getKey())->firstOrFail();
        $role = OrganizationRole::from((string) $membership->pivot->getAttribute('role'));
        abort_unless($this->canManageRole($request->user()->roleFor($context->get()), $role->value), 403);
        $this->protectLastOwner($context, $role, null);

        $context->get()->users()->detach($user->getKey());
        if ($user->current_organization_id === $context->id()) {
            $nextOrganization = $user->organizations()->orderBy('organizations.name')->first();
            $user->forceFill(['current_organization_id' => $nextOrganization?->getKey()])->save();
        }

        return back()->with('success', 'Acesso removido da organização.');
    }

    public function destroyInvitation(
        Request $request,
        OrganizationInvitation $invitation,
        OrganizationContext $context,
    ): RedirectResponse {
        $this->assertAdministrator($request, $context);
        abort_unless($invitation->organization_id === $context->id(), 403);
        abort_unless($this->canManageRole($request->user()->roleFor($context->get()), $invitation->role), 403);
        $invitation->delete();

        return back()->with('success', 'Convite cancelado.');
    }

    public function show(string $token): Response
    {
        $invitation = $this->validInvitation($token);

        return Inertia::render('auth/AcceptInvitation', [
            'email' => $invitation->email,
            'organization' => $invitation->organization->name,
            'token' => $token,
            'existingUser' => User::query()->where('email', $invitation->email)->exists(),
        ]);
    }

    public function accept(Request $request, string $token): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = DB::transaction(function () use ($token, $data): User {
            $invitation = OrganizationInvitation::query()->withoutGlobalScopes()
                ->where('token_hash', hash('sha256', $token))
                ->lockForUpdate()
                ->firstOrFail();
            abort_if($invitation->accepted_at || $invitation->expires_at->isPast(), 410, 'Convite expirado ou já utilizado.');

            $user = User::query()->where('email', $invitation->email)->first();
            if ($user && ! Hash::check($data['password'], $user->password)) {
                abort(422, 'A senha não confere com a conta já existente.');
            }
            if (! $user) {
                $user = User::query()->create([
                    'name' => $data['name'],
                    'email' => $invitation->email,
                    'password' => $data['password'],
                ]);
                $user->forceFill(['email_verified_at' => now('UTC')])->save();
            }
            $user->organizations()->syncWithoutDetaching([$invitation->organization_id => [
                'role' => $invitation->role,
                'accepted_at' => now('UTC'),
            ]]);
            $user->forceFill(['current_organization_id' => $invitation->organization_id])->save();
            $invitation->forceFill(['accepted_at' => now('UTC')])->save();

            return $user;
        }, 3);

        Auth::login($user);

        return redirect()->route('security.edit')->with('success', 'Convite aceito. Configure o 2FA antes de operar.');
    }

    private function validInvitation(string $token): OrganizationInvitation
    {
        $invitation = OrganizationInvitation::query()->withoutGlobalScopes()
            ->with('organization')
            ->where('token_hash', hash('sha256', $token))
            ->firstOrFail();
        abort_if($invitation->accepted_at || $invitation->expires_at->isPast(), 410, 'Convite expirado ou já utilizado.');

        return $invitation;
    }

    private function assertAdministrator(Request $request, OrganizationContext $context): void
    {
        abort_unless(in_array($request->user()->roleFor($context->get()), [OrganizationRole::Owner, OrganizationRole::Admin], true), 403);
    }

    /** @return list<OrganizationRole> */
    private function assignableRoles(?OrganizationRole $actorRole): array
    {
        return $actorRole === OrganizationRole::Owner
            ? OrganizationRole::cases()
            : array_values(array_filter(
                OrganizationRole::cases(),
                static fn (OrganizationRole $role): bool => $role !== OrganizationRole::Owner,
            ));
    }

    private function canManageRole(?OrganizationRole $actorRole, string $targetRole): bool
    {
        return $actorRole === OrganizationRole::Owner
            || ($actorRole === OrganizationRole::Admin && $targetRole !== OrganizationRole::Owner->value);
    }

    /**
     * @param  list<OrganizationRole>  $roles
     * @return list<string>
     */
    private function roleValues(array $roles): array
    {
        return array_map(static fn (OrganizationRole $role): string => $role->value, $roles);
    }

    private function protectLastOwner(
        OrganizationContext $context,
        OrganizationRole $currentRole,
        ?OrganizationRole $newRole,
    ): void {
        if ($currentRole !== OrganizationRole::Owner || $newRole === OrganizationRole::Owner) {
            return;
        }

        $owners = $context->get()->users()->wherePivot('role', OrganizationRole::Owner->value)->count();
        abort_if($owners <= 1, 422, 'A organização precisa manter ao menos um proprietário.');
    }
}
