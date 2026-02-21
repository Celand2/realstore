<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Cart extends Model
{
    protected $table = 'carts';
    protected $fillable = [
        'user_id'
    ];

    // Cette foncton montre que un cart appartient a un seul user
    public function user():BelongsTo{
        return $this->belongsTo(User::class,'user_id','id');
    }

    // Cette fonction montre que un cart peut appartenir a plusieurs produits
    public function products():BelongsToMany{
        return $this->belongsToMany(Product::class,'cart_products','product_id','cart_id')
                    ->withPivot('quantity');
    }
}
