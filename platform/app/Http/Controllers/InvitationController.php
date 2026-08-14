<?php

namespace App\Http\Controllers;

use App\Domain\Identity\Enums\OrganizationRole;
use App\Domain\Identity\Models\OrganizationInvitation;
use App\Models\User;
use App\Notifications\OrganizationInvitationNotification;
use App\Support\OrganizationContext;
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
        return Inertia::render('Platform/Members', [
            'members' => $context->get()->users()->orderBy('name')->get(),
            'invitations' => OrganizationInvitation::query()->whereNull('accepted_at')->latest()->get(),
        ]);
    }

    public function store(Request $request, OrganizationContext $context): RedirectResponse
    {
        $this->assertAdministrator($request, $context);
        $data = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'role' => ['required', 'in:owner,admin,operator,developer,auditor'],
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
}
