<?php

namespace App\Http\Controllers\User;

use App\Bikeride;
use Json;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;

class BikerideController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        $user_id = $user->id;
        $saved_bikerides = Bikeride::with('user')
            ->where(function($query) use ($user_id){
                $query->where('user_id', 'like', $user_id);
            })
            ->whereNull('bike_reimbursement_id')
            ->get();
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
        $user_id = $user->id;
        $bikerides = explode(',', $request->fietsritten);
        $melding="";
        foreach ($bikerides as $datum) {
            if($melding===""){
                $melding = $datum;
            }
            else{
                $melding = $melding . ', ' . $datum;
            }
            $datum = date_create_from_format('Y-m-d', $datum);
            $bikeride = new Bikeride();
            $bikeride->user_id = $user->id;
            $bikeride->date = $datum;
            $bikeride->number_of_km = $user->number_of_km;
            $bikeride->save();
        }
        session()->flash('success', "De fietsritten <b>$melding</b> zijn toegevoegd.");
        $bikerides2 = Bikeride::with('user')
            ->where(function($query) use ($user_id){
                $query->where('user_id', 'like', $user_id);
            })
            ->get();
        $fietsritten = "";
        foreach ($bikerides2 as $bikeride){
            $fietsritten = $fietsritten . "," . $bikeride->date;
        }
        $fietsritten = substr($fietsritten,1, strlen($fietsritten)-1);
        $result = compact('bikerides', 'fietsritten');
        Json::dump($result);
        return view('user.request_bike_reimbursement', $result);
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
