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
        /*
         *  using joins to test, eloquent model relations are required
         *  to use the "with"-statement
         * */
        $data = Cost_center::join('programme_cost_centers as pcc', 'cost_centers.cost_centerID', '=', 'pcc.cost_centerID')
            ->join('programmes as pgs', 'pcc.programmeID', '=', 'pgs.programmeID')
            ->join('cost_center_budgets as ccb', 'pcc.cost_centerID', '=', 'ccb.cost_centerID')
            ->join('users as u', 'cost_centers.userID_Cost_center_manager', '=', 'u.userID')
            ->select('cost_centers.name as cost_center_name', 'pgs.name as programme_name', 'ccb.amount',
                'cost_centers.description', 'u.first_name', 'u.last_name', 'cost_centers.cost_centerID');

        $toReturn = $data->get();
        $table_data = compact('toReturn');
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
        //Bug: $cost_center is always empty
        $this->validate($request, ['budget'=>'required|integer']);

        $cost_center_budget = Cost_center_budget::where("cost_centerID", "=", $request->id);
        $cost_center_budget->update(['amount'=>$request->budget]);
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
        //Bug: $cost_center is always empty
        $id = $cost_center->id ?? 0;
        $cost_center->delete();
        return response(['r'=>'ok','id'=>$id]);
    }
}
