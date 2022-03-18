<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->string('amount_captured');
            $table->string('currency');
            $table->longText('response_object');
            $table->string('payment_method');
            $table->string('payment_intent');
            $table->string('payment_status');

            $table->string('stripe_prod_id')->nullable();
            $table->string('stripe_plan_id')->nullable();
            $table->string('stripe_sub_id')->nullable();
            $table->string('subscription_status')->nullable();
            $table->string('stripe_sub_cycle')->default(0);
            $table->string('stripe_customer_id')->nullable();
            $table->longText('stripe_response_card_info')->nullable();
            
            $table->string('paypal_payment_id')->nullable();
            $table->string('paypal_transaction_id')->nullable();
            $table->string('paypal_payer_id')->nullable();
            $table->string('paypal_merchant_id')->nullable();
            
            $table->string('coupon_code_id')->nullable();
            $table->string('coupon_discount')->nullable();
            $table->float('coupon_amount')->nullable();

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
        Schema::dropIfExists('payments');
    }
}
