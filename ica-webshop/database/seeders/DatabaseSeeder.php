<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Assign to true if you need seeds for testing.
        $test = true;

        if($test) {
            $this->sampleSeed();
        } else {
            $this->initSeed();
        }
    }


    /**
     * Seed the application's database.
     */
    private function initSeed()
    {
        Category::factory()->create(['name' => 'Munkavédelmi ruházat']);
        Category::factory()->create(['name' => 'Vadászruházat']);
        Category::factory()->create(['name' => 'Outdoor ruházat']);
    }

    /**
     * Seed the application's database with samples.
     */
    private function sampleSeed()
    {
        $this->call(UserSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(ProductImageSeeder::class);
        $this->call(StockSeeder::class);
        $this->call(CartSeeder::class);
        $this->call(FavoriteSeeder::class);
        $this->call(OrderSeeder::class);
        $this->call(OrderedProductSeeder::class);
        $this->call(AddressSeeder::class);
    }
}
