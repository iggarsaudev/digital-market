<?php

namespace App\Services;

use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class CheckoutService
{
    public function processCheckout($user, array $productIds)
    {
        // 1. configuramos la clave secreta de stripe
        Stripe::setApiKey(config('services.stripe.secret'));

        // usamos una transacción de base de datos. Si algo falla a la mitad, 
        // no se guarda nada y evitamos datos corruptos.
        return DB::transaction(function () use ($user, $productIds) {

            // 2. obtenemos los productos REALES de la base de datos
            $products = Product::whereIn('id', $productIds)
                ->where('is_published', true)
                ->get();

            if ($products->isEmpty()) {
                throw new \Exception('no hay productos válidos para comprar.');
            }

            // calculamos el total seguro
            $totalPrice = $products->sum('price');

            // 3. creamos la orden en estado 'pending'
            $order = Order::create([
                'user_id' => $user->id,
                'total_price' => $totalPrice,
                'status' => 'pending',
            ]);

            $lineItems = [];

            // 4. creamos los items de la orden y preparamos el array para stripe
            foreach ($products as $product) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'price' => $product->price, // guardamos el precio histórico
                ]);

                $lineItems[] = [
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => $product->name,
                        ],
                        'unit_amount' => $product->price, // ya está en céntimos
                    ],
                    'quantity' => 1, // al ser digital, la cantidad siempre es 1
                ];
            }

            // 5. creamos la sesión de pago en los servidores de Stripe
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => $lineItems,
                'mode' => 'payment',
                // aquí volverá el usuario tras pagar (las crearemos en Vue después)
                'success_url' => env('FRONTEND_URL') . '/checkout/success?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => env('FRONTEND_URL') . '/cart',
            ]);

            // 6. actualizamos la orden con el ID de la sesión de Stripe
            $order->update(['stripe_session_id' => $session->id]);

            // devolvemos la URL de pago generada por Stripe
            return $session->url;
        });
    }
}
