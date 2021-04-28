<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBikeridesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bikerides', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('bike_reimbursement_id')->nullable();
            $table->foreignId('user_id');
            $table->float('number_of_km')->nullable();
            $table->timestamps();

            // Foreign key relation
            $table->foreign('bike_reimbursement_id')->references('id')->on('bike_reimbursements')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });

        DB::table('bikerides')->insert(
            [
                [
                    'date' => date('Y-m-d h:m:s',strtotime("-1 days")),
                    'bike_reimbursement_id' => 1,
                    'user_id' => 3,
                    'created_at' => now()
                ],
                [
                    'date' => date('Y-m-d h:m:s',strtotime("-3 days")),
                    'bike_reimbursement_id' => 1,
                    'user_id' => 3,
                    'created_at' => now()
                ],
                [
                    'date' => date('Y-m-d h:m:s',strtotime("-6 days")),
                    'bike_reimbursement_id' => 1,
                    'user_id' => 3,
                    'created_at' => now()
                ],
                [
                    'date' => date('Y-m-d h:m:s',strtotime("-7 days")),
                    'bike_reimbursement_id' => 1,
                    'user_id' => 3,
                    'created_at' => now()
                ],
                [
                    'date' => date('Y-m-d h:m:s',strtotime("-8 days")),
                    'bike_reimbursement_id' => 1,
                    'user_id' => 3,
                    'created_at' => now()
                ],
                [
                    'date' => date('Y-m-d h:m:s',strtotime("-9 days")),
                    'bike_reimbursement_id' => 1,
                    'user_id' => 3,
                    'created_at' => now()
                ],
                [
                    'date' => date('Y-m-d h:m:s',strtotime("-1 days")),
                    'bike_reimbursement_id' => 1,
                    'user_id' => 5,
                    'created_at' => now()
                ],
                [
                    'date' => date('Y-m-d h:m:s',strtotime("-2 days")),
                    'bike_reimbursement_id' => 1,
                    'user_id' => 5,
                    'created_at' => now()
                ],
                [
                    'date' => date('Y-m-d h:m:s',strtotime("-3 days")),
                    'bike_reimbursement_id' => 1,
                    'user_id' => 5,
                    'created_at' => now()
                ],
                [
                    'date' => date('Y-m-d h:m:s',strtotime("-6 days")),
                    'bike_reimbursement_id' => 1,
                    'user_id' => 5,
                    'created_at' => now()
                ],
                [
                    'date' => date('Y-m-d h:m:s',strtotime("-9 days")),
                    'bike_reimbursement_id' => 1,
                    'user_id' => 5,
                    'created_at' => now()
                ],
                [
                    'date' => date('Y-m-d h:m:s',strtotime("-11 days")),
                    'bike_reimbursement_id' => 1,
                    'user_id' => 5,
                    'created_at' => now()
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
        Schema::dropIfExists('bikerides');
    }
}
