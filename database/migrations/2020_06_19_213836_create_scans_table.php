<?php

use Domain\Scan\Models\Scan;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scans', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->unsignedBigInteger('broker_id');
            $table->unsignedBigInteger('attestation_type_id');
            $table->unsignedInteger('total_count')->default(0);
            $table->unsignedInteger('mismatches_count')->default(0);
            $table->string('state');
            $table->string('attestation_state');
            $table->string('type');
            $table->string('source')->default(Scan::SOURCE_INTERNAL);
            $table->json('payload')->nullable();
            $table->string('failure_reason')->nullable();
            $table->unsignedBigInteger('initiated_by')->nullable();
            $table->timestamps();
            $table->foreign('attestation_type_id')->references('id')->on('attestation_types');
            $table->foreign('initiated_by')->references('id')->on('users')->onDelete('SET NULL');
            $table->foreign('broker_id')->references('id')->on('brokers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scans');
    }
}
