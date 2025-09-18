<?php

namespace App\Services\Payment\Gateways;

use App\Models\Transaction;

interface PaymentGatewayInterface
{
    public function pay($amount, $callbackUrl);
    public function verify($request,   $transactionId);

}