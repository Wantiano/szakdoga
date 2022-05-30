<?php

namespace Database\Factories;

use App\Enums\CountryEnum;
use App\Models\Address;
use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Address::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'country' => $this->faker->randomElement(CountryEnum::valueArray()),
            'city' => $this->faker->city(),
            'street_number' => $this->faker->streetAddress(),
            'zip_code' => $this->faker->postcode(),
        ];
    }
}
