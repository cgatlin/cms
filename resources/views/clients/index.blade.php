@props([
    'clients' => [],
])

<x-layout active="clients">
    @if (auth()->user()->role === 'admin')
        <div>
            <a class="btn btn-xs btn-info text-neutral" href="/clients/create">Create</a>
        </div>
    @endif

    <ul class="list text-base-content flex items-center justify-center">
        @foreach ($clients as $client )
            <li class="bg-base-100 rounded-box p-2 m-2">
                <a class="link" href="/clients/{{ $client->id }}">
                    {{ $client->first_name }} {{ $client->last_name }}
                </a>
            </li>
        @endforeach
    </ul>

</x-layout>