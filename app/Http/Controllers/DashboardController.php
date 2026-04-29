<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\CaseRecordsStatus;
use App\Models\CaseRecords;
use App\Models\User;

class DashboardController extends Controller
{
    //
    public function index()
    {

        if (auth()->user()->isAdmin()) {
            $caseCount = CaseRecords::selectRaw("
                count(*) as total_count,
                count(case when status = '".CaseRecordsStatus::OPEN->value."' then 1 end) as open_count,
                count(case when status = '".CaseRecordsStatus::IN_PROGRESS->value."' then 1 end) as progress_count,
                count(case when status = '".CaseRecordsStatus::CLOSED->value."' then 1 end) as closed_count
            ")
                ->first();

            $caseRecordsRecent = CaseRecords::latest('updated_at')->take(6)->get();

        } else {
            $caseCount = User::withcount([
                // Count Total cases
                'assignedCases as total_count',
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
                ->orderBy('opened_at', 'desc')
                ->findOrFail(auth()->id());

            $caseRecordsRecent = $caseCount->assignedCases()->orderBy('updated_at', 'desc')->take(6)->get();
        }

        $labels = [CaseRecordsStatus::OPEN->label(), CaseRecordsStatus::IN_PROGRESS->label(), CaseRecordsStatus::CLOSED->label()];
        $data = [$caseCount->open_count, $caseCount->progress_count, $caseCount->closed_count];

        return view('dashboard.index', [
            'caseCount' => $caseCount,
            'CaseRecordsRecent' => $caseRecordsRecent,
            'labels' => $labels,
            'data' => $data,
        ]);
    }
}
