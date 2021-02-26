<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaptopInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laptop_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('filepath');
            $table->foreignId('userID');
            $table->float('amount');
            $table->string('invoice_description')->nullable();
            $table->date('purchase_date');
            $table->foreignId('userID_Cost_center_manager');
            $table->foreignId('userID_Financial_employee')->nullable();
            $table->boolean('isApproved_Cost_center_manager')->default(false);
            $table->boolean('isApproved_Financial_employee')->default(false);
            $table->date('review_date_Cost_center_manager')->nullable();
            $table->date('review_date_Financial_employee')->nullable();
            $table->string('comment_Cost_center_manager')->nullable();
            $table->string('comment_Financial_employee')->nullable();
            $table->timestamps();

            // Foreign key relation
            $table->foreign('userID')->references('userID')->on('id')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('userID_Cost_center_manager')->references('id')->on('id')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('userID_Financial_employee')->references('id')->on('id')->onDelete('restrict')->onUpdate('cascade');
        });

        for ($i = 1; $i <= 10; $i++) {
            DB::table('laptop_invoices')->insert(
                [
                    'filepath' => "/map/map/map/laptop$i.docx",
                    'userID' => 2,
                    'amount' => 750,
                    'invoice_description' => "aanschaf nieuwe laptop",
                    'purchase_date' => now(),
                    'userID_Cost_center_manager' => 2,
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
        Schema::dropIfExists('laptop_invoices');
    }
}
