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
            $table->id();
            $table->foreignId('laptop_reimbursement_id');
            $table->foreignId('parameter_id');
            $table->timestamps();

            // Foreign key relation
            $table->foreign('laptop_reimbursement_id')->references('id')->on('laptop_reimbursements')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('parameter_id')->references('id')->on('parameters')->onDelete('restrict')->onUpdate('cascade');
        });

        // insert for testing
        DB::table('laptop_reimbursement_parameters')->insert(
            [
                [
                    'laptop_reimbursement_id' => 1,
                    'parameter_id' => 3
                ],
                [
                    'laptop_reimbursement_id' => 1,
                    'parameter_id' => 4
                ],
                [
                    'laptop_reimbursement_id' => 2,
                    'parameter_id' => 3
                ],
                [
                    'laptop_reimbursement_id' => 2,
                    'parameter_id' => 4
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
