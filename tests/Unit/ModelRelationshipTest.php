<?php

use App\Models\CaseNote;
use App\Models\CaseRecords;
use App\Models\Category;
use App\Models\Client;
use App\Models\Task;
use App\Models\User;

it('returns the correct admin status for users', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $worker = User::factory()->create(['role' => 'case_worker']);

    expect($admin->isAdmin())->toBeTrue()
        ->and($worker->isAdmin())->toBeFalse();
});

it('loads the core relationships on a case record', function () {
    $client = Client::factory()->create();
    $category = Category::create(['name' => 'Housing']);
    $admin = User::factory()->create(['role' => 'admin']);
    $worker = User::factory()->create(['role' => 'case_worker']);

    $case = CaseRecords::factory()->create([
        'assigned_to' => $worker->id,
        'client_id' => $client->id,
        'category_id' => $category->id,
        'created_by' => $admin->id,
    ]);

    $note = CaseNote::create([
        'case_id' => $case->id,
        'user_id' => $worker->id,
        'note' => 'Follow up',
    ]);

    $task = Task::create([
        'case_id' => $case->id,
        'assigned_to' => $worker->id,
        'title' => 'Follow up task',
        'description' => 'Needs attention',
        'due_date' => now()->addDay(),
    ]);

    expect($case->assignedUser->id)->toBe($worker->id)
        ->and($case->client->id)->toBe($client->id)
        ->and($case->category->id)->toBe($category->id)
        ->and($case->creator->id)->toBe($admin->id)
        ->and($case->notes->first()->id)->toBe($note->id)
        ->and($case->tasks->first()->id)->toBe($task->id);
});

it('detects overdue or soon-due tasks on a case', function () {
    $client = Client::factory()->create();
    $category = Category::create(['name' => 'Housing']);
    $admin = User::factory()->create(['role' => 'admin']);
    $worker = User::factory()->create(['role' => 'case_worker']);

    $case = CaseRecords::factory()->create([
        'assigned_to' => $worker->id,
        'client_id' => $client->id,
        'category_id' => $category->id,
        'created_by' => $admin->id,
    ]);

    Task::create([
        'case_id' => $case->id,
        'assigned_to' => $worker->id,
        'title' => 'Overdue task',
        'description' => 'Needs attention',
        'due_date' => now()->subDay(),
        'is_completed' => false,
    ]);

    Task::create([
        'case_id' => $case->id,
        'assigned_to' => $worker->id,
        'title' => 'Upcoming task',
        'description' => 'Needs attention',
        'due_date' => now()->addDay(),
        'is_completed' => false,
    ]);

    Task::create([
        'case_id' => $case->id,
        'assigned_to' => $worker->id,
        'title' => 'Completed task',
        'description' => 'Already done',
        'due_date' => now()->subDay(),
        'is_completed' => true,
    ]);

    expect($case->fresh()->hasTaskOverdue())->toBeTrue();
});

it('returns false when a case has no active overdue tasks', function () {
    $client = Client::factory()->create();
    $category = Category::create(['name' => 'Housing']);
    $admin = User::factory()->create(['role' => 'admin']);
    $worker = User::factory()->create(['role' => 'case_worker']);

    $case = CaseRecords::factory()->create([
        'assigned_to' => $worker->id,
        'client_id' => $client->id,
        'category_id' => $category->id,
        'created_by' => $admin->id,
    ]);

    Task::create([
        'case_id' => $case->id,
        'assigned_to' => $worker->id,
        'title' => 'Future task',
        'description' => 'Later',
        'due_date' => now()->addDays(3),
        'is_completed' => false,
    ]);

    expect($case->fresh()->hasTaskOverdue())->toBeFalse();
});
