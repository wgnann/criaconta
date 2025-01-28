<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Uspdev\Idmail\IDMail;
use App\Models\User;
use App\Models\Account;

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

        // conta pertence ao usuário
        Gate::define('owner', function (User $user, Account $account) {
            if (!$account->ativo) {
                return false;
            }
            return $account->user_id === $user->id;
        });

        // usuário é docente ou funcionário
        Gate::define('institutional', function (User $user) {
            return $user->hasPermissionTo('Servidor', 'senhaunica') || $user->hasPermissionTo('Docente', 'senhaunica');
        });

        // usuário é elegível para contas (sem IDMail)
        Gate::define('regular', function (User $user) {
            $aluno = $user->hasPermissionTo('Alunopos', 'senhaunica') || $user->hasPermissionTo('Alunopd', 'senhaunica');
            return $aluno || $user->can('institutional');
        });

        // email existe (uso com IDMail)
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
