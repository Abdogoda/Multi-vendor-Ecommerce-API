<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Vendor extends Model
{
    use HasFactory, HasSlug;

    protected $fillable = [
        'user_id', 
        'shop_name_en', 
        'shop_name_ar',
        'shop_slug',
        'shop_address',
        'shop_phone',
        'shop_email',
        'shop_website',
        'shop_logo',
        'description',
    ];


    public function user(): BelongsTo{
        return $this->belongsTo(User::class);
    }

    public function products(): HasMany{
        return $this->hasMany(Product::class);
    }

    public function paymentMethods(){
        return $this->belongsToMany(PaymentMethod::class, 'vendor_payment_methods')
                    ->withPivot('integration_id', 'identifier')
                    ->withTimestamps();
    }

    public function getSlugOptions() : SlugOptions{
        return SlugOptions::create()
            ->generateSlugsFrom('shop_name_en')
            ->saveSlugsTo('shop_slug');
    }
}