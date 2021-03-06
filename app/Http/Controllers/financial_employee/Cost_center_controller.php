<?php

namespace App\Http\Controllers\financial_employee;

use Json;
use App\Cost_center;
use App\Cost_center_budget;
use App\Programme;
use App\ProgrammeCost_center;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class Cost_center_controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Cost_center::with(['user', 'cost_center_budgets', 'programmes']);
        $cost_centers = $data->get();

        $table_data = compact('cost_centers');

        return view('financial_employee.cost_center', $table_data);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Cost_center  $cost_center
     * @return \Illuminate\Http\Response
     */
    public function show(Cost_center $cost_center)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Cost_center  $cost_center
     * @return \Illuminate\Http\Response
     */
    public function edit(Cost_center $cost_center)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Cost_center  $cost_center
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cost_center $cost_center)
    {
        $this->validate($request, ['budget'=>'required|integer']);

        if(Cost_center_budget::where('cost_center_id', '=', $cost_center->id)->exists()) {
            $cost_center_budget = Cost_center_budget::where('cost_center_id', '=', $cost_center->id);
            $cost_center_budget->update(['amount'=>$request->budget]);
        }
        else {
            $cost_center_budget = new Cost_center_budget();
            $cost_center_budget->cost_center_id = $cost_center->id;
            $cost_center_budget->amount = $request->budget;
            $cost_center_budget->year = strval(date('Y'));
            $cost_center_budget->save();
        }
        return response(['r'=>'ok']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Cost_center  $cost_center
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cost_center $cost_center)
    {
        $id = $cost_center->id;
        $cost_center->delete();
        return response(['r'=>'ok','id'=>$id]);
    }
}
