<?php

namespace App\Contracts;

interface PaymentGatewayInterface
{
    public function pay($amount);
}
