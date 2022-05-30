<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\User;
use App\Utils\ArrayUtil;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('products')->truncate();

        $this->createProducts(30);
    }

    private function createProducts(int $amount) {
        for ($i = 0; $i < $amount; ++$i) {
            $adminId = $this->getRandomAdminId();
            Product::factory()->create([
                'created_by' => $adminId,
                'last_modified_by' => $adminId,
                'category_id' => $this->getRandomCategoryId(),
            ]);
        }
    }

    private function getRandomAdminId() {
        return ArrayUtil::getRandomElement(User::where('is_admin', '=', true)->pluck('id')->toArray());
    }

    private function getRandomCategoryId() {
        return ArrayUtil::getRandomElement(Category::all()->pluck('id')->toArray());
    }
}
