<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $table = 'categories';

    protected $fillable = [
        'name',
        'description',
    ];

    // Cette fonction montre que une Category possede plusieurs produits
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'category_id', 'id');
    }
}
