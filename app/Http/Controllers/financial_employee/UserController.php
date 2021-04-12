<?php

namespace App\Http\Controllers\financial_employee;

use App\Http\Controllers\Controller;
use App\Mail\SendNewUser;
use App\Mail\SendPasswordMail;
use App\Mailcontent;
use App\Programme;
use App\User;
use App\UserProgramme;
use Facades\App\Helpers\Json;
use http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Validator;
use View;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        $users = $this->getUsers();
//
//        $result = compact('users');
//        Json::dump($result);

        return view('financial_employee.users.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'voornaam' => 'required|min:2',
            'achternaam' => 'required|min:2',
            'adres' => 'required|min:3',
            'postcode' => 'required',
            'iban' => 'required',
            'email' => 'required|unique:users,email',
            'aantal_km' => 'required',
        ], [
            'voornaam.required' => 'Gelieve de voornaam in te vullen.',
            'voornaam.min' => 'Gelieve minstens 2 karakters in te geven.',
            'achternaam.required' => 'Gelieve de achternaam in te vullen.',
            'achternaam.min' => 'Gelieve minstens 2 karakters in te geven.',
            'adres.required' => 'Gelieve het adres in te vullen.',
            'adres.min' => 'Gelieve minstens 3 karakters in te geven.',
            'postcode.required' => 'Gelieve de postcode in te vullen.',
            'iban.required' => 'Gelieve de rekeningnummer in te vullen.',
            'email.required' => 'Gelieve het emailadres in te vullen.',
            'email.unique' => 'Dit emailadres is al in gebruik!',
            'aantal_km.required' => 'Gelieve het aantal kilometer in te vullen.',
        ]);

        $user = new User();
        $user->first_name = $request->voornaam;
        $user->last_name = $request->achternaam;
        $user->address = $request->adres;
        $user->city = $request->woonplaats;
        $user->zip_code = $request->postcode;
        $user->IBAN = $request->iban;
        $user->email = $request->email;
        $user->phone_number = $request->telefoonnummer;
        $user->isActive = true;

        $wachtwoord = $this->randomPassword();
        $user->password = Hash::make($wachtwoord);

        if ($request->actief == null){
            $user->isActive = false;
        } else {
            $user->isActive = true;
        }

        if ($request->kostenplaatsverantwoordelijke == null){
            $user->isCost_center_manager = false;
        } else {
            $user->isCost_center_manager = true;
        }

        if ($request->financieel_medewerker == null){
            $user->isFinancial_employee = false;
        } else {
            $user->isFinancial_employee = true;
        }

        $user->number_of_km = $request->aantal_km;
        $user->save();

        $programmes = explode(',', $request->opleidingen);

        foreach ($programmes as $programme){
            $newProgramme = new UserProgramme();
            $newProgramme->user_id = $user->id;
            $newProgramme->programme_id = $programme;
            $newProgramme->save();
        }

        $mailcontent = Mailcontent::firstWhere('mailtype', 'Nieuwe user');
        $mailtext = $mailcontent->content;

        $mailtext = str_replace('[NAAM]', $user->first_name, $mailtext);
        $mailtext = str_replace('[EMAIL]', $user->email, $mailtext);
        $mailtext = str_replace('[WACHTWOORD]', $wachtwoord, $mailtext);

        $mailtext = explode("\n", $mailtext);

        $data = array('content'=>$mailtext);

        Mail::to($user->email)->send(new SendNewUser($data));

        session()->flash('success', "De gebruiker <b>$user->first_name $user->last_name</b> is aangemaakt.");
        return View::make('shared.alert');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $user = User::with('userProgrammes.programme')->findOrFail($user->id);
        $user->is_active = $user->isActive;
        unset($user->isActive);
        return $user;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $this->validate($request,[
            'voornaam' => 'required|min:3',
            'achternaam' => 'required|min:3',
            'adres' => 'required|min:3',
            'postcode' => 'required',
            'iban' => 'required',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'aantal_km' => 'required',
        ], [
            'voornaam.required' => 'Gelieve de voornaam in te vullen.',
            'voornaam.min' => 'Gelieve minstens 2 karakters in te geven.',
            'achternaam.required' => 'Gelieve de achternaam in te vullen.',
            'achternaam.min' => 'Gelieve minstens 2 karakters in te geven.',
            'adres.required' => 'Gelieve het adres in te vullen.',
            'adres.min' => 'Gelieve minstens 3 karakters in te geven.',
            'postcode.required' => 'Gelieve de postcode in te vullen.',
            'iban.required' => 'Gelieve de rekeningnummer in te vullen.',
            'email.required' => 'Gelieve het emailadres in te vullen.',
            'email.unique' => 'Dit emailadres is al in gebruik!',
            'aantal_km.required' => 'Gelieve het aantal kilometer in te vullen.'
        ]);

        $user->first_name = $request->voornaam;
        $user->last_name = $request->achternaam;
        $user->address = $request->adres;
        $user->city = $request->woonplaats;
        $user->zip_code = $request->postcode;
        $user->IBAN = $request->iban;
        $user->email = $request->email;
        $user->phone_number = $request->telefoonnummer;

        if ($request->actief == null){
            $user->isActive = false;
        } else {
            $user->isActive = true;
        }

        if ($request->kostenplaatsverantwoordelijke == null){
            $user->isCost_center_manager = false;
        } else {
            $user->isCost_center_manager = true;
        }

        if ($request->financieel_medewerker == null){
            $user->isFinancial_employee = false;
        } else {
            $user->isFinancial_employee = true;
        }

        //Oude programmes verwijderen
        UserProgramme::where('user_id', $user->id)->delete();

        if ($request->opleidingen != null){

            //Nieuwe programmes toevoegen
            $programmes = explode(',', $request->opleidingen);

            foreach ($programmes as $programme){
                $newProgramme = new UserProgramme();
                $newProgramme->user_id = $user->id;
                $newProgramme->programme_id = $programme;
                $newProgramme->save();
            }
        }

        $user->number_of_km = $request->aantal_km;
        $user->save();

        session()->flash('success', "De gebruiker <b>$user->first_name $user->last_name</b> is bijgewerkt.");
        return View::make('shared.alert');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->isActive = 0;
        $user->save();

        session()->flash('success', "De gebruiker <b>$user->first_name $user->last_name</b> is gedeactiveerd");
        return View::make('shared.alert');
    }

    public function getUsers()
    {
        return User::with('userProgrammes.programme')
            ->get()
            ->transform(function ($item, $key) {
            $item->name = $item->first_name . ' ' . $item->last_name;
            $item->address = $item->address . ' ' . $item->zip_code . ' ' . $item->city;
            $item->is_active = $item->isActive;
            $exploded_mail = explode("@", $item->email);
            $item->email = $exploded_mail[0] . '&#8203;@' . $exploded_mail[1];

            unset($item->first_name, $item->last_name, $item->zip_code, $item->city, $item->isActive);

            return $item;
        });
    }

    public function getProgrammes(Request $request){
        $filter = '%' . $request->filter . '%';
        $programmes = Programme::where('name', 'like', $filter)->get();
        return $programmes;
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
