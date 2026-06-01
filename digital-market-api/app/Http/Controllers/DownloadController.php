<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Support\Facades\Storage;

class DownloadController extends Controller
{
    public function download(Request $request, $productId)
    {
        $user = $request->user();
        $product = Product::findOrFail($productId);

        // 1. EL PORTERO: ¿El usuario tiene alguna orden pagada que incluya este producto?
        $hasPurchased = Order::where('user_id', $user->id)
            ->where('status', 'paid')
            ->whereHas('items', function ($query) use ($productId) {
                $query->where('product_id', $productId);
            })
            ->exists();

        // Si no lo ha comprado, le denegamos el acceso (Error 403 Prohibido)
        if (!$hasPurchased) {
            return response()->json(['error' => 'No tienes permiso para descargar este archivo. Compra el producto primero.'], 403);
        }

        // 2. EL ARCHIVO: Obtenemos la ruta del archivo.
        // Si tu base de datos no tiene una ruta, inventamos una por defecto.
        $filePath = $product->file_path ?? 'productos/dummy_' . $product->id . '.txt';

        // Si el archivo físico no existe en tu carpeta storage, Laravel creará uno falso 
        // automáticamente con un mensaje de texto, para que podamos probar la descarga.
        if (!Storage::exists($filePath)) {
            Storage::put($filePath, '¡Enhorabuena! Has descargado tu producto premium: ' . $product->name);
        }

        // 3. LA DESCARGA: Forzamos la descarga del archivo de forma segura.
        // Cambiamos el nombre para que baje con el nombre del producto real y extensión .txt
        $downloadName = str_replace(' ', '_', strtolower($product->name)) . '.txt';

        return Storage::download($filePath, $downloadName);
    }
}
