<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiverseReimbursementLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diverse_reimbursement_lines', function (Blueprint $table) {
            $table->id('diverse_reimbursement_lineID');
            $table->foreignId('diverse_reimbursement_requestID');
            $table->float('amount')->nullable();
            $table->string('description');
            $table->float('number_of_km')->nullable();
            $table->foreignId('parameterID')->nullable();
            $table->timestamps();
        });

        DB::table('diverse_reimbursement_lines')->insert(
            [
                [
                    'diverse_reimbursement_requestID' => 1,
                    'description' => 'treinticket',
                    'amount' => 37.5,
                    'number_of_km' => null,
                    'parameterID' => null,
                ],
                [
                    'diverse_reimbursement_requestID' => 1,
                    'description' => 'stagebezoek ometa',
                    'amount' => null,
                    'number_of_km' => 25,
                    'parameterID' => 2,
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
        Schema::dropIfExists('diverse_reimbursement_lines');
    }
}
