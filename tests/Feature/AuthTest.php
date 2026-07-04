<?php

use App\Models\CaseRecords;
use App\Models\Category;
use App\Models\Client;
use App\Models\User;
use App\Notifications\TaskAssignedNotification;
use Illuminate\Support\Facades\Notification;

it('redirects guests from protected routes', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $client = Client::factory()->create();
    $worker = User::factory()->create();
    $category = Category::create(['name' => 'Housing']);

    $record = CaseRecords::factory()->create([
        'title' => 'For '.$worker->name,
        'assigned_to' => $worker->id,
        'client_id' => $client->id,
        'category_id' => $category->id,
        'created_by' => $admin->id,
    ]);

    $this->get('/notifications')->assertRedirect('/login');
    $this->get('/dashboard')->assertRedirect('/login');
    $this->get('/cases')->assertRedirect('/login');
    $this->get('/clients')->assertRedirect('/login');
    $this->get("/cases/{$record->id}/")->assertRedirect('/login');
    $this->get("/clients/{$client->id}")->assertRedirect('/login');
});

it('prevents non-admin users from accessing admin-only routes', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $this->actingAs($worker = User::factory()->create(['role' => 'case_worker']));
    $client = Client::factory()->create();
    $category = Category::create(['name' => 'Housing']);

    $record = CaseRecords::factory()->create([
        'title' => 'For '.$worker->name,
        'assigned_to' => $worker->id,
        'client_id' => $client->id,
        'category_id' => $category->id,
        'created_by' => $admin->id,
    ]);

    $this->get('/cases/create')->assertStatus(403);
    $this->get('/clients/create')->assertStatus(403);
    $this->get('/users/create')->assertStatus(403);
    $this->delete("/cases/{$record->id}")->assertStatus(403);
    $this->delete("/clients/{$client->id}")->assertStatus(403);
});

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

it('case worker can not add note to unassigned case', function () {
    $this->actingAs($worker = User::factory()->create());
    $worker2 = User::factory()->create();

    $client = Client::factory()->create();
    Category::create(['name' => 'Housing']);

    $record = CaseRecords::factory()->create([
        'title' => 'For '.$worker2->name,
        'assigned_to' => $worker2->id,
        'client_id' => $client->id,
        'category_id' => Category::first()->id,
        'created_by' => $worker2->id,
    ]);

    $this->post('/cases/'.$record->id.'/notes', [
        'note' => 'Unauthorized note',
    ])->assertStatus(403);

    $this->assertDatabaseMissing('case_notes', [
        'case_id' => $record->id,
        'note' => 'Unauthorized note',
    ]);
});

it('case worker can create task and send notification', function () {
    Notification::fake();

    $this->actingAs($worker = User::factory()->create());
    $admin = User::factory()->create(['role' => 'admin']);

    $client = Client::factory()->create();
    $category = Category::create(['name' => 'Housing']);

    $record = CaseRecords::factory()->create([
        'title' => 'For '.$worker->name,
        'assigned_to' => $worker->id,
        'client_id' => $client->id,
        'category_id' => $category->id,
        'created_by' => $admin->id,
    ]);

    $this->post('/cases/'.$record->id.'/tasks', [
        'title' => 'Assigned Task',
        'description' => 'Task description',
        'due_date' => now()->addDay()->format('Y-m-d'),
    ])->assertRedirect('/cases/'.$record->id);

    $this->assertDatabaseHas('tasks', [
        'case_id' => $record->id,
        'title' => 'Assigned Task',
        'description' => 'Task description',
        'assigned_to' => $worker->id,
    ]);

    Notification::assertSentTo(
        $worker,
        TaskAssignedNotification::class,
        fn ($notification) => $notification->toDatabase($worker)['case_id'] === $record->id
    );
});

it('admin can delete case', function () {
    $this->actingAs($admin = User::factory()->create(['role' => 'admin']));

    $client = Client::factory()->create();
    $category = Category::create(['name' => 'Housing']);

    $record = CaseRecords::factory()->create([
        'assigned_to' => $admin->id,
        'client_id' => $client->id,
        'category_id' => $category->id,
        'created_by' => $admin->id,
    ]);

    $this->delete('/cases/'.$record->id)
        ->assertRedirect('/cases');

    $this->assertDatabaseMissing('case_records', ['id' => $record->id]);
});

it('admin can create, update, and delete a client', function () {
    $this->actingAs(User::factory()->create(['role' => 'admin']));

    $this->post('/clients', [
        'first_name' => 'Jane',
        'last_name' => 'Doe',
        'email' => 'jane@example.com',
        'phone' => '1234567890',
    ])->assertRedirect('/clients');

    $client = Client::where('email', 'jane@example.com')->firstOrFail();

    $this->patch('/clients/'.$client->id, [
        'first_name' => 'Janet',
        'last_name' => 'Doe',
        'email' => 'janet@example.com',
        'phone' => '1234567890',
    ])->assertRedirect('/clients/'.$client->id);

    $this->assertDatabaseHas('clients', [
        'id' => $client->id,
        'first_name' => 'Janet',
        'email' => 'janet@example.com',
    ]);

    $this->delete('/clients/'.$client->id)
        ->assertRedirect('/clients');

    $this->assertDatabaseMissing('clients', ['id' => $client->id]);
});

it('admin can create, update, and delete a user', function () {
    $this->actingAs(User::factory()->create(['role' => 'admin']));

    $this->post('/users', [
        'name' => 'New Worker',
        'email' => 'worker@example.com',
        'password' => 'password123',
    ])->assertRedirect('/users');

    $user = User::where('email', 'worker@example.com')->firstOrFail();

    $this->patch('/users/'.$user->id, [
        'name' => 'Updated Worker',
        'email' => 'updated-worker@example.com',
        'password' => 'password123',
    ])->assertRedirect('/users/'.$user->id);

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'name' => 'Updated Worker',
        'email' => 'updated-worker@example.com',
    ]);

    $this->delete('/users/'.$user->id)
        ->assertRedirect('/users');

    $this->assertDatabaseMissing('users', ['id' => $user->id]);
});