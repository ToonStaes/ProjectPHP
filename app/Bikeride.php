<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bikeride extends Model
{
    // R13
    public function user() {
        return $this->belongsTo('App\User')->withDefault(); // a Bikeride has one user
    }

    // R14
    public function bike_reimbursement() {
        return $this->belongsTo('App\Bike_reimbursement')->withDefault(); // a Bikeride has one bike_reimbursement
    }
}
