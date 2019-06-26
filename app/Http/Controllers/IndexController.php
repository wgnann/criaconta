<?php

namespace App\Http\Controllers;

use App\Account;
use Auth;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index()
    {
        $account = NULL;
        if (Auth::check()) {
            $account = Account::where([
                ['user_id', Auth::user()->id],
                ['type', 'pessoal']
            ])->first();
        }
        return view('index', compact('account'));
    }
}
