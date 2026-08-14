<?php

use App\Http\Middleware\AuditRequest;
use App\Http\Middleware\AuthenticateApiClient;
use App\Http\Middleware\HandleAppearance;
use App\Http\Middleware\HandleInertiaRequests;
use App\Http\Middleware\IdentifyOrganization;
use App\Http\Middleware\RequireApiScope;
use App\Http\Middleware\RequireIdempotencyKey;
use App\Http\Middleware\RequireTwoFactorForSensitiveRole;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $trustedProxies = $_SERVER['TRUSTED_PROXIES']
            ?? $_ENV['TRUSTED_PROXIES']
            ?? '127.0.0.1';

        $middleware->trustProxies(
            at: is_string($trustedProxies) ? $trustedProxies : '127.0.0.1',
        );

        $middleware->encryptCookies(except: ['appearance', 'sidebar_state']);

        $middleware->web(append: [
            HandleAppearance::class,
            HandleInertiaRequests::class,
            AddLinkHeadersForPreloadedAssets::class,
        ]);

        $middleware->alias([
            'api.client' => AuthenticateApiClient::class,
            'api.scope' => RequireApiScope::class,
            'organization' => IdentifyOrganization::class,
            'sensitive.2fa' => RequireTwoFactorForSensitiveRole::class,
            'idempotency' => RequireIdempotencyKey::class,
            'audit' => AuditRequest::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();
