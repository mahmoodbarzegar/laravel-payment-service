<?php

namespace App\Services\Payment;

use App\Models\Transaction;
use App\Services\Payment\Gateways\ZarinpalGateway;

class PaymentService
{
    protected $gateway;

    public function setGateway(string $gateway)
    {
        switch ($gateway) {
            case 'zarinpal':
                $this->gateway = new ZarinpalGateway();
                break;
            case 'mellat':
//                $this->gateway = new MellatGateway();
//                break;
            default:
                throw new \Exception("درگاه انتخابی نامعتبر است.");
        }

        return $this;
    }

    public function pay($amount, $callbackUrl)
    {

        return $this->gateway->pay($amount, $callbackUrl);
    }

    public function verify($request, $transactionId)
    {
        return $this->gateway->verify($request, $transactionId);
    }
}