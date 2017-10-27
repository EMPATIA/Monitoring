<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDataCollectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_collections', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('component_server_id')->unsigned();
            $table->string('memory_used');
            $table->string('read_sector');
            $table->string('read_byte');
            $table->string('write_sector');
            $table->string('write_byte');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('data_collections');
    }
}
