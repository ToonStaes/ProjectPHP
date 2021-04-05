<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HelpController extends Controller
{
    public function index(){
        return view('user.help.help');
    }

    public function bikereimbursement(){
        return view('user.help.bikereimbursment');
    }
}
