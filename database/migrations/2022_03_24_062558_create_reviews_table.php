<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sender_id')->comment('sender review');
            $table->foreign('sender_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('receiver_id')->comment('receiver review');
            $table->foreign('receiver_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('stars');
            $table->longText('description')->nullable();
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
        Schema::dropIfExists('reviews');
    }
}