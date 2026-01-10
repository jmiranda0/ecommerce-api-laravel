<?php

namespace App\Http\Controllers\Api;

use App\Enums\PaymentMethod;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
class OrderController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validar
        $validated = $request->validate([
            // ... (resto de validaciones de dirección igual) ...
            'customer_name' => 'required|string',
            'customer_email' => 'required|email',
            'customer_phone' => 'required',
            'address' => 'required',
            'city' => 'required',
            'zip_code' => 'required',

            // Validar que el método de pago esté en nuestro Enum
            'payment_method' => ['required', 'string', Rule::in(PaymentMethod::getValues())],

            'items' => 'required|array|min:1',
            // ... (resto de items) ...
        ]);

        // OJO: Como exigimos login, necesitamos el ID del usuario.
        // Por ahora, como no hemos hecho el Login en Next.js,
        // si intentas probar esto fallará porque $request->user() será null.
        // Pero lo dejamos programado correctamente:

        $user = $request->user(); // Obtener usuario logueado via Sanctum token

        if (!$user) {
            return response()->json(['message' => 'Usuario no autenticado'], 401);
        }

        try {
            $order = DB::transaction(function () use ($validated, $user) {

                // ... (cálculo de total igual) ...
                $total = 0;
                foreach ($validated['items'] as $item) {
                     // Aquí deberías buscar el precio real en BD, pero seguimos confiando por ahora
                    $total += $item['price'] * $item['quantity'];
                }

                $order = Order::create([
                    'user_id' => $user->id, // <--- AHORA ASIGNAMOS EL ID DEL USUARIO REAL
                    'customer_name' => $validated['customer_name'],
                    'customer_email' => $validated['customer_email'],
                    'customer_phone' => $validated['customer_phone'],
                    'address' => $validated['address'],
                    'city' => $validated['city'],
                    'zip_code' => $validated['zip_code'],
                    'total_amount' => $total,
                    'payment_method' => $validated['payment_method'], // Laravel lo castea solo al Enum
                    'status' => \App\Enums\OrderStatus::NEW, // Usamos el Enum directamente
                ]);

                // ... (creación de items igual) ...
                foreach ($validated['items'] as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item['id'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['price'],
                        'total_price' => $item['price'] * $item['quantity'],
                    ]);
                }

                return $order;
            });

            return response()->json(['success' => true, 'order_id' => $order->id], 201);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
