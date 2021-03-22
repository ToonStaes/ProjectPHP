<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Laptop_reimbursement extends Model
{
    // R5
    public function laptop_invoice() {
        return $this->belongsTo('App\Laptop_invoice')->withDefault();
    }

    // R6
    public function laptop_reimbursement_parameters() {
        return $this->hasMany('App\Laptop_reimbursement_parameter');
    }

    public function status() {
        return $this->belongsTo('App\Status')->withDefault();
    }
}
