<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // listamos todos los productos
    public function index()
    {
        // obtenemos solo los publicados, ordenados por los más recientes
        $products = Product::where('is_published', true)
            ->latest()
            ->get();

        // devolvemos la colección transformada
        return ProductResource::collection($products);
    }

    // mostraremos un producto individual por su slug (para la página de detalle)
    public function show(string $slug)
    {
        $product = Product::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        return new ProductResource($product);
    }
}
