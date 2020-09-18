<?php

namespace App\Scopes\Product;

trait Scopes 
{
    /**
     * Scope a query to bring the products with highest prices.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopehighestPrices($query)
    {
        return $query->latest()->orderBy('price', 'desc');
    }

}