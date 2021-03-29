<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParametersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parameters', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('valid_from');
            $table->date('valid_until')->nullable();
            $table->float('amount_per_km')->nullable();
            $table->string('description')->nullable();
            $table->foreignId('standard_Cost_center_id')->nullable();
            $table->float('max_reimbursement_laptop')->nullable();
            $table->timestamps();

            // Foreign key relation
            $table->foreign('standard_Cost_center_id')->references('id')->on('cost_centers')->onDelete('restrict')->onUpdate('cascade');
        });

        DB::table('parameters')->insert(
            [
                [
                    'name' => 'Fietsvergoeding',
                    'valid_from' => now(),
                    'amount_per_km' => 0.45,
                    'description' => 'De prijs die een WN ontvangt per gefietste kilometer voor woon-werkverkeer'
                ],
                [
                    'name' => 'Autovergoeding',
                    'valid_from' => now(),
                    'amount_per_km' => 0.25,
                    'description' => 'De prijs die een WN ontvangt per afgelegde kilometer voor verplaatsingen tijdens schooluren. Bijvoorbeeld stagebezoeken ...'
                ]
            ]
        );

        DB::table('parameters')->insert(
            [
                [
                    'name' => 'Maximum schijfgrootte laptop',
                    'valid_from' => now(),
                    'max_reimbursement_laptop' => 187.5,
                    'description' => 'Maximale schijfgrootte voor terugbetaling van laptop'
                ]
            ]
        );

        DB::table('parameters')->insert(
            [
<<<<<<< HEAD
                [
                    'name' => 'Standaard kostenplaats laptopvergoeding',
                    'valid_from' => now(),
                    'standard_Cost_center_id' => 3,
                    'description' => 'Standaard kostenplaats van laptop'
                ],
                [
                    'name' => 'Standaard kostenplaats fietsvergoeding',
                    'valid_from' => now(),
                    'standard_Cost_center_id' => 4,
                    'description' => 'Standaard kostenplaats fietsvergoeding'
                ]

=======
                'name' => 'Standaard kostenplaats laptopvergoeding',
                'valid_from' => now(),
                'standard_Cost_center_id' => 3,
                'description' => 'Standaard kostenplaats voor terugbetaling van laptop'
>>>>>>> origin/main
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
        Schema::dropIfExists('parameters');
    }
}
