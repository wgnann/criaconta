<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Group;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Uspdev\Idmail\IDMail;

class AccountController extends Controller
{
    public function __construct()
    {
        if (env('USAR_IDMAIL')) {
            $this->middleware('can:email');
        }
        else {
            $this->middleware('can:regular');
        }
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
        if ($account) {
            return redirect()->route('account.show', $account);
        }
        $idmail = env('USAR_IDMAIL', true);
        $username = explode('@', Auth::user()->email)[0];
        $groups = Group::where('vinculo', Auth::user()->vinculo())->get();
        return view('account.index', compact('groups', 'username', 'idmail'));
    }

    public function show(Account $account)
    {
        Gate::authorize('owner', $account);
        return view('account.show', compact('account'));
    }

    public function listAdmin(Request $request)
    {
        Gate::authorize('admin');
        $search = $request->input('search');

        if ($search) {
            $user = User::where('codpes', $search)->first();
            if ($user) {
                $accounts = Account::where('username', 'like', "%$search")
                    ->orWhere('user_id', $user->id)
                    ->get();
            }
            else {
                $accounts = Account::where('username', 'like', "%$search")
                    ->get();
            }
        }
        else {
            $accounts = Account::all();
        }

        return view('account.listadmin', compact('accounts', 'search'));
    }

    public function store(Request $request)
    {
        $account = new Account();
        $user = Auth::user();

        $group = Group::where('id', $request->group)->first();
        if ($group == null) {
            die("grupo não encontrado.");
        }

        if (env('USAR_IDMAIL', true)) {
            $email = IDMail::find_email($user->codpes);
            if ($email == null) {
                die("email não encontrado.");
            }
            $account->username = explode('@', $email)[0];
        }
        else {
            $request->validate(['username' => 'required|alpha_num']);
            $account->username = $request->username;
        }

        $account->name = $user->name;
        $account->type = 'pessoal';
        $account->ativo = 0;
        $account->user_id = $user->id;
        $account->group_id = $group->id;
        $account->save();

        return redirect("/accounts");
    }
}
