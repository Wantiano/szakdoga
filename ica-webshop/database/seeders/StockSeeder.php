<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Stock;
use App\Models\Product;
use Faker\Generator;
use Illuminate\Container\Container;

class StockSeeder extends Seeder
{
    /**
     * The current Faker instance.
     *
     * @var \Faker\Generator
     */
    protected $faker;

    /**
     * Create a new seeder instance.
     *
     * @return void
     */
    public function __construct() {
        $this->faker = Container::getInstance()->make(Generator::class);
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('stocks')->truncate();

        Product::all()->pluck('id')->each(function ($productId) {
            $amount = random_int(1,10);
            $this->createStocks($productId, $amount);
        });
    }

    private function createStocks($productId, int $amount) {
        for ($i = 0; $i < $amount; ++$i) {
            $this->createStock($productId);
        }
    }

    private function createStock($productId) {
        try {
            Stock::create([
                'product_id' => $productId,
                'color' => $this->faker->colorName(),
                'size' => random_int(30,48),
                'in_stock' => random_int(0,50),
            ]);
        } catch (\Illuminate\Database\QueryException $ex) {
            $this->createStock($productId);
        }
    }
}
