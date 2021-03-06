<?php

use App\Models\Product;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Builder;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/reset-password', function() {
    return view('vueapp');
})->name('password.reset');

// Route::get('/test', function() {
//     $products = Product::whereHas('stock', function (Builder $query) {
//         $query->where('quantity', 4);
//     })->get();

//     return $products;
// });
Route::get('/{any}', function(){
    return view('vueapp');
})->where('any', '.*');