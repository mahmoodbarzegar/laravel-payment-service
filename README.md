# Laravel Multi-Gateway Payment Service

This project is a payment module for Laravel that uses the Strategy Pattern to support multiple gateways (Zarinpal,
Mellat, etc.).
The goal of this service is to separate the payment logic from the rest of the application and make it easy to add new
gateways.

## 📂 ساختار پروژه

app/
├── Services/
│ └── Payment/
│ ├── PaymentService.php
│ ├── Gateways/
│ │ ├── PaymentGatewayInterface.php
│ │ ├── ZarinpalGateway.php
│ │ └── MellatGateway.php

PaymentGatewayInterface → Common contract for all gateways (pay and verify methods)

ZarinpalGateway → Implementation for Zarinpal

MellatGateway → Implementation for Mellat Bank (as an example)

PaymentService → The main service that manages the selected gateway
---

## Payment Flow

1- Create a Transaction
A new transaction is created and stored in the database with status Pending.

2- Redirect to Gateway
Using the pay method, the user is redirected to the selected gateway.

3- Return from Gateway (Callback)
The gateway redirects the user back to the specified callback route with transaction parameters.

4- Verify Payment
The verify method communicates with the gateway API to confirm the payment.

5- Update Transaction Status

On success: Status changes to Paid and the ref_id is saved.

On failure or cancellation: Status changes to Failed and an error message is logged.
---

## Callback Example

        $paymentService = new PaymentService();
        $paymentService->setGateway($gateway);

        $res = $paymentService->verify($request->all(), $transactionId);

        $transaction = Transaction::findOrFail($transactionId);

        if ($res['success']) {


            if ($transaction->status !== TransactionStatus::Paid) {

                $transaction->update(['status' => TransactionStatus::Paid, 'ref_id' => $res['ref_id'] ?? null]);
            }

            return view('payment-callback.success', compact('transaction'));

        }

        $transaction->update(['status' => TransactionStatus::Failed, 'message' => $res['message'] ?? 'پرداخت ناموفق بود.'
        ]);

        return view('payment-callback.fail', ['message' => $res['message']]);

## Installation

git clone https://github.com/yourusername/laravel-multi-gateway-payment.git
cd laravel-multi-gateway-payment
composer install
php artisan migrate
php artisan serve

## Usage Example

use App\Services\Payment\PaymentService;
use App\Services\Payment\Gateways\ZarinpalGateway;

$payment = new PaymentService(new ZarinpalGateway());
return $payment->pay($transaction);

## Adding a New Gateway

Create a new class inside app/Services/Payment/Gateways/.

Implement the PaymentGatewayInterface.

Define the pay and verify methods according to the gateway documentation.

Use it in the PaymentService.

## Contributing

Pull requests (PRs) and issues are welcome.
Please make sure to test the project before submitting changes.


