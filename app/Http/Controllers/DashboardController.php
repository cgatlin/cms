<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\CaseRecordsStatus;
use App\Models\CaseRecords;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;

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

    public function report(Request $request)
    {
        $status = $request->input('status');
        $category_id = $request->input('category_id');
        $assigned = $request->input('assigned');
        $date_start = $request->input('date_start');
        $date_end = $request->input('date_end');

        // // Build the base query with grouping by worker
        // $query = CaseRecords::selectRaw("
        //     assigned_to,
        //     count(*) as total_count,
        //     count(case when status = '".CaseRecordsStatus::OPEN->value."' then 1 end) as open_count,
        //     count(case when status = '".CaseRecordsStatus::IN_PROGRESS->value."' then 1 end) as progress_count,
        //     count(case when status = '".CaseRecordsStatus::CLOSED->value."' then 1 end) as closed_count
        // ");

        // // Apply filters
        // if ($status && $status !== "All") {
        //     $query->where('status', $status);
        // }

        // if ($category_id && $category_id !== "All") {
        //     $query->where('category_id', $category_id);
        // }

        // if ($assigned) {
        //     $query->where('assigned_to', $assigned);
        // }

        // if ($date_start) {
        //     $query->where('opened_at', '>=', $date_start);
        // }

        // if ($date_end) {
        //     $query->where('opened_at', '<=', $date_end);
        // }

        // // Group by worker and get results
        // $casesByWorker = $query->groupBy('assigned_to')
        //     ->with('assignedUser:id,name')
        //     ->get();
        // $labels = [CaseRecordsStatus::OPEN->label(), CaseRecordsStatus::IN_PROGRESS->label(), CaseRecordsStatus::CLOSED->label()];

        return view('dashboard.report', [
            'status' => $status,
            'category_id' => $category_id,
            'assigned' => $assigned,
            'worker' => User::where('id', $assigned)->first()->name ?? null,
            'date_start' => $date_start,
            'date_end' => $date_end,
            'categories' => Category::all(),
            'users' => User::where('role', 'case_worker')->get(),
        ]);
    }

    public function exportReport(Request $request)
    {

        $status = $request->input('status');
        $category_id = $request->input('category_id');
        $assigned = $request->input('assigned');
        $date_start = $request->input('date_start');
        $date_end = $request->input('date_end');

        // Normalize 'all' values
        $statusIsAll = $status === null || strtolower((string) $status) === 'all';
        $categoryIsAll = $category_id === null || strtolower((string) $category_id) === 'all';

        // Build Eloquent query and fetch matching records (avoid complex SQL CASE expressions)
        $caseQuery = CaseRecords::with('assignedUser:id,name');

        if (! $statusIsAll) {
            $caseQuery->where('status', $status);
        }

        if (! $categoryIsAll) {
            $caseQuery->where('category_id', $category_id);
        }

        if ($assigned) {
            $caseQuery->where('assigned_to', $assigned);
        }

        if ($date_start) {
            $caseQuery->where('opened_at', '>=', $date_start);
        }

        if ($date_end) {
            $caseQuery->where('opened_at', '<=', $date_end);
        }

        $records = $caseQuery->get();

        // Group in PHP by assigned_to and compute counts
        $casesByWorker = $records->groupBy('assigned_to')->map(function ($group, $assignedTo) {
            $total = $group->count();
            $open = $group->filter(fn ($r) => $r->status === CaseRecordsStatus::OPEN)->count();
            $progress = $group->filter(fn ($r) => $r->status === CaseRecordsStatus::IN_PROGRESS)->count();
            $closed = $group->filter(fn ($r) => $r->status === CaseRecordsStatus::CLOSED)->count();

            $workerName = $group->first()->assignedUser?->name ?? 'Unassigned';

            return (object) [
                'assigned_to' => $assignedTo,
                'worker_name' => $workerName,
                'total_count' => $total,
                'open_count' => $open,
                'progress_count' => $progress,
                'closed_count' => $closed,
            ];
        })->values();

        $fileName = 'cases_report.csv';

        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$fileName",
        ];

        $callback = function () use ($casesByWorker) {
            $file = fopen('php://output', 'w');

            fputcsv($file, ['Worker', 'Total Cases', 'Open Cases', 'Case in Progress', 'Closed Cases']);

            foreach ($casesByWorker as $record) {
                fputcsv($file, [
                    $record->worker_name ?? 'Unassigned',
                    $record->total_count,
                    $record->open_count,
                    $record->progress_count,
                    $record->closed_count,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
