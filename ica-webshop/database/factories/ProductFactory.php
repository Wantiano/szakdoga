<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;

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
            'is_deleted' => $this->faker->boolean(10),
            'name' => ucfirst($this->faker->words(3, true)),
            'price' => strval(random_int(100,1000000)),
            'description' => $this->faker->text(),
            'created_by' => 0,
            'category_id' => 0,
        ];
    }
}
