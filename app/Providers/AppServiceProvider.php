<?php

namespace App\Providers;

use App\Listeners\LogFailedLogin;
use App\Models\Cliente;
use App\Models\Empresa;
use App\Models\User;
use App\Policies\ClientePolicy;
use App\Policies\EmpresaPolicy;
use App\Policies\UserPolicy;
use Illuminate\Auth\Events\Failed;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::policy(Cliente::class, ClientePolicy::class);
        Gate::policy(Empresa::class, EmpresaPolicy::class);
        Gate::policy(User::class, UserPolicy::class);

        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
        Event::listen(Failed::class, LogFailedLogin::class);
    }
}
