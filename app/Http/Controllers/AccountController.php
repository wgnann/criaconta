<?php

namespace App\Http\Controllers;

use App\Account;
use App\Group;
use App\Tools\IDMail;
use Auth;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('todoAccounts', 'activateAccount', 'cancelAccountRequest');
    }

    private function authAPI(Request $request)
    {
        return ($request->api_key == getenv('API_KEY'));
    }

    public function store(Request $request)
    {
        $account = new Account();
        $user = Auth::user();

        $group = Group::where('id', $request->group)->first();
        if ($group == null) {
            die("grupo não encontrado.");
        }

        $email = IDMail::find_email($user->nusp);
        if ($email == null) {
            die("email não encontrado.");
        }

        $account->username = explode('@', $email)[0];
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
        if (!$this->authAPI($request)) {
            return response('Forbidden.', 403);
        }

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

    private function genericInactiveAccount($id, $method, Request $request)
    {
        if (!$this->authAPI($request)) {
            return response('Forbidden.', 403);
        }

        $account = Account::where([
            ['id', $id],
            ['ativo', 0]
        ])->first();

        if (!$account) {
            return response('Not found.', 404);
        }

        $account->$method();

        return response('Success.', 200);
    }

    public function activateAccount($id, Request $request)
    {
        return $this->genericInactiveAccount($id, 'activate', $request);
    }

    public function cancelAccountRequest($id, Request $request)
    {
        return $this->genericInactiveAccount($id, 'delete', $request);
    }
}
