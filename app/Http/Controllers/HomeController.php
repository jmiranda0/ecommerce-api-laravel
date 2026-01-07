<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Traemos 8 productos activos para mostrar
        $products = Product::where('is_active', true)->take(10)->get();

        return view('home', compact('products'));
    }
}
