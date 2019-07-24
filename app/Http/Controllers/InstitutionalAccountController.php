<?php

namespace App\Http\Controllers;

use App\Account;
use App\Group;
use App\Tools\IDMail;
use Auth;
use Illuminate\Http\Request;

class InstitutionalAccountController extends Controller
{
    public function index()
    {
        $nusp = Auth::user()->nusp;
        $accounts = IDMail::find_lists($nusp);
        $active = [];
        $queued = [];
        $todo = [];
        foreach ($accounts as $account) {
            $username = explode("@", $account['email'])[0];
            $local_account = Account::where('username', $username)->first();
            if (is_null($local_account)) {
                $todo[] = $account;
            }
            else {
                if ($local_account->ativo) {
                    $active[] = $local_account;
                }
                else {
                    $queued[] = $local_account;
                }
            }
        }
        return view('institutional.index', compact('todo', 'queued', 'active'));
    }
}
