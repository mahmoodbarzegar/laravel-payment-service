# 🏦 سرویس پرداخت چنددرگاهی در لاراول

این پروژه یک ماژول پرداخت در لاراول است که از الگوی **Strategy Pattern** برای پشتیبانی از درگاه‌های مختلف (زرین‌پال، ملت و …) استفاده می‌کند.  
هدف این سرویس، جداسازی منطق پرداخت از بخش‌های دیگر برنامه و امکان توسعه آسان برای هر درگاه جدید است.

---

## 📂 ساختار پروژه

app/
├── Services/
│ └── Payment/
│ ├── PaymentService.php
│ ├── Gateways/
│ │ ├── PaymentGatewayInterface.php
│ │ ├── ZarinpalGateway.php
│ │ └── MellatGateway.php



- **PaymentGatewayInterface** → قرارداد مشترک برای همه درگاه‌ها (متدهای `pay` و `verify`)
- **ZarinpalGateway** → پیاده‌سازی درگاه زرین‌پال
- **MellatGateway** → پیاده‌سازی درگاه بانک ملت (به‌عنوان نمونه)
- **PaymentService** → سرویس اصلی که درگاه انتخابی را مدیریت می‌کند

---

## ⚙️ مراحل گردش پرداخت

1. **ایجاد تراکنش**  
   کاربر یک تراکنش ایجاد می‌کند و وضعیت آن در دیتابیس به حالت `Pending` ذخیره می‌شود.

2. **ارسال به درگاه**  
   با استفاده از متد `pay`، کاربر به درگاه انتخابی هدایت می‌شود.

3. **بازگشت از درگاه (Callback)**  
   درگاه کاربر را به مسیر مشخص‌شده بازمی‌گرداند و پارامترهای تراکنش را ارسال می‌کند.

4. **تأیید پرداخت (Verify)**  
   متد `verify` با API درگاه ارتباط می‌گیرد و صحت پرداخت را بررسی می‌کند.

5. **به‌روزرسانی وضعیت تراکنش**
    - در صورت موفقیت: وضعیت به `Paid` تغییر می‌کند و `ref_id` ذخیره می‌شود.
    - در صورت شکست یا لغو: وضعیت به `Failed` تغییر می‌کند و پیام خطا ثبت می‌شود.

---

## 🗄 جدول تراکنش‌ها

در جدول `transactions` اطلاعات هر تراکنش ذخیره می‌شود:

| ستون | توضیح |
|------|--------|
| `id` | شناسه تراکنش |
| `user_id` | شناسه کاربر |
| `amount` | مبلغ تراکنش |
| `status` | وضعیت (`Pending`, `Paid`, `Failed`, `Canceled`) |
| `ref_id` | کد پیگیری درگاه |
| `message` | پیام خطا یا توضیح وضعیت |
| `created_at` / `updated_at` | زمان ایجاد و به‌روزرسانی |

---

## 📑 نمونه کد Callback

```php
if ($res['success']) {

    $transaction = Transaction::find($transactionId);

    if ($transaction && $transaction->status !== TransactionStatus::Paid) {
        $transaction->update([
            'status' => TransactionStatus::Paid,
            'ref_id' => $res['ref_id'] ?? null
        ]);
    }

    return view('payment-callback.success', compact('transaction'));

} else {

    $transaction = Transaction::find($transactionId);

    if ($transaction) {
        $transaction->update([
            'status' => TransactionStatus::Failed,
            'message' => $res['message'] ?? 'پرداخت ناموفق بود.'
        ]);
    }

    return view('payment-callback.fail', ['message' => $res['message']]);
}

## ⚙️ مراحل گردش پرداخت

1. **ایجاد تراکنش**  
   کاربر یک تراکنش ایجاد می‌کند و وضعیت آن در دیتابیس به حالت `Pending` ذخیره می‌شود.  

2. **ارسال به درگاه**  
   با استفاده از متد `pay`، کاربر به درگاه انتخابی هدایت می‌شود.  

3. **بازگشت از درگاه (Callback)**  
   درگاه کاربر را به مسیر مشخص‌شده بازمی‌گرداند و پارامترهای تراکنش را ارسال می‌کند.  

4. **تأیید پرداخت (Verify)**  
   متد `verify` با API درگاه ارتباط می‌گیرد و صحت پرداخت را بررسی می‌کند.  

5. **به‌روزرسانی وضعیت تراکنش**  
   - در صورت موفقیت: وضعیت به `Paid` تغییر می‌کند و `ref_id` ذخیره می‌شود.  
   - در صورت شکست یا لغو: وضعیت به `Failed` تغییر می‌کند و پیام خطا ثبت می‌شود.  

---

## 🗄 جدول تراکنش‌ها

در جدول `transactions` اطلاعات هر تراکنش ذخیره می‌شود:  

| ستون | توضیح |
|------|--------|
| `id` | شناسه تراکنش |
| `user_id` | شناسه کاربر |
| `amount` | مبلغ تراکنش |
| `status` | وضعیت (`Pending`, `Paid`, `Failed`, `Canceled`) |
| `ref_id` | کد پیگیری درگاه |
| `message` | پیام خطا یا توضیح وضعیت |
| `created_at` / `updated_at` | زمان ایجاد و به‌روزرسانی |

---

## 📑 نمونه کد Callback

```php
if ($res['success']) {

    $transaction = Transaction::find($transactionId);

    if ($transaction && $transaction->status !== TransactionStatus::Paid) {
        $transaction->update([
            'status' => TransactionStatus::Paid,
            'ref_id' => $res['ref_id'] ?? null
        ]);
    }

    return view('payment-callback.success', compact('transaction'));

} else {

    $transaction = Transaction::find($transactionId);

    if ($transaction) {
        $transaction->update([
            'status' => TransactionStatus::Failed,
        ]);
    }

    return view('payment-callback.fail', ['message' => $res['message']]);
}


return [
    'zarinpal' => [
        'merchant_id' => env('ZARINPAL_MERCHANT_ID'),
        'sandbox' => env('ZARINPAL_SANDBOX', true),
    ],
    'mellat' => [
        'terminal_id' => env('MELLAT_TERMINAL_ID'),
        'username' => env('MELLAT_USERNAME'),
        'password' => env('MELLAT_PASSWORD'),
    ],
];


