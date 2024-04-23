<?php

namespace App\Providers;

use App\Models\Application;
use App\Models\DeviceProfile;
use App\Models\Gateway;
use App\Models\Tenant;
use App\Policies\ApplicationPolicy;
use App\Policies\DeviceProfilePolicy;
use App\Policies\GatewayPolicy;
use App\Policies\TenantPolicy;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

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
    }
}
