<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'description' => $this->faker->sentence,
            'ingredients' => 'cheese',
            'size' => 'small',
            'price' => 20, 
            'image_url' => 'https://cdnp.iconscout.com/photo/free/thumb/veg-cheese-pizza-is-served-on-kitty-face-wooden-plate-1057460.jpg'    
        ];
    }
}
