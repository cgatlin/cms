@props([
    'caseRecord' => [],
])
@use('App\CaseRecordsStatus')

<div class="card bg-primary text-primary-content shadow-sm">
  <div class="card-body">
    <h2 class="card-title">
        <p class="text-sm">
            {{ $caseRecord->title }}
        </p>
    </h2>
    <div>
      @can('view', $caseRecord)
          <a class="btn btn-outline btn-xs btn-info" href="/cases/{{ $caseRecord->id }}">View</a>
      @endcan
      @can('update', $caseRecord)
          <a class="btn btn-outline btn-xs btn-warning" href="/cases/{{ $caseRecord->id }}/edit">Edit</a>
      @endcan
      @can('delete', $caseRecord)
          <form action="/cases/{{ $caseRecord->id }}" method="POST" style="display: inline-block;">
              @csrf
              @method('DELETE')

              <button class="btn btn-outline btn-xs btn-error" type="submit">Delete</button>
          </form>
      @endcan
    </div>
    <p class="text-xs">Client: {{ $caseRecord->client->first_name }} {{ $caseRecord->client->last_name }}</p>
    <p class="text-md">{{ $caseRecord->description }}</p>
    <div class="card-actions justify-end">
      <div @class([
          'badge',
          'badge-error' => $caseRecord->status === CaseRecordsStatus::CLOSED,
          'badge-info' => $caseRecord->status === CaseRecordsStatus::IN_PROGRESS,
          'badge-success' => $caseRecord->status === CaseRecordsStatus::OPEN,
      ])>
        {{ $caseRecord->status->label() }}
      </div>
    </div>
  </div>
</div>