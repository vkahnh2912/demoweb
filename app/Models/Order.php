<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'coupon_id',
        'customer_name',
        'customer_phone',
        'customer_email',
        'shipping_address',
        'province',
        'district',
        'ward',
        'note',
        'subtotal',
        'discount_amount',
        'shipping_fee',
        'total',
        'payment_method',
        'payment_status',
        'status',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'shipping_fee' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Chờ xử lý',
            'confirmed' => 'Đã xác nhận',
            'processing' => 'Đang đóng gói',
            'shipping' => 'Đang giao hàng',
            'completed' => 'Đã giao thành công',
            'cancelled' => 'Đã hủy',
            default => ucfirst($this->status),
        };
    }

    public function getPaymentMethodLabelAttribute(): string
    {
        return match ($this->payment_method) {
            'cod' => 'Thanh toán khi nhận hàng (COD)',
            'bank_transfer' => 'Chuyển khoản ngân hàng',
            default => strtoupper($this->payment_method),
        };
    }

    public function getPaymentStatusLabelAttribute(): string
    {
        return match ($this->payment_status) {
            'pending' => 'Chưa thanh toán',
            'paid' => 'Đã thanh toán',
            'failed' => 'Thất bại',
            default => ucfirst($this->payment_status),
        };
    }
}
