<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Parameter extends Model
{
    // R26
    public function bike_reimbursementParameters() {
        return $this->hasMany('App/Bike_reimbursementParameter');
    }

    // R25
    public function laptop_reimbursementParameters() {
        return $this->hasMany('App/Laptop_reimbursementParameter');
    }

    // R29
    public function cost_center() {
        return $this->belongsTo('App\Cost_center', 'standard_Cost_center_id')->withDefault();
    }

    // R18
    public function diverse_reimbursement_lines() {
        return $this->hasMany('App/Diverse_reimbursement_line');
    }
}
