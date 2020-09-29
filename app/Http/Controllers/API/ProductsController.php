<?php

namespace App\Http\Controllers\API;

use App\Helpers\Paginator;
use App\Models\ProductVariation;
use App\Http\Controllers\Controller;

class ProductsController extends Controller
{
    public function index(ProductVariation $productVariation)
    {
        $products = $productVariation->productsWithVariations();
        
        return Paginator::paginate($products, 15);
    }
}