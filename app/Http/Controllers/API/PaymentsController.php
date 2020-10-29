<?php

namespace App\Http\Controllers\API;

use App\Models\Stock;
use App\Mail\OrderConfirmed;
use Illuminate\Http\Request;
use App\Models\ProductVariation;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class PaymentsController extends Controller
{
    public function checkPurchase(Request $request)
    {
        $informationForPurchase = $request->all(['items', 'totalPrice', 'itemsIds']);
        $productsFromClient = $informationForPurchase['items'];
        
        $itemsIds = $informationForPurchase['itemsIds'];

        $originalProducts = ProductVariation::whereIn('id', $itemsIds)->get()->keyBy('id');
        // return $originalProducts['8'];
        $rightTotal = 0;

        foreach ($productsFromClient as $clientProduct) {
            $clientProductKey = array_keys($clientProduct)[0];
            $clientProduct = array_values($clientProduct)[0];
            
            $originalProduct = $originalProducts[$clientProductKey];

            $rightPrice = $originalProduct['price'] * $clientProduct['quantityChoosed'];
            
            if ($rightPrice != $clientProduct['totalPrice']) {
                return response()->json('Something went wrong. Try clear the cart.', 400);
            } 

            $rightTotal += $rightPrice;
        }

        return $rightTotal;
    }

    public function chargeClient(Request $request)
    {
        try {
            auth()->user()->charge($request->totalPrice, $request->paymentMethod);
            
            \DB::transaction(function () {
                
                $productsFromClient = request('productsVariation');
    
                $stock = Stock::whereIn('product_variation_id', request('itemsIds'))->get()->keyBy('id');
    
                foreach ($productsFromClient as $clientProduct) {
                    $clientProductKey = array_keys($clientProduct)[0];
                    $clientProduct = array_values($clientProduct)[0];
                    
                    $productInStock = $stock[$clientProductKey];
                    $productInStock->quantity -= $clientProduct['quantityChoosed']; 
                    $productInStock->save();
                }
    
                auth()->user()->orders()->create([
                    'products' => json_encode($productsFromClient)
                ]);

                Mail::to(auth()->user())->send(new OrderConfirmed());
            });

            return response()->json('The purchase was successful', 200); 
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }
}
