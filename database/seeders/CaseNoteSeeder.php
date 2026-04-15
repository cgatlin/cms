<?php

namespace Database\Seeders;

use App\Models\CaseNote;
use App\Models\Cases;
use App\Models\User;
use Illuminate\Database\Seeder;

class CaseNoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $cases = Cases::all();
        $users = User::all();

        foreach ($cases as $case) {
            for ($i = 0; $i < 3; $i++) {
                CaseNote::create([
                    'case_id' => $case->id,
                    'user_id' => $users->random()->id,
                    'note' => 'Sample note for case',
                ]);
            }
        }
    }
}
