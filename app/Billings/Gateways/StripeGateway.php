<?php
namespace App\Billings\Gateways;

use App\Billings\PaymentGatewayInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Payment;
use App\Models\Setting;
use Auth;

class StripeGateway implements PaymentGatewayInterface
// class StripeGateway extends Controller
{

    private $stripe;

    public function __construct()
    {
        $this->stripe = StripeGateway::getGatewayObject();
    }

    public function getGatewayObject()
    {
        $settings = new Setting();
        $response = $settings->detailSetting(1);

        if ($response->stripe_mode == 'test') {
            $STRIPE_KEY = $response->stpk;
            $STRIPE_SECRET = $response->stsk;
        } else {
            $STRIPE_KEY = $response->slpk;
            $STRIPE_SECRET = $response->slsk;
        }
        $stripe = new \Stripe\StripeClient($STRIPE_SECRET);
        return $stripe;
    }

    public function chargePayment($posted_data = array()){

        if(count($posted_data) > 0 && $posted_data['customer_id'] != '' ) {
            try {
                $stripe_response = $this->stripe->charges->create([
                    'customer' => $posted_data['customer_id'],
                    'amount' => $posted_data['amount'],
                    'currency' => $posted_data['currency'],
                    'description' => $posted_data['description'],
                ]);

                return $stripe_response;
            }
            catch (\Throwable $th) {
                return false;
            }
        }
        else {
            return false;
        }

    }

    public function payerDetails(Request $request){
        // some code here...
    }

    public function transactionDetails(Request $request){
        // some code here...
    }

    public function receiverDetails(Request $request){
        // some code here...
    }

    public function createCustomer($posted_data = array()){
        // $stripe = StripeGateway::getGatewayObject();

        try {
            $customer_rec = $this->stripe->customers->create([
                'email' => $posted_data['email'],
                'source'  => $posted_data['stripeToken'],
                // 'source'  => $request->stripeToken,
                'description' => $posted_data['name'],
                // 'description' => 'Customer Name is '.$posted_data['card_name'],
                // 'description' => 'Customer Name is '.Auth::user()->name,
                // 'description' => 'Customer Name is '.$request->get('card_name'),
            ]);
            return $customer_rec->id;
        }
        catch (\Throwable $th) {
            return false;
        }
    }
    
    public function customerDetails(Request $request){
        // some code here...
    }
}