@props ([
    'user',
])
<x-layout active='users'>

<div>
    <div>
        <h1>
            <span class="uppercase">{{ $user->name }}</span>
            @if (auth()->user()->role === 'admin')
                <div>
                    <a class="btn btn-xs btn-warning text-neutral" href="/users/{{ $user->id }}/edit">Edit</a>

                    <form action="/users/{{ $user->id }}" method="POST" style="display: inline-block;">
                        @csrf
                        @method('DELETE')

                        <button class="btn btn-xs btn-error text-neutral" type="submit">Delete</button>
                    </form>
                </div>
            @endif
        </h1>
    </div>
</div>

</x-layout>