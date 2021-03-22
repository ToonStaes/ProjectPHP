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
            $table->id();
            $table->foreignId('user_id_Financial_employee');
            $table->foreignId('laptop_invoice_id');
            $table->date('payment_date');
            $table->foreignId('status_CC_manager')->default(1);
            $table->foreignId('status_FE')->default(1);

            // Foreign key relation
            $table->foreign('user_id_Financial_employee')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('laptop_invoice_id')->references('id')->on('laptop_invoices')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('status_CC_manager')->references('id')->on('statuses')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('status_FE')->references('id')->on('statuses')->onDelete('restrict')->onUpdate('cascade');
        });

        // insert for testing
        DB::table('laptop_reimbursements')->insert(
            [
                [
                    'user_id_Financial_employee' => 2,
                    'laptop_invoice_id' => 1,
                    'payment_date' => now()
                ],
                [
                    'user_id_Financial_employee' => 3,
                    'laptop_invoice_id' => 1,
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
