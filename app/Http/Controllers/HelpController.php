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

    public function manageRequests(){
        return view('financial_employee.help.manageRequest');
    }

    public function manageUsers(){
        return view('financial_employee.help.manageUsers');
    }

    public function manageCostcenters(){
        return view('financial_employee.help.manageCostcenters');
    }

    public function manageMail(){
        return view('financial_employee.help.manageMail');
    }

    public function manageParameters(){
        return view('financial_employee.help.manageParameters');
    }

}
