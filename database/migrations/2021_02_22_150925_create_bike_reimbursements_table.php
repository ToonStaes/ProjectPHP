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
            $table->foreignId('statusID');
            $table->foreignId('userID_Financial_employee')->nullable();
            $table->string('comment_Financial_employee')->nullable();
            $table->date('review_date_Financial_employee')->nullable();
            $table->date('request_date');
            $table->string('name');
            $table->boolean('isPaid')->default(false);
            $table->timestamps();

            // Foreign key relation
            $table->foreign('userID_Financial_employee')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('statusID')->references('id')->on('statuses')->onDelete('restrict')->onUpdate('cascade');
        });



        for ($i = 0; $i <= 20; $i++) {
            DB::table('bike_reimbursements')->insert(
                [
                    'statusID' => 1,
                    'request_date' => now(),
                    'name' => "reimbursement $i",
                    'created_at' => now()
                ]
            );
        }
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
