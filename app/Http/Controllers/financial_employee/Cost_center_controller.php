<?php

namespace App\Http\Controllers\financial_employee;

use App\ProgrammeCost_center;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Json;
use App\Parameter;
use App\Cost_center;
use App\Cost_center_budget;
use App\Programme;
use App\Laptop_reimbursementParameter;
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
        $cost_centers = Cost_center::with(['user', 'cost_center_budgets', 'programmes'])->get();
        $users = User::where('isActive', 1)
            ->where('isCost_center_manager', 1)
            ->get()
            ->transform(function($item, $key){
                unset($item['address']);
                unset($item['city']);
                unset($item['zip_code']);
                unset($item['IBAN']);
                unset($item['email']);
                unset($item['phone_number']);
                unset($item['password']);
                unset($item['isActive']);
                unset($item['isCost_center_manager']);
                unset($item['isFinancial_employee']);
                unset($item['number_of_km']);

                return $item;
        });
        $programmes = Programme::all();

        Json::dump($users);

        $table_data = compact(['cost_centers', 'users', 'programmes']);

        return view('financial_employee.cost_centers.cost_center', $table_data);
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
            'programme_id'=>'required|integer|min:0|exists:programmes,id',
            'user_id'=>'required|integer|exists:users,id',
            'budget'=>'integer|min:0',
            'cost_center_name'=>'required_without:cost_center_id|string',
            'description'=>'string',
            'isActive'=>'boolean'
        ]);

        $cost_center_budget = new Cost_center_budget();

        /*
         *  It is possible that we want to create a new
         *  cost center, or that the name of an already
         *  existing cost center is given, this is what
         *  we check for here
         * */
        if(empty($request->cost_center_id)) {
            try {
                //  We check if we can find the cost center
                $cost_center = Cost_center::where('name', $request->cost_center_name)->firstOrFail();
                $request->cost_center_id = $cost_center->id;
                $cost_center_budget->cost_center_id = $request->cost_center_id;
            }
            catch (ModelNotFoundException $e) {
                /*
                 *  If there is no existing cost center with
                 *  this name, we create a new one
                 * */
                $cost_center = new Cost_center();
                $cost_center->user_id_Cost_center_manager = $request->user_id;
                $cost_center->name = $request->cost_center_name;
                $cost_center->description = $request->description;
                $cost_center->isActive = $request->isActive;

                $request->cost_center_id = $cost_center->id;
            }
        }
        else{
            $cost_center_budget->cost_center_id = $request->cost_center_id;
        }

        $cost_center_budget->amount = $request->budget;
        $cost_center_budget->year = strval(date('Y'));

        $programme_cost_center = new ProgrammeCost_center();
        $programme_cost_center->programme_id = $request->programme_id;
        $programme_cost_center->cost_center_id = $request->cost_center_id;

        $cost_center->save();
        $cost_center_budget->save();
        $programme_cost_center->save();
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
        // Server-side validation of form input data
        $this->validate($request, ['budget'=>'required|integer|min:0']);

        /*
         * Just in case the cost_center does not yet
         * have a cost_center_budget, we check if it does
         *
         * If there is, we just update that one,
         * If there is none, we need to create a new one
         * */
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
        $parameters = Parameter::where('standard_Cost_center_id', $id);

        /*
         * The relations between parameter and cost_centers
         * is defined using a DTR (restrict on delete)
         *
         * This means we need to delete all corresponding
         * parameters first
         *
         * However, the relationship between parameter and
         * laptop_reimbursement_param also has a DTR,
         * so we also need to delete any of these first
         * */
        foreach($parameters->get() as $parameter){
            $laptop_reimbursement_param = Laptop_reimbursementParameter::where('parameter_id', $parameter->id);
            $laptop_reimbursement_param->delete();
        }
        $parameters->delete();
        $cost_center->delete();

        //respons with ok + the id of the deleted cost_center
        return response(['r'=>'ok','id'=>$id]);
    }
}
