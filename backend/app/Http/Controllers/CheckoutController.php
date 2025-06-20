<?php

namespace App\Http\Controllers;

use App\Contracts\PaymentGatewayInterface;

class CheckoutController extends Controller
{
    public function pay(PaymentGatewayInterface $gateway)
    {
        return $gateway->pay(100);
    }
}