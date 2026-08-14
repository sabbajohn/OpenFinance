<?php

namespace App\Providers;

use App\Support\LaravelPsrCache;
use App\Support\OrganizationContext;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Psr\SimpleCache\CacheInterface;
use Sabba\OpenFinance\Bradesco\BradescoHttpClient;
use Sabba\OpenFinance\Bradesco\BradescoProvider;
use Sabba\OpenFinance\Sicredi\SicrediHttpClient;
use Sabba\OpenFinance\Sicredi\SicrediProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(OrganizationContext::class);
        $this->app->singleton(CacheInterface::class, fn ($app) => new LaravelPsrCache($app->make(CacheRepository::class)));
        $this->app->singleton(BradescoHttpClient::class, fn ($app) => new BradescoHttpClient($app->make(CacheInterface::class)));
        $this->app->singleton(BradescoProvider::class);
        $this->app->singleton(SicrediHttpClient::class, fn ($app) => new SicrediHttpClient($app->make(CacheInterface::class)));
        $this->app->singleton(SicrediProvider::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }
}
