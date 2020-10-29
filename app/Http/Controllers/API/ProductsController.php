<?php

namespace App\Http\Controllers\API;

use Andrepgsilva\LaraArrayPagination\Classes\ArrayPaginator;
use App\Models\ProductVariation;
use App\Http\Controllers\Controller;

class ProductsController extends Controller
{
    public function index(ProductVariation $productVariation)
    {
        $paginator = new ArrayPaginator();
        $products = $productVariation->productsWithVariations();
        
        return $paginator->paginate($products, 15);
    }
}