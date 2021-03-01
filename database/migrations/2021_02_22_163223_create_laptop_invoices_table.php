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
            $table->foreignId('user_id');
            $table->float('amount');
            $table->string('invoice_description')->nullable();
            $table->date('purchase_date');
            $table->foreignId('user_id_Cost_center_manager');
            $table->foreignId('user_id_Financial_employee')->nullable();
            $table->boolean('isApproved_Cost_center_manager')->default(false);
            $table->boolean('isApproved_Financial_employee')->default(false);
            $table->date('review_date_Cost_center_manager')->nullable();
            $table->date('review_date_Financial_employee')->nullable();
            $table->string('comment_Cost_center_manager')->nullable();
            $table->string('comment_Financial_employee')->nullable();
            $table->timestamps();

            // Foreign key relation
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('user_id_Cost_center_manager')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('user_id_Financial_employee')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
        });

        for ($i = 1; $i <= 10; $i++) {
            DB::table('laptop_invoices')->insert(
                [
                    'filepath' => "/map/map/map/laptop$i.docx",
                    'user_id' => 2,
                    'amount' => 750,
                    'invoice_description' => "aanschaf nieuwe laptop",
                    'purchase_date' => now(),
                    'user_id_Cost_center_manager' => 2,
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
