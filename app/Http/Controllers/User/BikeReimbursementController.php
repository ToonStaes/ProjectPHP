<?php

namespace App\Http\Controllers\User;

use App\Bike_reimbursement;
use App\Bike_reimbursementParameter;
use App\Bikeride;
use App\Http\Controllers\Controller;
use App\Parameter;
use Auth;
use DateTime;
use Illuminate\Http\Request;
use Json;

class BikeReimbursementController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $user_id = $user->id;

        $saved_bikerides = Bikeride::with('user')
            ->where(function($query) use ($user_id){
                $query->where('user_id', 'like', $user_id);
            })
            ->whereNull('bike_reimbursement_id')
            ->get();

        $bike_reimbursement = new Bike_reimbursement();
        $bike_reimbursement->request_date = now();
        $bike_reimbursement->name = "Bike reimbursement " . $user->first_name . " " . $user->last_name;
        $bike_reimbursement->status_id = 1;
        $bike_reimbursement->save();

        foreach ($saved_bikerides as $bikeride) {
            $bikeride->bike_reimbursement_id = $bike_reimbursement->id;
            $bikeride->save();
        }

        session()->flash('success', "De fietsvergoeding is succesvol aangevraagd.");

        $requested_bikerides = Bikeride::with('user')
            ->where(function($query) use ($user_id){
                $query->where('user_id', 'like', $user_id);
            })
            ->whereNotNull('bike_reimbursement_id')
            ->get();

        $saved_fietsritten = "";
        foreach ($saved_bikerides as $bikeride){
            $saved_fietsritten = $saved_fietsritten . "," . $bikeride ->date;
        }
        $saved_fietsritten = substr($saved_fietsritten,1, strlen($saved_fietsritten)-1);

        $requested_fietsritten = "";
        foreach ($requested_bikerides as $bikeride){
            $requested_fietsritten = $requested_fietsritten . "," . $bikeride ->date;
        }
        $requested_fietsritten = substr($requested_fietsritten,1, strlen($requested_fietsritten)-1);
        $result = compact('saved_fietsritten', 'requested_fietsritten');

        //fietsvergoedingparameter aanmaken
        $bike_parameter = new Bike_reimbursementParameter();
        $parameter =  Parameter::whereNull('valid_until')->where('name', 'Fietsvergoeding')->get();
        $bike_parameter->parameter_id = $parameter[0]->id;
        $bike_parameter->bike_reimbursement_id = $bike_reimbursement->id;
        $bike_parameter->save();


        Json::dump($result);
        return view('user.request_bike_reimbursement', $result);
    }

}
