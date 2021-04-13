<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRandomChatUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('random_chat_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('random_chat_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('group_id')->nullable()->comment('冗余字段');
            $table->string('status')->nullable()->comment('同步random_chats中的status');
            $table->tinyInteger('type')->default(0)->comment('0 主动加入的 1申请加入的 2主动拉入的');
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
        Schema::dropIfExists('random_chat_users');
    }
}
