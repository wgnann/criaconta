<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Group;
use App\Tools\IDMail;
use Auth;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('todoAccounts', 'activateAccount', 'cancelAccountRequest', 'deleteAccount', 'accountFromUsername');
    }

    private function authAPI(Request $request)
    {
        return ($request->api_key == getenv('API_KEY'));
    }

    public function index()
    {
        $account = Account::where([
            ['user_id', Auth::user()->id],
            ['type', 'pessoal']
        ])->first();
        $groups = Group::where('vinculo', Auth::user()->vinculo)->get();
        return view('account.index', compact('account', 'groups'));
    }

    public function store(Request $request)
    {
        $account = new Account();
        $user = Auth::user();

        $group = Group::where('id', $request->group)->first();
        if ($group == null) {
            die("grupo não encontrado.");
        }

        $email = IDMail::find_email($user->codpes);
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

        return redirect("/accounts");
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
                'owner_vinculo' => $account->user->vinculo,
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
            'owner_vinculo' => $account->user->vinculo,
            'username'=> $account->username,
            'group' => $account->group->code,
            'name' => $account->name,
            'type' => $account->type,
            'obs' => $account->obs,
        ];

        return response()->json($acc);
    }
}
