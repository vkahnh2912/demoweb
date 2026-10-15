<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);
        return [
            'name' => ucfirst($name),
            'slug' => Str::slug($name) . '-' . Str::random(5),
            'image' => 'https://images.unsplash.com/photo-1445205170230-053b83016050?auto=format&fit=crop&w=600&q=80',
            'description' => fake()->sentence(10),
            'is_featured' => fake()->boolean(60),
            'status' => 'active',
            'sort_order' => fake()->numberBetween(1, 10),
            'meta_title' => ucfirst($name) . ' - NOVAWEAR',
            'meta_description' => fake()->sentence(15),
        ];
    }
}
