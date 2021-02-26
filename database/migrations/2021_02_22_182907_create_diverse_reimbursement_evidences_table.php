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
            $table->id();
            $table->string('filepath');
            $table->foreignId('diverse_reimbursement_lineID');
            $table->timestamps();

            // Foreign key relation
            $table->foreign('diverse_reimbursement_lineID')->references('id')->on('diverse_reimbursement_lines')->onDelete('cascade')->onUpdate('cascade');
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
