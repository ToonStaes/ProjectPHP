<?php

namespace App\Http\Controllers\financial_employee;


use App\Cost_center;
use App\Http\Controllers\Controller;
use App\Parameter;
use Illuminate\Http\Request;
use Json;

class ParameterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cost_centers = Cost_center::orderBy('name')->get();
        $bike_reimbursement = Parameter::whereNull('valid_until')->where('name', 'Fietsvergoeding')->get();
        $car_reimbursement = Parameter::whereNull('valid_until')->where('name', 'Autovergoeding')->get();
        $laptop = Parameter::whereNull('valid_until')->where('name', 'Maximum schijfgrootte laptop')->get();
        $cost_center_laptopreimbursement = Parameter::whereNull('valid_until')->where('name', 'Standaard kostenplaats laptopvergoeding')->get();
        $cost_center_bikereimbursement = Parameter::whereNull('valid_until')->where('name', 'Standaard kostenplaats fietsvergoeding')->get();
        $result = compact('cost_centers', 'bike_reimbursement', 'car_reimbursement', 'laptop', 'cost_center_laptopreimbursement', 'cost_center_bikereimbursement');

        Json::dump($result);
        return view('financial_employee.parameters', $result);
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
     * @param  \App\Parameter  $parameter
     * @return \Illuminate\Http\Response
     */
    public function show(Parameter $parameter)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Parameter  $parameter
     * @return \Illuminate\Http\Response
     */
    public function edit(Parameter $parameter)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Parameter  $parameter
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Parameter $parameter)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Parameter  $parameter
     * @return \Illuminate\Http\Response
     */
    public function destroy(Parameter $parameter)
    {
        //
    }
}
