<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBikeReimbursementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bike_reimbursements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('status_id');
            $table->foreignId('user_id_Financial_employee')->nullable();
            $table->string('comment_Financial_employee')->nullable();
            $table->date('review_date_Financial_employee')->nullable();
            $table->date('request_date');
            $table->string('name');
            $table->timestamps();

            // Foreign key relation
            $table->foreign('user_id_Financial_employee')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('status_id')->references('id')->on('statuses')->onDelete('restrict')->onUpdate('cascade');
        });



        DB::table('bike_reimbursements')->insert(
            [
                [
                    'status_id' => 1,
                    'request_date' => now(),
                    'name' => "bike_reimbursement 1 John Doe",
                    'created_at' => now()
                ],
                [
                    'status_id' => 1,
                    'request_date' => now(),
                    'name' => "bike_reimbursement 2 John Doe",
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
        Schema::dropIfExists('bike_reimbursements');
    }
}
