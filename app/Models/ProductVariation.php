<?php

namespace App\Models;

use App\Models\Stock;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class ProductVariation extends Model
{
    protected $table = 'product_variation';

    public function stock()
    {
        return $this->hasOne(Stock::class);
    }

    /**
     * Get all products with the variations too
     *
     * @return Illuminate\Database\Query\Builder
     **/
    protected function productsWithVariationsRaw()
    {
        return DB::table('product_variation')
            ->join('products', 'product_variation.product_id', '=', 'products.id')
            ->join('sizes', 'product_variation.size_id', '=', 'sizes.id')
            ->join('stocks', 'product_variation.id', '=', 'stocks.product_variation_id')
            ->join('ingredient_product', 'product_variation.product_id', '=', 'ingredient_product.product_id')
            ->join('ingredients', 'ingredients.id', '=', 'ingredient_product.ingredient_id')
            ->where('stocks.quantity', '>', 0)
            ->select(
                'product_variation.id',
                'product_variation.product_id',
                'product_variation.price',
                'products.name',
                'products.description',
                'products.image_url',
                'sizes.name as size_name',
                'sizes.id as size_id',
                'stocks.quantity',
                DB::raw("group_concat(ingredients.name separator ', ') as ingredients"),
            )
            ->groupBy('product_variation.id', 'products.name', 'stocks.quantity')
            ->orderBy('product_variation.price', 'desc');
    }

    /**
     * Products with variations already formated for easily data handle
     *
     * @return Array
     **/
    public function productsWithVariations()
    {
        $productsWithVariationsJoined = [];

        foreach ($this->productsWithVariationsRaw()->get() as $productVariation) {
            if (!in_array($productVariation->product_id, array_keys($productsWithVariationsJoined))) {
                $productsWithVariationsJoined[$productVariation->product_id] = [
                    'id' => $productVariation->product_id, 
                    'name' => $productVariation->name,
                    'description' => $productVariation->description, 
                    'image_url' => $productVariation->image_url,
                    'ingredients' => $productVariation->ingredients,
                ];
            }

            $productsWithVariationsJoined[$productVariation->product_id]['logistics'][] = [
                'id' => $productVariation->id, 
                'size_id' => $productVariation->size_id,
                'size_name' => $productVariation->size_name, 
                'price' => $productVariation->price,
                'quantity' => $productVariation->quantity,
            ];
        }

        return array_values($productsWithVariationsJoined);
    }
}
