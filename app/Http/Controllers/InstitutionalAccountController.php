<?php

namespace App\Http\Controllers;

use App\Account;
use App\Group;
use App\Tools\IDMail;
use Auth;
use Illuminate\Http\Request;

class InstitutionalAccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:institutional');
    }

    public function index()
    {
        $nusp = Auth::user()->codpes;
        $accounts = IDMail::find_lists($nusp);
        $active = [];
        $queued = [];
        $todo = [];
        foreach ($accounts as $account) {
            $username = explode("@", $account['email'])[0];
            $local_account = Account::where('username', $username)->first();
            if (is_null($local_account)) {
                $todo[] = $account;
            }
            else {
                if ($local_account->ativo) {
                    $active[] = $local_account;
                }
                else {
                    $queued[] = $local_account;
                }
            }
        }
        return view('institutional.index', compact('todo', 'queued', 'active'));
    }

    public function store(Request $request)
    {
        $account = new Account();
        $user = Auth::user();
        $email = null;

        # grupo é sempre spec
        $group = Group::where('code', 'spec')->first();

        $todo = IDMail::find_lists($user->nusp);
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
