<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Order;
use App\Models\DeliveryData;
use App\Enums\StatusEnum;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('orders')->truncate();

        $customers = User::where('is_admin', '=', false);

        $this->createOrdersForCustomers($customers);
    }

    private function createOrdersForCustomers($customers) {
        $customers->each(function ($customer) {
            $amount = random_int(0,5);

            $orders = [];

            for ($i = 0; $i < $amount; ++$i) {
                $deliveryData = DeliveryData::factory()->create([
                    'email' => User::find($customer->id)->email
                ]);

                $orders[] = Order::factory()->create([
                    'customer_id' => $customer->id,
                    'delivery_data_id' => $deliveryData->id
                ]);
            }

            foreach ($orders as $order) {
                if ($order->status != StatusEnum::PENDING && $order->status != StatusEnum::INCOMPLETE) {
                    $order->update(['order_managed_by' => User::where('is_admin', true)->get()->random()->id]);
                } else {
                    $order->update(['order_proccessed_at' => null]);
                }
            }
        });
    }
}

