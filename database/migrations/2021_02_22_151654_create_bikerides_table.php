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
            $table->id('bikerideID');
            $table->date('date');
            $table->foreignId('bike_reimbursementID')->nullable();
            $table->foreignId('userID');
            $table->float('number_of_km')->nullable();
            $table->timestamps();

            // Foreign key relation
            $table->foreign('bike_reimbursementID')->references('bike_reimbursementID')->on('bike_reimbursements')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('userID')->references('userID')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });

        for ($i = 0; $i <= 20; $i++) {
            DB::table('bikerides')->insert(
                [
                    'date' => now(),
                    'userID' => 3,
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
