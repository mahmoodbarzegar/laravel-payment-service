<?php

namespace App\Services\Payment\Gateways;

use App\Models\Transaction;
use Illuminate\Support\Facades\Http;

class ZarinpalGateway implements PaymentGatewayInterface
{
    protected $merchantId;

    public function __construct()
    {
        $this->merchantId = config('payment.zarinpal.merchant_id');
        $this->sandbox = config('payment.zarinpal.sandbox', true);

    }

    public function pay($amount, $callbackUrl)
    {
        $url = $this->sandbox
            ? 'https://sandbox.zarinpal.com/pg/v4/payment/request.json'
            : 'https://api.zarinpal.com/pg/v4/payment/request.json';

        $response = Http::post($url, [
            'merchant_id' => $this->merchantId,
            'amount' => $amount,
            'callback_url' => $callbackUrl,
            'description' => 'پرداخت سفارش',
//            'metadata' => [
//                'email' => '',
//                'mobile' => ''
//            ]
        ]);

        $data = $response->json();

        if (isset($data['data']['code']) && $data['data']['code'] == 100) {
            $authority = $data['data']['authority'];
            $paymentUrl = $this->sandbox
                ? "https://sandbox.zarinpal.com/pg/StartPay/$authority"
                : "https://www.zarinpal.com/pg/StartPay/$authority";

            return $paymentUrl;
        }

        throw new \Exception("خطا در ایجاد پرداخت: " . json_encode($data));
    }

    public function verify($request, $transactionId)
    {
        $authority = $request['Authority'] ?? null;
        $status = $request['Status'] ?? null;
        if ($status !== 'OK') {
            return [
                'success' => false,
                'message' => 'پرداخت ناموفق بود',
            ];
//            return view('payment-callback.fail');
        }

        $transaction = Transaction::findOrFail($transactionId);

        $url = $this->sandbox
            ? 'https://sandbox.zarinpal.com/pg/v4/payment/verify.json'
            : 'https://api.zarinpal.com/pg/v4/payment/verify.json';

        $response = Http::post($url, [
            'merchant_id' => $this->merchantId,
            'amount' => $transaction->amount,
            'authority' => $authority
        ]);

        $data = $response->json();

        if (isset($data['data']['code']) && $data['data']['code'] == 100) {
            return [
                'success'   => true,
                'ref_id'    => $data['data']['ref_id'],
                'message'   => 'پرداخت با موفقیت انجام شد.',
            ];
        }

        return [
            'success' => false,
            'message' => $data['errors']['message'] ?? 'خطا در تأیید پرداخت.',
        ];
    }
}