<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Group;
use Auth;
use Illuminate\Http\Request;
use Uspdev\Idmail\IDMail;

class InstitutionalAccountController extends Controller
{
    public function __construct()
    {
        if (env('USAR_IDMAIL')) {
            $this->middleware('can:institutional');
        }
        else {
            die('idmail (desabilitado).');
        }
    }

    public function index()
    {
        $nusp = Auth::user()->codpes;
        $accounts = IDMail::find_lists($nusp);
        $todo = [];
        foreach ($accounts as $account) {
            $username = explode("@", $account['email'])[0];
            $local_account = Account::where('username', $username)->first();
            if (is_null($local_account)) {
                $todo[] = $account;
            }
        }
        return view('institutional.index', compact('todo'));
    }

    public function store(Request $request)
    {
        $account = new Account();
        $user = Auth::user();
        $email = null;

        # grupo é sempre spec
        $group = Group::where('code', 'spec')->first();

        $todo = IDMail::find_lists($user->codpes);
        foreach ($todo as $todo_account) {
            $username = explode("@", $todo_account['email'])[0];
            $local_account = Account::where('username', $username)->first();
            if (is_null($local_account) and ($todo_account['email'] == $request->email)) {
                $email = $todo_account['email'];
                $account->username = $username;
                $account->name = $todo_account['name'];
                $account->type = 'institucional';
                $account->ativo = 0;
                $account->obs = $request->obs;
                $account->user_id = $user->id;
                $account->group_id = $group->id;
                $account->save();
            }
        }

        if ($email == null) {
            die("email não encontrado.");
        }

        return redirect("/institucional");
    }
}
