<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use App\Models\Product;
use App\Models\Size;
use App\Models\Stock;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $mozzarela = Ingredient::create(['name' => 'Mozzarela']);
        $gorgonzola = Ingredient::create(['name' => 'Gorgonzola']);
        $parmesan = Ingredient::create(['name' => 'Parmesan']);
        $cheddar = Ingredient::create(['name' => 'Cheddar']);
        $chicken = Ingredient::create(['name' => 'Chicken']);
        $olive = Ingredient::create(['name' => 'Olive']);
        $meat = Ingredient::create(['name' => 'Meat']);
        $fries = Ingredient::create(['name' => 'Fries']);

        $small = Size::create(['name' => 'Small']);
        $medium = Size::create(['name' => 'Medium']);
        $family = Size::create(['name' => 'Family']);

        $fourCheeses = Product::create([
            'name' => 'Four Cheeses',
            'description' => 'For the lovers of cheese!',
            'price' => 2000, 
            'image_url' => asset('img/pizzas/4_cheeses.jpeg')    
        ]);

        $fourCheeses->ingredients()->attach([
            $mozzarela->id,
            $gorgonzola->id,
            $parmesan->id,
            $cheddar->id,
        ]);

        $fourCheeses->sizes()->attach([
            $small->id,
            $medium->id,
            $family->id,
        ]);

        Stock::create([
            'product_id' => $fourCheeses->id,
            'quantity' => 4,
        ]);

        $chicken = Product::create([
            'name' => 'Chicken',
            'description' => 'Simple and delicious!',
            'price' => 3000, 
            'image_url' => asset('img/pizzas/chicken.jpg')     
        ]);

        $chicken->ingredients()->attach([
            $chicken->id,
            $mozzarela->id
        ]);

        $chicken->sizes()->attach([
            $small->id,
            $medium->id,
            $family->id,
        ]);

        Stock::create([
            'product_id' => $chicken->id,
            'quantity' => 6,
        ]);

        $chocolate = Product::create([
            'name' => 'Chocolate',
            'description' => 'So sweeetttt!',
            'price' => 1522, 
            'image_url' => asset('img/pizzas/chocolate.jpg')     
        ]);

        $chocolate->ingredients()->attach([
            $chocolate->id,
            $mozzarela->id,
        ]);

        $chocolate->sizes()->attach([
            $small->id,
            $medium->id,
            $family->id,
        ]);

        Stock::create([
            'product_id' => $chocolate->id,
            'quantity' => 2,
        ]);

        $meatAndFries = Product::create([
            'name' => 'Meat and Fries',
            'description' => 'One of our best pizzas!',
            'price' => 4000, 
            'image_url' => asset('img/pizzas/meat_and_fries.jpeg')     
        ]);

        $meatAndFries->ingredients()->attach([
            $meat->id,
            $fries->id,
            $mozzarela->id,
        ]);

        $meatAndFries->sizes()->attach([
            $small->id,
            $medium->id,
            $family->id,
        ]);

        Stock::create([
            'product_id' => $meatAndFries->id,
            'quantity' => 8,
        ]);

        $portuguesa = Product::create([
            'name' => 'Portuguesa',
            'description' => 'The amazing portuguese pizza!',
            'price' => 2320, 
            'image_url' => asset('img/pizzas/portuguesa.jpg')  
        ]);

        $portuguesa->sizes()->attach([
            $small->id,
            $medium->id,
            $family->id,
        ]);

        $portuguesa->ingredients()->attach([
            $olive->id,
            $mozzarela->id,
        ]);

        Stock::create([
            'product_id' => $portuguesa->id,
            'quantity' => 1,
        ]);
    }
}