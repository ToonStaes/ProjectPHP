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
            $table->foreignId('user_id_CC_manager');
            $table->date('request_date');

            // Foreign key relation
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('user_id_CC_manager')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('cost_center_id')->references('id')->on('cost_centers')->onDelete('restrict')->onUpdate('cascade');
        });

        DB::table('diverse_reimbursement_requests')->insert(
            [
                [
                    'user_id' => 3,
                    'cost_center_id' => 2,
                    'request_date' => now(),
                    'user_id_CC_manager' => 2,
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
