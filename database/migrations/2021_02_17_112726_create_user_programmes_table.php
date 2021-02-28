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
            $table->foreignId('userID');
            $table->foreignId('programmeID');
            $table->timestamps();

            // Foreign key relation
            $table->foreign('userID')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('programmeID')->references('id')->on('programmes')->onDelete('restrict')->onUpdate('cascade');
        });

        // insert for testing
        DB::table('user_programmes')->insert(
            [
                [
                    'userID' => 2,
                    'programmeID' => 1
                ],
                [
                    'userID' => 3,
                    'programmeID' => 1
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
        Schema::dropIfExists('user_programmes');
    }
}
