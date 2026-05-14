<?php

namespace Database\Seeders;

use App\Models\CaseNote;
use App\Models\CaseRecords;
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
        $cases = CaseRecords::all();
        $users = User::all();

        foreach ($cases as $case) {
            CaseNote::create([
                'case_id' => $case->id,
                'user_id' => $case->assignedUser->id,
                'note' => 'Spoke to Client over phone',
            ]);
            CaseNote::create([
                'case_id' => $case->id,
                'user_id' => $case->assignedUser->id,
                'note' => 'Client requests assistance for '.$case->category->name,
            ]);
            CaseNote::create([
                'case_id' => $case->id,
                'user_id' => $case->assignedUser->id,
                'note' => 'Working on paperwork for eligibility',
            ]);
        }
    }
}
