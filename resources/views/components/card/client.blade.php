@props([
    'client' => [],
])
@use('App\CaseRecordsStatus')

<div class="card bg-primary text-primary-content shadow-md">
  <div class="card-body">
    <h2 class="card-title">
        <p class="text-sm">
            {{ $client->first_name }} {{ $client->last_name }}
        </p>
    </h2>
    <div>
        <a class="btn btn-outline btn-xs btn-info" href="/clients/{{ $client->id }}">View</a>
        <a class="btn btn-outline btn-xs btn-warning" href="/clients/{{ $client->id }}/edit">Edit</a>
        <form action="/clients/{{ $client->id }}" method="POST" style="display: inline-block;">
            @csrf
            @method('DELETE')

            <button class="btn btn-outline btn-xs btn-error" type="submit">Delete</button>
        </form>
    </div>
    <p class="text-xs">Contact Info:</p>
    <p class="text-xs">Phone:</p>
    <p class="text-xs">{{ $client->phone }}</p>
    <p class="text-xs">Email:</p>
    <p class="text-xs">{{ $client->email }}</p>
    <p class="text-xs">Cases:</p>

    <a class="btn btn-xs">
        {{ CaseRecordsStatus::OPEN->label() }} 
        <div class="badge badge-xs badge-error">{{ $client->open_count }}</div>
    </a>
    
    <a class="btn btn-xs">
        {{ CaseRecordsStatus::IN_PROGRESS->label() }}
        <div class="badge badge-xs badge-info">{{ $client->progress_count }}</div>
    </a>
    
    <a class="btn btn-xs">
        {{ CaseRecordsStatus::CLOSED->label() }}
        <div class="badge badge-xs badge-success">{{ $client->closed_count }}</div>
    </a>

    <div class="card-actions justify-end">
      <p class="text-xs"><a class="btn btn-outline btn-xs btn-info" href="/cases?client={{ $client->id }}">View Cases</a></p>
    </div>
  </div>
</div>