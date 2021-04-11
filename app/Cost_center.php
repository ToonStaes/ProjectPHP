<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cost_center extends Model
{
    // R7
    public function user() {
        return $this->belongsTo('App\User', 'user_id_Cost_center_manager')->withDefault(); // a cost_center has one user
    }

    // R8
    public function cost_center_budgets(){
        return $this->hasMany('App\Cost_center_budget'); // a cost_center has many cost_centers
    }

    // R23
    public function programme_cost_centers(){
        return $this->hasMany('App\ProgrammeCost_center'); // a cost_center has many programmeCost_centers
    }

    // R29
    public function parameters(){
        return $this->hasMany('App\Parameter', 'standard_Cost_center_id'); // a cost_center has many parameters
    }

    // R22
    public function diverse_reimbursement_requests(){
        return $this->hasMany('App\Diverse_reimbursement_request'); // a cost_center has many diverse_reimbursement_requests
    }

    //R23
    public function programmes() {
        return $this->belongsToMany('App\Programme', 'programme_cost_centers');
    }
}
