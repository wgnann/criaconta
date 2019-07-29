<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Tools\IDMail;
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

    private function maiorVinculo($vinculos) {
        # o último é o com mais privilégio
        $ordenado = [
            "ALUNOPD",
            "ALUNOGR",
            "ALUNOPOS",
            "ESTAGIARIORH",
            "SERVIDOR"
        ];

        $vinculoUSP = "OUTRO";
        if (is_array($vinculos)) {
            foreach ($vinculos as $vinculo) {
                if (in_array($vinculo, $ordenado)) {
                    $vinculoUSP = $vinculo;
                }
            }
        }

        return $vinculoUSP;
    }

    public function handleProviderCallback()
    {
        $userSenhaUnica = Socialite::driver('senhaunica')->user();

        $user = User::where('nusp',$userSenhaUnica->codpes)->first();
        if (is_null($user)) {
            $user = new User;
        }

        $vinculos = [];
        foreach ($userSenhaUnica->vinculo as $vinculo) {
            if ($vinculo['siglaUnidade'] == "IME") {
                $vinculos[] = $vinculo['tipoVinculo'];
            }
        }

        $vinculo = $this->maiorVinculo($vinculos);
        $nusp = $userSenhaUnica->codpes;
        if ($vinculo != "SERVIDOR") {
            $emailIME = IDMail::find_email($nusp);
            if ($emailIME == null) {
                die("email não encontrado.");
            }
        }

        if (!$vinculos) {
            return redirect('/');
        }

        $user->email = $userSenhaUnica->email;
        $user->name = $userSenhaUnica->nompes;
        $user->nusp = $nusp;
        $user->vinculo = $vinculo;
        $user->save();

        Auth::login($user, true);
        return redirect('/');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
