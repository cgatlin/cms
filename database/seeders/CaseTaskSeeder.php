<?php

namespace Database\Seeders;

use App\CaseRecordsStatus;
use App\Models\CaseRecords;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class CaseTaskSeeder extends Seeder
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
            $is_complete = $case->status->value == CaseRecordsStatus::CLOSED->value;
            Task::create([
                'case_id' => $case->id,
                'assigned_to' => $case->assignedUser->id,
                'title' => 'Call Client',
                'description' => 'Call Client to abtain info.',
                'is_completed' => $is_complete ? 1 : 1,
                'due_date' => now()->subDays(2),
            ]);
            Task::create([
                'case_id' => $case->id,
                'assigned_to' => $case->assignedUser->id,
                'title' => 'Submit paperwork',
                'description' => 'Submit initial documentation.',
                'is_completed' => $is_complete ? 1 : 0,
                'due_date' => now()->subDays(2),
            ]);
            Task::create([
                'case_id' => $case->id,
                'assigned_to' => $case->assignedUser->id,
                'title' => 'Verify eligibility',
                'description' => 'Review if client meets minium requirements for follow-ups.',
                'is_completed' => $is_complete ? 1 : 0,
                'due_date' => now()->addDays(1),
            ]);
            Task::create([
                'case_id' => $case->id,
                'assigned_to' => $case->assignedUser->id,
                'title' => 'Notify Client',
                'description' => 'Notify Client of eligibility status',
                'is_completed' => $is_complete ? 1 : 0,
                'due_date' => now()->addDays(7),
            ]);
        }
    }
}
