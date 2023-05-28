<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttestationScanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attestation_scan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('scan_id')->index();
            $table->unsignedBigInteger('attestation_id')->index();
            $table->timestamps();

            $table->foreign('scan_id')->references('id')->on('scans')->cascadeOnDelete();
            $table->foreign('attestation_id')->references('id')->on('attestations')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attestation_scan');
    }
}
