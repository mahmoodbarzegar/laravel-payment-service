<?php

namespace App\Enums;

enum TransactionStatus: int
{
    case Pending = 1;   // در انتظار پرداخت
    case Paid    = 2;   // پرداخت شده
    case Failed    = 3;   // پرداخت نشده

    public function label(): string
    {
        return match($this) {
            self::Pending => 'در انتظار پرداخت',
            self::Paid    => 'پرداخت شده',
            self::Failed    => 'پرداخت ناموفق',
        };
    }
}
