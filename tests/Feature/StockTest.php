<?php
namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class StockTest extends TestCase
{
    use RefreshDatabase;


    public function test_stock_reduces_when_order_is_created()
    {
        // 1. PREPARAR (Arrange)
        $user = User::factory()->create(['role' => UserRole::CUSTOMER]);
        Sanctum::actingAs($user);

        $product = Product::factory()->create([
            'stock_quantity' => 10,
            'price' => 100,
            'is_active' => true
        ]);

        // 2. ACTUAR (Act)
        $response = $this->postJson('/api/orders', [
            'customer_name' => 'Test',
            'customer_email' => 'test@test.com',
            'customer_phone' => '123',
            'address' => 'Calle Falsa',
            'city' => 'Habana',
            'zip_code' => '12345',
            'payment_method' => 'cash',
            'items' => [
                [
                    'id' => $product->id,
                    'quantity' => 2,
                    'price' => 100
                ]
            ]
        ]);

        // 3. AFIRMAR (Assert)
        $response->assertStatus(201);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock_quantity' => 8
        ]);
    }


    public function test_order_fails_when_requesting_more_than_available_stock()
    {
        $user = User::factory()->create(['role' => UserRole::CUSTOMER]);
        Sanctum::actingAs($user);

        $product = Product::factory()->create(['stock_quantity' => 5, 'price' => 100]);

        $response = $this->postJson('/api/orders', [
            'customer_name' => 'Test',
            'customer_email' => 't@t.com',
            'customer_phone' => '1',
            'address' => 'a',
            'city' => 'c',
            'zip_code' => 'z',
            'payment_method' => 'cash',
            'items' => [
                ['id' => $product->id, 'quantity' => 10, 'price' => 100]
            ]
        ]);

        $response->assertStatus(422);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock_quantity' => 5
        ]);
    }
}
