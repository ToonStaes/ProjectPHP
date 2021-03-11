<?php

namespace App\Http\Controllers\User;

use App\Bikeride;
use App\Helpers\Json;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;

class BikerideController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('user.request_bike_reimbursement');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

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
        $datums = explode(',', $request->fietsritten);
        $melding="";
        foreach ($datums as $datum) {
            if($melding===""){
                $melding = $datum;
            }
            else{
                $melding = $melding . ', ' . $datum;
            }
            $datum = date_create_from_format('d/m/Y', $datum);
            $bikeride = new Bikeride();
            $bikeride->user_id = $user->id;
            $bikeride->date = $datum;
            $bikeride->number_of_km = $user->number_of_km;
            $bikeride->save();
        }
        session()->flash('success', "De fietsritten <b>$melding</b> zijn toegevoegd.");
        return view('user.request_bike_reimbursement');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Bikeride  $bikeride
     * @return \Illuminate\Http\Response
     */
    public function show(Bikeride $bikeride)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Bikeride  $bikeride
     * @return \Illuminate\Http\Response
     */
    public function edit(Bikeride $bikeride)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Bikeride  $bikeride
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bikeride $bikeride)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Bikeride  $bikeride
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bikeride $bikeride)
    {

    }
}
