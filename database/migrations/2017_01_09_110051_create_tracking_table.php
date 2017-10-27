<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrackingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trackings', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('is_logged');
            $table->string('auth_token')->nullable();
            $table->string('user_key')->nullable();
            $table->string('ip');
            $table->string('url');
            $table->string('site_key');
            $table->string('method');
            $table->string('session_id');
            $table->string('table_key');
            $table->string('time_start')->nullable();
            $table->string('time_end')->nullable();
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
        Schema::drop('trackings');
    }
}
