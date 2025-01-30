<?php

namespace App\Http\Controllers\API;

use App\Models\Account;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    private function authAPI(Request $request)
    {
        return ($request->api_key == getenv('API_KEY'));
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
                'owner' => $account->user->codpes,
                'owner_name' => $account->user->name,
                'owner_email' => $account->user->email,
                'owner_vinculo' => $account->user->vinculo(),
                'username'=> $account->username,
                'group' => $account->group->code,
                'name' => $account->name,
                'type' => $account->type,
                'obs' => $account->obs,
            ]);
        }
        return response()->json($todo);
    }

    private function genericInactiveAccount($id, $active, $method, Request $request)
    {
        if (!$this->authAPI($request)) {
            return response('Forbidden.', 403);
        }

        $account = Account::where([
            ['id', $id],
            ['ativo', $active]
        ])->first();

        if (!$account) {
            return response('Not found.', 404);
        }

        $account->$method();

        return response('Success.', 200);
    }

    public function activateAccount($id, Request $request)
    {
        return $this->genericInactiveAccount($id, 0, 'activate', $request);
    }

    public function cancelAccountRequest($id, Request $request)
    {
        return $this->genericInactiveAccount($id, 0, 'delete', $request);
    }

    public function deleteAccount($id, Request $request)
    {
        return $this->genericInactiveAccount($id, 1, 'delete', $request);
    }

    public function accountFromUsername($username, Request $request)
    {
        if (!$this->authAPI($request)) {
            return response('Forbidden.', 403);
        }

        $account = Account::where([
            ['username', $username],
            ['ativo', 1]
        ])->first();

        if (!$account) {
            return response('Not found.', 404);
        }

        $acc = [
            'id' => $account->id,
            'owner' => $account->user->codpes,
            'owner_name' => $account->user->name,
            'owner_email' => $account->user->email,
            'owner_vinculo' => $account->user->vinculo(),
            'username'=> $account->username,
            'group' => $account->group->code,
            'name' => $account->name,
            'type' => $account->type,
            'obs' => $account->obs,
        ];

        return response()->json($acc);
    }
}
