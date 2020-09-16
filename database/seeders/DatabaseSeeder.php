<?php

namespace Database\Seeders;

use App\Models\Product;
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
        $fourCheeses = Product::create([
            'name' => 'Four Cheeses',
            'description' => 'For the lovers of cheese!',
            'ingredients' => 'Mozzarela, Gorgonzola, Parmesan, Cheddar',
            'size' => 'small',
            'price' => 20, 
            'image_url' => asset('img/pizzas/4_cheeses.jpeg')    
        ]);

        $chicken = Product::create([
            'name' => 'Chicken',
            'description' => 'Simple and delicious!',
            'ingredients' => 'Chicken, Cheese',
            'size' => 'small',
            'price' => 15, 
            'image_url' => asset('img/pizzas/chicken.jpg')     
        ]);

        $chicken = Product::create([
            'name' => 'Chocolate',
            'description' => 'So sweeetttt!',
            'ingredients' => 'Chocolate, Cheese',
            'size' => 'small',
            'price' => 22, 
            'image_url' => asset('img/pizzas/chocolate.jpg')     
        ]);

        $meatAndFries = Product::create([
            'name' => 'Meat and Fries',
            'description' => 'One of our best pizzas!',
            'ingredients' => 'Meat, Fries, Cheese',
            'size' => 'small',
            'price' => 28, 
            'image_url' => asset('img/pizzas/meat_and_fries.jpeg')     
        ]);

        $portuguesa = Product::create([
            'name' => 'Portuguesa',
            'description' => 'The amazing portuguese pizza!',
            'ingredients' => 'Olive, Cheese',
            'size' => 'small',
            'price' => 23, 
            'image_url' => asset('img/pizzas/portuguesa.jpg')  
        ]);
    }
}