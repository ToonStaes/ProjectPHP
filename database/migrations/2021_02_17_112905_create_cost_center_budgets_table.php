<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCostCenterBudgetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cost_center_budgets', function (Blueprint $table) {
            $table->id();
            $table->float('amount')->nullable();
            $table->foreignId('cost_center_id');
            $table->string('year');
            $table->timestamps();

            // foreign key relations
            $table->foreign('cost_center_id')->references('id')->on('cost_centers')->onDelete('cascade')->onUpdate('cascade');
        });

        // insert for testing
        DB::table('cost_center_budgets')->insert(
            [
                [
                    'amount' => 4588,
                    'cost_center_id' => 1,
                    'year' => 2021,
                    'created_at' => now()
                ],
                [
                    'amount' => 4888,
                    'cost_center_id' => 2,
                    'year' => 2021,
                    'created_at' => now()
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
        Schema::dropIfExists('cost_center_budgets');
    }
}
