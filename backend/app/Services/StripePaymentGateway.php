<?php

// app/Services/StripePaymentGateway.php
namespace App\Services;

use App\Contracts\PaymentGatewayInterface;

class StripePaymentGateway implements PaymentGatewayInterface
{
    public function pay($amount)
    {
        return "Paid {$amount} via Stripe.";
    }
}
