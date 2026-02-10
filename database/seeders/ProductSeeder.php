<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener todas las categorías existentes
        $categories = Category::all();

        // Crear 50 productos reutilizando las categorías
        Product::factory(50)
            ->recycle($categories)
            ->create();
    }
}
