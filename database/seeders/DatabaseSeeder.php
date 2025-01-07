<?php

namespace Database\Seeders;

use App\Models\categories;
use App\Models\colors;
use App\Models\images;
use App\Models\measures;
use App\Models\products;
use App\Models\shipments;
use App\Models\sizes;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Alberto Sosa',
            'email' => 'petox.somart@outlook.com',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'remember_token' => null,
            'profile_photo_path' => null,
            'current_team_id' => null,
            'updated_at' => '2024-04-22 01:45:37',
            'created_at' => '2024-04-22 01:45:37',
        ]);
        User::factory()->create([
            'name' => 'Alexis Dubschek',
            'email' => 'dinosaurdubs@gmail.com',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'remember_token' => null,
            'profile_photo_path' => null,
            'current_team_id' => null,
            'updated_at' => '2024-04-22 02:07:29',
            'created_at' => '2024-04-22 02:07:29',
        ]);

        colors::factory()->create([
            'color' => 'Blanco',
            'hexadecimal' => '#f2f2f2'
        ]);
        colors::factory()->create([
            'color' => 'Negro',
            'hexadecimal' => '#0f0f0f'
        ]);
        colors::factory()->create([
            'color' => 'Azul',
            'hexadecimal' => '#3117bd'
        ]);

        sizes::factory()->create([
            'size' => 'S'
        ]);
        sizes::factory()->create([
            'size' => 'M'
        ]);
        sizes::factory()->create([
            'size' => 'L'
        ]);

        categories::factory()->create([
            'category' => 'Blusa'
        ]);
        categories::factory()->create([
            'category' => 'Camisa'
        ]);
        categories::factory()->create([
            'category' => 'Saco'
        ]);
        categories::factory()->create([
            'category' => 'Mujer'
        ]);
        categories::factory()->create([
            'category' => 'Niña'
        ]);

        products::factory()->create([
            'name' => 'Blusa para mujer',
            'slug' => 'blusa-para-mujer',
            'price' => 1200,
            'description' => 'Blusa de mangas para mujeres',
            'is_visible' => 1,
        ]);

        DB::table('products_sizes')->insert([
            'products_id' => 1,
            'sizes_id' => 1
        ]);
        DB::table('products_sizes')->insert([
            'products_id' => 1,
            'sizes_id' => 2
        ]);
        DB::table('products_sizes')->insert([
            'products_id' => 1,
            'sizes_id' => 3
        ]);

        DB::table('product_categories')->insert([
            'products_id' => 1,
            'categories_id' => 1
        ]);

        DB::table('product_colors')->insert([
            'products_id' => 1,
            'colors_id' => 1
        ]);

        measures::factory()->create([
            'products_id' => 1,
            'sizes_id' => 1,
            'part' => 'Mangas',
            'measure' => 100
        ]);

        images::factory()->create([
            'products_id' => 1,
            'image' => '01HW1STQ7Y935QDB91MMSYFKC3.jpg',
            'colors_id' => 1
        ]);

        shipments::factory()->create([
            'type' => 'Local',
            'description' => 'Envíos dentro de la ciudad de Chetumal',
            'price' => 40,
            'is_visible' => 1
        ]);
        shipments::factory()->create([
            'type' => 'Nacional',
            'description' => 'Envíos dentro de la república Mexicana',
            'price' => 160,
            'is_visible' => 1
        ]);
    }
}
