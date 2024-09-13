<?php

namespace App\Rules;

use App\Models\Product;
use Closure;
use Illuminate\Contracts\Validation\Rule;

class StockAvailable implements Rule{
    protected $quantity;

    public function __construct($quantity){
        $this->quantity = $quantity;
    }

    public function passes($attribute, $value){
        $product = Product::find($value);
        
        return $product && $this->quantity <= $product->stock;
    }

    public function message(){
        return __('messages.product_out_of_stock');
    }
}