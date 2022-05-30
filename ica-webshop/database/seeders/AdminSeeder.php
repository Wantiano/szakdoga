<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::factory()->create([
            'password' => bcrypt('AdMiN'),
            'is_admin' => true,
        ]);

        $admin->update(['email' => 'admin' . $admin->id . '@ica.hu',]);
    }
}
