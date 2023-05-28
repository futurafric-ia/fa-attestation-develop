<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPayloadToScansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('scans', function (Blueprint $table) {
            $table->string('resource_name')->nullable();
            $table->json('files_paths')->nullable();
            $table->json('query_results')->nullable();
            $table->json('parser_results')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('scans', function (Blueprint $table) {
            //
        });
    }
}
