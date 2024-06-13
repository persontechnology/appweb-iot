<?php

namespace App\Providers;

use App\Models\Alerta;
use App\Models\Application;
use App\Models\DeviceProfile;
use App\Models\Dispositivo;
use App\Models\Gateway;
use App\Models\Lectura;
use App\Models\Tenant;
use App\Models\TenantUser;
use App\Policies\AlertaPolicy;
use App\Policies\ApplicationPolicy;
use App\Policies\DeviceProfilePolicy;
use App\Policies\DispositivoPolicy;
use App\Policies\GatewayPolicy;
use App\Policies\LecturaPolicy;
use App\Policies\TenantPolicy;
use App\Policies\TenantUserPolicy;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Tenant::class, TenantPolicy::class);
        Gate::policy(Gateway::class, GatewayPolicy::class);
        Gate::policy(Application::class, ApplicationPolicy::class);
        Gate::policy(DeviceProfile::class, DeviceProfilePolicy::class);
        Gate::policy(Dispositivo::class, DispositivoPolicy::class);
        Gate::policy(Alerta::class, AlertaPolicy::class);
        Gate::policy(TenantUser::class, TenantUserPolicy::class);
        Gate::policy(Lectura::class, LecturaPolicy::class);

        Paginator::useBootstrapFive();

    }
}
