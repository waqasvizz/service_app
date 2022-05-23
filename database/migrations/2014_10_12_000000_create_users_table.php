<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('role');
            $table->foreign('role')->references('id')->on('roles')->onUpdate('cascade')->onDelete('cascade');
            $table->string('name');
            $table->string('first_name', 100)->nullable();
            $table->string('last_name', 100)->nullable();
            $table->string('email')->unique();
            $table->text('user_type')->comment('app, facebook, google');
            $table->string('account_status',100)->nullable()->comment('yes, no');
            $table->date('account_expiry')->default(NULL)->nullable();
            $table->date('active_status', 50)->default('yes')->nullable()->comment('yes, no');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->text('address')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('profile_image')->nullable();
            $table->string('license_document',255)->nullable();
            $table->string('insurance_document',255)->nullable();
            $table->string('experience',100)->nullable();
            $table->integer('no_of_jobs_completed')->default(0);
            $table->string('avg_rating',100)->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}