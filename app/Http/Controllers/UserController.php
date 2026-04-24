<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\CaseRecordsStatus;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $users = User::withcount([
            // Count Open cases
            'assignedCases as open_count' => function ($query) {
                $query->where('status', CaseRecordsStatus::OPEN);
            },
            // Count In-Progress cases
            'assignedCases as progress_count' => function ($query) {
                $query->where('status', CaseRecordsStatus::IN_PROGRESS);
            },
            // Count Closed cases
            'assignedCases as closed_count' => function ($query) {
                $query->where('status', CaseRecordsStatus::CLOSED);
            },
        ])
            ->get();

        return view('users.index', ['users' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {

        User::create($request->validated());

        return redirect('/users');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
        return view('users.show', ['user' => $user]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
        return view('users.edit', ['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreUserRequest $request, User $user)
    {

        $user->update($request->validated());

        return redirect("/users/{$user->id}");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
        $user->delete();

        return redirect('/users');
    }
}
