<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->is_admin()->create([
            'first_name' => 'Lisandru',
            'last_name' => 'Secondi',
            'email' => 'lisandrusecondi@gmail.com',
            'password' => 'test'
        ]);
        User::factory()->create([
            'first_name' => 'Lisandru2',
            'last_name' => 'Secondi2',
            'email' => 'lisandrusecondi@gmail.cm',
            'password' => 'test'
        ]);
        Category::factory()->create([
            'name' => 'Livre'
        ]);
        Category::factory()->create([
            'name' => 'Nourriture'
        ]);
        Category::factory()->sub_1()->create([
            'name' => 'Roman'
        ]);
        Category::factory()->sub_1()->create([
            'name' => 'Récit'
        ]);
        Category::factory()->sub_3()->create([
            'name' => 'Policier'
        ]);
        Product::factory(20)->create();
    }
}
