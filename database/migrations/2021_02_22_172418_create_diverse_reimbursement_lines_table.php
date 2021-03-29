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
            $table->foreignId('DR_request_id');
            $table->float('amount')->nullable();
            $table->float('number_of_km')->nullable();
            $table->foreignId('parameter_id')->nullable();

            $table->foreignId('user_id_Fin_employee')->nullable();
            $table->date('review_date_Cost_center_manager')->nullable();
            $table->date('review_date_Financial_employee')->nullable();
            $table->string('comment_Cost_center_manager')->nullable();
            $table->string('comment_Financial_employee')->nullable();
            $table->foreignId('status_CC_manager')->default(1);
            $table->foreignId('status_FE')->default(1);

            $table->timestamps();

            // Foreign key relation
            $table->foreign('DR_request_id')->references('id')->on('diverse_reimbursement_requests')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('parameter_id')->references('id')->on('parameters')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('user_id_Fin_employee')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('status_CC_manager')->references('id')->on('statuses')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('status_FE')->references('id')->on('statuses')->onDelete('restrict')->onUpdate('cascade');

        });

        DB::table('diverse_reimbursement_lines')->insert(
            [
                [
                    'DR_request_id' => 1,
                    'amount' => 37.5,
                    'number_of_km' => null,
                    'parameter_id' => null,
                ],
                [
                    'DR_request_id' => 1,
                    'amount' => null,
                    'number_of_km' => 25,
                    'parameter_id' => 2,
                ],
                [
                    'DR_request_id' => 2,
                    'amount' => null,
                    'number_of_km' => 12,
                    'parameter_id' => 2,
                ],
                [
                    'DR_request_id' => 2,
                    'amount' => 85,
                    'number_of_km' => null,
                    'parameter_id' => null,
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
