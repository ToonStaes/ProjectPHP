<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCostCenterProgrammesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cost_center_programmes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('programme_id');
            $table->foreignId('cost_center_id');
            $table->timestamps();

            $table->foreign('programme_id')->references('id')->on('programmes')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('cost_center_id')->references('id')->on('cost_centers')->onDelete('cascade')->onUpdate('cascade');
        });

        DB::table('cost_center_programmes')->insert(
            [
                [
                    'programme_id'=>1,
                    'cost_center_id'=>1
                ],
                [
                    'programme_id'=>1,
                    'cost_center_id'=>2
                ],
                [
                    'programme_id'=>1,
                    'cost_center_id'=>3
                ],
                [
                    'programme_id'=>1,
                    'cost_center_id'=>4
                ],
                [
                    'programme_id'=>1,
                    'cost_center_id'=>5
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
        Schema::dropIfExists('cost_center_programmes');
    }
}
