<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);
        $price = fake()->randomElement([250000, 320000, 450000, 590000, 750000, 950000, 1200000, 1500000]);
        $hasSale = fake()->boolean(40);
        $salePrice = $hasSale ? (float) ($price * fake()->randomElement([0.7, 0.8, 0.85, 0.9])) : null;

        return [
            'category_id' => Category::inRandomOrder()->first()?->id ?? Category::factory(),
            'brand_id' => Brand::inRandomOrder()->first()?->id ?? Brand::factory(),
            'name' => ucfirst($name),
            'slug' => Str::slug($name) . '-' . Str::random(6),
            'sku' => 'NVW-' . strtoupper(Str::random(6)),
            'description' => fake()->paragraphs(3, true),
            'short_description' => fake()->sentence(12),
            'price' => $price,
            'sale_price' => $salePrice,
            'stock' => fake()->numberBetween(10, 150),
            'status' => 'active',
            'featured' => fake()->boolean(25),
            'bestseller' => fake()->boolean(20),
            'new_arrival' => fake()->boolean(30),
            'meta_title' => ucfirst($name) . ' | NOVAWEAR',
            'meta_description' => fake()->sentence(16),
        ];
    }
}
