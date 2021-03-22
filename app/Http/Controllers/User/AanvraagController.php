<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AanvraagController extends Controller
{
    public function index() {
        
        return view('user.MijnAanvragen.mijnAanvragen');
    }
}
