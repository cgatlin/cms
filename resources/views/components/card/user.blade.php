@props([
    'user' => [],
])
@use('App\CaseRecordsStatus')

<div class="card bg-primary text-primary-content shadow-md">
  <div class="card-body">
    <h2 class="card-title">
        <p class="text-sm">
            {{ $user->name }}
        </p>
    </h2>
    <div>
        <a class="btn btn-outline btn-xs btn-info" href="/users/{{ $user->id }}">View</a>
        <a class="btn btn-outline btn-xs btn-warning" href="/users/{{ $user->id }}/edit">Edit</a>
        <form action="/users/{{ $user->id }}" method="POST" style="display: inline-block;">
            @csrf
            @method('DELETE')

            <button class="btn btn-outline btn-xs btn-error" type="submit">Delete</button>
        </form>
    </div>
    <p class="text-xs">Contact Info:</p>
    <p class="text-xs">Email:</p>
    <p class="text-xs">{{ $user->email }}</p>
    <p class="text-xs">Cases:</p>

    <div class="card-actions justify-center">
        <a class="btn btn-xs">
            {{ CaseRecordsStatus::OPEN->label() }} 
            <div class="badge badge-xs badge-error">{{ $user->open_count }}</div>
        </a>
        
        <a class="btn btn-xs">
            {{ CaseRecordsStatus::IN_PROGRESS->label() }}
            <div class="badge badge-xs badge-info">{{ $user->progress_count }}</div>
        </a>
        
        <a class="btn btn-xs">
            {{ CaseRecordsStatus::CLOSED->label() }}
            <div class="badge badge-xs badge-success">{{ $user->closed_count }}</div>
        </a>
    </div>

    <div class="card-actions justify-end">
      <p class="text-xs"><a class="btn btn-outline btn-xs btn-info" href="/cases?assigned={{ $user->id }}">View Cases</a></p>
    </div>
  </div>
</div>