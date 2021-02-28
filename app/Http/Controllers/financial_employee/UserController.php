<?php

namespace App\Http\Controllers\financial_employee;

use App\Http\Controllers\Controller;
use App\Mail\SendPasswordMail;
use App\User;
use Facades\App\Helpers\Json;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

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
            'voornaam' => 'required|min:3',
            'achternaam' => 'required|min:3',
            'adres' => 'required|min:3',
            'postcode' => 'required',
            'iban' => 'required',
            'email' => 'required|unique:users,email',
            'aantal_km' => 'required',
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

        $data = array(
            'naam' => $user->first_name,
            'email' => $user->email,
            'paswoord' => $wachtwoord,
        );

        Mail::to($user->email)->send(new SendPasswordMail($data));

        return response()->json([
            'type' => 'success',
            'text' => "The user <b>$user->first_name $user->last_name</b> has been added"
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
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
            'email' => 'required|email|unique:users,email,'.$user->userID.',userID',
            'aantal_km' => 'required',
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

        $user->number_of_km = $request->aantal_km;
        $user->save();
        return response()->json([
            'type' => 'success',
            'text' => "The user <b>$user->first_name $user->last_name</b> has been updated"
        ]);
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
        session()->flash('success', "The user <b>$user->name</b> has been deleted");

        $users = $this->getUsers();

        $result = compact('users');
        Json::dump($result);

        return $result;
    }

    public function getUsers()
    {
        return User::all()->transform(function ($item, $key) {
            $item->name = $item->first_name . ' ' . $item->last_name;
            $item->address = $item->address . ' ' . $item->zip_code . ' ' . $item->city;
            $item->is_active = $item->isActive;

            unset($item->first_name, $item->last_name, $item->zip_code, $item->city, $item->isActive);

            return $item;
        });
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
