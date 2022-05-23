<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBidsTabel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bids', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('provider_id');
            $table->foreign('provider_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('post_id');
            $table->foreign('post_id')->references('id')->on('posts')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('price');
            $table->tinyInteger('status')->default(1)->comment('1=Pending, 2=Reject, 3=Accept');
            $table->longText('detail')->nullable();
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
        Schema::dropIfExists('bids');
    }
}