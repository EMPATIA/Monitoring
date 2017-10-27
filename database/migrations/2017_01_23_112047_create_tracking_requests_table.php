<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrackingRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tracking_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->string('table_key');
            $table->string('method');
            $table->string('module_token');
            $table->string('url');
            $table->string('result');
            $table->integer('tracking_id')->unsigned();
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
        Schema::drop('tracking_requests');
    }
}
