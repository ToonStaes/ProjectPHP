<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProgrammeCost_center extends Model
{
    // R23
    public function cost_center() {
        return $this->belongsTo('App\Cost_center')->withDefault();
    }

    // R29
    public function programme() {
        return $this->belongsTo('App\Programme')->withDefault();
    }
}
