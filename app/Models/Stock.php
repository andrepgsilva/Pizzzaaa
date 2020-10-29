<?php

namespace App\Models;

use App\Models\ProductVariation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Stock extends Model
{
    use HasFactory;

    public function productVariation()
    {
        return $this->belongsTo(ProductVariation::class);
    }
}
