<?php

namespace Database\Factories;

use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BlogFactory extends Factory
{
    protected $model = Blog::class;

    public function definition(): array
    {
        $title = fake()->unique()->sentence(6);
        return [
            'category_id' => BlogCategory::inRandomOrder()->first()?->id ?? 1,
            'user_id' => User::where('role', 'admin')->first()?->id ?? 1,
            'title' => $title,
            'slug' => Str::slug($title) . '-' . Str::random(5),
            'excerpt' => fake()->paragraph(2),
            'content' => fake()->paragraphs(5, true),
            'image' => 'https://images.unsplash.com/photo-1490481651871-ab68de25d43d?auto=format&fit=crop&w=800&q=80',
            'views' => fake()->numberBetween(100, 2500),
            'status' => 'published',
            'meta_title' => $title . ' - NOVAWEAR Journal',
            'meta_description' => fake()->sentence(12),
        ];
    }
}
