<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScanMismatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scan_mismatches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('attestation_number')->nullable();
            $table->string('matriculation')->nullable();
            $table->string('insured_name')->nullable();
            $table->string('police_number')->nullable();
            $table->string('address')->nullable();
            $table->text('content')->nullable();
            $table->string('image_path')->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->string('vehicle_type')->nullable();
            $table->string('make')->nullable();
            $table->unsignedBigInteger('attestation_type_id');
            $table->unsignedBigInteger('scan_id');
            $table->timestamps();
            $table->foreign('attestation_type_id')->references('id')->on('attestation_types');
            $table->foreign('scan_id')->references('id')->on('scans');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scan_mismatches');
    }
}
