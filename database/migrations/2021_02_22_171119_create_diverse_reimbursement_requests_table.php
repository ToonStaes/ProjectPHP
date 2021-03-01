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
            $table->foreignId('userID');
            $table->foreignId('cost_centerID');
            $table->string('invoice_description')->nullable();
            $table->date('request_date');
            $table->foreignId('userID_CC_manager');
            $table->foreignId('userID_Financial_employee')->nullable();
            $table->date('review_date_Cost_center_manager')->nullable();
            $table->date('review_date_Financial_employee')->nullable();
            $table->string('comment_Cost_center_manager')->nullable();
            $table->string('comment_Financial_employee')->nullable();

            // Foreign key relation
            $table->foreign('userID')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('userID_CC_manager')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('userID_Financial_employee')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('cost_centerID')->references('id')->on('cost_centers')->onDelete('restrict')->onUpdate('cascade');
        });

        DB::table('diverse_reimbursement_requests')->insert(
            [
                [
                    'userID' => 3,
                    'cost_centerID' => 2,
                    'request_date' => now(),
                    'userID_CC_manager' => 2,
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
