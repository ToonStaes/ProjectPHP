<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgrammesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('programmes', function (Blueprint $table) {
            $table->id('programmeID');
            $table->string('name');
            $table->timestamps();
        });

        // insert for testing
        DB::table('programmes')->insert(
            [
                [
                    'name' => 'Toegepaste Informatica',
                    'created_at' => now()
                ],
                [
                    'name' => 'Electronica-ICT',
                    'created_at' => now()
                ],
                [
                    'name' => 'Electromechanica',
                    'created_at' => now()
                ]
            ]
        );

        for ($i = 0; $i <= 20; $i++) {
            DB::table('programmes')->insert(
                [
                    'name' => "programme $i",
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
        Schema::dropIfExists('programmes');
    }
}
