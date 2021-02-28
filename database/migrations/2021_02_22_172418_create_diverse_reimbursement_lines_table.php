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
            $table->id();
            $table->foreignId('DR_requestID');
            $table->float('amount')->nullable();
            $table->string('description');
            $table->float('number_of_km')->nullable();
            $table->foreignId('parameterID')->nullable();
            $table->timestamps();

            // Foreign key relation
            $table->foreign('DR_requestID')->references('id')->on('diverse_reimbursement_requests')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('parameterID')->references('id')->on('parameters')->onDelete('restrict')->onUpdate('cascade');
        });

        DB::table('diverse_reimbursement_lines')->insert(
            [
                [
                    'DR_requestID' => 1,
                    'description' => 'treinticket',
                    'amount' => 37.5,
                    'number_of_km' => null,
                    'parameterID' => null,
                ],
                [
                    'DR_requestID' => 1,
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
