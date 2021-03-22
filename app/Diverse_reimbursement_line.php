<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Diverse_reimbursement_line extends Model
{
    // R19
    public function diverse_reimbursement_evidences() {
        return $this->hasMany('App\Diverse_reimbursement_evidence', 'DR_line_id');
    }

    // R20
    public function diverse_reimbursement_request() {
        return $this->belongsTo('App\Diverse_reimbursement_request');
    }

    // R18
    public function parameter() {
        return $this->belongsTo('App\Parameter')->withDefault();
    }

    // R21
    public function status(){
        return $this->belongsTo('App\Status')->withDefault();
    }

    // R9
    public function financialEmployee() {
        return $this->belongsTo('App\User')->withDefault();
    }
}
