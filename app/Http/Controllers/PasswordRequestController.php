<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\PasswordRequest;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PasswordRequestController extends Controller
{
    public function store(Account $account)
    {
        Gate::authorize('owner', $account);

        $password_request = PasswordRequest::where([
            ['account_id', $account->id],
            ['ativo', 1]
        ])->first();

        if (!$password_request) {
            $password_request = new PasswordRequest();
            $password_request->ativo = 1;
            $password_request->account_id = $account->id;
            $password_request->save();
        }

        return view('password.store', compact('password_request'));
    }
}
