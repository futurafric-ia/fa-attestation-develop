<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttestationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attestations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('attestation_number')->unique();
            $table->string('insured_name')->nullable();
            $table->string('police_number')->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->string('vehicle_type')->nullable();
            $table->string('make')->nullable();
            $table->string('matriculation')->nullable();
            $table->text('content')->nullable();
            $table->string('image_path')->nullable();
            $table->string('address')->nullable();
            $table->string('state');
            $table->string('source')->nullable();
            $table->unsignedBigInteger('attestation_type_id');
            $table->unsignedBigInteger('supply_id');
            $table->unsignedBigInteger('current_broker_id')->nullable();
            $table->unsignedBigInteger('last_scan_id')->nullable();
            $table->unsignedBigInteger('last_delivery_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('attestation_type_id')->references('id')->on('attestation_types')->cascadeOnDelete();
            $table->foreign('supply_id')->references('id')->on('supplies')->cascadeOnDelete();
            $table->foreign('last_scan_id')->references('id')->on('scans')->onDelete('SET NULL');
            $table->foreign('last_delivery_id')->references('id')->on('deliveries')->onDelete('SET NULL');
            $table->foreign('current_broker_id')->references('id')->on('brokers')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attestations');
    }
}
