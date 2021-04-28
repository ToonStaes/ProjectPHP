<?php

namespace App\Http\Controllers\User;

use App\Bikeride;
use Json;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use function PHPUnit\Framework\isEmpty;

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
            ->where(function ($query) use ($user_id) {
                $query->where('user_id', 'like', $user_id);
            })
            ->whereNull('bike_reimbursement_id')
            ->get();
        $requested_bikerides = Bikeride::with('user')
            ->where(function ($query) use ($user_id) {
                $query->where('user_id', 'like', $user_id);
            })
            ->whereNotNull('bike_reimbursement_id')
            ->get();

        $saved_fietsritten = "";
        foreach ($saved_bikerides as $bikeride) {
            $saved_fietsritten = $saved_fietsritten . "," . $bikeride->date;
        }
        $saved_fietsritten = substr($saved_fietsritten, 1, strlen($saved_fietsritten) - 1);

        $requested_fietsritten = "";
        foreach ($requested_bikerides as $bikeride) {
            $requested_fietsritten = $requested_fietsritten . "," . $bikeride->date;
        }
        $requested_fietsritten = substr($requested_fietsritten, 1, strlen($requested_fietsritten) - 1);
        $result = compact('saved_fietsritten', 'requested_fietsritten');
        Json::dump($result);
        return view('user.request_bike_reimbursement', $result);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            "fietsritten" => "required"
        ]);
        $user = Auth::user();
        $user_id = $user->id;
        $bikerides = explode(',', $request->fietsritten);

        $melding = "";
        $melding_error = "";
        foreach ($bikerides as $datum) {
            $date = date_create_from_format('Y-m-d', $datum);
            $bikes = Bikeride::with('user')
                ->where(function ($query) use ($user_id) {
                    $query->where('user_id', 'like', $user_id);
                })
                ->whereDate('date', '=', $date)
                ->get();
            if(sizeof($bikes) == 0){
                $bikeride = new Bikeride();
                $bikeride->user_id = $user->id;
                $bikeride->date = $date;
                $bikeride->number_of_km = $user->number_of_km;
                $bikeride->save();
                if ($melding === "") {
                    $melding = $datum;
                } else {
                    $melding = $melding . ', ' . $datum;
                }
            }
            else{
                if ($melding_error === "") {
                    $melding_error = $datum;
                } else {
                    $melding_error = $melding . ', ' . $datum;
                }
            }

        }
        if($melding != ""){
            session()->flash('success', "De fietsritten <b>$melding</b> zijn toegevoegd.");
        }
        if($melding_error != ""){
            session()->flash('danger', "De fietsritten <b>$melding_error</b> bestaan al.");
        }


        $saved_bikerides = Bikeride::with('user')
            ->where(function ($query) use ($user_id) {
                $query->where('user_id', 'like', $user_id);
            })
            ->whereNull('bike_reimbursement_id')
            ->get();

        $requested_bikerides = Bikeride::with('user')
            ->where(function ($query) use ($user_id) {
                $query->where('user_id', 'like', $user_id);
            })
            ->whereNotNull('bike_reimbursement_id')
            ->get();

        $saved_fietsritten = "";
        foreach ($saved_bikerides as $bikeride) {
            $saved_fietsritten = $saved_fietsritten . "," . $bikeride->date;
        }
        $saved_fietsritten = substr($saved_fietsritten, 1, strlen($saved_fietsritten) - 1);

        $requested_fietsritten = "";
        foreach ($requested_bikerides as $bikeride) {
            $requested_fietsritten = $requested_fietsritten . "," . $bikeride->date;
        }
        $requested_fietsritten = substr($requested_fietsritten, 1, strlen($requested_fietsritten) - 1);
        $result = compact('saved_fietsritten', 'requested_fietsritten');
        Json::dump($result);
        return view('user.request_bike_reimbursement', $result);
    }

}
