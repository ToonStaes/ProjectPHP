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
                    'first_name' => 'Arne',
                    'last_name' => 'Hus',
                    'address' => 'Laagland 29',
                    'zip_code' => '2450',
                    'city' => 'Meerhout',
                    'IBAN' => 'BE12345678912345',
                    'email' => 'arne.hus@hotmail.com',
                    'phone_number' => '0470 65 74 58',
                    'password' => Hash::make('arne'),
                    'changedPassword' => false,
                    'isCost_Center_manager' => true,
                    'isFinancial_employee' => true,
                    'number_of_km' => 14,
                    'created_at' => now()
                ],
                [
                    'first_name' => 'Jorne',
                    'last_name' => 'Marx',
                    'address' => 'Torenstraat',
                    'zip_code' => '3581',
                    'city' => 'Beverlo',
                    'IBAN' => 'BE00 0000 0000',
                    'email' => 'jornemarx@telenet.be',
                    'phone_number' => '0470 00 00 00',
                    'password' => Hash::make('jorne'),
                    'changedPassword' => false,
                    'isCost_Center_manager' => true,
                    'isFinancial_employee' => false,
                    'number_of_km' => 30,
                    'created_at' => now()
                ],
                [
                    'first_name' => 'Britt',
                    'last_name' => 'Theunis',
                    'address' => 'Kapucijnenplein 8',
                    'zip_code' => '3920',
                    'city' => 'Lommel',
                    'IBAN' => 'BE00 0000 0000',
                    'email' => 'britt.theunis@hotmail.com',
                    'phone_number' => '0470 00 00 00',
                    'password' => Hash::make('britt'),
                    'changedPassword' => false,
                    'isCost_Center_manager' => false,
                    'isFinancial_employee' => false,
                    'number_of_km' => 20,
                    'created_at' => now()
                ],
                [
                    'first_name' => 'Wilfer',
                    'last_name' => 'Spaepen',
                    'address' => 'Duitslandlaan 11',
                    'zip_code' => '2400',
                    'city' => 'Mol',
                    'IBAN' => 'BE00 0000 0000',
                    'email' => 'wilferspaepen@gmail.com',
                    'phone_number' => '0470 00 00 00',
                    'password' => Hash::make('wilfer'),
                    'changedPassword' => false,
                    'isCost_Center_manager' => true,
                    'isFinancial_employee' => false,
                    'number_of_km' => 15,
                    'created_at' => now()
                ],
                [
                    'first_name' => 'Toon',
                    'last_name' => 'Staes',
                    'address' => 'Polderken 7',
                    'zip_code' => '2460',
                    'city' => 'Kasterlee',
                    'IBAN' => 'BE00 0000 0000',
                    'email' => 'toon.staes@hotmail.com',
                    'phone_number' => '0470 00 00 01',
                    'password' => Hash::make('toon'),
                    'changedPassword' => false,
                    'isCost_Center_manager' => true,
                    'isFinancial_employee' => false,
                    'number_of_km' => 11,
                    'created_at' => now()
                ],
            ]
        );
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
