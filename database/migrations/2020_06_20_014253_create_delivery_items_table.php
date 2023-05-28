<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveryItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('delivery_id')->index();
            $table->unsignedBigInteger('range_start')->index();
            $table->unsignedBigInteger('range_end')->index();
            $table->timestamps();
            $table->foreign('delivery_id')->references('id')->on('deliveries');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('delivery_items');
    }
}
