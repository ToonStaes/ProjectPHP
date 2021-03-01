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
            $table->id();
            $table->foreignId('programme_id');
            $table->foreignId('cost_center_id');
            $table->timestamps();

            // Foreign key relation
            $table->foreign('programme_id')->references('id')->on('programmes')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('cost_center_id')->references('id')->on('cost_centers')->onDelete('cascade')->onUpdate('cascade');
        });

        // insert for testing
        DB::table('programme_cost_centers')->insert(
            [
                [
                    'programme_id' => 1,
                    'cost_center_id' => 1
                ],
                [
                    'programme_id' => 1,
                    'cost_center_id' => 2
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
