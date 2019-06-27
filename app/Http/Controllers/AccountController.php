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
}
