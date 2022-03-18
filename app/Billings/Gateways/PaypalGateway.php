<?php
namespace App\Billings\Gateways;

use App\Billings\PaymentGatewayInterface;
use Illuminate\Http\Request;
use Srmklive\PayPal\Services\ExpressCheckout;

class PaypalGateway implements PaymentGatewayInterface
{

    public function process(Request $request)
    {
        $products = [];
        $products['items'] = [
            [
                'name'    => 'Laravel Book',
                'price'   => 50,
                'des'     => "Laravel Book for Advance Learning",
                'qty'     => 1
            ]
        ];

        $products['invoice_id'] = uniqid();
        $products['invoice_description'] = "Order #{$products['invoice_id']} Billing";
        $products['return_url'] = route('success.pay');
        $products['cancel_url'] = route('success.pay');
        $products['total'] = 50;

        $paypal = new ExpressCheckout();
        return $paypal->setExpressCheckout($products);
    }

    public function checkout($res)
    {
        return redirect($res['paypal_link']);
    }

    public function payerDetails(Request $request){
        // some code here...
    }

    public function receiverDetails(Request $request){
        // some code here...
    }

    public function transactionDetails(Request $request){
        // some code here...
    }
    
}