@props([
    'caseRecords' => [],
])

<x-layout active="cases">
    @if (auth()->user()->role === 'admin')
        <div>
            <a class="btn btn-xs btn-info text-neutral" href="/cases/create">Create</a>
        </div>
    @endif
<div class="grid grid-cols-6 gap-2 m-4">
        @foreach ($caseRecords->sortByDesc('created_at') as $caseRecord )
            <x-card.case :caseRecord="$caseRecord"/>
        @endforeach
</div>
</x-layout>