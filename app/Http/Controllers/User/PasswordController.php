<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Mailcontent;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendPasswordReset;

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
            'password_confirmation' => 'required|min:8',
        ],[
            'current_password.required' => 'Gelieve uw huidig wachtwoord in te vullen.',
            'password.required' => 'Gelieve uw nieuw wachtwoord in te vullen.',
            'password.min' => 'Uw nieuw wachtwoord moet langer zijn dan 8 tekens.',
            'password.confirmed' => 'Uw nieuw wachtwoord moet hetzelfde zijn als de bevestiging.',
            'password_confirmation.required' => 'Gelieve de bevestiging van uw nieuw wachtwoord in te vullen.',
            'password_confirmation.min' => 'Uw nieuw wachtwoord moet langer zijn dan 8 tekens.',
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

    public function reset(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|exists:users,email',
        ]);

        $newPass = $this->randomPassword();

        $user = User::where('email', $request->email)->firstOrFail();
        $user->update(['password'=>Hash::make($newPass)]);
        $user->update(['changedPassword'=>false]);

        $mailcontent = Mailcontent::firstWhere('mailtype', 'Wachtwoord vergeten');
        $mailtext = $mailcontent->content;

        $mailtext = str_replace('[NAAM]', $user->first_name, $mailtext);
        $mailtext = str_replace('[EMAIL]', $user->email, $mailtext);
        $mailtext = str_replace('[WACHTWOORD]', $newPass, $mailtext);

        $mailtext = explode("\n", $mailtext);

        $data = array(
            'content'=>$mailtext
        );

        Mail::to($user->email)->send(new SendPasswordReset($data));

        session()->flash('success', 'We hebben je een mail gestuurd met een nieuw wachtwoord. Vergeet ook je SPAM niet na te kijken!');
        return back();
    }

    private function randomPassword() {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }
}
