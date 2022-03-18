<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();

            $table->text('stripe_mode')->comment('test/live');
            $table->text('stpk')->comment('Publishable Test Key');
            $table->text('stsk')->comment('Secret Test Key');
            $table->text('slpk')->comment('Publishable live Key');
            $table->text('slsk')->comment('Secret Live Key');

            $table->text('paypal_mode')->comment('test/live');
            $table->text('pl_username')->comment('Paypal Live Username');
            $table->text('pl_password')->comment('Paypal Live Passowrd');
            $table->text('pl_client_id')->comment('Paypal Live Client ID');
            $table->text('pl_app_id')->comment('Paypal Live App ID');
            $table->text('pl_client_secret')->comment('Paypal Live Secret Key');

            $table->text('pt_username')->comment('Paypal Live Username');
            $table->text('pt_password')->comment('Paypal Live Passowrd');
            $table->text('pt_client_id')->comment('Paypal Live Client ID');
            $table->text('pt_app_id')->comment('Paypal Live App ID');
            $table->text('pt_client_secret')->comment('Paypal Live Secret Key');

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
        Schema::dropIfExists('settings');
    }
}
