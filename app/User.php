<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * App\User
 *
 * @property boolean $isCost_Center_manager
 * @property boolean $isFinancial_employee
 */

class User extends Authenticatable
{
    use Notifiable;

    protected $primaryKey = 'userID';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // R1, R2, R3
    public function laptop_invoices() {
        return $this->hasMany('App\Laptop_invoice');
    }

    // R4
    public function laptop_reimbursements() {
        return $this->hasMany('App\Laptop_reimbursement');
    }

    // R28
    public function userProgrammes() {
        return $this->hasMany('App\UserProgramme');
    }

    // R7
    public function cost_centers() {
        return $this->hasMany('App\Cost_center');
    }

    // R9, R10, R11
    public function diverse_reimbursement_requests() {
        return $this->hasMany('App\Diverse_reimbursement_request');
    }

    // R12
    public function bike_reimbursements() {
        return $this->hasMany('App\Bike_reimbursement');
    }

    // R13
    public function bikerides() {
        return $this->hasMany('App\Bikeride');
    }
}

