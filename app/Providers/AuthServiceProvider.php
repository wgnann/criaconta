<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Uspdev\Idmail\IDMail;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('institutional', function ($user) {
            return $user->hasPermissionTo('Servidor', 'senhaunica') || $user->hasPermissionTo('Docente', 'senhaunica');
        });

        Gate::define('email', function ($user) {
            if ($user == null)
                return false;

            $emailIME = IDMail::find_email($user->codpes);
            if ($emailIME == null)
                return false;

            return true;
        });
    }
}
