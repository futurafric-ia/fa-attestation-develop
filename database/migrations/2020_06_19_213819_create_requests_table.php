<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->unsignedBigInteger('broker_id');
            $table->unsignedBigInteger('attestation_type_id');
            $table->unsignedBigInteger('created_by');
            $table->unsignedInteger('quantity');
            $table->timestamp('expected_at')->nullable();
            $table->unsignedInteger('quantity_approved')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->unsignedInteger('quantity_validated')->nullable();
            $table->timestamp('validated_at')->nullable();
            $table->unsignedInteger('quantity_delivered')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->string('state');
            $table->text('notes')->nullable();
            $table->text('reason')->nullable();
            $table->timestamp('aborted_at')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('broker_id')->references('id')->on('brokers');
            $table->foreign('parent_id')->references('id')->on('requests');
            $table->foreign('created_by')->references('id')->on('users');
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
        Schema::dropIfExists('requests');
    }
}
