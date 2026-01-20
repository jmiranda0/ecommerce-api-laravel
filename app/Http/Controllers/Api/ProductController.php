<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {

        $query = Product::where('is_active', true)
            ->with('category');

        $query->when($request->search, function ($q, $search) {
            return $q->where(function ($subQ) use ($search) {
            $subQ->where('name', 'like', "%{$search}%")->orWhere('description', 'like', "%{$search}%");
            });
        });

        $query->when($request->category, function ($q, $category) {
            return $q->whereHas('category', function ($subQ) use ($category) {
            $subQ->where('slug', $category);
            });
        });

        $query->when($request->sort === 'price_asc', fn($q) => $q->orderBy('price', 'asc'));
        $query->when($request->sort === 'price_desc', fn($q) => $q->orderBy('price', 'desc'));

        $products = $query->get();

        return ProductResource::collection($products);
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return new ProductResource($product);
    }
}
