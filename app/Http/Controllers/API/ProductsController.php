<?php

namespace App\Http\Controllers\API;

use App\Models\Product;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;

class ProductsController extends Controller
{
    public function index()
    {
        $productsResource = ProductResource::collection(Product::highestPrices());
        
        return $productsResource;
        // TODO Size
        // TODO Return ingredients and show them
    }
}