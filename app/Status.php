<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    // R27
    public function diverse_reimbursement_requests() {
        return $this->hasMany('App/Diverse_reimbursement_request');
    }

    //R17
    public function bike_reimbursements() {
        return $this->hasMany('App/Bike_reimbursement');
    }
}
