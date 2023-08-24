<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Group;
use Auth;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index()
    {
        return view('index');
    }
}
