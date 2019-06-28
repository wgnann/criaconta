<?php

namespace App\Http\Controllers;

use App\Account;
use App\Group;
use Auth;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
    }

    public function store(Request $request)
    {
        $account = new Account();
        $user = Auth::user();
        $group = Group::where('id', $request->group)->first();

        $account->username = explode('@', $user->email)[0];
        $account->name = $user->name;
        $account->type = 'pessoal';
        $account->ativo = 0;
        $account->user_id = $user->id;
        $account->group_id = $group->id;

        $account->save();

        return redirect("/");
    }

    public function todoAccounts(Request $request)
    {
        $accounts = Account::where('ativo', 0)->get();
        $todo = [];
        foreach ($accounts as $account) {
            array_push($todo, [
                'id' => $account->id,
                'owner' => $account->user->nusp,
                'owner_name' => $account->user->name,
                'owner_email' => $account->user->email,
                'owner_vinculo' => $account->user->vinculo,
                'username'=> $account->username,
                'group' => $account->group->code,
                'name' => $account->name,
                'type' => $account->type,
            ]);
        }
        return response()->json($todo);
    }

    public function activateAccount($id)
    {
        $account = Account::where([
            ['id', $id],
            ['ativo', 0]
        ])->first();

        if (!$account) {
            return response('Not found.', 404);
        }

        $account->ativo = 1;
        $account->save();

        return response('Success.', 200);
    }
}
