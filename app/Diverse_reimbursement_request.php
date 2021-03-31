<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Diverse_reimbursement_request extends Model
{
    // R20
    public function diverse_reimbursement_lines() {
        return $this->hasMany('App\Diverse_reimbursement_line', 'DR_request_id');
    }

    // R22
    public function cost_center(){
        return $this->belongsTo('App\Cost_center')->withDefault();
    }

    // R10
    public function cost_center_manager() {
        return $this->belongsTo('App\User', 'user_id_CC_manager')->withDefault();
    }

    // R11
    public function user() {
        return $this->belongsTo('App\User', 'user_id')->withDefault();
    }

    //R21
    public function status_cc_manager() {
        return $this->belongsTo('App\Status', 'status_CC_manager')->withDefault();
    }

    //R31
    public function status_fe() {
        return $this->belongsTo('App\Status', 'status_FE')->withDefault();
    }

    // R9
    public function financial_employee() {
        return $this->belongsTo('App\User', 'user_id_Fin_employee')->withDefault();
    }
}
