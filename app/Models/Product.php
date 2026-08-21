<?php

namespace App\Models;

use App\Models\Cart;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    protected $table = 'products';
    protected $fillable = [
        'category_id',
        'title',
        'price',
        'image',
        'description',
        'actif',
        'stock',
    ];

    // Cette fonction montre que un product appartient a un seul users
    public function category():BelongsTo{
        return $this->belongsTo(Category::class,'category_id','id');
    }

    // Cette fonction montre que un product peut appartenir a plusieurs carts
     public function carts():BelongsToMany{
        return $this->belongsToMany(Cart::class,'cart_products','product_id','cart_id')
                    ->withPivot('quantity');
    }
    
}
