<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\CaseRecordsStatus;
use App\Http\Requests\StoreClientRequest;
use App\Models\Client;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $clients = Client::withcount([
            // Count Open cases
            'cases as open_count' => function ($query) {
                $query->where('status', CaseRecordsStatus::OPEN);
            },
            // Count In-Progress cases
            'cases as progress_count' => function ($query) {
                $query->where('status', CaseRecordsStatus::IN_PROGRESS);
            },
            // Count Closed cases
            'cases as closed_count' => function ($query) {
                $query->where('status', CaseRecordsStatus::CLOSED);
            },
        ])
            ->get();

        return view('clients.index', ['clients' => $clients]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('clients.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClientRequest $request)
    {
        //
        Client::create($request->validated());

        return redirect('/clients');
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        //
        return view('clients.show', ['client' => $client]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        //
        return view('clients.edit', ['client' => $client]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreClientRequest $request, Client $client)
    {
        //
        $client->update($request->validated());

        return redirect("/clients/{$client->id}");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        //
        $client->delete();

        return redirect('/clients');
    }
}
