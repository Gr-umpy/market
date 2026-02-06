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
        Category::factory(2)->create();
        Category::factory(2)->sub_1()->create();
        Product::factory(20)->create();
    }
}
