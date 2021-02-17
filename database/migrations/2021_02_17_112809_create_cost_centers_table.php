<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCostCentersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cost_centers', function (Blueprint $table) {
            $table->id('cost_centerID');
            $table->string('name')->unique();
            $table->foreignId('userID_Cost_center_manager');
            $table->string('description')->nullable();
            $table->boolean('isActive')->default(true);
            $table->timestamps();

            // foreign key relation
            $table->foreign('userID_Cost_center_manager')->references('userID')->on('users')->onDelete('restrict')->onUpdate('cascade');
        });

        // insert for testing
        DB::table('cost_centers')->insert(
            [
                [
                    'name' => 'WU3TIGB00000',
                    'userID_Cost_center_manager' => 2,
                    'description' => 'TI Beleid en Opleidingsinitiatieven',
                    'created_at' => now()
                ],
                [
                    'name' => 'WU3TIGB01000',
                    'userID_Cost_center_manager' => 2,
                    'description' => 'Professionalisering Personeel',
                    'created_at' => now()
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
        Schema::dropIfExists('cost_centers');
    }
}
