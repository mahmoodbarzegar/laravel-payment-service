@extends('layout')
@section('title','بررسی و  پرداخت')
@section('content')
    <div class="row">



        <div class="border rounded shadow p-5">
            @if($errors->any())
                @foreach($errors->all() as $error)
                    <div class="text-danger">{{ $error }}</div>
                @endforeach
            @endif
            <h1 class="my-5">{{ $product->name }} </h1>

            <p>
                {{ $product->description }}
            </p>
            <h2 class="fs-5 my-3">
                مبلغ قابل پرداخت:
            </h2>
            <div class="d-flex gap-3">
                <span class="">{{ \Illuminate\Support\Number::format($product->price ) }} تومان </span>
                <form action="{{ route('pay') }}" method="post">
                    @csrf
                    <input type="hidden" value="{{ $product->price }}" name="amount">
                    <input type="hidden" value="{{ $product->id }}" name="product_id">
                    <select class="form-select" name="gateway" aria-label="Default select example">
                        <option selected value="">انتخاب درگاه</option>
                        <option value="zarinpal">زرین پال</option>
                        {{--                        <option value="mellat">ملت</option>--}}
                    </select>

                    <button type="submit" class="btn btn-primary btn-sm mt-3">
                        تایید و پرداخت
                    </button>
                </form>
            </div>
        </div>


    </div>
@endsection