<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;
use App\Models\Order;
use App\Utils\ArrayUtil;
use App\Enums\StatusEnum;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $status = ArrayUtil::getRandomElement(StatusEnum::valueArray());

        $data = [
            'public_id' => $this->fakerOrderPublicId(),
            'customer_id' => 0,
            'customer_message' => $this->fakerSentenceOrNull(70),
            'status' => $status,
            'order_managed_by' => null,
            'order_proccessed_at' => null,
        ];

        if ($status != StatusEnum::PENDING) {
            $data['created_at'] = $this->fakerPastDate();
            $data['order_proccessed_at'] = $this->fakerOrderProcessedDate($data['created_at']);
        }

        return $data;
    }

    public function fakerOrderPublicId() {
        $id = $this->faker->lexify('??') . $this->faker->numerify('####') . $this->faker->lexify('??');
        while( !is_null(Order::where('public_id', $id)->first()) ) {
            $id = $this->faker->lexify('??') . $this->faker->numerify('####') . $this->faker->lexify('??');
        }
        return $id;
    }

    public function fakerSentenceOrNull($chanceOfGettingNull) {
        return $this->faker->numberBetween(0,100) < $chanceOfGettingNull ? null : $this->faker->sentence();
    }

    public function fakerPastDate() {
        return Carbon::now()->subMinutes($this->faker->numberBetween(1000,4000));
    }

    public function fakerOrderProcessedDate(Carbon $created_at) {
        return $created_at->addMinutes($this->faker->numberBetween(0,1000));
    }
}