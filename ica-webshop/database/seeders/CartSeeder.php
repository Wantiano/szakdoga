<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Stock;
use App\Models\User;
use App\Models\Cart;

class CartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('carts')->truncate();

        $customers = User::where('is_admin', '=', false);
        $stocks = Stock::all();

        $this->createCarts($customers, $stocks);
    }

    private function createCarts($customers, $stocks) {
        $customers->each(function ($customer) use (&$stocks) {
            $stockIds = $stocks->random(random_int(0, 5))->pluck('id')->toArray();
            foreach ($stockIds as $stockId) {
                Cart::create([
                    'customer_id' => $customer->id,
                    'stock_id' => $stockId,
                    'amount' => random_int(1, 20)
                ]);
            }
        });
    }
}

