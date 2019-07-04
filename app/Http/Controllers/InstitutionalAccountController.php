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
        $idmail = new IDMail();
        $json = json_decode($idmail->id_get_emails($nusp));
        $emails = $idmail->list_emails($json, "ime.usp.br", ["Institucional", "Grupo"]);
        return view('institutional.index', compact('emails'));
    }
}
