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
        $bikerides = Bikeride::with('user')
            ->where(function($query) use ($user_id){
                $query->where('user_id', 'like', $user_id);
            })
            ->get();
        $datums = array();
        foreach ($bikerides as $bikeride) {
            array_push($datums, $bikeride->date);
        }
        $result = compact('bikerides');
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
        $bikerides = explode(',', $request->fietsritten);
        $melding="";
        foreach ($bikerides as $datum) {
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
        $fietsritten = "";
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
