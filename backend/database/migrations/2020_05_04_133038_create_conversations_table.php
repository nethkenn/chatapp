<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConversationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('conversation_info_id')->unsigned();
            $table->foreign('conversation_info_id')->references('id')->onDelete('cascade')->on('conversation_infos');
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->onDelete('cascade')->on('users');
            $table->string('nickname');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('conversations');
    }
}
