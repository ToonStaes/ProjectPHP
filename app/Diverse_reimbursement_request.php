<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Diverse_reimbursement_request extends Model
{
    // R20
    public function diverse_reimbursement_lines() {
        return $this->hasMany('App\Diverse_reimbursement_line', 'DR_request_id');
    }

    // R21
    public function status(){
        return $this->belongsTo('App\Status')->withDefault();
    }

    // R22
    public function cost_center(){
        return $this->belongsTo('App\Cost_center')->withDefault();
    }

    // R9
    public function financialEmployee() {
        return $this->belongsTo('App\User')->withDefault();
    }

    // R10
    public function cost_center_manager() {
        return $this->belongsTo('App\User')->withDefault();
    }

    // R11
    public function user() {
        return $this->belongsTo('App\User')->withDefault();
    }
}
