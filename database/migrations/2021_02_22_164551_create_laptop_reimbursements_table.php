<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaptopReimbursementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laptop_reimbursements', function (Blueprint $table) {
            $table->id('laptop_reimbursementID');
            $table->foreignId('userID_Financial_employee');
            $table->foreignId('laptop_invoiceID');
            $table->date('payment_date');

            // Foreign key relation
            $table->foreign('userID_Financial_employee')->references('userID')->on('users')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('laptop_invoiceID')->references('laptop_invoiceID')->on('laptop_invoices')->onDelete('restrict')->onUpdate('cascade');
        });

        // insert for testing
        DB::table('laptop_reimbursements')->insert(
            [
                [
                    'userID_Financial_employee' => 2,
                    'laptop_invoiceID' => 1,
                    'payment_date' => now()
                ],
                [
                    'userID_Financial_employee' => 3,
                    'laptop_invoiceID' => 1,
                    'payment_date' => now()
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
        Schema::dropIfExists('laptop_reimbursements');
    }
}
