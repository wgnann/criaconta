<?php

namespace App\Http\Controllers;

use App\Account;
use App\PasswordRequest;
use Auth;
use Illuminate\Http\Request;

class PasswordRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('resetPassword', 'listRequests');
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
        if (!$account) {
            die("conta não existente.");
        }

        $password_request = PasswordRequest::where([
            ['account_id', $account->id],
            ['ativo', 1]
        ])->first();

        return view('password.index', compact('password_request'));
    }

    public function store()
    {
        $account = Account::where([
            ['user_id', Auth::user()->id],
            ['type', 'pessoal']
        ])->first();
        if (!$account) {
            die("conta não existente.");
        }

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

        return redirect("/password");
    }

    public function resetPassword($id, Request $request)
    {
        if (!$this->authAPI($request)) {
            return response('Forbidden.', 403);
        }

        $password_request = PasswordRequest::where([
            ['id', $id],
            ['ativo', 1]
        ])->first();

        if (!$password_request) {
            return response('Not found.', 404);
        }

        $password_request->ativo = 0;
        $password_request->save();

        return response('Success.', 200);
    }

    public function listRequests(Request $request)
    {
        $password_requests = PasswordRequest::where('ativo', 1)->get();
        $request_list = [];
        foreach ($password_requests as $pr) {
            array_push($request_list, [
                'id' => $pr->id,
                'owner' => $pr->account->user->nusp,
                'owner_name' => $pr->account->user->name,
                'owner_email' => $pr->account->user->email,
                'username' => $pr->account->username
            ]);
        }
        return response()->json($request_list);
    }
}
