@props([
    'users' => [],
])

<x-layout active="users">

    @if (auth()->user()->role === 'admin')
        <div>
            <a class="btn btn-xs btn-info text-neutral" href="/users/create">Create</a>
        </div>
    @endif

     <div class="grid grid-cols-5 gap-2 m-4">
        @foreach ($users as $user )
            <x-card.user :user="$user"/>
        @endforeach
    </div>

</x-layout>