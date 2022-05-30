<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Category;
use App\Models\User;
use App\Utils\ArrayUtil;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->truncate();

        $categories = $this->createBasicCategories();
        $categories = $this->createMoreCategories(30, $categories);
    }

    private function createMoreCategories(int $amount, array $categories) {
        //Cycle because it has to build up like a tree
        for ($i = 0; $i < $amount; ++$i) {
            $creatorId = User::where('is_admin', '=', true)->inRandomOrder()->first()->id;
            array_push($categories,
                Category::factory()->create([
                    'parent_category_id' => ArrayUtil::getRandomElement($categories)->id,
                    'created_by' => $creatorId,
                    'last_modified_by' => $creatorId,
                ])
            );
        }

        return $categories;
    }

    private function createBasicCategories() {
        return [
            Category::factory()->create(['name' => 'Munkavédelmi ruházat']),
            Category::factory()->create(['name' => 'Vadászruházat']),
            Category::factory()->create(['name' => 'Outdoor ruházat'])
        ];
    }
}
