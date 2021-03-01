<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMailcontentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mailcontents', function (Blueprint $table) {
            $table->id();
            $table->string('mailtype');
            $table->string('content');
            $table->timestamps();
        });

        DB::table('mailcontents')->insert(
            [
                'mailtype' => 'afwijzing',
                'content' => 'uw mail is afgewezen omdat ...',
                'created_at' => now()
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
        Schema::dropIfExists('mailcontents');
    }
}
