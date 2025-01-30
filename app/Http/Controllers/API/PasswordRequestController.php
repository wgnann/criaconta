<?php

namespace App\Http\Controllers\API;

use App\Models\PasswordRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PasswordRequestController extends Controller
{
    private function authAPI(Request $request)
    {
        return ($request->api_key == getenv('API_KEY'));
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
        if (!$this->authAPI($request)) {
            return response('Forbidden.', 403);
        }

        $password_requests = PasswordRequest::where('ativo', 1)->get();
        $request_list = [];
        foreach ($password_requests as $pr) {
            array_push($request_list, [
                'id' => $pr->id,
                'owner' => $pr->account->user->codpes,
                'owner_name' => $pr->account->user->name,
                'owner_email' => $pr->account->user->email,
                'username' => $pr->account->username
            ]);
        }
        return response()->json($request_list);
    }
}
