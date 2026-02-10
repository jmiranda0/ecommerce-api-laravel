<?php

namespace App\Http\Controllers\Api;

use App\Enums\PaymentMethod;
use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Order\CreateOrderRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    public function store(CreateOrderRequest $request)
    {
        // 1. Validar datos de entrada
        $validated = $request->validated();

        // 2. Verificar autenticación
        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'Usuario no autenticado'], 401);
        }

        // 3. Procesar orden dentro de transacción
        $order = DB::transaction(function () use ($validated, $user) {

            $total = 0;
            $orderItems = [];

            // Pre-validar stock y calcular total
            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['id']);

                // Validar que esté activo
                if (!$product->is_active) {
                    throw ValidationException::withMessages([
                        'items' => ["El producto '{$product->name}' no está disponible"]
                    ]);
                }

                // Validar stock disponible
                if ($product->stock_quantity < $item['quantity']) {
                    throw ValidationException::withMessages([
                        'items' => [
                            "Stock insuficiente para '{$product->name}'. " .
                            "Disponible: {$product->stock_quantity}, solicitado: {$item['quantity']}"
                        ]
                    ]);
                }

                // Usar precio REAL de la base de datos (SEGURIDAD)
                $unitPrice = $product->price;
                $subtotal = $unitPrice * $item['quantity'];
                $total += $subtotal;

                $orderItems[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'unit_price' => $unitPrice,
                    'total_price' => $subtotal,
                ];
            }

            // Crear la orden
            $order = Order::create([
                'user_id' => $user->id,
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'],
                'customer_phone' => $validated['customer_phone'],
                'address' => $validated['address'],
                'city' => $validated['city'],
                'zip_code' => $validated['zip_code'],
                'total_amount' => $total,
                'payment_method' => $validated['payment_method'],
                'status' => OrderStatus::NEW,
            ]);

            // Crear items y decrementar stock
            foreach ($orderItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product']->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['total_price'],
                ]);

                $item['product']->decrement('stock_quantity', $item['quantity']);
            }

            return $order;
        });

        return response()->json([
            'success' => true,
            'order_id' => $order->id,
            'total_amount' => $order->total_amount
        ], 201);
    }

    public function index(Request $request)
    {
        $orders = $request->user()
            ->orders()
            ->with('items.product')
            ->latest()
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    }
}
