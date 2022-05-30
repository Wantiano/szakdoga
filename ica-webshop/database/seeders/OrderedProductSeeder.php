<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\OrderedProduct;
use App\Models\Order;
use App\Models\Stock;
use Exception;
use App\Utils\ArrayUtil;

class OrderedProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ordered_products')->truncate();

        $orders = Order::all();
        foreach($orders as $order) {
            $this->createOrderedProductsForOrder($order);
        }
    }

    private function createOrderedProductsForOrder($order) {
        $stockIds = Stock::all()->pluck('id')->toArray();
        $orderedProductsAmount = random_int(1, 10);
        
        for ($i = 0; $i < $orderedProductsAmount; ++$i) {
            $stockIdIndex = random_int(0, count($stockIds)-1);
        
            $stock = Stock::find($stockIds[$stockIdIndex]);
            $product = $stock->product;

            OrderedProduct::create([
                'order_id' => $order->id, 
                'product_id' => $product->id,
                'product_name' => $product->name, 
                'product_description' => $product->description, 
                'product_created_by' => $product->created_by, 
                'product_color' => $stock->color, 
                'product_size' => $stock->size, 
                'product_amount' => random_int(1, 500), 
                'product_price' => $product->price,
            ]);

            $stockIds = ArrayUtil::removeElementByIndex($stockIds, $stockIdIndex);
        }
    }
}
