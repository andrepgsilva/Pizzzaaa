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
        $chocolateIngredient = Ingredient::create(['name' => 'Chocolate']);

        $medium = Size::create(['name' => 'Medium']);
        $family = Size::create(['name' => 'Family']);
        $small = Size::create(['name' => 'Small']);

        $fourCheeses = Product::create([
            'name' => 'Four Cheeses',
            'description' => 'For the lovers of cheese!',
            'image_url' => asset('img/pizzas/4_cheeses.jpeg')    
        ]);

        $fourCheeses->ingredients()->attach([
            $mozzarela->id,
            $gorgonzola->id,
            $parmesan->id,
            $cheddar->id,
        ]);

        $fourCheeses->sizes()->attach($medium->id, ['price' => 3000]);
        $fourCheesesMedium = Stock::create([
            'product_variation_id' => 1,
            'quantity' => 10,
        ]);

        $fourCheeses->sizes()->attach($family->id, ['price' => 4000]);
        $fourCheesesFamily = Stock::create([
            'product_variation_id' => 2,
            'quantity' => 5,
        ]);

        $fourCheeses->sizes()->attach($small->id, ['price' => 2000]);
        $fourCheesesSmall = Stock::create([
            'product_variation_id' => 3,
            'quantity' => 2,
        ]);


        $chicken = Product::create([
            'name' => 'Chicken',
            'description' => 'Simple and delicious!',
            'image_url' => asset('img/pizzas/chicken.jpg')     
        ]);

        $chicken->ingredients()->attach([
            $chicken->id,
            $mozzarela->id
        ]);

        $chicken->sizes()->attach($medium->id, ['price' => 3500]);
        $chickenMedium = Stock::create([
            'product_variation_id' => 4,
            'quantity' => 15,
        ]);

        $chicken->sizes()->attach($family->id, ['price' => 4500]);
        $chickenFamily = Stock::create([
            'product_variation_id' => 5,
            'quantity' => 7,
        ]);

        $chicken->sizes()->attach($small->id, ['price' => 3000]);
        $chickenSmall = Stock::create([
            'product_variation_id' => 6,
            'quantity' => 1,
        ]);

        $chocolate = Product::create([
            'name' => 'Chocolate',
            'description' => 'So sweeetttt!', 
            'image_url' => asset('img/pizzas/chocolate.jpg')     
        ]);

        $chocolate->ingredients()->attach([
            $chocolateIngredient->id,
            $mozzarela->id,
        ]);

        $chocolate->sizes()->attach($medium->id, ['price' => 5500]);
        $chocolateMedium = Stock::create([
            'product_variation_id' => 7,
            'quantity' => 15,
        ]);

        $chocolate->sizes()->attach($family->id, ['price' => 6500]);
        $chocolateFamily = Stock::create([
            'product_variation_id' => 8,
            'quantity' => 7,
        ]);

        $chocolate->sizes()->attach($small->id, ['price' => 4100]);
        $chocolateSmall = Stock::create([
            'product_variation_id' => 9,
            'quantity' => 1,
        ]);

        $meatAndFries = Product::create([
            'name' => 'Meat and Fries',
            'description' => 'One of our best pizzas!',
            'image_url' => asset('img/pizzas/meat_and_fries.jpeg')     
        ]);

        $meatAndFries->ingredients()->attach([
            $meat->id,
            $fries->id,
            $mozzarela->id,
        ]);

        $meatAndFries->sizes()->attach($medium->id, ['price' => 4600]);
        $meatAndFriesMedium = Stock::create([
            'product_variation_id' => 10,
            'quantity' => 5,
        ]);

        $meatAndFries->sizes()->attach($family->id, ['price' => 5700]);
        $meatAndFriesFamily = Stock::create([
            'product_variation_id' => 11,
            'quantity' => 10,
        ]);

        $meatAndFries->sizes()->attach($small->id, ['price' => 4000]);
        $meatAndFriesSmall = Stock::create([
            'product_variation_id' => 12,
            'quantity' => 3,
        ]);

        $portuguesa = Product::create([
            'name' => 'Portuguesa',
            'description' => 'The portuguese pizza!', 
            'image_url' => asset('img/pizzas/portuguesa.jpg')  
        ]);

        $portuguesa->ingredients()->attach([
            $olive->id,
            $mozzarela->id,
        ]);

        $portuguesa->sizes()->attach($medium->id, ['price' => 3500]);
        $portuguesaMedium = Stock::create([
            'product_variation_id' => 13,
            'quantity' => 8,
        ]);

        $portuguesa->sizes()->attach($family->id, ['price' => 5500]);
        $portuguesaFamily = Stock::create([
            'product_variation_id' => 14,
            'quantity' => 4,
        ]);

        $portuguesa->sizes()->attach($small->id, ['price' => 2900]);
        $portuguesaSmall = Stock::create([
            'product_variation_id' => 15,
            'quantity' => 1,
        ]);
    }
}