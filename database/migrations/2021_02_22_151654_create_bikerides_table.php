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

        for ($i = 0; $i <= 20; $i++) {
            DB::table('bikerides')->insert(
                [
                    'date' => now(),
                    'user_id' => 3,
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
        Schema::dropIfExists('bikerides');
    }
}
