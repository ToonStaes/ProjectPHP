<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Laptop_invoice extends Model
{
    // R2
    public function financialEmployee() {
        return $this->belongsTo('App\User')->withDefault();
    }

    // R1
    public function cost_center_manager() {
        return $this->belongsTo('App\User')->withDefault();
    }

    // R3
    public function user() {
        return $this->belongsTo('App\User')->withDefault();
    }

    // R5
    public function laptop_reimbursements() {
        return $this->hasMany('App\Laptop_reimbursement');
    }
}
