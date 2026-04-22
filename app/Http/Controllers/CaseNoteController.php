<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreCaseNoteRequest;
use App\Models\CaseRecords;
use Illuminate\Support\Facades\Auth;

class CaseNoteController extends Controller
{
    //
    public function store(StoreCaseNoteRequest $request, CaseRecords $caseRecords)
    {

        $caseRecords->notes()->create([
            'note' => $request->validated('note'),
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('cases.show', $caseRecords)
            ->with('success', 'Note added.');
    }
}
