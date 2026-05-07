<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Models\CaseRecords;
use App\Models\Task;
use App\Notifications\TaskAssignedNotification;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TaskController extends Controller
{
    use AuthorizesRequests;

    //
    public function store(StoreTaskRequest $request, CaseRecords $caseRecords)
    {
        $this->authorize('view', $caseRecords);

        $data = $request->validated();
        // For now tasks will be assigned to case worker assign to case
        $data['assigned_to'] = $caseRecords->assignedUser->id ?? null;

        $task = $caseRecords->tasks()->create($data);

        if ($task->assigned_to) {
            $task->assignedUser->notify(
                new TaskAssignedNotification($task)
            );
        }

        return redirect()->route('cases.show', $caseRecords)->with('success', 'Task added.');
    }

    public function complete(Task $task)
    {
        $this->authorize('update', $task->case);

        $task->update([
            'is_completed' => true,
        ]);

        return back()->with('success', 'Task completed.');
    }

    public function destroy(Task $task)
    {
        $this->authorize('update', $task->case);

        $task->delete();

        return back()->with('success', 'Task deleted.');
    }
}
