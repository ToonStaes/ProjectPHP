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

    public function status_cc_manager() {
        return $this->belongsTo('App\Status', 'status_CC_manager')->withDefault();
    }

    public function status_fe() {
        return $this->belongsTo('App\Status', 'status_FE')->withDefault();
    }

    // R9
    public function financial_employee() {
        return $this->belongsTo('App\User', 'user_id_Fin_employee')->withDefault();
    }
}
