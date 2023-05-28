<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestDiscussions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_discussions', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->text('notes');
            $table->unsignedBigInteger('request_id');
            $table->unsignedBigInteger('user_id');

            $table->foreign('request_id')->references('id')->on('requests');
            $table->foreign('user_id')->references('id')->on('users');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('request_discussions');
    }
}
