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
            $table->id();
            $table->foreignId('bike_reimbursement_id');
            $table->foreignId('parameter_id');
            $table->timestamps();

            // Foreign key relation
            $table->foreign('bike_reimbursement_id')->references('id')->on('bike_reimbursements')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('parameter_id')->references('id')->on('parameters')->onDelete('restrict')->onUpdate('cascade');
        });

        DB::table('bike_reimbursement_parameters')->insert(
            [
                [
                    'bike_reimbursement_id' => 1,
                    'parameter_id' => 1,
                    'created_at' => now()
                ],
                [
                    'bike_reimbursement_id' => 1,
                    'parameter_id' => 5,
                    'created_at' => now()
                ],
                [
                    'bike_reimbursement_id' => 2,
                    'parameter_id' => 1,
                    'created_at' => now()
                ],
                [
                    'bike_reimbursement_id' => 2,
                    'parameter_id' => 5,
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
        Schema::dropIfExists('bike_reimbursement_parameters');
    }
}
