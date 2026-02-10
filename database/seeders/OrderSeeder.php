<?php

namespace Database\Seeders;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\UserRole;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Usar transacción para mejor performance
        DB::transaction(function () {
            // Obtener todos los clientes y productos
            $customers = User::where('role', UserRole::CUSTOMER)->get();
            $products = Product::all();

            // Generar pedidos históricos para cada cliente
            foreach ($customers as $customer) {
                // Cada cliente tendrá entre 0 y 5 pedidos
                $numOrders = rand(0, 5);

                for ($i = 0; $i < $numOrders; $i++) {
                    // Fecha aleatoria en el último año (para gráficos realistas)
                    $date = fake()->dateTimeBetween('-1 year', 'now');

                    // Crear la orden
                    $order = Order::create([
                        'user_id' => $customer->id,
                        'customer_name' => $customer->name,
                        'customer_email' => $customer->email,
                        'customer_phone' => fake()->phoneNumber(),
                        'address' => fake()->address(),
                        'city' => fake()->city(),
                        'zip_code' => fake()->postcode(),
                        'total_amount' => 0, // Se calcula después
                        'status' => fake()->randomElement(OrderStatus::getValues()),
                        'payment_status' => PaymentStatus::PAID,
                        'payment_method' => 'stripe',
                        'created_at' => $date,
                        'updated_at' => $date,
                    ]);

                    // Agregar items al pedido (entre 1 y 4 productos)
                    $total = 0;
                    $numItems = rand(1, 4);

                    for ($j = 0; $j < $numItems; $j++) {
                        $product = $products->random();
                        $quantity = rand(1, 3);
                        $price = $product->price;

                        OrderItem::create([
                            'order_id' => $order->id,
                            'product_id' => $product->id,
                            'quantity' => $quantity,
                            'unit_price' => $price,
                            'total_price' => $quantity * $price,
                            'created_at' => $date,
                            'updated_at' => $date,
                        ]);

                        $total += ($quantity * $price);
                    }

                    // Actualizar el total de la orden
                    $order->update(['total_amount' => $total]);
                }
            }
        });
    }
}
