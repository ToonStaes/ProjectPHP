<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Laptop_reimbursement_parameter extends Model
{
    // R6
    public function laptop_reimbursement() {
        return $this->belongsTo('App\Laptop_reimbursement')->withDefault();
    }

    // R25
    public function parameter() {
        return $this->belongsTo('App\Parameter')->withDefault();
    }
}
