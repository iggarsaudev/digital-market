<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class UserOrderController extends Controller
{
    /**
     * Devuelve el historial de compras PAGADAS del usuario autenticado.
     */
    public function index(Request $request)
    {
        // 1. Obtenemos el usuario autenticado (gracias a Sanctum)
        $user = $request->user();

        // 2. Buscamos sus órdenes con una consulta optimizada
        $orders = Order::where('user_id', $user->id)
            ->where('status', 'paid') // SOLO queremos mostrar las que están pagadas
            ->with(['items.product']) // Trae la orden -> sus items -> y el producto de cada item.
            ->latest() // Ordenamos por las más recientes
            ->get();

        // 3. Devolvemos la colección de órdenes en formato JSON
        return response()->json($orders);
    }
}
