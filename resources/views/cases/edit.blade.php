@props([
    'caseRecord' => [],
    'categories' => [],
    'clients' => [],
    'workers' => [],
])

<x-layout active='cases'>

<div class="flex items-center justify-center text-base-content">
    <form class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 mt-2" action="/cases/{{ $caseRecord->id }}" method="POST">
        <h1 class="block text-sm font-bold mb-2">Edit Case:</h1>
        @csrf
        @method('PATCH')

            <label class="block text-gray-700 text-sm font-bold mb-2" for="title">
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" type="text" id="title" name="title" placeholder="Title:" required value="{{ $caseRecord->title }}">
            </label>
            @if ($errors->has('title'))
                <div class="alert alert-outline max-sm:alert-vertical alert-error text-xs font-bold m-1"> {{ $errors->first('title') }} </div>
            @endif

            <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
                <textarea class="textarea shadow appearance-none border border-black rounded w-full py-2 px-3 bg-white text-gray-700" type="text" id="description" name="description" placeholder="Description:" required>{{ $caseRecord->description }}</textarea>
            </label>
            @if ($errors->has('description'))
                <div class="alert alert-outline max-sm:alert-vertical alert-error text-xs font-bold m-1"> {{ $errors->first('description') }} </div>
            @endif

            <label class="block text-gray-700 text-sm font-bold mb-2" for="category_id">
                <select class="select shadow appearance-none border border-black rounded w-full py-2 px-3 bg-white text-gray-700" name="category_id" id="category_id" required>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected($caseRecord->category->name === $category->name)> {{ $category->name }}</option>
                    @endforeach
                </select>

            </label>
            @if ($errors->has('category_id'))
                <div class="alert alert-outline max-sm:alert-vertical alert-error text-xs font-bold m-1"> {{ $errors->first('category_id') }} </div>
            @endif

            <label class="block text-gray-700 text-sm font-bold mb-2" for="client">
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700"  type="text" name="client" id="client" list="clients" placeholder="Type to search for client..." value="{{ $caseRecord->client->first_name }} {{ $caseRecord->client->last_name }}">
                <datalist id="clients">
                    @foreach ($clients as $client)
                        <option data-id='{{ $client->id }}' value='{{ $client->first_name }} {{ $client->last_name }}'>
                    @endforeach
                </datalist>
            </label>
            @if ($errors->has('client_id'))
                <div class="alert alert-outline max-sm:alert-vertical alert-error text-xs font-bold m-1"> {{ $errors->first('client_id') }} </div>
            @endif

            <input type="hidden" name="client_id" id="client_id" value="{{ $caseRecord->client->id }}">
            <script>
                document.getElementById('client').addEventListener('input', function(e) {
                    var input = e.target;
                    var list = input.getAttribute('list');
                    var options = document.querySelectorAll('#' + list + ' option');
                    var hiddenInput = document.getElementById('client_id');
                    var inputValue = input.value;

                    hiddenInput.value = ""; // Reset if no match

                    for (var i = 0; i < options.length; i++) {
                        var option = options[i];
                        if (option.value === inputValue) {
                            hiddenInput.value = option.getAttribute('data-id');
                            break;
                        }
                    }
                });
            </script>

            <label class="block text-gray-700 text-sm font-bold mb-2" for="worker">
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700"  type="text" name="worker" id="worker" list="workers" placeholder="Assign Worker..." value="{{ $caseRecord->assignedUser->name ?? '' }}">
                <datalist id="workers">
                    @foreach ($workers as $worker)
                        <option data-id='{{ $worker->id }}' value='{{ $worker->name }}'>
                    @endforeach
                </datalist>
            </label>
            @if ($errors->has('assigned_to'))
                <div class="alert alert-outline max-sm:alert-vertical alert-error text-xs font-bold m-1"> {{ $errors->first('assigned_to') }} </div>
            @endif

            <input type="hidden" name="assigned_to" id="assigned_to" value="{{ $caseRecord->assignedUser->id ?? '' }}">
            <script>
                document.getElementById('worker').addEventListener('input', function(e) {
                    var input = e.target;
                    var list = input.getAttribute('list');
                    var options = document.querySelectorAll('#' + list + ' option');
                    var hiddenInput = document.getElementById('assigned_to');
                    var inputValue = input.value;

                    hiddenInput.value = ""; // Reset if no match

                    for (var i = 0; i < options.length; i++) {
                        var option = options[i];
                        if (option.value === inputValue) {
                            hiddenInput.value = option.getAttribute('data-id');
                            break;
                        }
                    }
                });
            </script>


        <button class="btn btn-soft btn-primary" type="submit">Create Case</button>
    </form>
</div>

</x-layout>
   