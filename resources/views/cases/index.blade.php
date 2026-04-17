@props([
    'caseRecords' => [],
])

<x-layout active="cases">
    @if (auth()->user()->role === 'admin')
        <div>
            <a class="btn btn-xs btn-info text-neutral" href="/cases/create">Create</a>
        </div>
    @endif

    <ul class="list text-base-content flex items-center justify-center">
        @foreach ($caseRecords as $caseRecord )
            <li class="bg-base-100 rounded-box p-2 m-2">
                <a class="link" href="/cases/{{ $caseRecord->id }}">
                    {{ $caseRecord->title }}
                </a>
            </li>
        @endforeach
    </ul>

</x-layout>