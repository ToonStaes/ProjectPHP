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
            $table->timestamps();

            // Foreign key relation
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });

        DB::table('laptop_invoices')->insert(
            [
                'filepath' => "/map/map/map/laptopaanvraag.docx",
                'user_id' => 3,
                'amount' => 600,
                'invoice_description' => "aanschaf laptop",
                'purchase_date' => now(),
                'created_at' => now()
            ]
        );

        for ($i = 1; $i <= 10; $i++) {
            DB::table('laptop_invoices')->insert(
                [
                    'filepath' => "/map/map/map/laptop$i.docx",
                    'user_id' => 2,
                    'amount' => 750,
                    'invoice_description' => "aanschaf nieuwe laptop",
                    'purchase_date' => now(),
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
