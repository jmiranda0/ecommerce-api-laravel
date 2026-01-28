<?php

namespace Database\Seeders;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\UserRole;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Crear tu usuario Admin (Si no existe)
        User::firstOrCreate([
            'email' => 'admin@admin.com',
        ], [
            'name' => 'Admin',
            'password' => bcrypt('pass'),
            'role' => UserRole::ADMIN,
        ]);
        // 2. Crear 10 Clientes
        $users = User::factory(10)->create([
            'role' => UserRole::CUSTOMER
        ]);
        // 3. Crear Categorías y Productos
        $categories = Category::factory(5)->create();

        $products = Product::factory(50)
            ->recycle($categories) // Que usen las categorías de arriba
            ->create();
        // 4. GENERAR PEDIDOS HISTÓRICOS (Magia pura)
        // Recorremos cada usuario y le creamos entre 0 y 5 pedidos
        foreach ($users as $user) {

            $numOrders = rand(0, 5);
            for ($i = 0; $i < $numOrders; $i++) {

                // Fecha aleatoria en el último año (Para que los gráficos se vean bien)
                $date = fake()->dateTimeBetween('-1 year', 'now');
                $order = Order::create([
                    'user_id' => $user->id,
                    'customer_name' => $user->name,
                    'customer_email' => $user->email,
                    'customer_phone' => fake()->phoneNumber(),
                    'address' => fake()->address(),
                    'city' => fake()->city(),
                    'zip_code' => fake()->postcode(),
                    'total_amount' => 0, // Lo calculamos abajo
                    'status' => fake()->randomElement(OrderStatus::getValues()),
                    'payment_status' => PaymentStatus::PAID,
                    'payment_method' => 'stripe',
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
                // Agregar Items al pedido
                $total = 0;
                $numItems = rand(1, 4);
                for ($j = 0; $j < $numItems; $j++) {
                    $product = $products->random();
                    $qty = rand(1, 3);
                    $price = $product->price;
                    OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'unit_price' => $price,
                    'total_price' => $qty * $price,
                    'created_at' => $date, // Importante para reportes
                    'updated_at' => $date,
                    ]);
                    $total += ($qty * $price);
                }
                // Actualizar total orden
                $order->update(['total_amount' => $total]);
            }
        }
    }
}
