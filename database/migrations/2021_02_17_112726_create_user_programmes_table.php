<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserProgrammesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_programmes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('programme_id');
            $table->timestamps();

            // Foreign key relation
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('programme_id')->references('id')->on('programmes')->onDelete('restrict')->onUpdate('cascade');
        });

        // insert for testing
        DB::table('user_programmes')->insert(
            [
                [
                    'user_id' => 1,
                    'programme_id' => 1
                ],
                [
                    'user_id' => 2,
                    'programme_id' => 1
                ],
                [
                    'user_id' => 3,
                    'programme_id' => 1
                ],
                [
                    'user_id' => 4,
                    'programme_id' => 1
                ],
                [
                    'user_id' => 5,
                    'programme_id' => 1
                ],
                [
                    'user_id' => 5,
                    'programme_id' => 2
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
        Schema::dropIfExists('user_programmes');
    }
}
