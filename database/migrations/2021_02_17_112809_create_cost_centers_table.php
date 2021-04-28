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
                    'description' => 'Fietsvergoedingen',
                    'created_at' => now()
                ],
                [
                    'name' => 'WU3TIGB000001',
                    'user_id_Cost_center_manager' => 4,
                    'description' => 'Laptopvergoedingen',
                    'created_at' => now()
                ],
                [
                    'name' => 'WU3TIGB000002',
                    'user_id_Cost_center_manager' => 4,
                    'description' => 'Opleiding personeel',
                    'created_at' => now()
                ],
                [
                    'name' => 'WU3TIGB000003',
                    'user_id_Cost_center_manager' => 5,
                    'description' => 'Opendeurdag',
                    'created_at' => now()
                ],
                [
                    'name' => 'WU3TIGB000004',
                    'user_id_Cost_center_manager' => 2,
                    'description' => 'Stagebezoeken',
                    'created_at' => now()
                ],
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
