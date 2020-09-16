<?php

namespace App\Models;

use App\Finance\MoneyHandler;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected static function booted()
    {
        static::retrieved(function ($product) {
            $moneyHandler = new MoneyHandler($product->price);

            $product->price = $moneyHandler->format(); 
        });
    }
}
