<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bike_reimbursementParameter extends Model
{
    // R26
    public function parameter() {
        return $this->belongsTo('App\Parameter')->withDefault(); // a Bike_reimbursementParameter has one paraameter
    }

    // R16
    public function bike_reimbursement() {
        return $this->belongsTo('App\Bike_reimbursement')->withDefault(); // a Bike_reimbursementParameter has one bike_reimbursement
    }
}
