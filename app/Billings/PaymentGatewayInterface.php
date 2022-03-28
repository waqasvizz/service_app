<?php
namespace App\Billings;

use Illuminate\Http\Request;

interface PaymentGatewayInterface
{
    // public function processPayment(Request $request);
    public function payerDetails(Request $request);
    public function receiverDetails(Request $request);
    public function transactionDetails(Request $request);
}