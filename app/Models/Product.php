<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Product extends Model{
    use HasFactory, HasSlug;
    protected $fillable = [
        'category_id', 
        'vendor_id', 
        'name_en', 
        'name_ar', 
        'slug', 
        'description', 
        'price', 
        'discount_price', 
        'stock', 
        'status',
        'main_image'
    ];

    public function vendor(): BelongsTo{
        return $this->belongsTo(Vendor::class);
    }

    public function category(): BelongsTo{
        return $this->belongsTo(Category::class);
    }

    public function tags(): BelongsToMany{
        return $this->belongsToMany(Tag::class);
    }

    public function reviews(): HasMany{
        return $this->hasMany(Review::class);
    }

    public function images(): HasMany{
        return $this->hasMany(ProductImage::class);
    }

    public function orders(): BelongsToMany{
        return $this->belongsToMany(Order::class)->withPivot('quantity', 'price');
    }

    public function getSlugOptions() : SlugOptions{
        return SlugOptions::create()
            ->generateSlugsFrom('name_en')
            ->saveSlugsTo('slug');
    }
}