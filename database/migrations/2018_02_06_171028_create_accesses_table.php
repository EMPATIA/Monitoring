<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class CreateAccessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accesses', function (Blueprint $table) {
            $table->increments('id');
            $table->datetime('date');
            $table->string('IP');
            $table->string('url');
            $table->string('session_id');
            $table->string('entity_key');
            $table->string('site_key');
            $table->string('user_key')->nullable();
            $table->string('content_key')->nullable();
            $table->string('cb_key')->nullable();
            $table->string('topic_key')->nullable();
            $table->string('post_key')->nullable();
            $table->string('q_key')->nullable();
            $table->string('vote_key')->nullable();
            $table->string('action');
            $table->boolean('result');
            $table->text('error')->nullable();
            $table->text('details')->nullable();
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
        Schema::dropIfExists('accesses');
    }
}
