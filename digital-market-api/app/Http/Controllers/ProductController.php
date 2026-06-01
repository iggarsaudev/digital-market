<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

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

    public function store(Request $request)
    {
        // 1. Validamos los datos (Añadimos la validación de la imagen)
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // Máximo 2MB
        ]);

        // 2. Procesamos la imagen si el usuario ha subido una
        $imageUrl = 'https://via.placeholder.com/600x400?text=Sin+Imagen'; // Por defecto

        if ($request->hasFile('image')) {
            // Guarda la imagen en storage/app/public/products
            $path = $request->file('image')->store('products', 'public');
            // Genera la URL pública para el frontend
            $imageUrl = url(Storage::url($path));
        }

        // 3. Creamos el producto
        $product = Product::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']) . '-' . substr(uniqid(), -5),
            'description' => $validated['description'],
            'price' => $validated['price'],
            'is_published' => true,
            'image_url' => $imageUrl, // <--- Guardamos la URL real
        ]);

        return response()->json(new ProductResource($product), 201);
    }
}
