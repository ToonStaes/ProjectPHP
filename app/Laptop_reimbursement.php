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

    public function status_cc_manager() {
        return $this->belongsTo('App\Status', 'status_CC_manager')->withDefault();
    }

    public function status_fe() {
        return $this->belongsTo('App\Status', 'status_FE')->withDefault();
    }

    public function cost_center_manager() {
        return $this->belongsTo('App\User', 'user_id_Cost_center_manager')->withDefault();
    }

    public function financial_employee() {
        return $this->belongsTo('App\User', 'user_id_Financial_employee')->withDefault();
    }
}
