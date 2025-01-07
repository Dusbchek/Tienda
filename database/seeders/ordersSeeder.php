<?php

namespace Database\Seeders;

use App\Models\orders;
use App\Models\products;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ordersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        orders::factory(15)
        ->create()
        ->each(function ($order) {
            // Decide cuántos productos agregar a la orden
            $numProducts = rand(1, 3);

            for ($i = 0; $i < $numProducts; $i++) {
                // Obtiene un producto
                $product = products::inRandomOrder()->first();

                // Obtiene un color y una talla aleatorios del producto
                $randomColor = $product->colors->random()->color;
                $randomSize = $product->sizes->random()->size;

                // Agrega el producto a la orden con una cantidad aleatoria de piezas
                $randQuantity = rand(1, 2);

                $categories = $product->categories->pluck('category')->toArray();

                $order->orderProducts()->create([
                    'product_name' => $product->name,
                    'size' => $randomSize,
                    'color' => $randomColor,
                    'categories' => json_encode($categories),
                    'quantity' => $randQuantity,
                    'unit_price' => $product->price * $randQuantity,
                    'created_at' => $order->created_at,
                    'updated_at' => $order->updated_at,
                ]);

            }
        });
    }
}
