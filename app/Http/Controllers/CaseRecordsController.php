<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreCaseRecordsRequest;
use App\Models\CaseRecords;
use App\Models\Category;
use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

class CaseRecordsController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        if (auth()->user()->isAdmin()) {
            $caseRecords = CaseRecords::orderByRaw('CASE WHEN assigned_to IS NULL THEN 0 ELSE 1 END')
                ->orderBy('opened_at', 'desc')
                ->get();
        } else {
            $caseRecords = CaseRecords::where('assigned_to', auth()->id())->orderBy('opened_at', 'desc')->get();
        }

        return view('cases.index', ['caseRecords' => $caseRecords]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $this->authorize('create', CaseRecords::class);

        $clients = Client::all();
        $categories = Category::all();
        $workers = User::where('role', 'case_worker')->get();

        return view('cases.create', ['clients' => $clients, 'categories' => $categories, 'workers' => $workers]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCaseRecordsRequest $request)
    {
        //
        $this->authorize('create', CaseRecords::class);
        $data = $request->validated();
        $data['created_by'] = Auth::id();

        CaseRecords::create($data);

        return redirect('/cases');
    }

    /**
     * Display the specified resource.
     */
    public function show(CaseRecords $caseRecords)
    {
        //
        $this->authorize('view', $caseRecords);

        return view('cases.show', ['case' => $caseRecords]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CaseRecords $caseRecords)
    {
        //
        $this->authorize('update', $caseRecords);

        $clients = Client::all();
        $categories = Category::all();
        $workers = User::where('role', 'case_worker')->get();

        return view('cases.edit', ['caseRecord' => $caseRecords, 'clients' => $clients, 'categories' => $categories, 'workers' => $workers]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreCaseRecordsRequest $request, CaseRecords $caseRecords)
    {
        //
        $this->authorize('update', $caseRecords);

        $caseRecords->update($request->validated());

        return redirect("/cases/{$caseRecords->id}");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CaseRecords $caseRecords)
    {
        //
        $this->authorize('delete', $caseRecords);

        $caseRecords->delete();

        return redirect('/cases');
    }
}
