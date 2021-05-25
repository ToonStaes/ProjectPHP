<?php

namespace App\Http\Controllers\financial_employee;


use App\Cost_center;
use App\Http\Controllers\Controller;
use App\Parameter;
use DateTime;
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

        return view('financial_employee.parameters', $result);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $melding = "";
        $this->validate($request,[
            'bikereimbursement' => 'required|numeric|min:0',
            'carreimbursement' => 'required|numeric|min:0',
            'laptop' => 'required|numeric|min:0',
            'cost_center_laptopreimbursement' => 'required',
            'cost_center_bikereimbursement' => 'required',
        ], [
            'bikereimbursement.numeric' => 'Het bedrag voor de fietsvergoeding moet een getal zijn.',
            'bikereimbursement.min' => 'Het bedrag voor de fietsvergoeding moet groter of gelijk zijn aan 0.',
            'bikereimbursement.required' => 'Het bedrag voor de fietsvergoeding moet ingevuld zijn.',
            'carreimbursement.numeric' => 'Het bedrag voor de autovergoeding moet een getal zijn.',
            'carreimbursement.min' => 'Het bedrag voor de autovergoeding moet groter of gelijk zijn aan 0.',
            'carreimbursement.required' => 'Het bedrag voor de autovergoeding moet ingevuld zijn.',
            'laptop.numeric' => 'Het bedrag voor de maximum schijfgrootte laptopvergoeding moet een getal zijn.',
            'laptop.min' => 'Het bedrag voor de maximum schijfgrootte laptopvergoeding moet groter of gelijk zijn aan 0.',
            'laptop.required' => 'Het bedrag voor de maximum schijfgrootte laptopvergoeding moet ingevuld zijn.',
            'cost_center_laptopreimbursement.required' => 'Selecteer een kostenplaats.',
            'cost_center_bikereimbursement.required' => 'Selecteer een kostenplaats.',
        ]);

        //fietsvergoeding
        $bike_reimbursement = Parameter::whereNull('valid_until')->where('name', 'Fietsvergoeding')->get();
        if($bike_reimbursement->isEmpty()){
            //nieuwe fietsvergoeding aanmaken
            $bike_reimbursement_new = new Parameter();
            $bike_reimbursement_new->name = "Fietsvergoeding";
            $bike_reimbursement_new->valid_from = new DateTime();
            $bike_reimbursement_new->amount_per_km = $request->bikereimbursement;
            $bike_reimbursement_new->description = 'De prijs die een WN ontvangt per gefietste kilometer voor woon-werkverkeer';
            $bike_reimbursement_new->save();
            $melding = $melding . "De fietsvergoeding is aangemaakt, <b>€ $bike_reimbursement_new->amount_per_km</b> per km. ";
        }
        else{
            if($request->bikereimbursement != $bike_reimbursement[0]->amount_per_km){
                //oude fietsvergoeding valid until aanpassen
                $bike_reimbursement[0]->valid_until = new DateTime();
                $bike_reimbursement[0]->save();

                //nieuwe fietsvergoeding aanmaken
                $bike_reimbursement_new = new Parameter();
                $bike_reimbursement_new->name = $bike_reimbursement[0]->name;
                $bike_reimbursement_new->valid_from = $bike_reimbursement[0]->valid_until;
                $bike_reimbursement_new->amount_per_km = $request->bikereimbursement;
                $bike_reimbursement_new->description = $bike_reimbursement[0]->description;
                $bike_reimbursement_new->save();
                $melding = $melding . "De fietsvergoeding is aangepast naar <b>€ $bike_reimbursement_new->amount_per_km</b> per km. ";
            }
        }

        //autovergoeding
        $car_reimbursement = Parameter::whereNull('valid_until')->where('name', 'Autovergoeding')->get();
        if($car_reimbursement->isEmpty()){
            //nieuwe autovergoeding aanmaken
            $car_reimbursement_new = new Parameter();
            $car_reimbursement_new->valid_from = new DateTime();
            $car_reimbursement_new->name = "Autovergoeding";
            $car_reimbursement_new->amount_per_km = $request->carreimbursement;
            $car_reimbursement_new->description = 'De prijs die een WN ontvangt per afgelegde kilometer voor verplaatsingen tijdens schooluren. Bijvoorbeeld stagebezoeken ...';
            $car_reimbursement_new->save();
            $melding = $melding . "De autovergoeding is aangemaakt, <b>€ $car_reimbursement_new->amount_per_km</b> per km. ";
        }
        else{
            if($request->carreimbursement != $car_reimbursement[0]->amount_per_km){
                //oude autovergoeding valid until aanpassen
                $car_reimbursement[0]->valid_until = new DateTime();
                $car_reimbursement[0]->save();

                //nieuwe autovergoeding aanmaken
                $car_reimbursement_new = new Parameter();
                $car_reimbursement_new->valid_from = $car_reimbursement[0]->valid_until;
                $car_reimbursement_new->name = $car_reimbursement[0]->name;
                $car_reimbursement_new->amount_per_km = $request->carreimbursement;
                $car_reimbursement_new->description = $car_reimbursement[0]->description;
                $car_reimbursement_new->save();
                $melding = $melding  . "De autovergoeding is aangepast naar <b>€ $car_reimbursement_new->amount_per_km</b> per km. ";
            }
        }

        //max schijfgrootte laptop
        $laptop = Parameter::whereNull('valid_until')->where('name', 'Maximum schijfgrootte laptop')->get();
        if($laptop->isEmpty()){
            //nieuwe laptop aanmaken
            $laptop_new = new Parameter();
            $laptop_new->valid_from = new DateTime();
            $laptop_new->name = 'Maximum schijfgrootte laptop';
            $laptop_new->max_reimbursement_laptop = $request->laptop;
            $laptop_new->description = 'Maximale schijfgrootte voor terugbetaling van laptop';
            $laptop_new->save();
            $melding = $melding . "De maximum schijfgrootte laptop is aangemaakt, <b>€ $laptop_new->max_reimbursement_laptop</b>. ";
        }
        else{
            if($request->laptop != $laptop[0]->max_reimbursement_laptop){
                //oude laptop valid until aanpassen
                $laptop[0]->valid_until = new DateTime();
                $laptop[0]->save();

                //nieuwe laptop aanmaken
                $laptop_new = new Parameter();
                $laptop_new->valid_from = $laptop[0]->valid_until;
                $laptop_new->name = $laptop[0]->name;
                $laptop_new->max_reimbursement_laptop = $request->laptop;
                $laptop_new->description = $laptop[0]->description;
                $laptop_new->save();
                $melding = $melding . "De maximum schijfgrootte laptop is aangepast naar <b>€ $laptop_new->max_reimbursement_laptop</b>. ";
            }
        }

        //laptopkostenplaats
        $cost_center_laptopreimbursement = Parameter::whereNull('valid_until')->where('name', 'Standaard kostenplaats laptopvergoeding')->get();
        if($cost_center_laptopreimbursement->isEmpty()){
            //nieuwe laptopkostenplaats aanmaken
            $cost_center_laptopreimbursement_new = new Parameter();
            $cost_center_laptopreimbursement_new->valid_from = new DateTime();
            $cost_center_laptopreimbursement_new->name = 'Standaard kostenplaats laptopvergoeding';
            $cost_center_laptopreimbursement_new->standard_Cost_center_id = $request->cost_center_laptopreimbursement;
            $cost_center_laptopreimbursement_new->description = 'Standaard kostenplaats voor terugbetaling van laptop';
            $cost_center_laptopreimbursement_new->save();
            $cost_center = Cost_center::where('id', $cost_center_laptopreimbursement_new->standard_Cost_center_id)->get();
            $cost_center_name = $cost_center[0]->name;
            $melding = $melding . "De standaard kostenplaats voor de laptopvergoeding is aangemaakt, <b>$cost_center_name</b>. ";
        }
        else{
            if($request->cost_center_laptopreimbursement != $cost_center_laptopreimbursement[0]->standard_Cost_center_id){
                //oude laptopkostenplaats valid until aanpassen
                $cost_center_laptopreimbursement[0]->valid_until = new DateTime();
                $cost_center_laptopreimbursement[0]->save();

                //nieuwe laptopkostenplaats aanmaken
                $cost_center_laptopreimbursement_new = new Parameter();
                $cost_center_laptopreimbursement_new->valid_from = $cost_center_laptopreimbursement[0]->valid_until;
                $cost_center_laptopreimbursement_new->name = $cost_center_laptopreimbursement[0]->name;
                $cost_center_laptopreimbursement_new->standard_Cost_center_id = $request->cost_center_laptopreimbursement;
                $cost_center_laptopreimbursement_new->description = $cost_center_laptopreimbursement[0]->description;
                $cost_center_laptopreimbursement_new->save();
                $cost_center = Cost_center::where('id', $cost_center_laptopreimbursement_new->standard_Cost_center_id)->get();
                $cost_center_name = $cost_center[0]->name;
                $melding = $melding . "De standaard kostenplaats voor de laptopvergoeding is aangepast naar <b>$cost_center_name</b>. ";
            }
        }


        //fietskostenplaats
        $cost_center_bikereimbursement = Parameter::whereNull('valid_until')->where('name', 'Standaard kostenplaats fietsvergoeding')->get();
        if($cost_center_bikereimbursement->isEmpty()){
            //nieuwe fietskostenplaats aanmaken
            $cost_center_bikereimbursement_new = new Parameter();
            $cost_center_bikereimbursement_new->valid_from = new DateTime();
            $cost_center_bikereimbursement_new->name = 'Standaard kostenplaats fietsvergoeding';
            $cost_center_bikereimbursement_new->standard_Cost_center_id = $request->cost_center_bikereimbursement;
            $cost_center_bikereimbursement_new->description = 'Standaard kostenplaats voor de fietsvergoedingen';
            $cost_center_bikereimbursement_new->save();
            $cost_center = Cost_center::where('id', $cost_center_bikereimbursement_new->standard_Cost_center_id)->get();
            $cost_center_name = $cost_center[0]->name;
            $melding = $melding .  "De standaard kostenplaats voor de fietsvergoeding is aangemaakt, <b>$cost_center_name</b>. ";
        }
        else{
            if($request->cost_center_bikereimbursement != $cost_center_bikereimbursement[0]->standard_Cost_center_id){
                //oude fietskostenplaats valid until aanpassen
                $cost_center_bikereimbursement[0]->valid_until = new DateTime();
                $cost_center_bikereimbursement[0]->save();

                //nieuwe fietskostenplaats aanmaken
                $cost_center_bikereimbursement_new = new Parameter();
                $cost_center_bikereimbursement_new->valid_from = $cost_center_bikereimbursement[0]->valid_until;
                $cost_center_bikereimbursement_new->name = $cost_center_bikereimbursement[0]->name;
                $cost_center_bikereimbursement_new->standard_Cost_center_id = $request->cost_center_bikereimbursement;
                $cost_center_bikereimbursement_new->description = $cost_center_bikereimbursement[0]->description;
                $cost_center_bikereimbursement_new->save();
                $cost_center = Cost_center::where('id', $cost_center_bikereimbursement_new->standard_Cost_center_id)->get();
                $cost_center_name = $cost_center[0]->name;
                $melding = $melding .  "De standaard kostenplaats voor de fietsvergoeding is aangepast naar <b>$cost_center_name</b>. ";

            }
        }
        session()->flash('success', $melding);
        $cost_centers = Cost_center::orderBy('name')->get();
        $bike_reimbursement = Parameter::whereNull('valid_until')->where('name', 'Fietsvergoeding')->get();
        $car_reimbursement = Parameter::whereNull('valid_until')->where('name', 'Autovergoeding')->get();
        $laptop = Parameter::whereNull('valid_until')->where('name', 'Maximum schijfgrootte laptop')->get();
        $cost_center_laptopreimbursement = Parameter::whereNull('valid_until')->where('name', 'Standaard kostenplaats laptopvergoeding')->get();
        $cost_center_bikereimbursement = Parameter::whereNull('valid_until')->where('name', 'Standaard kostenplaats fietsvergoeding')->get();
        $result = compact('cost_centers', 'bike_reimbursement', 'car_reimbursement', 'laptop', 'cost_center_laptopreimbursement', 'cost_center_bikereimbursement');

        return view('financial_employee.parameters', $result);
    }
}
