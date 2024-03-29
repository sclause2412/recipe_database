<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where(['name' => 'admin'])->first();

        if (is_null($admin)) {
            User::factory()->create([
                'name' => 'admin',
                'email' => 'admin@dev.lo',
                'firstname' => 'Admin',
                'lastname' => 'Admin',
                'admin' => true,
                'two_factor_confirmed_at' => now(),
            ]);
        }
        User::factory(10)->create();
    }
}
