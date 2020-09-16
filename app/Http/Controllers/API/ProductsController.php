<?php

namespace App\Http\Controllers\API;

use App\Models\Product;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;

class ProductsController extends Controller
{
    public function index()
    {
        $productsResource = ProductResource::collection(Product::all());
        
        return response()->json($productsResource);

        // TODO Pagination
        // TODO Scope filter
        // TODO Size and Ingredients separation
    }
}