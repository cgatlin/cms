@props([
    '$notifications' => [],
])


<x-layout active="notifications">

    <h1>Notifications</h1>
    <form method="POST" action="/notifications/read">
        @csrf
        <button class="btn btn-soft btn-secondary btn-sm" type="submit">Mark All As Read</button>
    </form>

    @forelse($notifications as $notification)
        <div>
            <p>
                {{ $notification->data['message'] }}
                {{-- @if($notification->due_date)
                    <strong class="badge badge-error badge-xs">NEW</strong>
                @endif --}}

                @if(is_null($notification->read_at))
                    <strong class="badge badge-success badge-xs">NEW</strong>
                @endif
            </p>

            <small>
                Task: {{ $notification->data['title'] }}
            </small>

            <small>
                {{ $notification->created_at->diffForHumans() }}
            </small>
            <a class="btn btn-soft btn-accent btn-xs" href="/cases/{{ $notification->data['case_id'] }}">
                View Case
            </a>
        </div>
    @empty
        <p>No notifications.</p>
    @endforelse

</x-layout>