<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiverseReimbursementRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diverse_reimbursement_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('cost_center_id');
            $table->string('description');
            $table->foreignId('user_id_CC_manager');
            $table->date('request_date');
            $table->foreignId('user_id_Fin_employee')->nullable();
            $table->date('review_date_Cost_center_manager')->nullable();
            $table->date('review_date_Financial_employee')->nullable();
            $table->string('comment_Cost_center_manager')->nullable();
            $table->string('comment_Financial_employee')->nullable();
            $table->foreignId('status_CC_manager')->default(1);
            $table->foreignId('status_FE')->default(1);
            $table->timestamps();

            // Foreign key relation
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('user_id_CC_manager')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('cost_center_id')->references('id')->on('cost_centers')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('user_id_Fin_employee')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('status_CC_manager')->references('id')->on('statuses')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('status_FE')->references('id')->on('statuses')->onDelete('restrict')->onUpdate('cascade');
        });

        DB::table('diverse_reimbursement_requests')->insert(
            [
                [
                    'user_id' => 5,
                    'cost_center_id' => 5,
                    'description' => "Stagebezoek met 2ITF3",
                    'request_date' => now(),
                    'user_id_CC_manager' => 2,
                ],
                [
                    'user_id' => 4,
                    'cost_center_id' => 4,
                    'description' => "Slingers gekocht voor opendeurdag",
                    'request_date' => now(),
                    'user_id_CC_manager' => 5,
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
        Schema::dropIfExists('diverse_reimbursement_requests');
    }
}
