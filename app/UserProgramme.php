<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserProgramme extends Model
{
    // R28
    public function user() {
        return $this->belongsTo('App\User')->withDefault();
    }

    // R27
    public function programme() {
        return $this->belongsTo('App\Programme')->withDefault();
    }
}
