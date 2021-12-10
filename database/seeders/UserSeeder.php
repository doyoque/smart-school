<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
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
        User::factory()->create([
            'role_id' => 1,
            'school_id' => 1,
            'username' => 'testing123',
        ]);
        User::factory()->count(3)->create(['role_id' => 2, 'school_id' => 1]);
        User::factory()->count(10)->create(['role_id' => 3, 'school_id' => 1]);
    }
}
