<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CheckoutService;

class CheckoutController extends Controller
{
    // inyectamos nuestro servicio en el constructor
    protected $checkoutService;

    public function __construct(CheckoutService $checkoutService)
    {
        $this->checkoutService = $checkoutService;
    }

    public function createSession(Request $request)
    {
        // validamos que el frontend nos envíe un array de IDs obligatoriamente
        $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id',
        ]);

        try {
            // llamamos a la magia de nuestro servicio
            $checkoutUrl = $this->checkoutService->processCheckout(
                $request->user(),
                $request->product_ids
            );

            // devolvemos la URL de Stripe al frontend
            return response()->json(['url' => $checkoutUrl]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
