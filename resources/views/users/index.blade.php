@props([
    'users' => [],
])

<x-layout active="users">

    @if (auth()->user()->role === 'admin')
        <div>
            <a class="btn btn-xs btn-info text-neutral" href="/users/create">Create</a>
        </div>
    @endif

    <ul class="list flex items-center justify-center">
        @foreach ($users as $user )
            <li class="bg-primary text-primary-content rounded-box p-2 m-2">
                <a class="link" href="/users/{{ $user->id }}">
                    {{ $user->name }}
                </a>
            </li>
        @endforeach
    </ul>

</x-layout>