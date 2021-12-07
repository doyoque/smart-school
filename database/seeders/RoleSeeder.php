<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            ['name' => 'school_admin', 'created_at' => now(), 'updated_at' => now()], 
            ['name' => 'teacher', 'created_at' => now(), 'updated_at' => now()], 
            ['name' => 'student', 'created_at' => now(), 'updated_at' => now()], 
        ];

        Role::insert($roles);
    }
}
