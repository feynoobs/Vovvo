<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('responses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('thread_id')->comment('スレッドID');
            $table->string('name')->nullable()->comment('投稿者の名前');
            $table->string('email')->nullable()->comment('投稿者のメアド');
            $table->string('uid')->nullable()->comment('投稿者のID');
            $table->string('ip')->comment('投稿者のIP Address');
            $table->text('message')->comment('投稿内容');
            $table->softDeletes()->index();
            $table->timestamps();

            $table->foreign('thread_id')->references('id')->on('threads')->onDelete('cascade');
            $table->comment('レステーブル');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('responses');
    }
};
