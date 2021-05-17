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
        $cost_centers = Cost_center::where('isActive', true)
            ->with(['user', 'cost_center_budgets', 'programmes'])
            ->get()
            ->transform(function($item, $key){
                //  cost center attributes
                $item['name'] = htmlentities($item['name']);
                $item['description'] = htmlentities($item['description']);

                //  user attributes
                $item['first_name'] = htmlentities($item['first_name']);
                $item['last_name'] = htmlentities($item['last_name']);

                //  programmes attributes
                $item['name'] = htmlentities($item['name']);
            });

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

                $item['first_name'] = htmlentities($item['first_name']);
                $item['last_name'] = htmlentities($item['last_name']);

                return $item;
        });
        $programmes = Programme::all();

        $cost_center_names = [];
        foreach($cost_centers as $cost_center){
            if(!in_array($cost_center->name, $cost_center_names)){
                array_push($cost_center_names, $cost_center->name);
            }
        }

        $table_data = compact(['cost_centers', 'users', 'programmes', 'cost_center_names']);

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
            'cost_center_name'=>'required_without:cost_center_id|string|max:256',
            'description'=>'string|max:1024|nullable',
            'isActive'=>'boolean'
        ],
        [
            'programme_id.required'=>'Je moet een unit kiezen',
            'programme_id.integer'=>'Je moet een unit kiezen',
            'programme_id.min'=>'Je moet een unit kiezen',
            'programme_id.exists'=>'De gekozen unit bestaat niet',
            'user_id.required'=>'Je moet een verantwoordelijke kiezen',
            'user_id.integer'=>'Je moet een verantwoordelijke kiezen',
            'user_id.exists'=>'De gekozen verantwoordelijke bestaat niet',
            'budget.integer'=>'Het budget moet een getal zijn',
            'budget.min'=>'Het budget kan minimaal €0 zijn',
            'cost_center_name.required_without'=>'De naam van de kostenplaats is verplicht',
            'cost_center_name.string'=>'De naam van de kostenplaats moet tekst zijn',
            'cost_center_name.max'=>'De naam mag maximaal 256 tekens lang zijn',
            'description.string'=>'De omschrijving moet een tekst zijn',
            'description.max'=>'De omschrijving mag maximaal 1024 tekens lang zijn',
        ]);

        //  We need to check if a cost center with
        //  the same programme already exists
        $possible_conflicts = Cost_center::where('name', $request->cost_center_name)->with('programme_cost_centers')->get();
        if(count($possible_conflicts)>0){
            foreach($possible_conflicts as $possible_conflict){
                if($possible_conflict->programme_cost_centers[0]->programme_id == $request->programme_id){
                    abort(409, "Deze unit heeft al een kostenplaats met naam ".$request->cost_center_name.".");
                }
            }
        }

        $cost_center_budget = new Cost_center_budget();

        $cost_center = new Cost_center();
        $cost_center->user_id_Cost_center_manager = $request->user_id;
        $cost_center->name = $request->cost_center_name;
        $cost_center->description = $request->description;
        $cost_center->isActive = $request->isActive;
        $cost_center->save();

        $request->cost_center_id = $cost_center->id;
        $cost_center_budget->cost_center_id = $cost_center->id;

        $cost_center_budget->amount = $request->budget;
        $cost_center_budget->year = strval(date('Y'));

        $programme_cost_center = new ProgrammeCost_center();
        $programme_cost_center->programme_id = $request->programme_id;
        $programme_cost_center->cost_center_id = $request->cost_center_id;

        $cost_center_budget->save();
        $programme_cost_center->save();

        return response(['id'=>$request->cost_center_id]);
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
        $this->validate($request, [
            'resp'=>'required_without:budget|integer|min:0',
            'budget'=>'required_without:resp|integer|min:0'
        ]);

        if(!is_null($request->budget)){
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
        $cost_center->user_id_Cost_center_manager = $request->resp;
        $cost_center->save();
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
        $cost_center->isActive = false;
        $cost_center->save();

        //response with ok + the id of the deleted cost_center
        return response(['r'=>'ok','id'=>$cost_center->id]);
    }
}
