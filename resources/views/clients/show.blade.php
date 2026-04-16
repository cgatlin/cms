@props([
    'client' => [],
])

<x-layout active="clients">

    <div>
        <h1>
            {{ $client->first_name }} {{ $client->last_name }}

            @if (auth()->user()->role === 'admin')
                <div>
                    <a class="btn btn-xs btn-warning text-neutral" href="/clients/{{ $client->id }}/edit">Edit</a>

                    <form action="/clients/{{ $client->id }}" method="POST" style="display: inline-block;">
                        @csrf
                        @method('DELETE')

                        <button class="btn btn-xs btn-error text-neutral" type="submit">Delete</button>
                    </form>
                </div>
            @endif
        </h1>



    </div>


    <ul class="list text-base-content flex items-center justify-center">
        @foreach ($client->cases as $case )
            <li class="list-row bg-base-100 rounded-box shadow-md p-2 m-2">
                <a class="link" href="/cases/{{ $case->id }}">
                    {{ $case->title }}
                </a>
            </li>
        @endforeach
    </ul>

</x-layout>