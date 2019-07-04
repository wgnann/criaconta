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
                if ($vinculo['tipoVinculo'] == 'ESTAGIARIORH') {
                    $authorized = 'ESTAGIARIORH';
                }
                elseif ($vinculo['tipoVinculo'] == 'ALUNOPOS') {
                    $authorized = 'ALUNOPOS';
                }
                elseif ($vinculo['tipoVinculo'] == 'ALUNOPD') {
                    $authorized = 'ALUNOPD';
                }
                elseif ($vinculo['tipoVinculo'] == 'SERVIDOR') {
                    $authorized = 'SERVIDOR';
                    break;
                }
            }
        }

        # aqui ficará autorização individual, se necessário

        if (!$authorized) {
            return redirect('/');
        }

        $nusp = $userSenhaUnica->codpes;
        $email = IDMail::find_mail($nusp);
        if ($email == "") {
            $idmail = new IDMail();
            $json = json_decode($idmail->id_get_emails($nusp));
            $email = $idmail->extract_email($json);
        }

        if ($email == "") {
            die("email não encontrado.");
        }

        $user->name = $userSenhaUnica->nompes;
        $user->email = $email;
        $user->nusp = $userSenhaUnica->codpes;
        $user->vinculo = $authorized;
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
