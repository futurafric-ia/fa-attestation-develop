<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBrokersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('brokers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('code')->unique();
            $table->string('slug')->unique();
            $table->string('email')->unique();
            $table->string('address')->nullable();
            $table->string('contact')->nullable();
            $table->string('logo_url')->nullable();
            $table->boolean('active')->default(true);
            $table->unsignedInteger('department_id');
            $table->unsignedBigInteger('owner_id')->nullable();
            $table->unsignedMediumInteger('minimum_consumption_percentage')->default(config('saham.brokers.minimum_consumption_percentage'));
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('department_id')->references('id')->on('departments');
            $table->foreign('owner_id')->references('id')->on('users')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('brokers');
    }
}
