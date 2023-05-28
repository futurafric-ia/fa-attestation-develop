<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->unsignedBigInteger('quantity');
            $table->unsignedBigInteger('request_id')->nullable();
            $table->unsignedBigInteger('attestation_type_id');
            $table->unsignedBigInteger('delivered_by')->nullable();
            $table->unsignedBigInteger('broker_id')->nullable();
            $table->timestamp('delivered_at')->useCurrent();
            $table->string('state');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('request_id')->references('id')->on('requests')->onDelete('SET NULL');
            $table->foreign('delivered_by')->references('id')->on('users')->onDelete('SET NULL');
            $table->foreign('broker_id')->references('id')->on('brokers')->onDelete('SET NULL');
            $table->foreign('attestation_type_id')->references('id')->on('attestation_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deliveries');
    }
}
