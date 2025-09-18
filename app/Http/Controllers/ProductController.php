<?php

namespace App\Http\Controllers;

use App\Models\product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();

        return view('products', compact('products'));
    }

    public function checkout(int $id)
    {
        $product = Product::findOrFail($id);
        return view('checkout', compact('product'));
    }
}
