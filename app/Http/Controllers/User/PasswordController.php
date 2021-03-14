<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    public function edit()
    {
        return view('user.password');
    }

    public function update(Request $request)
    {
        $this->validate($request,[
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::findOrFail(auth()->user()->id);
        if (!Hash::check($request->current_password, $user->password)) {
            session()->flash('danger', "Het huidige wachtwoord dat u heeft opgegeven is niet correct.");
            return back();
        }
        $user->password = Hash::make($request->password);

        if (!$user->changedPassword){
            $user->changedPassword = true;

            $user->save();

            session()->flash('success', 'Uw nieuw wachtwoord is opgeslagen.');
            return redirect('/');
        } else {
            $user->save();

            session()->flash('success', 'Uw nieuw wachtwoord is opgeslagen.');
            return back();
        }
    }
}
