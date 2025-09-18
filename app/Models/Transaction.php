<?php

namespace App\Models;

use App\Enums\TransactionStatus;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable=['amount','product_id','ref_id','status','message'];

    protected $casts = [
        'status' => TransactionStatus::class,
    ];
}
