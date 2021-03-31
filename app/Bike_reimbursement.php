<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bike_reimbursement extends Model
{
    // R14
    public function bikerides(){
        return $this->hasMany('App\Bikeride'); // a bike_reimbursement has many bikerides
    }

    // R16
    public function bike_reimbursement_parameters(){
        return $this->hasMany('App\bike_reimbursementParameter'); // a bike_reimbursement has many bike_reimbursementParameters
    }

    // R17
    public function status()
    {
        return $this->belongsTo('App\Status')->withDefault();   // a Bike_reimbursement belongs to a status
    }

    // R12
    public function users(){
        return $this->hasMany('App\User'); // a bike_reimbursement has many users
    }
}
