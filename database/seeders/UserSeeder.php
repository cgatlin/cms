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
        //
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@ivfs.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Case Worker',
            'email' => 'cworker@ivfs.com',
            'password' => bcrypt('password'),
            'role' => 'case_worker',
        ]);

        User::factory(5)->create([
            'role' => 'case_worker',
        ]);
    }
}
