<?php

use App\Models\CaseRecords;
use App\Models\Category;
use App\Models\Client;
use App\Models\User;

it('case worker can notes', function () {
    $this->actingAs($worker = User::factory()->create());
    $worker2 = User::factory()->create();

    $client = Client::factory()->create();
    Category::create(['name' => 'Housing']);

    $record = CaseRecords::factory()->create([
        'title' => 'For '.$worker->name,
        'assigned_to' => $worker->id,
    ]);
    CaseRecords::factory()->create([
        'title' => 'For '.$worker2->name,
        'assigned_to' => $worker2->id,
    ]);

    visit('/cases/'.$record->id)
        ->click('Add Note')
        ->fill('note', 'Testing Note Modal')
        ->click('[data-testid="add-note-btn"]')
        ->assertPathIs('/cases/'.$record->id)
        ->assertSee('Testing Note Modal')
        ->assertSee('By '.$worker->name);
});

it('case worker can tasks', function () {
    $this->actingAs($worker = User::factory()->create());
    $worker2 = User::factory()->create();

    $client = Client::factory()->create();
    Category::create(['name' => 'Housing']);

    $record = CaseRecords::factory()->create([
        'title' => 'For '.$worker->name,
        'assigned_to' => $worker->id,
    ]);
    CaseRecords::factory()->create([
        'title' => 'For '.$worker2->name,
        'assigned_to' => $worker2->id,
    ]);

    visit('/cases/'.$record->id)
        ->click('Add Task')
        ->fill('title', 'Task Modal')
        ->fill('description', 'Testing Task Modal')
        ->fill('due_date', now()->addDay()->format('Y-m-d'))
        ->click('[data-testid="add-task-btn"]')
        ->assertPathIs('/cases/'.$record->id)
        ->assertSee('Task Modal')
        ->assertSee('Testing Task Modal')
        ->assertSee('Due By: 1 day from now')
        ->assertSee('Incomplete');
});
