<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;
    protected $table   = 'products';
    protected $guarded =[];
    protected $casts = ['gallery_image' => 'object'];

    public function carts() :HasMany{
        return $this->hasMany(Cart::class,'product_id','id');
    }
}
