<?php

namespace App\Http\Controllers;

use App\Billings\Gateways\StripeGateway;
use App\Billings\PaymentGatewayInterface;
use Illuminate\Http\Request;
// use Srmklive\PayPal\Services\ExpressCheckout;
// use Srmklive\PayPal\Services\PayPal as PayPalClient;
use App\Models\Payment;
use App\Models\Setting;
use App\Models\User;
use Auth;
use Illuminate\Support\Facades\Crypt;

class PaymentController extends Controller
{
    
    public function cart(){

        $settings = new Setting();
        $response = $settings->detailSetting(1);

        $paypal_credentials = array();

        if ($response->paypal_mode == 'test' ) {
            $paypal_credentials = array(
                'username'       => Crypt::decryptString($response->pt_username),
                'password'       => Crypt::decryptString($response->pt_password),
                'client_id'      => Crypt::decryptString($response->pt_client_id),
                'app_id'         => Crypt::decryptString($response->pt_app_id),
                'client_secret'  => Crypt::decryptString($response->pt_client_secret)
            );
        }

        return view('payment.add_payment', compact('paypal_credentials'));
    }

    public function cartStripe() {

        $settings = new Setting();
        $response = $settings->detailSetting(1);

        $stripe_credentials = array(
            'stripe_publish_key' => $response->stripe_mode == 'test' ? Crypt::decryptString($response->stpk) : Crypt::decryptString($response->slpk),
            'stripe_secret_key'  => $response->stripe_mode == 'test' ? Crypt::decryptString($response->stsk) : Crypt::decryptString($response->slsk),
        );

        return view('payment.add_stripe_payment', compact('stripe_credentials'));
    }

    public function payment(Request $request, PaymentGatewayInterface $gateway){

        $class_instance = class_basename($gateway);
        if ($class_instance == 'StripeGateway') {

            // $settings = new Setting();
            // $response = $settings->detailSetting(1);

            // $stripe_credentials = array(
            //     'stripe_publish_key' => $response->stripe_mode == 'test' ? Crypt::decryptString($response->stpk) : Crypt::decryptString($response->slpk),
            //     'stripe_secret_key'  => $response->stripe_mode == 'test' ? Crypt::decryptString($response->stsk) : Crypt::decryptString($response->slsk),
            // );

            $posted_data['id'] = Auth::user()->id;
            $posted_data['detail'] = true; 

            $result = User::getUser($posted_data);
            $stripe_customer_id = $result->stripe_customer_id;

            if ($stripe_customer_id == '') {
                $stripe_customer_id = $gateway->createCustomer([
                    'stripeToken'   => isset($request->stripeToken) ? $request->stripeToken : '',
                    'email'   => Auth::user()->email,
                    'name'     => isset($request->card_name) ? $request->card_name : ''
                ]);
            }

            $stripe_response = $gateway->chargePayment([
                'customer_id'       => $stripe_customer_id,
                'amount'            => $request->get('amount') * 100,
                'currency'          => $request->get('currency') ? $request->get('currency') : 'usd',
                'description'       => 'Payment Successful',
            ]);

            if (isset($stripe_response)) {

                $payments = new Payment();

                $data_arr = array();
                $data_arr['user_id'] = \Auth::user()->id;
                if (isset($stripe_response->object)) $data_arr['response_object'] = json_encode($stripe_response->object);
                if (isset($stripe_response->amount_captured)) $data_arr['amount_captured'] = $stripe_response->amount_captured / 100;
                if (isset($stripe_response->balance_transaction)) $data_arr['balance_transaction'] = $stripe_response->balance_transaction;
                if (isset($stripe_response->object)) $data_arr['payment_intent'] = $stripe_response->object;
                $data_arr['payment_method'] = 'stripe';
                $data_arr['payment_status'] = 'COMPLETED';
                $data_arr['stripe_customer_id'] = $stripe_customer_id;
                if (isset($stripe_response->currency)) $data_arr['currency'] = $stripe_response->currency;
                if (isset($stripe_response->created)) $data_arr['created'] = $stripe_response->created;

                $result = $payments->saveUpdatePayment($data_arr);
            }

        }
        else if ($class_instance == 'PaypalGateway') {
            // paypal code here....
        }
        
        exit("@@@@");

        // $response = $gateway->processPayment($request);
        // return $gateway->checkout($response);

        // $products = [];
        // $products['items'] = [
        //     [
        //         'name'    => 'Laravel Book',
        //         'price'   => 50,
        //         'des'     => "Laravel Book for Advance Learning",
        //         'qty'     => 1
        //     ]
        // ];

        // $products['invoice_id'] = uniqid();
        // $products['invoice_description'] = "Order # {$products['invoice_id']} Billing";
        // $products['return_url'] = route('success.pay');
        // $products['cancel_url'] = route('success.pay');
        // $products['total'] = 50;

        // $paypal = new ExpressCheckout();

        // return $paypal->setExpressCheckout($products);
    }
       
    // public function paymentSuccess(Request $request){
    //     $paypal = new ExpressCheckout();
    //     $response = $paypal->getExpressCheckoutDetails($request->token);
    // }

    public function paymentSuccess(Request $request)
    {
        dump($request->all());
        $paypal = new ExpressCheckout();
        $response = $paypal->getExpressCheckoutDetails($request->token);
        dump($response);
    }

    public function savePaymentResponse(Request $request) {

        $payments = new Payment();
        $response = json_decode(json_encode($request->all()), true);
        
        $posted_data = array();
        $posted_data['payment_method'] = isset($response['purchase_units'][0]['payee']['merchant_id']) ? 'paypal' : 'stripe';
        $posted_data['response_object'] = json_encode($request->all());
        $posted_data['user_id'] = Auth::user()->id;

        if ($posted_data['payment_method'] == 'paypal') {

            $posted_data['amount_captured'] = isset($response['purchase_units'][0]['payments']['captures'][0]['amount']['value']) ? $response['purchase_units'][0]['payments']['captures'][0]['amount']['value'] : 0;
            $posted_data['currency'] = isset($response['purchase_units'][0]['payments']['captures'][0]['amount']['currency_code']) ? $response['purchase_units'][0]['payments']['captures'][0]['amount']['currency_code'] : NULL;
            $posted_data['payment_status'] = isset($response['status']) ? $response['status'] : NULL;
            $posted_data['payment_intent'] = isset($response['intent']) ? $response['intent'] : NULL;
            $posted_data['paypal_payment_id'] = isset($response['purchase_units'][0]['payments']['captures'][0]['id']) ? $response['purchase_units'][0]['payments']['captures'][0]['id'] : NULL;
            $posted_data['paypal_transaction_id'] = isset($response['id']) ? $response['id'] : NULL;
            $posted_data['paypal_payer_id'] = isset($response['payer']['payer_id']) ? $response['payer']['payer_id'] : NULL;
            $posted_data['paypal_merchant_id'] = isset($response['purchase_units'][0]['payee']['merchant_id']) ? $response['purchase_units'][0]['payee']['merchant_id'] : NULL;

            $result = $payments->saveUpdatePayment($posted_data);
        }
        else if ($posted_data['payment_method'] == 'stripe') {
            
            // $stripe = new StripeGateway();
            
        }
    }
}