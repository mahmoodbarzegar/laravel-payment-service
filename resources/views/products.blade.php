@extends('layout')
@section('title','لیست محصولات')
@section('content')
    <h1 class="my-5">لیست محصولات </h1>
    <div class="row g-4 py-5 row-cols-1 row-cols-lg-3">
        @forelse($products as $product)

            <div class="col-md-3 col-12">
                <div class="border rounded shadow p-5">

                    <h3 class="fs-2 text-body-emphasis">{{ $product->name }}</h3>
                    <p>
                        {{ $product->description }}
                    </p>
                    <div class="d-flex gap-3">
                        <span class="">{{ \Illuminate\Support\Number::format($product->price ) }} تومان </span>
                        <a href="{{ route('checkout',['id'=>$product->id]) }}" class="btn btn-primary btn-sm">
                            خرید
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <p>
                در حال حاضر هیچ محصولی وجود ندارد
            </p>
        @endforelse

    </div>
@endsection