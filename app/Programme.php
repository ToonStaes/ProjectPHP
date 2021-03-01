<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Programme extends Model
{
    // R27
    public function userProgrammes() {
        return $this->hasMany('App/UserProgramme');
    }

    // R24
    public function programmeCost_Centers() {
        return $this->hasMany('App/ProgrammeCost_center');
    }
}
