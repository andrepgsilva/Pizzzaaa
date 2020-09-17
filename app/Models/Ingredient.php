<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ingredient extends Model
{
    use HasFactory;

    public function Products()
    {
        return $this->belongsToMany(Product::class)->withTimestamps();
    }
}
