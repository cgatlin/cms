@props([
    'clients' => [],
])

<x-layout active="clients">
    @if (auth()->user()->role === 'admin')
        <div>
            <a class="btn btn-xs btn-info text-neutral" href="/clients/create">Create</a>
        </div>
    @endif

    <div class="grid grid-cols-5 gap-2 m-4">
        @foreach ($clients as $client )
            <x-card.client :client="$client"/>
        @endforeach
    </div>

</x-layout>