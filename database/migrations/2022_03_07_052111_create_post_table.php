<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('service_id');
            $table->foreign('service_id')->references('id')->on('services')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('customer_id');
            $table->foreign('customer_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('price');
            $table->string('title', 100);
            $table->string('lat', 100)->nullable();
            $table->string('long', 100)->nullable();
            $table->string('address', 100)->nullable();
            $table->date('task_completion_date')->nullable();
            $table->longText('description')->nullable();
            $table->tinyInteger('pay_with')->default(1)->comment('1=COD, 2=Paypal, 3=Stripe');
            $table->tinyInteger('status')->default(1)->comment('1=Pending, 2=In-Progress, 3=Complete');
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
        Schema::dropIfExists('post');
    }
}