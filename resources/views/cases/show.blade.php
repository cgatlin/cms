@props([
    'case' => [],
    'timeline' => 0,
])

<x-layout active="cases">

    <div>
        <h1>
            {{ $case->title }}
                <div>
                    @can('update', $case)
                        <a class="btn btn-xs btn-warning text-neutral" href="/cases/{{ $case->id }}/edit">Edit</a>
                    @endcan
                    @can('delete', $case)
                        <form action="/cases/{{ $case->id }}" method="POST" style="display: inline-block;">
                            @csrf
                            @method('DELETE')

                            <button class="btn btn-xs btn-error text-neutral" type="submit">Delete</button>
                        </form>
                     @endcan
                </div>
           
        </h1>
    </div>

    <div>
        {{ $case->description }}
    </div>

    <p>Client: {{ $case->client->first_name }} {{ $case->client->last_name }}</p>
    <p>Category: {{ $case->category->name }}</p>
    <p>Status: {{ $case->status }}</p>
    <p>Assigned: {{ $case->assignedUser->name ?? 'Unassigned' }}</p>

    <div class="flex items-center justify-center gap-4 m-2">
        <span>
            <button class="btn btn-primary" onclick="document.getElementById('add_note_modal').showModal()">Add Note</button>
            
            <dialog id="add_note_modal" class="modal text-black">
                <div class="modal-box w-11/12 max-w-2xl">
                    <form method="dialog">
                        <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                    </form>
                    <h3 class="font-bold text-lg mb-4">Add Note</h3>
                    <form method="POST" action="/cases/{{ $case->id }}/notes">
                        @csrf
                        <div class="form-control">
                            <label class="label" for="note">
                                <textarea class="textarea textarea-bordered input-neutral h-32" name="note" required placeholder="Write a note..."></textarea>
                            </label>
                        </div>
                        @if ($errors->any())
                            <div class="mt-2">
                                @foreach ($errors->all() as $error)
                                    <p class="alert alert-error text-xs font-bold mb-1">{{ $error }}</p>
                                @endforeach
                            </div>
                        @endif
                        <div class="modal-action">
                            <button data-testid="add-note-btn" type="submit" class="btn btn-primary">Add Note</button>
                            <button type="button" class="btn" onclick="document.getElementById('add_note_modal').close()">Cancel</button>
                        </div>
                    </form>
                </div>
                <form method="dialog" class="modal-backdrop">
                    <button>close</button>
                </form>
            </dialog>
        </span>

        <span>
            <button class="btn btn-primary" onclick="document.getElementById('add_task_modal').showModal()">Add Task</button>
            
            <dialog id="add_task_modal" class="modal text-black">
                <div class="modal-box w-11/12 max-w-2xl">
                    <form method="dialog">
                        <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                    </form>
                    <h3 class="font-bold text-lg mb-4">Add Task</h3>
                    <form method="POST" action="/cases/{{ $case->id }}/tasks">
                        @csrf
                        <div class="form-control">
                            <label class="label block m-2" for="title">
                                <input class="input input-neutral" type="text" name="title" placeholder="Task title" required>
                            </label>

                            <label class="label block m-2" for="description">
                                <textarea class="textarea textarea-bordered input-neutral h-32" name="description" required placeholder="Task Details..."></textarea>
                            </label>

                            <label class="label block m-2" for="due_date">
                                <input class="input input-neutral" type="date" name="due_date">
                            </label>
                        </div>
                        @if ($errors->any())
                            <div class="mt-2">
                                @foreach ($errors->all() as $error)
                                    <p class="alert alert-error text-xs font-bold mb-1">{{ $error }}</p>
                                @endforeach
                            </div>
                        @endif
                        <div class="modal-action">
                            <button data-testid="add-task-btn" type="submit" class="btn btn-primary">Add Task</button>
                            <button type="button" class="btn" onclick="document.getElementById('add_task_modal').close()">Cancel</button>
                        </div>
                    </form>
                </div>
                <form method="dialog" class="modal-backdrop">
                    <button>close</button>
                </form>
            </dialog>
        </span>

    </div>

    <p>Tasks:</p>
    <ul class="timeline timeline-horizontal flex items-center justify-center m-4">
        @forelse ($case->tasks->sortByDesc('created_at') as $task )
            <li>

                @php
                    // Ensure $task->due_date is a Carbon instance
                    $dueDate = \Carbon\Carbon::parse($task->due_date);
                    $now = now()->startOfDay(); // Today 00:00:00
                    
                    // Check if it is past due or due within 2 days (0, 1, or 2 days left)
                    // diffInDays(..., false) allows returning negative numbers for overdue
                    $daysLeft = $now->diffInDays($dueDate->copy()->startOfDay(), false);
                    $isWarning = $daysLeft <= 2 && !$task->is_completed;
                    $isOverdue = $dueDate->isPast() && !$task->is_completed;
                @endphp

                <hr @class([
                            'badge badge-success' => $task->is_completed,
                            'badge badge-info' => !$isWarning && !$isOverdue,
                            'badge badge-error' => $isWarning || $isOverdue,
                            ])/>

                <div @class([
                            'timeline-start' => $timeline % 2 === 0,
                            'timeline-end' => $timeline % 2,
                            'timeline-box bg-primary',
                            'text-primary-content' => $task->is_completed,
                            'text-accent-content' => !$isWarning && !$isOverdue,
                            'text-secondary-content' => $isWarning || $isOverdue,
                            ])>

                    <time class="font-mono italic">Due By: {{ $task->due_date?$task->due_date->diffForHumans(now()->startOfDay(), \Carbon\CarbonInterface::DIFF_RELATIVE_TO_NOW):'No Deadline' }}</time>
                    <div>
                        <p>{{ $task->title }}</p>
                        <p>{{ $task->description }}</p>
                        <p>

                            {{ $task->is_completed?'Completed':'Incomplete' }}
                        </p>
                    </div>
                    
                    @if(!$task->is_completed)
                        <form class="m-1" method="POST" action="/tasks/{{ $task->id }}/complete">
                            @csrf
                            @method('PATCH')
                            <button class="btn btn-xs btn-soft btn-primary" type="submit">Mark Complete</button>
                        </form>
                    @endif

                    <form class="m-1" method="POST" action="/tasks/{{ $task->id }}">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-xs btn-soft btn-primary" type="submit">Delete</button>
                    </form>
                </div>
                <hr @class([
                            'badge badge-success' => $task->is_completed,
                            'badge badge-info' => !$isWarning && !$isOverdue,
                            'badge badge-error' => $isWarning || $isOverdue,
                            ])/>
            </li>
            @php $timeline++; @endphp
        @empty
            <p>No current tasks for this case.</p>
        @endforelse
    </ul>

    <p>Notes:</p>
    <ul class="timeline timeline-vertical">
        @forelse ($case->notes->sortByDesc('created_at') as $note )
            <li class="text-base-content">
                <hr/>
                <div class="{{ $timeline % 2 ? 'timeline-start' : 'timeline-end'}}  timeline-box bg-primary text-primary-content">
                    <a class="link" href="/notes/{{ $note->id }}">
                        <time class="font-mono italic">{{ $note->created_at->diffForHumans() }}</time>
                        <div>
                            <p>{{ $note->note }}</p>
                            <small>
                                By {{ $note->user->name }}
                            </small>
                        </div>
                    </a>
                </div>
                <hr/>
            </li>
            @php $timeline++; @endphp
        @empty
            <p>No notes for this case.</p>
        @endforelse
    </ul>

</x-layout>