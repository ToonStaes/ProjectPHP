<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('address');
            $table->string('city')->nullable();
            $table->string('zip_code');
            $table->string('IBAN');
            $table->string('email')->unique();
            $table->string('phone_number')->nullable();
            $table->string('password');
            $table->boolean('changedPassword')->default(false);
            $table->boolean('isActive')->default(true);
            $table->boolean('isCost_Center_manager')->default(false);
            $table->boolean('isFinancial_employee')->default(false);
            $table->float('number_of_km');
            $table->rememberToken();
            $table->timestamps();
        });

        // insert for testing
        DB::table('users')->insert(
            [
                [
                    'first_name' => 'Financial',
                    'last_name' => 'Employee',
                    'address' => 'Hazenstraat 1',
                    'zip_code' => '2440',
                    'IBAN' => 'BE12345678912345',
                    'email' => 'financial.employee@mailinator.com',
                    'password' => Hash::make('finance1234'),
                    'changedPassword' => true,
                    'isCost_Center_manager' => false,
                    'isFinancial_employee' => true,
                    'number_of_km' => 6.7,
                    'created_at' => now()
                ],
                [
                    'first_name' => 'Cost_center',
                    'last_name' => 'Manager',
                    'address' => 'Leeuwerikstraat 4',
                    'zip_code' => '2300',
                    'IBAN' => 'BE12345678912346',
                    'email' => 'cost_center.manager@mailinator.com',
                    'changedPassword' => true,
                    'password' => Hash::make('costcenter1234'),
                    'isCost_Center_manager' => true,
                    'isFinancial_employee' => false,
                    'number_of_km' => 17.5,
                    'created_at' => now()
                ],
                [
                    'first_name' => 'John',
                    'last_name' => 'Doe',
                    'address' => 'Boslaan 7',
                    'zip_code' => '2450',
                    'IBAN' => 'BE12345678912347',
                    'email' => 'john.doe@mailinator.com',
                    'password' => Hash::make('user1234'),
                    'changedPassword' => true,
                    'isCost_Center_manager' => false,
                    'isFinancial_employee' => false,
                    'number_of_km' => 10.3,
                    'created_at' => now()
                ]
            ]
        );

        for ($i = 0; $i <= 20; $i++) {
            DB::table('users')->insert(
                [
                    'first_name' => 'normal',
                    'last_name' => "User $i",
                    'password' => Hash::make('user1234'),
                    'address' => 'Kleinhoefstraat 4',
                    'zip_code' => '2440',
                    'IBAN' => "BE12345678912341$i",
                    'email' => "normaluser$i@mailinator.com",
                    'number_of_km' => '0',
                    'created_at' => now()
                ]
            );
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
