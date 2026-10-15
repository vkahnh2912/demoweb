<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        $subtotal = fake()->randomElement([350000, 680000, 1150000, 1890000, 2400000]);
        $shipping = 30000;
        $discount = fake()->randomElement([0, 50000, 100000]);
        $total = $subtotal + $shipping - $discount;

        return [
            'order_number' => 'ORD-' . strtoupper(Str::random(8)),
            'user_id' => User::where('role', 'customer')->inRandomOrder()->first()?->id ?? User::factory(),
            'coupon_id' => null,
            'customer_name' => fake()->name(),
            'customer_phone' => fake()->phoneNumber(),
            'customer_email' => fake()->safeEmail(),
            'shipping_address' => fake()->streetAddress(),
            'province' => 'TP. Hồ Chí Minh',
            'district' => 'Quận 1',
            'ward' => 'Phường Bến Nghé',
            'note' => fake()->boolean(40) ? fake()->sentence(8) : null,
            'subtotal' => $subtotal,
            'discount_amount' => $discount,
            'shipping_fee' => $shipping,
            'total' => $total,
            'payment_method' => fake()->randomElement(['cod', 'bank_transfer']),
            'payment_status' => fake()->randomElement(['pending', 'paid']),
            'status' => fake()->randomElement(['pending', 'confirmed', 'processing', 'shipping', 'completed', 'cancelled']),
        ];
    }
}
