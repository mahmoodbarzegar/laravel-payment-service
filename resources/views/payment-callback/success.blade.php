
@extends('layout')
@section('title','پرداخت موفق')
@section('content')
    <div class="row">

        <div class="border rounded shadow p-5">
            <div class="text-success">پرداخت شما با موفقیت انجام شد</div>
            <span>
               کد پیگیری : {{ $transaction->ref_id }}
           </span>
        </div>


    </div>
@endsection


