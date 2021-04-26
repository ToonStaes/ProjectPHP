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
            $table->foreignId('DR_line_id');
            $table->timestamps();

            // Foreign key relation
            $table->foreign('DR_line_id')->references('id')->on('diverse_reimbursement_lines')->onDelete('cascade')->onUpdate('cascade');
        });

        DB::table('diverse_reimbursement_evidences')->insert(
            [
                [
                    'filepath' => '0000000000000treinticket.jpg',
                    'DR_line_id' => 1,
                ],
                [
                    'filepath' => '0000000000000stagebezoekOmeta.docx',
                    'DR_line_id' => 2
                ],
                [
                    'filepath' => '0000000000000tankbon.jpg',
                    'DR_line_id' => 3,
                ],
                [
                    'filepath' => '0000000000000factuur_cadeau.pdf',
                    'DR_line_id' => 4
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
