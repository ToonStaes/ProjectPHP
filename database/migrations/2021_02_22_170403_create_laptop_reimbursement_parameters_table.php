<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaptopReimbursementParametersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laptop_reimbursement_parameters', function (Blueprint $table) {
            $table->id('laptop_reimbursementParameterID');
            $table->foreignId('laptop_reimbursementID');
            $table->foreignId('parameterID');
            $table->timestamps();

            // Foreign key relation
            $table->foreign('laptop_reimbursementID')->references('laptop_reimbursementID')->on('laptop_reimbursements')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('parameterID')->references('parameterID')->on('parameters')->onDelete('restrict')->onUpdate('cascade');
        });

        // insert for testing
        DB::table('laptop_reimbursement_parameters')->insert(
            [
                [
                    'laptop_reimbursementID' => 1,
                    'parameterID' => 3
                ],
                [
                    'laptop_reimbursementID' => 1,
                    'parameterID' => 4
                ],
                [
                    'laptop_reimbursementID' => 2,
                    'parameterID' => 3
                ],
                [
                    'laptop_reimbursementID' => 2,
                    'parameterID' => 4
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
        Schema::dropIfExists('laptop_reimbursement_parameters');
    }
}
