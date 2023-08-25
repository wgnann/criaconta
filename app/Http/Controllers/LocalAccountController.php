<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Group;
use Auth;
use Illuminate\Http\Request;

class LocalAccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:institutional');
    }

    public function index()
    {
        $accounts = Account::where([
            ['user_id', Auth::user()->id],
            ['type', 'local']
        ])->get();
        $groups = Group::where('vinculo', 'Outro')->get();
        return view('local.index', compact('accounts', 'groups'));
    }

    public function store(Request $request)
    {
        $username = $request->username."-local";
        $account = Account::where('username', $username)->first();
        if ($account) {
            die("username já utilizado.");
        }

        $account = new Account();
        $user = Auth::user();

        $group = Group::where('id', $request->group)->first();
        if ($group == null) {
            die("grupo não encontrado.");
        }

        $account->username = $username;
        $account->name = $request->name;
        $account->type = 'local';
        $account->ativo = 0;
        $account->user_id = $user->id;
        $account->group_id = $group->id;
        $account->obs = $request->obs;
        $account->save();

        return redirect("/local");
    }
}
