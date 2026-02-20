<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CheckoutService;
use App\Models\Order;
use Stripe\Stripe;
use Stripe\Checkout\Session;

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

    public function verifySession(Request $request)
    {
        // Validamos que Vue nos envíe el ID de la sesión
        $request->validate(['session_id' => 'required|string']);

        // Configuramos Stripe con nuestra clave secreta
        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            // Le pedimos a Stripe la información real de esta sesión
            $session = Session::retrieve($request->session_id);

            // Si Stripe confirma que el pago se ha completado...
            if ($session->payment_status === 'paid') {

                // Buscamos la orden en nuestra base de datos
                $order = Order::where('stripe_session_id', $session->id)->first();

                // Si existe y estaba pendiente, la marcamos como pagada
                if ($order && $order->status === 'pending') {
                    $order->update(['status' => 'paid']);
                }

                return response()->json(['message' => 'pago verificado', 'status' => 'paid']);
            }

            return response()->json(['message' => 'pago no completado', 'status' => $session->payment_status]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
