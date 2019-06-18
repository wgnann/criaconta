<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Socialite;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function redirectToProvider()
    {
        return Socialite::driver('senhaunica')->redirect();
    }

    public function handleProviderCallback()
    {
        $userSenhaUnica = Socialite::driver('senhaunica')->user();

        $user = User::where('nusp',$userSenhaUnica->codpes)->first();
        if (is_null($user)) {
            $user = new User;
        }

        # autorização por grupo
        $authorized = false;
        foreach ($userSenhaUnica->vinculo as $vinculo) {
            if ($vinculo['siglaUnidade'] == "IME") {
                if ($vinculo['tipoVinculo'] == 'SERVIDOR' or
                    $vinculo['tipoVinculo'] == 'ALUNOPOS' or
                    $vinculo['tipoVinculo'] == 'ALUNOPD') {
                    $authorized = true;
                }
            }
        }

        # aqui ficará autorização individual, se necessário

        if (!$authorized) {
            return redirect('/');
        }

        $user->nusp = $userSenhaUnica->codpes;
        $user->email = $userSenhaUnica->codpes.'@usp.br';
        $user->name = $userSenhaUnica->nompes;
        $user->save();

        Auth::login($user, true);
        return redirect('/');
    }
}
