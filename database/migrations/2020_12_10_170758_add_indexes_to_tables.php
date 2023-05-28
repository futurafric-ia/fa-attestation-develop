<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attestations', function (Blueprint $table) {
            $table->index('state');
            $table->index('anterior');
        });

        Schema::table('scans', function (Blueprint $table) {
            $table->index('state');
            $table->index('attestation_state');
        });

        Schema::table('supplies', function (Blueprint $table) {
            $table->index('state');
            $table->index('received_at');
        });

        Schema::table('deliveries', function (Blueprint $table) {
            $table->index('state');
        });
    }
}
