<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sender');
            $table->foreign('sender')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('receiver');
            $table->foreign('receiver')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->tinyText('slugs')->nullable()->comment('new-chat, assign-job, new-post, new-bid');
            $table->string('notification_text');
            $table->string('seen_by')->nullable();
            $table->string('metadata')->nullable();
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
        Schema::dropIfExists('notifications');
    }
}
