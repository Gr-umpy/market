<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->text(15),
            'description' => fake()->text(100),
            'user_id' => 1
        ];
    }

    /**
     * Attacher des catégories aléatoires après création.
     */
    public function configure(): static
    {
        return $this->afterCreating(function ($product) {
            $categories = Category::inRandomOrder()->take(rand(1, 3))->get();

            $allCategoryIds = collect();
            foreach ($categories as $category) {
                $allCategoryIds->push($category->id);
                $parent = $category->category;
                while ($parent) {
                    $allCategoryIds->push($parent->id);
                    $parent = $parent->category;
                }
            }

            $product->categories()->sync($allCategoryIds->unique());
        });
    }
}
