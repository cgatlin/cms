<?php

namespace Database\Seeders;

use App\Models\CaseRecords;
use App\Models\User;
use Illuminate\Database\Seeder;

class CaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $users = User::where('role', 'case_worker')->pluck('id');

        for ($i = 1; $i <= 20; $i++) {
            CaseRecords::create([
                'title' => "Case #$i",
                'description' => 'Sample case description',
                'status' => 'open',
                'assigned_to' => $users->random(),
                'client_id' => $i,
                'category_id' => rand(1, 4),
                'created_by' => 1, // admin
            ]);
        }
    }
}
