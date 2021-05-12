<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Laptop_invoice extends Model
{
    // R3
    public function user() {
        return $this->belongsTo('App\User')->withDefault();
    }

    public function me() {
        return $this->belongsTo('App\User')->where('id', auth()->user()->id)->withDefault();
    }

    // R5
    public function laptop_reimbursements() {
        return $this->hasMany('App\Laptop_reimbursement');
    }

    protected $casts = [
        'created_at' => 'datetime:d/m/Y',
        'updated_at' => 'datetime:d/m/Y'
    ];
}
