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
        $emails = IDMail::find_lists($nusp);
        return view('institutional.index', compact('emails'));
    }
}
