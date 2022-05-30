<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Product;

class FavoriteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('favorites')->truncate();

        $customers = User::where('is_admin', '=', false)->get();

        foreach ($customers as $customer) {
            $amount = random_int(1,5);
            $productIds = Product::all()->random($amount)->pluck('id')->toArray();
            $customer->favoriteProducts()->attach($productIds);
            $customer->save();
        }
    }
}

