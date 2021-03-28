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
                [
                    'mailtype' => 'Afwijzing',
                    'content' => 'Beste NAAM

Onze financieel medewerker NAAM FINANCIEEL MEDEWERKER heeft je vergoedingsaanvraag, AANVRAAG, afgekeurd omwille van de volgende reden: REDEN.

MVG
Onkostenportaal Thomas More',
                    'created_at' => now()
                ],
                [
                    'mailtype' => 'Nieuwe user',
                    'content' => 'Bedankt voor je registratie en welkom op onze website. In deze mail kan je je inloggegevens vinden.

e-mail: EMAIL
wachtwoord: WACHTWOORD

MVG
Onkostenportaal Thomas More',
                    'created_at' => now()
                ],
                [
                    'mailtype' => 'Wachtwoord vergeten',
                    'content' => 'Beste NAAM

Je hebt op onze applicatie op de knop wachtwoord vergeten gedrukt. Hieronder vind je je nieuwe inloggegevens.

e-mail: EMAIL
wachtwoord: WACHTWOORD

MVG
Onkostenportaal Thomas More',
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
        Schema::dropIfExists('mailcontents');
    }
}
