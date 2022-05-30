<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\DeliveryData;
use App\Models\User;
use App\Models\Address;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('addresses')->truncate();

        $deliveryDatas = DeliveryData::all();

        $this->createAddresses($deliveryDatas);
    }

    private function createAddresses($deliveryDatas) {
        $deliveryDatas->each(function ($deliveryData) {
            $deliveryAddress = Address::factory()->create();
            $deliveryData->delivery_address_id = $deliveryAddress->id;
            if(random_int(0,1)) {
                $billingAddress = Address::factory()->create();
                $deliveryData->billing_address_id = $billingAddress->id;
            } else {
                $data = $deliveryAddress->attributesToArray();
                unset($data['id']);
                unset($data['created_at']);
                unset($data['updated_at']);
                $billingAddress = Address::factory()->create($data);
                $deliveryData->billing_address_id = $billingAddress->id;
            }
            $deliveryData->save();
        });
    }
}

