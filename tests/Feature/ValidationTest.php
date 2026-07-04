<?php

use App\Models\User;

it('rejects invalid case creation input', function () {
    $this->actingAs(User::factory()->create(['role' => 'admin']));

    $this->post('/cases', [
        'title' => '',
        'description' => '',
        'category_id' => 999,
        'client_id' => 999,
        'status' => 'invalid',
    ])->assertSessionHasErrors(['title', 'description', 'category_id', 'client_id', 'status']);
});

it('rejects invalid client creation input', function () {
    $this->actingAs(User::factory()->create(['role' => 'admin']));

    $this->post('/clients', [
        'first_name' => '',
        'last_name' => '',
        'email' => 'not-an-email',
        'phone' => '123',
    ])->assertSessionHasErrors(['first_name', 'last_name', 'email', 'phone']);
});

it('rejects invalid user creation input', function () {
    $this->actingAs(User::factory()->create(['role' => 'admin']));

    $this->post('/users', [
        'name' => '',
        'email' => 'bad-email',
        'password' => 'short',
    ])->assertSessionHasErrors(['name', 'email', 'password']);
});
