<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgrammeCostCentersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('programme_cost_centers', function (Blueprint $table) {
            $table->id('programmeCost_centerID');
            $table->foreignId('programmeID');
            $table->foreignId('cost_centerID');
            $table->timestamps();

            // Foreign key relation
            $table->foreign('programmeID')->references('programmeID')->on('programmes')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('cost_centerID')->references('cost_centerID')->on('cost_centers')->onDelete('cascade')->onUpdate('cascade');
        });

        // insert for testing
        DB::table('programme_cost_centers')->insert(
            [
                [
                    'programmeID' => 1,
                    'cost_centerID' => 1
                ],
                [
                    'programmeID' => 1,
                    'cost_centerID' => 2
                ]
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
        Schema::dropIfExists('programme_cost_centers');
    }
}
