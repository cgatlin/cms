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
            <p>{{ $notification->data['message'] }}</p>

            <small>
                Task: {{ $notification->data['title'] }}
            </small>

            <small>
                {{ $notification->created_at->diffForHumans() }}
            </small>
        </div>
    @empty
        <p>No notifications.</p>
    @endforelse

</x-layout>