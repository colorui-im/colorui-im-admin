<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            //暂无用到
            $table->id();
            $table->unsignedBigInteger('from_id')->nullable();
            $table->unsignedBigInteger('to_id')->nullable()->comment('即群的id');
            $table->text('from')->nullable()->comment('发送者信息冗余');
            $table->text('to')->nullable()->comment('接收者信息冗余');
            $table->string('type')->nullable()->comment('留作备用');
            $table->string('message_type')->nullable()->comment('消息类型');
            $table->text('data')->nullable()->comment('消息内容是一个json');
            $table->timestamp('sended_at')->nullable();
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
        Schema::dropIfExists('messages');
    }
}
