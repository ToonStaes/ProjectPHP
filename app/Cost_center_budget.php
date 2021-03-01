<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cost_center_budget extends Model
{
    // R8
    public function cost_center() {
        return $this->belongsTo('App\Cost_center')->withDefault(); // a cost_centerBudget has one cost_center
    }
}
