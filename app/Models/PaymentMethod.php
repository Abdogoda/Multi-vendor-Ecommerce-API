<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model{
    use HasFactory;

    protected $fillable = ['name', 'description', 'status', "icon"];

    public function vendors(){
        return $this->belongsToMany(Vendor::class, 'vendor_payment_methods')
                    ->withPivot('integration_id', 'identifier')
                    ->withTimestamps();
    }

    protected $hidden = ['created_at', 'updated_at'];
}