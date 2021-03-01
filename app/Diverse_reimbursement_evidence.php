<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Diverse_reimbursement_evidence extends Model
{
    // R19
    public function diverse_reimbursement_line(){
        return $this->belongsTo('App\Diverse_reimbursement_line');
    }
}
