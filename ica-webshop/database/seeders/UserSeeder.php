<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->truncate();

        $this->createAdmins(3);
        $this->createCustomers(10);
    }

    private function createAdmins(int $amount) {
        for ($i = 1; $i <= $amount; $i++) {
            User::factory()->create([
                'email' => 'admin' . $i . '@ica.hu',
                'password' => bcrypt('password'),
                'is_admin' => true,
            ]);
        }
    }

    private function createCustomers(int $amount) {
        for ($i = 1; $i <= $amount; $i++) {
            User::factory()->create([
                'email' => 'vasarlo' . $i . '@ica.hu',
                'password' => bcrypt('password'),
            ]);
        }
    }
}
