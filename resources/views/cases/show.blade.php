@props([
    'case' => [],
    'timeline' => 0,
])

<x-layout active="cases">

    <div>
        <h1>
            {{ $case->title }}
                <div>
                    @can('update', $case)
                        <a class="btn btn-xs btn-warning text-neutral" href="/cases/{{ $case->id }}/edit">Edit</a>
                    @endcan
                    @can('delete', $case)
                        <form action="/cases/{{ $case->id }}" method="POST" style="display: inline-block;">
                            @csrf
                            @method('DELETE')

                            <button class="btn btn-xs btn-error text-neutral" type="submit">Delete</button>
                        </form>
                     @endcan
                </div>
           
        </h1>
    </div>

    <div>
        {{ $case->description }}
    </div>

    <p>Client: {{ $case->client->first_name }} {{ $case->client->last_name }}</p>
    <p>Category: {{ $case->category->name }}</p>
    <p>Status: {{ $case->status }}</p>
    <p>Assigned: {{ $case->assignedUser->name ?? 'Unassigned' }}</p>

    <h3>Add Note:</h3>
    <div class="flex items-center justify-center text-base-content">
        <form class="px-8 pt-6 pb-8 mb-4 mt-2" method="POST" action="/cases/{{ $case->id }}/notes">
            @csrf
            <label class="block text-gray-700 text-sm font-bold mb-2" for="note">
                <textarea class="textarea shadow appearance-none border border-black rounded w-full py-2 px-3 bg-white text-gray-700" name="note" required placeholder="Write a note..."></textarea>
            </label>
            <button class="btn btn-soft btn-primary" type="submit">Add Note</button>
            @if ($errors->any())
                <div>
                    @foreach ($errors->all() as $error)
                        <p class="alert alert-outline max-sm:alert-vertical alert-error text-xs font-bold m-1">{{ $error }}</p>
                    @endforeach
                </div>
            @endif
        </form>
    </div>


    <ul class="timeline timeline-vertical">
        @foreach ($case->notes->sortByDesc('created_at') as $note )
            <li class="text-base-content">
                <hr/>
                <div class="{{ $timeline % 2 ? 'timeline-start' : 'timeline-end'}}  timeline-box bg-primary text-primary-content">
                    <a class="link" href="/notes/{{ $note->id }}">
                        <time class="font-mono italic">{{ $note->created_at->diffForHumans() }}</time>
                        <div>
                            <p>{{ $note->note }}</p>
                            <small>
                                By {{ $note->user->name }}
                            </small>
                        </div>
                    </a>
                </div>
                <hr/>
            </li>
            @php $timeline++; @endphp
        @endforeach
    </ul>

</x-layout>