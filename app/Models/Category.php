<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Category extends Model{
    
    use HasFactory, HasSlug;
    protected $fillable = [
        'name_en', 
        'name_ar', 
        'slug', 
        'parent_id', 
        'description',
        'icon',
        'image',
    ];

    public function children(): HasMany{
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function products(): HasMany{
        return $this->hasMany(Product::class);
    }

    public function getSlugOptions() : SlugOptions{
        return SlugOptions::create()
            ->generateSlugsFrom('name_en')
            ->saveSlugsTo('slug');
    }
}