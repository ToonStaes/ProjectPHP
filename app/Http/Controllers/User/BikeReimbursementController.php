<?php

namespace App\Http\Controllers\User;

use App\Bike_reimbursement;
use App\Bikeride;
use App\Http\Controllers\Controller;
use Auth;
use DateTime;
use Illuminate\Http\Request;
use Json;

class BikeReimbursementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

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
        Json::dump($result);
        return view('user.request_bike_reimbursement', $result);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Bike_reimbursement  $bike_reimbursement
     * @return \Illuminate\Http\Response
     */
    public function show(Bike_reimbursement $bike_reimbursement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Bike_reimbursement  $bike_reimbursement
     * @return \Illuminate\Http\Response
     */
    public function edit(Bike_reimbursement $bike_reimbursement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Bike_reimbursement  $bike_reimbursement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bike_reimbursement $bike_reimbursement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Bike_reimbursement  $bike_reimbursement
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bike_reimbursement $bike_reimbursement)
    {
        //
    }
}
