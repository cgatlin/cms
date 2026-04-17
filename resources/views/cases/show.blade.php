@props([
    'case' => [],
])

<x-layout active="cases">

    <div>
        <h1>
            {{ $case->title }}

            @if (auth()->user()->role === 'admin')
                <div>
                    <a class="btn btn-xs btn-warning text-neutral" href="/cases/{{ $case->id }}/edit">Edit</a>

                    <form action="/cases/{{ $case->id }}" method="POST" style="display: inline-block;">
                        @csrf
                        @method('DELETE')

                        <button class="btn btn-xs btn-error text-neutral" type="submit">Delete</button>
                    </form>
                </div>
            @endif
        </h1>
    </div>

    <div>
        {{ $case->description }}
    </div>

    <ul class="list text-base-content flex items-center justify-center">
        @foreach ($case->notes as $note )
            <li class="list-row bg-base-100 rounded-box shadow-md p-2 m-2">
                <a class="link" href="/notes/{{ $note->id }}">
                    {{ $note->note }}
                </a>
            </li>
        @endforeach
    </ul>

</x-layout>