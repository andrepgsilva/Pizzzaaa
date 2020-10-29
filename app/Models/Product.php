<?php

namespace App\Models;

use App\Models\Size;
use App\Models\Ingredient;
use App\Finance\MoneyHandler;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected static function booted()
    {
        static::retrieved(function ($product) {
            // $moneyHandler = new MoneyHandler($product->price);

            // $product->price = $moneyHandler->format(); 
        });
    }

    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class)->withTimestamps();
    }

    public function sizes()
    {
        return $this->belongsToMany(Size::class, 'product_variation')->withTimestamps();
    }
}
