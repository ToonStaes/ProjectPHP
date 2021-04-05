<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HelpController extends Controller
{
    public function index(){
        return view('user.help.help');
    }

    public function bikereimbursement(){
        return view('user.help.bikereimbursement');
    }

    public function laptopreimbursement(){
        return view('user.help.laptopreimbrusement');
    }

    public function diversrequests(){
        return view('user.help.diversrequests');
    }

    public function myrequests(){
        return view('user.help.myrequests');
    }

}
