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
            $table->id();
            $table->string('name');
            $table->foreignId('user_id_Cost_center_manager');
            $table->string('description')->nullable();
            $table->boolean('isActive')->default(true);
            $table->timestamps();

            // foreign key relation
            $table->foreign('user_id_Cost_center_manager')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
        });

        // insert for testing
        DB::table('cost_centers')->insert(
            [
                [
                    'name' => 'WU3TIGB00000',
                    'user_id_Cost_center_manager' => 2,
                    'description' => 'TI Beleid en Opleidingsinitiatieven',
                    'created_at' => now()
                ],
                [
                    'name' => 'WU3TIGB01000',
                    'user_id_Cost_center_manager' => 2,
                    'description' => 'Professionalisering Personeel',
                    'created_at' => now()
                ],
                [
                    'name' => 'WU3TIGB01001',
                    'user_id_Cost_center_manager' => 2,
                    'description' => 'laptopvergoedingen',
                    'created_at' => now()
                ],
                [
                    'name' => 'WU3TIGB01002',
                    'user_id_Cost_center_manager' => 2,
                    'description' => 'fietsvergoedingen',
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
