<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\ProductImage;
use App\Models\Product;

class ProductImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('product_images')->truncate();

        Product::all()->pluck('id')->each(function ($productId) {
            $imageNumber = random_int(1,3);
            for($i = 0; $i < $imageNumber; ++$i) {
                ProductImage::create([
                    'image_url' => 'img' . $i . '.jpg',
                    'product_id' => $productId
                ]);
            }
        });
    }
}

