<?php

namespace Database\Factories;

use App\Enums\DeliveryMethodEnum;
use App\Enums\PaymentMethodEnum;
use App\Models\DeliveryData;
use Illuminate\Database\Eloquent\Factories\Factory;

class DeliveryDataFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DeliveryData::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $deliveryMethod = $this->faker->randomElement(DeliveryMethodEnum::methodArray());
        $paymentMethod = $this->faker->randomElement(PaymentMethodEnum::methodArray());

        return [
            'delivery_method' => $deliveryMethod['value'],
            'payment_method' => $paymentMethod['value'], 
            'delivery_cost' => $deliveryMethod['cost'],
            'payment_cost' => $paymentMethod['cost'],
            'phone_number' => '+36' . $this->faker->numerify('#########'),
            'email' => $this->faker->safeEmail(),
            'delivery_address_id' => null,
            'billing_address_id' => null,
        ];
    }
}
