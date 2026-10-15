<?php

namespace Database\Factories;

use App\Models\Brand;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Brand>
 */
class BrandFactory extends Factory
{
    protected $model = Brand::class;

    public function definition(): array
    {
        $name = fake()->unique()->company();
        return [
            'name' => $name,
            'slug' => Str::slug($name) . '-' . Str::random(5),
            'logo' => null,
            'description' => fake()->paragraph(2),
            'is_featured' => fake()->boolean(70),
            'status' => 'active',
        ];
    }
}
