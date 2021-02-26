<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiverseReimbursementEvidencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diverse_reimbursement_evidences', function (Blueprint $table) {
            $table->id('diverse_reimbursement_evidenceID');
            $table->string('filepath');
            $table->foreignId('diverse_reimbursement_lineID');
            $table->timestamps();
        });

        DB::table('diverse_reimbursement_evidences')->insert(
            [
                [
                    'filepath' => '/map/map/map/treinticket.jpg',
                    'diverse_reimbursement_lineID' => 1,
                ],
                [
                    'filepath' => '/map/map/map/stagebezoekOmeta.docx',
                    'diverse_reimbursement_lineID' => 2
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
        Schema::dropIfExists('diverse_reimbursement_evidences');
    }
}
