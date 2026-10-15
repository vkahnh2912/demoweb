<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition(): array
    {
        $reviews = [
            'Chất lượng vải rất tốt, form dáng chuẩn đẹp y hình. Đóng gói cẩn thận!',
            'Áo mặc rất mát và đứng form, đường may kỹ lưỡng. Sẽ ủng hộ shop tiếp.',
            'Giao hàng nhanh chóng, màu sắc bên ngoài đẹp hơn cả trên ảnh.',
            'Chất liệu cao cấp, mặc rất thoải mái và tôn dáng. 10/10!',
            'Sản phẩm đúng như mô tả, tư vấn nhiệt tình, rất hài lòng!',
        ];

        return [
            'product_id' => Product::inRandomOrder()->first()?->id ?? Product::factory(),
            'user_id' => User::where('role', 'customer')->inRandomOrder()->first()?->id ?? User::factory(),
            'rating' => fake()->randomElement([4, 5, 5, 5, 4]),
            'comment' => fake()->randomElement($reviews),
            'images' => null,
            'status' => 'approved',
        ];
    }
}
