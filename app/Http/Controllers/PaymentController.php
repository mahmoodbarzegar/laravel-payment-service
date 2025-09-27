<?php

namespace App\Http\Controllers;

use App\Enums\TransactionStatus;
use App\Http\Requests\PayRequest;
use App\Models\Transaction;
use App\Services\Payment\PaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function pay(PayRequest $request)
    {
        $gateway = $request->gateway; // zarinpal, mellat, ...
        $amount = $request->amount;
        $productId = $request->product_id;

        $transaction = Transaction::query()->create([
            'amount' => $amount,
            'product_id' => $productId,
            'status' => TransactionStatus::Pending
        ]);

        $paymentService = new PaymentService();
        $paymentService->setGateway($gateway);
        $paymentUrl = $paymentService->pay($amount, route('payment.callback', [$gateway, $transaction]));

        return redirect($paymentUrl);
    }

    public function callback(Request $request, $gateway, $transactionId)
    {
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
    }


}
