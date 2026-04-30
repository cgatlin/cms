@props([
    'assigned',
    'worker',
    'workers' => [],
    'users' => [],
])

<x-layout active="users">

    @if (auth()->user()->role === 'admin')
        <div>
            <a class="btn btn-xs btn-info text-neutral" href="/users/create">Create</a>
        </div>
    @endif


    <div class="p-2">
        <form class="text-neutral" method="GET">

             @if (auth()->user()->role === 'admin')
                <label class="text-neutral text-sm font-bold mb-2" for="worker">Type to Search Workers:
                    <input class="input shadow bg-primary text-primary-content w-30" type="text" id="worker" list="workers" value="{{ $worker ?? '' }}">
                
                    <datalist id="workers">
                        <option data-id='' value='ALL'>
                        @foreach ($workers as $worker)
                            <option data-id='{{ $worker->id }}' value='{{ $worker->name }}'>
                        @endforeach
                    <input type="hidden" name="assigned" id="assigned" value="{{ $assigned }}">
                    <script>
                        document.getElementById('worker').addEventListener('input', function(e) {
                            var input = e.target;
                            var list = input.getAttribute('list');
                            var options = document.querySelectorAll('#' + list + ' option');
                            var hiddenInput = document.getElementById('assigned');
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
                
                
                
                </label>
            @endif

            <button class="btn btn-xs btn-accent" type="submit">Filter</button>
        </form>
    </div>





     <div class="grid grid-cols-5 gap-2 m-4">
        @foreach ($users as $user )
            <x-card.user :user="$user"/>
        @endforeach
    </div>

</x-layout>