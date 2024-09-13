<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'order_date', 
        'total_amount', 
        'status',
        'payment_method_id',
        'payment_status',
        'shipping_address',
    ];

    public function user(): BelongsTo{
        return $this->belongsTo(User::class, 'user_id');
    }

    public function payment_method(): BelongsTo{
        return $this->belongsTo(PaymentMethod::class);
    }

    public function items(): BelongsToMany{
        return $this->belongsToMany(Product::class)->withPivot('quantity', 'price');
    }
}