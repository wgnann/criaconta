<?php

namespace App\Http\Controllers;

use App\Account;
use App\Group;
use Auth;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index()
    {
        $account = NULL;
        $groups = NULL;
        if (Auth::check()) {
            $account = Account::where([
                ['user_id', Auth::user()->id],
                ['type', 'pessoal']
            ])->first();
            $groups = Group::where('vinculo', Auth::user()->vinculo)->get();
        }
        return view('index', compact('account', 'groups'));
    }
}
