<?php

use App\Models\CaseRecords;
use App\Models\Category;
use App\Models\Client;
use App\Models\User;

it('admin can create case', function () {
    $this->actingAs($admin = User::factory()->create(['role' => 'admin']));

    $client = Client::factory()->create();
    $category = Category::create(['name' => 'Housing']);

    visit('/cases')
        ->click('Create')
        ->assertPathIs('/cases/create')
        ->fill('title', 'Housing Assistance')
        ->fill('description', 'Client requests housing')
        ->select('category_id', 'Housing')
        ->type('client', $client->first_name.' '.$client->last_name)
        ->assertValue('client', $client->first_name.' '.$client->last_name)
        ->assertValue('client_id', $client->id)
        ->select('status', 'OPEN')
        ->click('Create Case')
        ->assertPathIs('/cases')
        ->assertSee('Housing Assistance')
        ->assertSee('Client: '.$client->first_name.' '.$client->last_name)
        ->assertSee('Client requests housing')
        ->assertSee('OPEN');

});

it('case worker can not create case', function () {
    $this->actingAs($worker = User::factory()->create());

    $client = Client::factory()->create();
    $category = Category::create(['name' => 'Housing']);

    visit('/cases')
        ->assertDontSee('Create');

    $this->get('/cases/create')
        ->assertStatus(403);

});

it('admin can see all assigned cases', function () {
    $this->actingAs($admin = User::factory()->create(['role' => 'admin']));
    $worker = User::factory()->create();
    $worker2 = User::factory()->create();

    $client = Client::factory()->create();
    $category = Category::create(['name' => 'Housing']);

    CaseRecords::factory()->create([
        'title' => 'For '.$worker->name,
        'assigned_to' => $worker->id,
    ]);
    CaseRecords::factory()->create([
        'title' => 'For '.$worker2->name,
        'assigned_to' => $worker2->id,
    ]);

    visit('/cases')
        ->assertSee('For '.$worker->name)
        ->assertSee('For '.$worker2->name);
});

it('case worker can see assigned cases', function () {
    $this->actingAs($worker = User::factory()->create());
    $worker2 = User::factory()->create();

    $client = Client::factory()->create();
    $category = Category::create(['name' => 'Housing']);

    CaseRecords::factory()->create([
        'title' => 'For '.$worker->name,
        'assigned_to' => $worker->id,
    ]);
    CaseRecords::factory()->create([
        'title' => 'For '.$worker2->name,
        'assigned_to' => $worker2->id,
    ]);

    visit('/cases')
        ->assertSee('For '.$worker->name)
        ->assertDontSee('For '.$worker2->name);
});

it('admin can edit cases', function () {
    $this->actingAs($admin = User::factory()->create(['role' => 'admin']));
    $worker = User::factory()->create();

    $client = Client::factory()->create();
    $category = Category::create(['name' => 'Housing']);

    $record = CaseRecords::factory()->create([
        'title' => 'For '.$worker->name,
        'assigned_to' => $worker->id,
    ]);

    visit('/cases')
        ->click('Edit')
        ->assertPathIs('/cases/'.$record->id.'/edit')
        ->assertValue('title', $record->title)
        ->assertValue('description', $record->description)
        ->assertValue('client', $client->first_name.' '.$client->last_name)
        ->fill('title', 'Rehousing Assistance')
        ->fill('description', 'Client requests housing')
        ->select('status', 'CLOSED')
        ->click('Save Edit')
        ->assertPathIs('/cases/'.$record->id)
        ->assertSee('Rehousing Assistance')
        ->assertSee('Status: closed');
});

it('case worker can edit assigned cases', function () {
    $this->actingAs($worker = User::factory()->create());
    $worker2 = User::factory()->create();

    $client = Client::factory()->create();
    $category = Category::create(['name' => 'Housing']);

    $record = CaseRecords::factory()->create([
        'title' => 'For '.$worker->name,
        'assigned_to' => $worker->id,
    ]);
    $record2 = CaseRecords::factory()->create([
        'title' => 'For '.$worker2->name,
        'assigned_to' => $worker2->id,
    ]);

    visit('/cases')
        ->click('Edit')
        ->assertPathIs('/cases/'.$record->id.'/edit')
        ->assertValue('title', $record->title)
        ->assertValue('description', $record->description)
        ->assertValue('client', $client->first_name.' '.$client->last_name)
        ->fill('title', 'Rehousing Assistance')
        ->fill('description', 'Client requests housing')
        ->select('status', 'CLOSED')
        ->click('Save Edit')
        ->assertPathIs('/cases/'.$record->id)
        ->assertSee('Rehousing Assistance')
        ->assertSee('Status: closed');

    $this->get('/cases/'.$record2->id.'/edit')
        ->assertStatus(403);
});
