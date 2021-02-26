<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBikeReimbursementParametersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bike_reimbursement_parameters', function (Blueprint $table) {
            $table->id('bike_reimbursementParameterID');
            $table->foreignId('bike_reimbursementID');
            $table->foreignId('parameterID');
            $table->timestamps();

            // Foreign key relation
            $table->foreign('bike_reimbursementID')->references('bike_reimbursementID')->on('bike_reimbursements')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('parameterID')->references('parameterID')->on('parameters')->onDelete('restrict')->onUpdate('cascade');
        });

        for ($i = 1; $i <= 21; $i++) {
            DB::table('bike_reimbursement_parameters')->insert(
                [
                    'bike_reimbursementID' => "$i",
                    'parameterID' => 1,
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
        Schema::dropIfExists('bike_reimbursement_parameters');
    }
}
