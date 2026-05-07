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

    <div class="flex items-center justify-center">
        <span>
            <h3>Add Note:</h3>
            <div class="flex items-center justify-center text-base-content">
                <form class="px-8 pt-6 pb-8 mb-4 mt-2" method="POST" action="/cases/{{ $case->id }}/notes">
                    @csrf
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="note">
                        <textarea class="textarea shadow appearance-none border border-black rounded w-full py-2 px-3 bg-white text-gray-700" name="note" required placeholder="Write a note..."></textarea>
                    </label>
                    <button class="btn btn-soft btn-primary" type="submit">Add Note</button>
                    @if ($errors->any())
                        <div>
                            @foreach ($errors->all() as $error)
                                <p class="alert alert-outline max-sm:alert-vertical alert-error text-xs font-bold m-1">{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif
                </form>
            </div>
        </span>

        <span>
            <h3>Add Task:</h3>
            <div class="flex items-center justify-center text-base-content">
                <form class="px-8 pt-6 pb-8 mb-4 mt-2" method="POST" action="/cases/{{ $case->id }}/tasks">
                    @csrf

                    <label class="block text-gray-700 text-sm font-bold mb-2" for="title">
                        <input type="text" name="title" placeholder="Task title" required>
                    </label>

                    <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
                        <textarea class="textarea shadow appearance-none border border-black rounded w-full py-2 px-3 bg-white text-gray-700" name="description" placeholder="Details..."></textarea>
                    </label>

                    {{-- <select name="assigned_to">
                        <option value="">Unassigned</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select> --}}

                    <label class="block text-gray-700 text-sm font-bold mb-2" for="due_date">
                        <input type="date" name="due_date">
                    </label>
                    @if ($errors->any())
                        <div>
                            @foreach ($errors->all() as $error)
                                <p class="alert alert-outline max-sm:alert-vertical alert-error text-xs font-bold m-1">{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    

                    <button class="btn btn-soft btn-primary" type="submit">Add Task</button>
                </form>
            </div>
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